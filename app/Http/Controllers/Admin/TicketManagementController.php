<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Customer;
use App\Models\User;
use App\Models\NetworkAsset;
use Illuminate\Http\Request;

class TicketManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with(['customer.user', 'technician']);

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter berdasarkan customer
        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        $tickets = $query->latest()->paginate(15);
        $customers = Customer::with('user')->get();
        $statuses = ['open', 'assigned', 'in_progress', 'pending', 'resolved', 'closed'];
        $types = ['survey', 'installation', 'repair'];

        return view('admin.tickets.index', compact('tickets', 'customers', 'statuses', 'types'));
    }

    public function create()
    {
        $customers = Customer::with('user')->get();
        $technicians = User::where('role', 'technician')->get();
        $routers = NetworkAsset::all();

        return view('admin.tickets.create', compact('customers', 'technicians', 'routers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'type' => 'required|in:survey,installation,repair',
            'subject' => 'required|string|max:255',
            'status' => 'required|in:open,assigned,in_progress,pending,resolved,closed',
            'technician_id' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
        ]);

        Ticket::create($validated);

        return redirect()->route('admin.tickets.index')->with('success', 'Tiket berhasil dibuat');
    }

    public function show(Ticket $ticket)
    {
        $ticket->load(['customer.user', 'technician']);

        return view('admin.tickets.show', compact('ticket'));
    }

    public function edit(Ticket $ticket)
    {
        $customers = Customer::with('user')->get();
        $technicians = User::where('role', 'technician')->get();
        $routers = NetworkAsset::all();

        return view('admin.tickets.edit', compact('ticket', 'customers', 'technicians', 'routers'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'type' => 'required|in:survey,installation,repair',
            'subject' => 'required|string|max:255',
            'status' => 'required|in:open,assigned,in_progress,pending,resolved,closed',
            'technician_id' => 'nullable|exists:users,id',
            'survey_date' => 'nullable|date',
            'survey_status' => 'nullable|string',
            'survey_notes' => 'nullable|string',
            'installation_date' => 'nullable|date',
            'installation_status' => 'nullable|string',
            'installation_notes' => 'nullable|string',
            'connection_type' => 'nullable|string',
            'connectivity_status' => 'nullable|string',
            'speed_test_result' => 'nullable|numeric',
        ]);

        $ticket->update($validated);

        return redirect()->route('admin.tickets.show', $ticket)->with('success', 'Tiket berhasil diperbarui');
    }

    public function destroy(Ticket $ticket)
    {
        // Kembalikan kuota port ODP jika tiket instalasi belum tuntas dihapus
        if ($ticket->type === 'installation' && $ticket->status !== 'resolved') {
            $ticket->releaseOdpPort();
        }

        $ticket->delete();

        return redirect()->route('admin.tickets.index')->with('success', 'Tiket berhasil dihapus');
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'status' => 'required|in:open,assigned,in_progress,pending,resolved,closed',
        ]);

        $ticket->update($validated);

        if ($validated['status'] === 'closed' && $ticket->type === 'installation' && $ticket->installation_status !== 'completed') {
            $ticket->releaseOdpPort();
        }

        return response()->json(['success' => true, 'message' => 'Status tiket diperbarui']);
    }
}
