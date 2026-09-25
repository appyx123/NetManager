<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CustomerDocumentController extends Controller
{
    /**
     * Tampilkan berkas KTP secara aman bagi user yang terotentikasi dan memiliki izin.
     */
    public function showKtp(Lead $lead)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Hanya staf operasional atau customer pemilik lead bersangkutan
        $isStaff = in_array($user->role, ['super_admin', 'admin', 'marketing', 'technician']);
        $isOwner = ($user->role === 'customer' && $user->customer?->lead_id === $lead->id);

        if (!$isStaff && !$isOwner) {
            abort(403, 'Akses Ditolak. Anda tidak memiliki izin untuk melihat dokumen ini.');
        }

        if (empty($lead->ktp_image_path)) {
            abort(404, 'Dokumen KTP tidak ditemukan pada lead ini.');
        }

        // Cek disk local (penyimpanan privat baru)
        if (Storage::disk('local')->exists($lead->ktp_image_path)) {
            return response()->file(Storage::disk('local')->path($lead->ktp_image_path));
        }

        // Fallback backward-compatibility untuk file lama di disk public
        if (Storage::disk('public')->exists($lead->ktp_image_path)) {
            return response()->file(Storage::disk('public')->path($lead->ktp_image_path));
        }

        abort(404, 'File fisik KTP tidak ditemukan pada server.');
    }
}
