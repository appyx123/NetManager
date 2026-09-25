<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller; // Wajib import Base Controller
use App\Models\Lead;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Customer;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class LeadController extends Controller
{
    // 1. INDEX: Menampilkan Daftar Prospek
    public function index()
    {
        $user = Auth::user();

        // Admin/SuperAdmin lihat semua, Marketing lihat miliknya sendiri
        if (in_array($user->role, ['admin', 'super_admin'])) {
            $leads = Lead::with('package')->orderBy('created_at', 'desc')->paginate(15);
        } else {
            $leads = Lead::with('package')->where('marketing_id', $user->id)->orderBy('created_at', 'desc')->paginate(15);
        }

        // PERUBAHAN PATH VIEW
        return view('marketing.leads.index', compact('leads'));
    }

    // 2. CREATE: Form Input Baru
    public function create()
    {
        $packages = Package::where('is_active', true)->get();

        // PERUBAHAN PATH VIEW
        return view('marketing.leads.create', compact('packages'));
    }

    // 3. STORE: Simpan Data Baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string',
            'address_installation' => 'required|string',
            'package_id' => 'required|exists:packages,id',
            'customer_type' => 'required|in:personal,business',
            'address' => 'required|string',
            'district' => 'required|string',
            'city' => 'required|string',
            'ktp_image' => 'nullable|image|max:5120',
            'house_image' => 'nullable|image|max:5120',
            'customer_image' => 'nullable|image|max:5120',
        ]);

        $ktpPath = $request->file('ktp_image') ? $request->file('ktp_image')->store('uploads/ktp', 'local') : null;
        $housePath = $request->file('house_image') ? $request->file('house_image')->store('uploads/house', 'public') : null;
        $custPath = $request->file('customer_image') ? $request->file('customer_image')->store('uploads/customer', 'public') : null;

        Lead::create([
            'marketing_id' => Auth::id(),
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'customer_type' => $request->customer_type,
            'business_name' => $request->business_name,
            'address_ktp' => $request->address_ktp,
            'address_installation' => $request->address_installation,
            'emergency_name' => $request->emergency_name,
            'emergency_phone' => $request->emergency_phone,
            'emergency_relation' => $request->emergency_relation,
            'address' => $request->address,
            'rt_rw' => $request->rt_rw,
            'village' => $request->village,
            'district' => $request->district,
            'city' => $request->city,
            'province' => $request->province,
            'postal_code' => $request->postal_code,
            'landmark' => $request->landmark,
            'coordinates' => $request->coordinates,
            'package_id' => $request->package_id,
            'promo_code' => $request->promo_code,
            'status' => 'prospek',
            'source' => $request->source,
            'survey_date' => $request->survey_date,
            'installation_date' => $request->installation_date,
            'preferred_time' => $request->preferred_time,
            'notes_summary' => $request->notes_summary,
            'notes_obstacle' => $request->notes_obstacle,
            'notes_special' => $request->notes_special,
            'ktp_image_path' => $ktpPath,
            'house_image_path' => $housePath,
            'customer_image_path' => $custPath,
        ]);

        return redirect()->route('marketing.leads.index')->with('success', 'Prospek berhasil disimpan!');
    }

    // 4. SHOW: Lihat Detail
    public function show(Lead $lead)
    {
        if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'super_admin' && $lead->marketing_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        // PERUBAHAN PATH VIEW
        return view('marketing.leads.show', compact('lead'));
    }

    // 5. EDIT: Form Edit
    public function edit(Lead $lead)
    {
        if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'super_admin' && $lead->marketing_id !== Auth::id()) {
            abort(403);
        }

        if ($lead->status === 'converted') {
            return back()->with('error', 'Data yang sudah menjadi pelanggan tidak bisa diedit.');
        }

        $packages = Package::where('is_active', true)->get();

        // PERUBAHAN PATH VIEW
        return view('marketing.leads.edit', compact('lead', 'packages'));
    }

    // 6. UPDATE: Simpan Perubahan
    public function update(Request $request, Lead $lead)
    {
        if ($lead->status === 'converted') {
            return back()->with('error', 'Data terkunci (sudah convert).');
        }

        $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'package_id' => 'required|exists:packages,id',
            'ktp_image' => 'nullable|image|max:5120',
            'house_image' => 'nullable|image|max:5120',
            'customer_image' => 'nullable|image|max:5120',
        ]);

        $ktpPath = $lead->ktp_image_path;
        if ($request->hasFile('ktp_image')) {
            if ($ktpPath) {
                if (Storage::disk('local')->exists($ktpPath)) {
                    Storage::disk('local')->delete($ktpPath);
                } elseif (Storage::disk('public')->exists($ktpPath)) {
                    Storage::disk('public')->delete($ktpPath);
                }
            }
            $ktpPath = $request->file('ktp_image')->store('uploads/ktp', 'local');
        }

        $housePath = $lead->house_image_path;
        if ($request->hasFile('house_image')) {
            if ($housePath && Storage::disk('public')->exists($housePath)) {
                Storage::disk('public')->delete($housePath);
            }
            $housePath = $request->file('house_image')->store('uploads/house', 'public');
        }

        $custPath = $lead->customer_image_path;
        if ($request->hasFile('customer_image')) {
            if ($custPath && Storage::disk('public')->exists($custPath)) {
                Storage::disk('public')->delete($custPath);
            }
            $custPath = $request->file('customer_image')->store('uploads/customer', 'public');
        }

        $lead->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'customer_type' => $request->customer_type,
            'business_name' => $request->business_name,
            'emergency_name' => $request->emergency_name,
            'emergency_phone' => $request->emergency_phone,
            'emergency_relation' => $request->emergency_relation,
            'address' => $request->address,
            'address_ktp' => $request->address_ktp,
            'address_installation' => $request->address_installation,
            'rt_rw' => $request->rt_rw,
            'village' => $request->village,
            'district' => $request->district,
            'city' => $request->city,
            'province' => $request->province,
            'postal_code' => $request->postal_code,
            'landmark' => $request->landmark,
            'coordinates' => $request->coordinates,
            'package_id' => $request->package_id,
            'promo_code' => $request->promo_code,
            'status' => $request->status ?? $lead->status, // Mengizinkan update status
            'source' => $request->source,
            'survey_date' => $request->survey_date,
            'installation_date' => $request->installation_date,
            'preferred_time' => $request->preferred_time,
            'notes_summary' => $request->notes_summary,
            'notes_obstacle' => $request->notes_obstacle,
            'notes_special' => $request->notes_special,
            'ktp_image_path' => $ktpPath,
            'house_image_path' => $housePath,
            'customer_image_path' => $custPath,
        ]);

        return redirect()->route('marketing.leads.index')->with('success', 'Data berhasil diperbarui.');
    }

    // 7. DESTROY: Hapus Data
    public function destroy(Lead $lead)
    {
        if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'super_admin' && $lead->marketing_id !== Auth::id()) {
            abort(403);
        }

        if ($lead->ktp_image_path && Storage::disk('public')->exists($lead->ktp_image_path)) {
            Storage::disk('public')->delete($lead->ktp_image_path);
        }
        if ($lead->house_image_path && Storage::disk('public')->exists($lead->house_image_path)) {
            Storage::disk('public')->delete($lead->house_image_path);
        }
        if ($lead->customer_image_path && Storage::disk('public')->exists($lead->customer_image_path)) {
            Storage::disk('public')->delete($lead->customer_image_path);
        }

        $lead->delete();
        return redirect()->route('marketing.leads.index')->with('success', 'Data prospek dihapus permanen.');
    }

    // 8. CONVERT: Jadi Pelanggan & Buat Tiket
    public function convert(Request $request, Lead $lead)
    {
        return $this->convertToCustomer($request, $lead);
    }

    public function convertToCustomer(Request $request, Lead $lead)
    {
        if ($lead->status === 'converted' || $lead->status === 'aktif') {
            return back()->with('error', 'Sudah menjadi pelanggan.');
        }

        DB::transaction(function () use ($lead) {
            // A. Buat Akun Login User
            $uniqueId = 'CUST-' . strtoupper(Str::random(5));
            $password = Str::random(8);

            $user = User::create([
                'name' => $lead->name,
                'email' => $lead->email ?? strtolower(str_replace(' ', '', $lead->name)) . rand(100, 999) . '@net.local',
                'password' => Hash::make($password),
                'role' => 'customer',
                'is_active' => true,
            ]);

            // B. Buat Data Customer
            $customer = Customer::create([
                'user_id' => $user->id,
                'lead_id' => $lead->id,
                'customer_code' => $uniqueId,
                'phone_number' => $lead->phone,
                'address_installation' => $lead->address_installation ?? $lead->address,
                'coordinates' => $lead->coordinates,
            ]);

            // C. Update Status Lead
            $lead->update(['status' => 'converted']);

            // D. Buat Tiket Langsung Terhubung ke Customer (Bypass model InstallationForm yang usang)
            $customer->tickets()->create([
                'technician_id' => null, // Belum ada teknisi
                'type' => 'installation',
                'status' => 'open',
                'subject' => 'Pasang Baru: ' . ($lead->package->name ?? 'Paket Kustom'),
                'description' => 'Instalasi pelanggan baru ' . $lead->name . '. Paket: ' . ($lead->package->name ?? '-') . '. Alamat: ' . ($lead->address_installation ?? $lead->address),
                'connection_type' => 'fiber',
                'notes' => $lead->notes_summary ?? null,
            ]);

            session()->flash('generated_credential', [
                'username' => $user->email,
                'password' => $password,
                'code' => $uniqueId,
            ]);
        });

        return redirect()->route('marketing.leads.index')->with('success', 'Konversi Berhasil! Akun Pelanggan & Tiket Instalasi (Jobdesk Teknisi) telah dibuat.');
    }
}
