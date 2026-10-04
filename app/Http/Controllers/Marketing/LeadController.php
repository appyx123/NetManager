<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller; // Wajib import Base Controller
use App\Models\Lead;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Customer;
use App\Models\Package;
use App\Models\Subscription;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Services\WhatsappService;

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
            'package_id' => 'required|exists:packages,id',
            'customer_type' => 'required|in:personal,business',
            'address' => 'required|string',
            'address_installation' => 'nullable|string',
            'village' => 'nullable|string',
            'district' => 'nullable|string',
            'city' => 'nullable|string',
            'ktp_image' => 'nullable|image|max:5120',
            'house_image' => 'nullable|image|max:5120',
            'customer_image' => 'nullable|image|max:5120',
        ]);

        $ktpPath = null;
        $housePath = null;
        $custPath = null;

        try {
            $ktpPath = $request->file('ktp_image') ? $request->file('ktp_image')->store('uploads/ktp', 'local') : null;
            $housePath = $request->file('house_image') ? $request->file('house_image')->store('uploads/house', 'public') : null;
            $custPath = $request->file('customer_image') ? $request->file('customer_image')->store('uploads/customer', 'local') : null;

            $lead = DB::transaction(function () use ($request, $ktpPath, $housePath, $custPath) {
                return Lead::create([
                    'marketing_id' => Auth::id(),
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'customer_type' => $request->customer_type,
                    'business_name' => $request->business_name,
                    'address_ktp' => $request->address_ktp,
                    'address_installation' => $request->address_installation ?? $request->address,
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
                    'installation_fee' => $request->filled('installation_fee') ? (float) preg_replace('/[^0-9]/', '', (string) $request->installation_fee) : 0,
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
            });

            if ($request->ajax() || $request->wantsJson()) {
                session()->flash('success', 'Prospek berhasil disimpan!');
                return response()->json([
                    'success' => true,
                    'message' => 'Prospek berhasil disimpan!',
                    'redirect' => route('marketing.leads.index')
                ]);
            }

            return redirect()->route('marketing.leads.index')->with('success', 'Prospek berhasil disimpan!');
        } catch (\Throwable $e) {
            if ($ktpPath && Storage::disk('local')->exists($ktpPath)) Storage::disk('local')->delete($ktpPath);
            if ($housePath && Storage::disk('public')->exists($housePath)) Storage::disk('public')->delete($housePath);
            if ($custPath && Storage::disk('local')->exists($custPath)) Storage::disk('local')->delete($custPath);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan data prospek: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()->with('error', 'Gagal menyimpan prospek: ' . $e->getMessage());
        }
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

        if ($lead->status === 'aktif') {
            return back()->with('error', 'Data yang sudah menjadi pelanggan tidak dapat diubah.');
        }

        $packages = Package::where('is_active', true)->get();

        // PERUBAHAN PATH VIEW
        return view('marketing.leads.edit', compact('lead', 'packages'));
    }

    // 6. UPDATE: Simpan Perubahan
    public function update(Request $request, Lead $lead)
    {
        if ($lead->status === 'aktif') {
            return back()->with('error', 'Data terkunci (sudah dikonversi menjadi pelanggan).');
        }

        $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'package_id' => 'required|exists:packages,id',
            'ktp_image' => 'nullable|image|max:5120',
            'house_image' => 'nullable|image|max:5120',
            'customer_image' => 'nullable|image|max:5120',
        ]);

        $oldKtp = $lead->ktp_image_path;
        $oldHouse = $lead->house_image_path;
        $oldCust = $lead->customer_image_path;

        $newKtp = null;
        $newHouse = null;
        $newCust = null;

        try {
            if ($request->hasFile('ktp_image')) {
                $newKtp = $request->file('ktp_image')->store('uploads/ktp', 'local');
            }
            if ($request->hasFile('house_image')) {
                $newHouse = $request->file('house_image')->store('uploads/house', 'public');
            }
            if ($request->hasFile('customer_image')) {
                $newCust = $request->file('customer_image')->store('uploads/customer', 'local');
            }

            DB::transaction(function () use ($request, $lead, $newKtp, $newHouse, $newCust, $oldKtp, $oldHouse, $oldCust) {
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
                    'installation_fee' => $request->filled('installation_fee') ? (float) preg_replace('/[^0-9]/', '', (string) $request->installation_fee) : 0,
                    'promo_code' => $request->promo_code,
                    'status' => $request->status ?? $lead->status, // Mengizinkan update status
                    'source' => $request->source,
                    'survey_date' => $request->survey_date,
                    'installation_date' => $request->installation_date,
                    'preferred_time' => $request->preferred_time,
                    'notes_summary' => $request->notes_summary,
                    'notes_obstacle' => $request->notes_obstacle,
                    'notes_special' => $request->notes_special,
                    'ktp_image_path' => $newKtp ?? $oldKtp,
                    'house_image_path' => $newHouse ?? $oldHouse,
                    'customer_image_path' => $newCust ?? $oldCust,
                ]);

                // Hapus file lama jika ada upload baru
                if ($newKtp && $oldKtp) {
                    if (Storage::disk('local')->exists($oldKtp)) Storage::disk('local')->delete($oldKtp);
                    elseif (Storage::disk('public')->exists($oldKtp)) Storage::disk('public')->delete($oldKtp);
                }
                if ($newHouse && $oldHouse && Storage::disk('public')->exists($oldHouse)) {
                    Storage::disk('public')->delete($oldHouse);
                }
                if ($newCust && $oldCust) {
                    if (Storage::disk('local')->exists($oldCust)) Storage::disk('local')->delete($oldCust);
                    elseif (Storage::disk('public')->exists($oldCust)) Storage::disk('public')->delete($oldCust);
                }
            });

            if ($request->ajax() || $request->wantsJson()) {
                session()->flash('success', 'Data berhasil diperbarui.');
                return response()->json([
                    'success' => true,
                    'message' => 'Data berhasil diperbarui.',
                    'redirect' => route('marketing.leads.index')
                ]);
            }

            return redirect()->route('marketing.leads.index')->with('success', 'Data berhasil diperbarui.');
        } catch (\Throwable $e) {
            // Hapus file baru yang gagal disimpan
            if ($newKtp && Storage::disk('local')->exists($newKtp)) Storage::disk('local')->delete($newKtp);
            if ($newHouse && Storage::disk('public')->exists($newHouse)) Storage::disk('public')->delete($newHouse);
            if ($newCust && Storage::disk('local')->exists($newCust)) Storage::disk('local')->delete($newCust);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui data: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    // 7. DESTROY: Hapus Data
    public function destroy(Lead $lead)
    {
        if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'super_admin' && $lead->marketing_id !== Auth::id()) {
            abort(403);
        }

        if ($lead->ktp_image_path) {
            if (Storage::disk('local')->exists($lead->ktp_image_path)) {
                Storage::disk('local')->delete($lead->ktp_image_path);
            } elseif (Storage::disk('public')->exists($lead->ktp_image_path)) {
                Storage::disk('public')->delete($lead->ktp_image_path);
            }
        }
        if ($lead->house_image_path && Storage::disk('public')->exists($lead->house_image_path)) {
            Storage::disk('public')->delete($lead->house_image_path);
        }
        if ($lead->customer_image_path) {
            if (Storage::disk('local')->exists($lead->customer_image_path)) {
                Storage::disk('local')->delete($lead->customer_image_path);
            } elseif (Storage::disk('public')->exists($lead->customer_image_path)) {
                Storage::disk('public')->delete($lead->customer_image_path);
            }
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
        if ($lead->status === 'aktif') {
            return back()->with('error', 'Sudah menjadi pelanggan.');
        }

        DB::transaction(function () use ($lead) {
            // A. Buat Akun Login User (Password default otomatis: 'password')
            $uniqueId = 'CUST-' . strtoupper(Str::random(5));
            $password = 'password';

            $user = User::create([
                'name' => $lead->name,
                'email' => $lead->email ?? strtolower(str_replace(' ', '', $lead->name)) . rand(100, 999) . '@net.local',
                'password' => Hash::make($password),
                'role' => 'customer',
                'is_active' => true,
            ]);

            // B. Buat Data Customer (Default: is_isolated = true untuk model Pasang Dulu Baru Bayar)
            $customer = Customer::create([
                'user_id' => $user->id,
                'lead_id' => $lead->id,
                'customer_code' => $uniqueId,
                'phone_number' => $lead->phone,
                'address_installation' => $lead->address_installation ?? $lead->address,
                'coordinates' => $lead->coordinates,
                'is_isolated' => true,
            ]);

            // C. Update Status Lead
            $lead->update(['status' => 'aktif']);

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

            // E. Buat Subscription Awal (Status isolated: Menunggu Pembayaran & Selesai Pasang)
            $package = $lead->package ?? ($lead->package_id ? Package::find($lead->package_id) : null);
            $subscription = Subscription::create([
                'customer_id'       => $customer->id,
                'package_id'        => $lead->package_id,
                'status'            => 'isolated',
                'installation_date' => now()->toDateString(),
                'billing_due_date'  => now()->addDays(7)->toDateString(),
            ]);

            // F. Buat Tagihan Perdana (Gabungan Biaya Paket + Biaya Instalasi)
            $packagePrice = $package ? (float) $package->price : 0;
            $installationFee = (float) ($lead->installation_fee ?? $package?->installation_fee ?? 0);
            $initialAmount = $packagePrice + $installationFee;

            Invoice::create([
                'subscription_id' => $subscription->id,
                'invoice_number'  => 'INV-' . strtoupper(Str::random(8)),
                'amount'          => $initialAmount,
                'status'          => 'unpaid',
                'due_date'        => now()->addDays(7)->toDateString(),
            ]);

            session()->flash('generated_credential', [
                'name' => $lead->name,
                'phone' => $lead->phone,
                'username' => $user->email,
                'password' => $password,
                'code' => $uniqueId,
            ]);

            // E. Otomatis Kirim Kredensial Login (Username & Password) ke WhatsApp Pelanggan
            if (!empty($lead->phone)) {
                try {
                    WhatsappService::sendAccountCreated(
                        $lead->name,
                        $lead->phone,
                        $uniqueId,
                        $user->email,
                        $password
                    );
                } catch (\Throwable $e) {
                    Log::error("Gagal mengirim WhatsApp kredensial akun pelanggan baru ({$uniqueId}): " . $e->getMessage());
                }
            }
        });

        return redirect()->route('marketing.leads.index')->with('success', 'Konversi Berhasil! Akun Pelanggan telah dibuat dan kredensial login otomatis dikirim ke WhatsApp.');
    }
}
