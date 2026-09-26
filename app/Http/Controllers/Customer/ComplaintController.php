<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    public function index()
    {
        $customer = $this->customer();
        $tickets = $customer->tickets()->latest()->get();

        return view('client.complaints.index', compact('tickets'));
    }

    public function show(Ticket $ticket)
    {
        $ticket = $this->customer()
            ->tickets()
            ->with('technician')
            ->findOrFail($ticket->id);

        return view('client.complaints.show', compact('ticket'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => ['required', 'in:network_slow,installation_issue,billing_issue,other'],
            'title' => ['required', 'string', 'max:255'],
            'priority' => ['required', 'in:low,medium,high,urgent'],
            'description' => ['required', 'string', 'max:5000'],
            'photo' => ['nullable', 'image', 'max:5120'],
        ]);

        $photoPath = $request->file('photo')?->store('uploads/customer-complaints', 'public');

        $this->customer()->tickets()->create([
            'type' => 'repair',
            'status' => 'open',
            'subject' => $validated['title'],
            'description' => $validated['description'],
            'notes' => 'Kategori: '.$validated['category'].'; Prioritas: '.$validated['priority'],
            'evidence_photo_path' => $photoPath,
        ]);

        return redirect()->route('client.complaints.index')
            ->with('success', 'Laporan kerusakan berhasil dikirim.');
    }

    private function customer(): Customer
    {
        return Customer::where('user_id', Auth::id())->firstOrFail();
    }
}
