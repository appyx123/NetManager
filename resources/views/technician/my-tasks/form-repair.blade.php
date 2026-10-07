<x-app-layout>
    <div class="min-h-screen bg-slate-950 py-10 selection:bg-rose-500/30">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <a href="{{ route('technician.tasks.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-400 hover:text-white transition-colors mb-4 group">
                    <svg class="w-4 h-4 mr-1.5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali ke Daftar Tugas
                </a>
                <div class="mt-2">
                    <p class="text-xs font-black uppercase tracking-[0.2em] text-rose-400">Laporan Repair #{{ $ticket->id }}</p>
                    <h1 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-white to-slate-400 tracking-tight mt-1">Form Perbaikan</h1>
                </div>
            </div>
            <form method="POST" action="{{ route('technician.process.update', $ticket) }}" enctype="multipart/form-data" class="space-y-6 rounded-2xl border border-slate-800 bg-slate-900/80 p-6 sm:p-8 shadow-xl backdrop-blur-md">
            @csrf @method('PUT')
            <div><label class="label">Kondisi Perangkat Fisik</label><select name="device_condition" required class="field"><option value="">Pilih kondisi</option><option value="baik">Baik</option><option value="rusak">Rusak</option><option value="diganti">Diganti</option></select></div>
            <div><label class="label">Catatan Perbaikan</label><textarea name="technical_notes" rows="5" required class="field">{{ old('technical_notes', $ticket->technical_notes) }}</textarea></div>
            <div><label class="label">Status Jaringan Pasca-perbaikan</label><select name="connectivity_status" required class="field"><option value="">Pilih status</option><option value="normal">Normal</option><option value="intermittent">Intermiten</option><option value="offline">Offline</option></select></div>
            <div><label class="label">Foto Dokumentasi Perbaikan</label><input type="file" name="evidence_photo_path" accept="image/*" required class="field"></div>
            <button class="w-full rounded-xl bg-rose-500 px-5 py-3 font-black text-white hover:bg-rose-400">Simpan Laporan Perbaikan</button>
        </form>
    </div></div>
    <style>.label{display:block;margin-bottom:.5rem;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#94a3b8}.field{width:100%;border-radius:.75rem;border:1px solid #334155;background:#1e293b;color:#e2e8f0;padding:.75rem}.field:focus{outline:2px solid #f43f5e}</style>
</x-app-layout>