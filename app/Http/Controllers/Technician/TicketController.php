<?php

namespace App\Http\Controllers\Technician;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Exception;

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
            ->get();

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
            ->get();

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

        return redirect()->route('technician.process.index')
            ->with('success', 'Laporan pekerjaan berhasil disimpan.');
    }

    public function historyIndex()
    {
        $tickets = Ticket::with(['customer.user'])
            ->where('technician_id', Auth::id())
            ->whereIn('status', ['closed', 'resolved'])
            ->latest('completed_at')
            ->get();

        return view('technician.history.index', compact('tickets'));
    }
}