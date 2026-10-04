<x-app-layout>
    <div class="min-h-screen bg-slate-950 py-10"><div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
        <div class="mb-8"><p class="text-xs font-black uppercase tracking-[0.2em] text-amber-400">Laporan Survey #{{ $ticket->id }}</p><h1 class="mt-2 text-3xl font-black text-white">Form Survey Lapangan</h1></div>
        <form method="POST" action="{{ route('technician.process.update', $ticket) }}" enctype="multipart/form-data" class="space-y-6 rounded-[2.5rem] border border-slate-800 bg-slate-900/80 p-8 shadow-2xl backdrop-blur-md">
            @csrf @method('PUT')
            <div><label class="label">Status Kelayakan</label><select name="survey_status" required class="field"><option value="">Pilih status</option><option value="layak">Layak</option><option value="tidak_layak">Tidak layak</option></select></div>
            <div class="grid gap-6 sm:grid-cols-2">
                <div>
                    <label class="label">Rekomendasi ODP Target</label>
                    <select name="odp_id" class="field">
                        <option value="">-- Pilih Titik ODP --</option>
                        @foreach($odps ?? [] as $odp)
                            <option value="{{ $odp->id }}" {{ old('odp_id', $ticket->odp_id ?? $ticket->lead?->odp_id) == $odp->id ? 'selected' : '' }}>
                                {{ $odp->name }} (Sisa: {{ $odp->odp_available_ports }}/{{ $odp->port_capacity ?? 8 }} Port)
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label">Rekomendasi Port ODP Kosong</label>
                    <input type="text" name="odp_port" value="{{ old('odp_port', $ticket->odp_port ?? $ticket->lead?->odp_port) }}" placeholder="Contoh: Port 4" class="field">
                </div>
            </div>
            <div><label class="label">Catatan Lapangan</label><textarea name="survey_notes" rows="4" required class="field">{{ old('survey_notes', $ticket->survey_notes) }}</textarea></div>
            <div><label class="label">Hambatan Lokasi</label><textarea name="location_obstacle" rows="3" class="field">{{ old('location_obstacle', $ticket->location_obstacle) }}</textarea></div>
            <div><label class="label">Foto Lokasi</label><input type="file" name="location_photo_path" accept="image/*" class="field"></div>
            <button class="w-full rounded-xl bg-amber-500 px-5 py-3 font-black text-white hover:bg-amber-400">Simpan Laporan Survey</button>
        </form>
    </div></div>
    <style>.label{display:block;margin-bottom:.5rem;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#94a3b8}.field{width:100%;border-radius:.75rem;border:1px solid #334155;background:#1e293b;color:#e2e8f0;padding:.75rem}.field:focus{outline:2px solid #f59e0b}</style>
</x-app-layout>