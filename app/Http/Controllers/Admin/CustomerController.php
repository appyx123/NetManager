<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Subscription;
use App\Services\NetworkService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::with(['user', 'subscriptions']);

        // Search by customer code, name, or phone number
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('customer_code', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  })
                  ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        $customers = $query->paginate(15);
        return view('admin.customers.index', compact('customers'));
    }

    public function show(Customer $customer)
    {
        $customer->load(['user', 'subscriptions.package', 'lead', 'tickets']);
        return view('admin.customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'phone_number' => 'required|string',
            'address_installation' => 'required|string',
            'coordinates' => 'nullable|string',
        ]);

        $customer->update($validated);

        return redirect()->route('admin.customers.show', $customer)->with('success', 'Data pelanggan berhasil diperbarui');
    }

    public function isolate(Request $request, Customer $customer)
    {
        $reason = $request->validate(['reason' => 'required|string'])['reason'];

        Subscription::where('customer_id', $customer->id)
            ->update(['status' => 'isolated']);

        $customer->update(['is_isolated' => true]);

        // Putus koneksi PPPoE & masukkan ke blacklist address-list MikroTik
        try {
            $networkService = app(NetworkService::class);
            foreach ($customer->subscriptions as $subscription) {
                $networkService->disableCustomer($subscription);
            }
        } catch (Throwable $e) {
            Log::error("CustomerController isolate: Gagal isolir router untuk Customer #{$customer->id}: " . $e->getMessage());
        }

        // Log activity
        \App\Models\AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'isolate_customer',
            'description' => "Pelanggan {$customer->id} diisolir. Alasan: {$reason}",
            'details' => ['reason' => $reason],
        ]);

        return redirect()->back()->with('success', 'Pelanggan berhasil diisolir');
    }

    public function activate(Customer $customer)
    {
        Subscription::where('customer_id', $customer->id)
            ->update(['status' => 'active']);

        $customer->update(['is_isolated' => false]);

        // Aktifkan kembali koneksi PPPoE di MikroTik
        try {
            $networkService = app(NetworkService::class);
            foreach ($customer->subscriptions as $subscription) {
                $networkService->enableCustomer($subscription);
            }
        } catch (Throwable $e) {
            Log::error("CustomerController activate: Gagal aktivasi router untuk Customer #{$customer->id}: " . $e->getMessage());
        }

        \App\Models\AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'activate_customer',
            'description' => "Pelanggan {$customer->id} diaktifkan kembali",
        ]);

        return redirect()->back()->with('success', 'Pelanggan berhasil diaktifkan');
    }

    public function search(Request $request)
    {
        $query = $request->validate(['q' => 'required|string'])['q'];

        $customers = Customer::with(['user'])
            ->where('customer_code', 'like', "%{$query}%")
            ->orWhereHas('user', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->get();

        return response()->json($customers);
    }
}
