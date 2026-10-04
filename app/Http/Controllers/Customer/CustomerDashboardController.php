<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Subscription;
use App\Models\Package;
use Illuminate\Support\Str;

class CustomerDashboardController extends Controller
{
    public function index()
    {
        // 1. Cari data customer berdasarkan user yang sedang login (Lebih Aman)
        $customer = Customer::where('user_id', Auth::id())->first();

        // Jika profil pelanggan tidak ditemukan (Belum di-Approve Admin)
        // Arahkan ke halaman "Menunggu Persetujuan" yang ramah — bukan error 403
        if (!$customer) {
            return view('user.dashboard.pending');
        }

        // 2. Ambil data langganan yang sedang berjalan
        $subscription = $customer->subscriptions()->with('package')->latest()->first();

        // 3. Self-healing untuk pelanggan baru: Buat Subscription & Tagihan Perdana jika belum ada
        if (!$subscription) {
            $lead = $customer->lead;
            $package = $lead?->package ?? ($lead?->package_id ? Package::find($lead->package_id) : null);
            $packagePrice = $package ? (float) $package->price : 0;
            $installationFee = (float) ($lead?->installation_fee ?? $package?->installation_fee ?? 0);
            $initialAmount = $packagePrice + $installationFee;

            if ($package || $initialAmount > 0) {
                $subscription = Subscription::create([
                    'customer_id'       => $customer->id,
                    'package_id'        => $lead?->package_id,
                    'status'            => 'isolated',
                    'installation_date' => now()->toDateString(),
                    'billing_due_date'  => now()->addDays(7)->toDateString(),
                ]);

                Invoice::create([
                    'subscription_id' => $subscription->id,
                    'invoice_number'  => 'INV-' . strtoupper(Str::random(8)),
                    'amount'          => $initialAmount,
                    'status'          => 'unpaid',
                    'due_date'        => now()->addDays(7)->toDateString(),
                ]);

                $subscription->load('package');
            }
        } elseif ($subscription && $subscription->invoices()->count() === 0) {
            // Subscription ada tapi belum ada invoice sama sekali
            $lead = $customer->lead;
            $package = $subscription->package ?? $lead?->package ?? ($subscription->package_id ? Package::find($subscription->package_id) : null);
            $packagePrice = $package ? (float) $package->price : 0;
            $installationFee = (float) ($lead?->installation_fee ?? $package?->installation_fee ?? 0);
            $initialAmount = $packagePrice + $installationFee;

            Invoice::create([
                'subscription_id' => $subscription->id,
                'invoice_number'  => 'INV-' . strtoupper(Str::random(8)),
                'amount'          => $initialAmount,
                'status'          => 'unpaid',
                'due_date'        => now()->addDays(7)->toDateString(),
            ]);
        }

        // 4. Cek tagihan yang belum dibayar
        $unpaidInvoices = [];
        if ($subscription) {
            $unpaidInvoices = Invoice::where('subscription_id', $subscription->id)->where('status', 'unpaid')->latest()->get();
        }

        $recentTickets = $customer->tickets()->latest()->take(5)->get();

        return view('user.dashboard.index', compact('customer', 'subscription', 'unpaidInvoices', 'recentTickets'));
    }
}
