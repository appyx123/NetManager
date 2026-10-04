<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Package;
use App\Models\User;
use Illuminate\Http\Request;

class LeadManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Lead::with(['package', 'marketing']);

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan marketing
        if ($request->filled('marketing_id')) {
            $query->where('marketing_id', $request->marketing_id);
        }

        // Search by nama atau nomor HP
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $leads = $query->latest()->paginate(15);
        $marketers = User::where('role', 'marketing')->get();
        $statuses = ['prospek', 'survey', 'instalasi', 'aktif', 'batal'];
        $packages = Package::where('is_active', true)->get();

        return view('admin.leads.index', compact('leads', 'marketers', 'statuses', 'packages'));
    }

    public function create()
    {
        $packages = Package::where('is_active', true)->get();
        $marketers = User::where('role', 'marketing')->get();

        return view('admin.leads.create', compact('packages', 'marketers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'marketing_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'phone' => 'required|string',
            'email' => 'nullable|email',
            'customer_type' => 'nullable|string',
            'mother_name' => 'nullable|string',
            'business_name' => 'nullable|string',
            'address_ktp' => 'nullable|string',
            'address_installation' => 'required|string',
            'rt_rw' => 'nullable|string',
            'village' => 'nullable|string',
            'district' => 'nullable|string',
            'city' => 'nullable|string',
            'package_id' => 'required|exists:packages,id',
            'status' => 'required|in:prospek,survey,instalasi,aktif,batal',
            'source' => 'nullable|string',
            'promo_code' => 'nullable|string',
            'survey_date' => 'nullable|date',
            'installation_date' => 'nullable|date',
            'preferred_time' => 'nullable|string',
            'notes_summary' => 'nullable|string',
            'notes_obstacle' => 'nullable|string',
            'notes_special' => 'nullable|string',
        ]);

        Lead::create($validated);

        return redirect()->route('admin.leads.index')->with('success', 'Prospek berhasil dibuat.');
    }

    public function show(Lead $lead)
    {
        $lead->load(['package', 'marketing']);

        return view('admin.leads.show', compact('lead'));
    }

    public function edit(Lead $lead)
    {
        $packages = Package::where('is_active', true)->get();
        $marketers = User::where('role', 'marketing')->get();

        return view('admin.leads.edit', compact('lead', 'packages', 'marketers'));
    }

    public function update(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'marketing_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'phone' => 'required|string',
            'email' => 'nullable|email',
            'customer_type' => 'nullable|string',
            'mother_name' => 'nullable|string',
            'business_name' => 'nullable|string',
            'address_ktp' => 'nullable|string',
            'address_installation' => 'required|string',
            'rt_rw' => 'nullable|string',
            'village' => 'nullable|string',
            'district' => 'nullable|string',
            'city' => 'nullable|string',
            'package_id' => 'required|exists:packages,id',
            'status' => 'required|in:prospek,survey,instalasi,aktif,batal',
            'source' => 'nullable|string',
            'promo_code' => 'nullable|string',
            'survey_date' => 'nullable|date',
            'installation_date' => 'nullable|date',
            'preferred_time' => 'nullable|string',
            'notes_summary' => 'nullable|string',
            'notes_obstacle' => 'nullable|string',
            'notes_special' => 'nullable|string',
        ]);

        $lead->update($validated);

        return redirect()->route('admin.leads.show', $lead)->with('success', 'Prospek berhasil diperbarui.');
    }

    public function destroy(Lead $lead)
    {
        $lead->delete();

        return redirect()->route('admin.leads.index')->with('success', 'Prospek berhasil dihapus.');
    }

    public function updateStatus(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'status' => 'required|in:prospek,survey,instalasi,aktif,batal',
        ]);

        $lead->update($validated);

        return response()->json(['success' => true, 'message' => 'Status lead diperbarui']);
    }

    public function bulkImport(Request $request)
    {
        // Future: bulk import dari CSV
        return view('admin.leads.bulk-import');
    }
}
