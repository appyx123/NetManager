<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Services\NetworkService;
use App\Services\WhatsappService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class BillingController extends Controller
{
    // 1. Tampilkan Daftar Tagihan & Statistik
    public function index()
    {
        $invoices = Invoice::with(['subscription.customer.user', 'subscription.package'])
            ->latest()
            ->get();

        $stats = [
            'paid_this_month' => Invoice::where('status', 'paid')->whereMonth('paid_at', now()->month)->sum('amount'),
            'unpaid_total'    => Invoice::where('status', 'unpaid')->sum('amount'),
            'unpaid_count'    => Invoice::where('status', 'unpaid')->count(),
        ];

        return view('admin.billing.index', compact('invoices', 'stats'));
    }

    // 2. Tampilkan Detail Tagihan (Invoice)
    public function show(Invoice $invoice)
    {
        $invoice->load(['subscription.customer.user', 'subscription.package']);
        return view('admin.billing.show', compact('invoice'));
    }

    // 3. Konfirmasi Pembayaran Manual (Oleh Admin)
    public function markAsPaid(Request $request, Invoice $invoice)
    {
        if ($invoice->status === 'paid') {
            return back()->with('error', 'Tagihan ini sudah lunas.');
        }

        // 1. Eksekusi pembaruan status database secara atomik
        DB::transaction(function () use ($invoice) {
            $invoice->update([
                'status' => 'paid',
                'paid_at' => now(),
                'payment_method' => 'manual_admin',
            ]);

            if ($invoice->subscription) {
                $invoice->subscription->update(['status' => 'active']);
                if ($invoice->subscription->customer) {
                    $invoice->subscription->customer->update(['is_isolated' => false]);
                }
            }
        });

        // 2. Aktifkan Router MikroTik jika pelanggan sebelumnya terisolir (di luar DB transaction)
        if ($invoice->subscription) {
            try {
                app(NetworkService::class)->enableCustomer($invoice->subscription);
            } catch (Throwable $e) {
                Log::error("Admin markAsPaid: Gagal aktivasi router: " . $e->getMessage());
            }
        }

        // Kirim WhatsApp bukti pembayaran lunas
        if ($invoice->subscription && $invoice->subscription->customer) {
            try {
                $customer = $invoice->subscription->customer;
                $customerName = $customer->user?->name ?? 'Pelanggan';
                $customerPhone = $customer->phone_number ?? $customer->user?->phone_number ?? null;

                if ($customerPhone) {
                    WhatsappService::sendPaymentSuccess(
                        $customerName,
                        $customerPhone,
                        $invoice->invoice_number,
                        $invoice->amount
                    );
                }
            } catch (Throwable $e) {
                Log::error("Admin markAsPaid: Gagal kirim WA lunas: " . $e->getMessage());
            }
        }

        return back()->with('success', 'Tagihan berhasil ditandai LUNAS secara manual dan notifikasi WhatsApp telah dikirim.');
    }

    // Tampilkan Form Edit Tagihan
    public function edit(Invoice $invoice)
    {
        $invoice->load(['subscription.customer.user', 'subscription.package']);
        return view('admin.billing.edit', compact('invoice'));
    }

    // Simpan Perubahan Tagihan
    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'status' => 'required|in:unpaid,paid',
        ]);

        $data = $request->except(['_token', '_method']);

        // Jika diubah jadi lunas tapi tanggal bayar kosong, otomatis isi dengan hari ini
        if ($data['status'] === 'paid' && empty($data['paid_at'])) {
            $data['paid_at'] = now();
        } elseif ($data['status'] === 'unpaid') {
            $data['paid_at'] = null; // Kosongkan tanggal bayar jika diubah kembali ke belum lunas
        }

        $invoice->update($data);

        return redirect()->route('admin.billing.show', $invoice->id)->with('success', 'Data tagihan berhasil diperbarui oleh Admin.');
    }
}