<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $marketingId = Auth::id();

        $query = Customer::whereHas('lead', function ($q) use ($marketingId) {
            $q->where('marketing_id', $marketingId);
        })->with(['user', 'lead.package', 'subscriptions']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('customer_code', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'aktif') {
                $query->where('is_isolated', false);
            } elseif ($request->status === 'nonaktif') {
                $query->where('is_isolated', true);
            }
        }

        $customers = $query->latest()->paginate(10)->withQueryString();

        return view('marketing.customers.index', compact('customers'));
    }

    public function show(Customer $customer)
    {
        $marketingId = Auth::id();

        // Ensure marketing owns this customer's lead
        if ($customer->lead && $customer->lead->marketing_id !== $marketingId) {
            abort(403, 'Unauthorized');
        }

        $customer->load(['user', 'lead.package', 'subscriptions.package', 'tickets']);

        return view('marketing.customers.show', compact('customer'));
    }
}
