<?php

namespace App\Http\Controllers\Technician;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Subscription;
use App\Models\Invoice;
use App\Models\Package;
use App\Services\NetworkService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Exception;
use Throwable;

class TicketController extends Controller
{
    /**
     * Menampilkan daftar Bursa Tugas (Tiket Open)
     */
    public function index()
    {
        // Mengambil tiket yang belum diambil siapa pun (status: open)
        $tickets = Ticket::with(['customer', 'customer.user'])
            ->where('status', 'open')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        // Mengarahkan ke view yang Anda miliki: technician/open-tickets/index.blade.php
        return view('technician.open-tickets.index', compact('tickets'));
    }

    /**
     * Menampilkan detail tiket sebelum diambil
     */
    public function show(Ticket $ticket)
    {
        // Pastikan relasi dimuat
        $ticket->load(['customer', 'customer.user']);

        // Mengarahkan ke view: technician/open-tickets/show.blade.php
        return view('technician.open-tickets.show', compact('ticket'));
    }

    /**
     * Fungsi untuk Teknisi mengambil/mengklaim tugas
     */
    public function take(Request $request, Ticket $ticket)
    {
        try {
            DB::transaction(function () use ($ticket) {
                // Lock row tiket untuk mencegah race condition konkurensi teknisi lain
                $lockedTicket = Ticket::where('id', $ticket->id)->lockForUpdate()->firstOrFail();

                if ($lockedTicket->status !== 'open') {
                    throw new Exception('Maaf, tugas ini sudah diambil oleh teknisi lain atau sudah ditutup.');
                }

                $lockedTicket->update([
                    'technician_id' => Auth::id(),
                    'status' => 'assigned',
                ]);
            });

            return redirect()->route('technician.process.index')
                ->with('success', 'Tugas berhasil diambil! Silakan mulai pengerjaan dari Meja Kerja Anda.');
        } catch (\Throwable $e) {
            return redirect()->route('technician.ticket.index')
                ->with('error', $e->getMessage() ?: 'Gagal mengambil tugas.');
        }
    }

    /**
     * Menampilkan daftar "Meja Kerja" (Tugas yang sedang dikerjakan teknisi)
     */
    public function processIndex()
    {
        // Ambil tiket yang sudah diklaim oleh teknisi ini, dan belum selesai
        $tasks = Ticket::with(['customer', 'customer.user'])
            ->where('technician_id', Auth::id())
            ->whereIn('status', ['assigned', 'in_progress'])
            ->orderBy('updated_at', 'desc')
            ->paginate(15);

        // Mengarahkan ke view my-tasks/index.blade.php
        return view('technician.my-tasks.index', compact('tasks'));
    }

    /**
     * Membuka form pengerjaan tiket sesuai tipenya (Survey/Instalasi/Repair)
     */
    public function processShow(Ticket $ticket)
    {
        // Proteksi: Pastikan tiket ini benar-benar milik teknisi yang login
        if ($ticket->technician_id !== Auth::id()) {
            abort(403, 'Akses Ditolak! Anda tidak dapat membuka tugas milik teknisi lain.');
        }

        // Jika tugas baru saja dibuka pertama kali, ubah statusnya menjadi "in_progress" (Sedang dikerjakan)
        if ($ticket->status === 'assigned') {
            $ticket->update(['status' => 'in_progress']);
        }

        $ticket->load(['customer', 'customer.user']);

        // Arahkan ke form Blade yang tepat berdasarkan tipe tiket menggunakan struktur folder Anda
        return match ($ticket->type) {
            'survey'       => view('technician.my-tasks.form-survey', compact('ticket')),
            'installation' => view('technician.my-tasks.form-installation', compact('ticket')),
            'repair'       => view('technician.my-tasks.form-repair', compact('ticket')),
            default        => redirect()->route('technician.process.index')->with('error', 'Tipe tugas tidak dikenal.'),
        };
    }

    public function processUpdate(Request $request, Ticket $ticket)
    {
        abort_unless($ticket->technician_id === Auth::id(), 403);

        $validated = $request->validate([
            'survey_status' => 'nullable|string|max:100',
            'survey_notes' => 'nullable|string',
            'location_obstacle' => 'nullable|string',
            'installation_status' => 'nullable|string|max:100',
            'cable_length' => 'nullable|numeric|min:0',
            'device_brand' => 'nullable|string|max:100',
            'device_mac' => 'nullable|string|max:50',
            'odp_port' => 'nullable|string|max:50',
            'dbm_signal' => 'nullable|numeric',
            'device_condition' => 'nullable|string|max:100',
            'technical_notes' => 'nullable|string',
            'connectivity_status' => 'nullable|string|max:100',
            'location_photo_path' => 'nullable|image|max:5120',
            'evidence_photo_path' => 'nullable|image|max:5120',
        ]);

        foreach (['location_photo_path' => 'uploads/teknisi/lokasi', 'evidence_photo_path' => 'uploads/teknisi/bukti'] as $field => $directory) {
            if ($request->hasFile($field)) {
                // Bersihkan berkas foto lama dari storage disk public jika ada
                if ($ticket->$field && Storage::disk('public')->exists($ticket->$field)) {
                    Storage::disk('public')->delete($ticket->$field);
                }

                /** @var UploadedFile $file */
                $validated[$field] = $request->file($field)->store($directory, 'public');
            }
        }

        $ticket->update(array_merge($validated, [
            'technical_notes' => $validated['technical_notes'] ?? $ticket->technical_notes,
            'status' => 'resolved',
            'completed_at' => now(),
        ]));

        if ($ticket->type === 'installation') {
            $this->finalizeInstallation($ticket);
        }

        return redirect()->route('technician.process.index')
            ->with('success', 'Laporan pekerjaan berhasil disimpan.');
    }

    /**
     * Otomasi pasca-instalasi: pembuatan profil Subscription, Invoice perdana,
     * dan pendaftaran PPPoE Secret ke MikroTik dengan fault tolerance.
     */
    private function finalizeInstallation(Ticket $ticket): void
    {
        $subscription = null;

        // 1. Transaksi Database: Buat/Sinkronkan Subscription dan Invoice
        try {
            DB::transaction(function () use ($ticket, &$subscription) {
                $customer = $ticket->customer()->with('lead.package')->first();
                if (!$customer) {
                    return;
                }

                $packageId = $customer->lead?->package_id ?? Package::first()?->id;
                $username = $ticket->pppoe_username 
                    ?: ($customer->pppoe_username ?: strtolower($customer->customer_code));
                $password = $ticket->pppoe_password 
                    ?: ($customer->pppoe_password ?: 'net' . mt_rand(1000, 9999));

                // Pastikan kredensial tersimpan di tiket jika sebelumnya kosong
                if (empty($ticket->pppoe_username) || empty($ticket->pppoe_password)) {
                    $ticket->update([
                        'pppoe_username' => $username,
                        'pppoe_password' => $password,
                    ]);
                }

                // Buat atau dapatkan Subscription untuk customer ini (Model: Pasang Dulu, Baru Bayar)
                $subscription = Subscription::firstOrCreate(
                    ['customer_id' => $customer->id],
                    [
                        'package_id'        => $packageId,
                        'router_id'         => $ticket->router_id,
                        'pppoe_username'    => $username,
                        'pppoe_password'    => $password,
                        'installation_date' => now()->toDateString(),
                        'billing_due_date'  => now()->addDays(7)->toDateString(),
                        'status'            => 'isolated',
                    ]
                );

                // Sinkronkan router_id, username & password jika subscription sudah ada sebelumnya
                $subscriptionUpdates = [];
                if ($ticket->router_id && $subscription->router_id !== $ticket->router_id) {
                    $subscriptionUpdates['router_id'] = $ticket->router_id;
                }
                if ($subscription->pppoe_username !== $username) {
                    $subscriptionUpdates['pppoe_username'] = $username;
                }
                if ($subscription->pppoe_password !== $password) {
                    $subscriptionUpdates['pppoe_password'] = $password;
                }

                // Tahan akses (Isolasi Awal): Jika belum ada invoice yang lunas, pastikan status tetap terisolir
                $hasPaidInvoice = Invoice::where('subscription_id', $subscription->id)
                    ->where('status', 'paid')
                    ->exists();

                if (!$hasPaidInvoice) {
                    $subscriptionUpdates['status'] = 'isolated';
                    $customer->update(['is_isolated' => true]);
                }

                if (!empty($subscriptionUpdates)) {
                    $subscription->update($subscriptionUpdates);
                }

                // Buat Invoice Perdana jika belum ada tagihan unpaid
                $existingUnpaidInvoice = Invoice::where('subscription_id', $subscription->id)
                    ->where('status', 'unpaid')
                    ->first();

                if (!$existingUnpaidInvoice) {
                    $package = $subscription->package ?? Package::find($subscription->package_id);
                    $amount = $package ? $package->price : 0;

                    Invoice::create([
                        'subscription_id' => $subscription->id,
                        'invoice_number'  => 'INV-' . strtoupper(Str::random(8)),
                        'amount'          => $amount,
                        'status'          => 'unpaid',
                        'due_date'        => now()->addDays(7)->toDateString(),
                    ]);
                }

                // Update status lead ke aktif jika ada
                if ($customer->lead && $customer->lead->status !== 'aktif') {
                    $customer->lead->update(['status' => 'aktif']);
                }
            });
        } catch (Throwable $e) {
            Log::error("Gagal memproses transaksi DB pasca-instalasi Tiket #{$ticket->id}: " . $e->getMessage(), [
                'ticket_id' => $ticket->id,
                'trace'     => $e->getTraceAsString(),
            ]);
        }

        // 2. Operasi MikroTik API di luar transaksi DB dengan try-catch terisolasi (Fault Tolerance)
        if ($subscription) {
            try {
                $networkService = app(NetworkService::class);
                $networkService->addCustomer($subscription, $ticket);

                // Jika berstatus terisolir (menunggu pembayaran perdana), pastikan profile/status isolir diterapkan di router
                if ($subscription->status === 'isolated') {
                    $networkService->disableCustomer($subscription);
                }
            } catch (Throwable $e) {
                Log::error("MikroTik Provisioning Exception pada Tiket #{$ticket->id}: " . $e->getMessage(), [
                    'ticket_id'       => $ticket->id,
                    'subscription_id' => $subscription->id,
                ]);
            }
        }
    }

    public function historyIndex()
    {
        $tickets = Ticket::with(['customer.user'])
            ->where('technician_id', Auth::id())
            ->whereIn('status', ['closed', 'resolved'])
            ->latest('completed_at')
            ->paginate(15);

        return view('technician.history.index', compact('tickets'));
    }
}