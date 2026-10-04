<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Invoice;

class InvoiceController extends Controller
{
    public function index()
    {
        $customer = Auth::user()->customer;
        $invoices = [];

        if ($customer) {
            // Ambil tagihan berdasarkan subscription milik customer ini
            // Eager load subscription dan customer relationships to avoid N+1
            $invoices = Invoice::with('subscription.customer')
                ->whereHas('subscription', function($query) use ($customer) {
                    $query->where('customer_id', $customer->id);
                })
                ->latest()
                ->paginate(10);
        }

        return view('user.billing.index', compact('invoices'));
    }

    public function show(Invoice $invoice)
    {
        // Eager load relationships to avoid N+1 queries
        $invoice->load('subscription.customer.user');

        // Pastikan invoice ini milik user yang sedang login
        if ($invoice->subscription->customer->user_id !== Auth::id()) {
            abort(403, 'Akses Ditolak.');
        }

        return view('user.billing.show', compact('invoice'));
    }

    /**
     * Handle customer self-service payment via Midtrans Snap.
     */
    public function pay(Request $request, Invoice $invoice)
    {
        // Validasi kepemilikan invoice
        $invoice->load('subscription.customer.user');

        if ($invoice->subscription->customer->user_id !== Auth::id()) {
            abort(403, 'Akses Ditolak.');
        }

        // Jika sudah lunas, jangan proses lagi
        if ($invoice->status === 'paid') {
            return back()->with('info', 'Tagihan ini sudah berstatus lunas.');
        }

        // Konfigurasi Midtrans SDK
        \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
        \Midtrans\Config::$isProduction = (bool) config('services.midtrans.is_production', false);
        \Midtrans\Config::$isSanitized = (bool) config('services.midtrans.is_sanitized', true);
        \Midtrans\Config::$is3ds = (bool) config('services.midtrans.is_3ds', true);

        // Jika snap_token belum pernah dibuat, minta token baru ke Midtrans Snap API
        if (empty($invoice->snap_token)) {
            $customerUser = $invoice->subscription->customer->user ?? Auth::user();
            $customerProfile = $invoice->subscription->customer;

            $package = $invoice->subscription->package;
            $packagePrice = $package ? (int) $package->price : 0;
            $totalAmount = (int) $invoice->amount;
            $installationFee = $totalAmount > $packagePrice ? ($totalAmount - $packagePrice) : 0;

            $itemDetails = [];
            if ($packagePrice > 0 && $installationFee > 0) {
                $itemDetails[] = [
                    'id'       => 'PKG-' . ($package->id ?? 1),
                    'price'    => $packagePrice,
                    'quantity' => 1,
                    'name'     => 'Paket: ' . ($package->name ?? 'Internet Service'),
                ];
                $itemDetails[] = [
                    'id'       => 'FEE-INST',
                    'price'    => $installationFee,
                    'quantity' => 1,
                    'name'     => 'Biaya Instalasi / Pasang Baru',
                ];
            } else {
                $itemDetails[] = [
                    'id'       => 'INV-' . $invoice->id,
                    'price'    => $totalAmount,
                    'quantity' => 1,
                    'name'     => 'Langganan Internet: ' . ($package->name ?? 'Internet Service'),
                ];
            }

            $params = [
                'transaction_details' => [
                    'order_id' => $invoice->invoice_number,
                    'gross_amount' => $totalAmount,
                ],
                'customer_details' => [
                    'first_name' => $customerProfile->name ?? $customerUser->name,
                    'email' => $customerUser->email,
                    'phone' => $customerProfile->phone_number ?? '',
                ],
                'item_details' => $itemDetails,
            ];

            try {
                $snapToken = \Midtrans\Snap::getSnapToken($params);
                $invoice->update(['snap_token' => $snapToken]);
            } catch (\Exception $e) {
                return back()->with('error', 'Gagal memproses pembayaran Midtrans: ' . $e->getMessage());
            }
        }

        return back()->with('snap_token', $invoice->snap_token);
    }

    /**
     * Sinkronisasi status transaksi langsung dari Midtrans API
     * (Sangat krusial untuk localhost / dev di mana webhook tidak bisa menembus firewall / private IP)
     */
    public function checkStatus(Request $request, Invoice $invoice)
    {
        $invoice->load('subscription.customer.user');

        if ($invoice->subscription->customer->user_id !== Auth::id()) {
            abort(403, 'Akses Ditolak.');
        }

        if ($invoice->status === 'paid') {
            return back()->with('info', 'Tagihan ini sudah berstatus lunas.');
        }

        \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
        \Midtrans\Config::$isProduction = (bool) config('services.midtrans.is_production', false);

        try {
            $status = \Midtrans\Transaction::status($invoice->invoice_number);
            $transactionStatus = is_object($status) ? $status->transaction_status : ($status['transaction_status'] ?? null);
            $fraudStatus = is_object($status) ? ($status->fraud_status ?? null) : ($status['fraud_status'] ?? null);
            $paymentType = is_object($status) ? ($status->payment_type ?? 'midtrans') : ($status['payment_type'] ?? 'midtrans');

            if ($transactionStatus === 'settlement' || ($transactionStatus === 'capture' && $fraudStatus === 'accept')) {
                $invoice->update([
                    'status'         => 'paid',
                    'paid_at'        => now(),
                    'payment_method' => $paymentType,
                ]);

                // Dispatch Background Queue Job: Sinkronisasi MikroTik & WhatsApp secara asinkron
                \App\Jobs\SyncPaidInvoiceHardwareJob::dispatch($invoice);

                return back()->with('info', 'Pembayaran berhasil dikonfirmasi! Tagihan telah lunas.');
            } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire'])) {
                $invoice->update([
                    'status'     => 'unpaid',
                    'snap_token' => null,
                ]);
                return back()->with('error', 'Pembayaran dibatalkan atau kedaluwarsa. Silakan lakukan pembayaran ulang.');
            }

            return back()->with('info', 'Status pembayaran di Midtrans: ' . strtoupper($transactionStatus ?? 'Menunggu Pembayaran'));
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memeriksa status dari Midtrans: ' . $e->getMessage());
        }
    }
}