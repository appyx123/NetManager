<x-app-layout>
    <div class="py-10 bg-slate-950 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <a href="{{ route('client.complaints.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-400 hover:text-white transition-colors mb-4 group">
                    <svg class="w-4 h-4 mr-1.5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Daftar Pengajuan
                </a>
                <div class="flex items-center gap-3">
                    <h1 class="text-3xl font-black text-white">Detail Pengajuan</h1>
                    <span class="text-slate-400 font-mono">#{{ $ticket->id }}</span>
                </div>
            </div>

            <div class="bg-slate-800 border border-slate-700 rounded-2xl p-6 sm:p-8">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 border-b border-slate-700 pb-6">
                    <div>
                        <p class="text-sm text-slate-400">{{ $ticket->created_at->format('d M Y H:i') }}</p>
                        <h2 class="text-2xl font-bold text-white mt-2">{{ $ticket->subject }}</h2>
                    </div>
                    <span class="inline-flex self-start px-3 py-1 rounded-full text-xs font-semibold bg-emerald-900/30 text-emerald-300 border border-emerald-700 capitalize">
                        {{ str_replace('_', ' ', $ticket->status) }}
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 py-6 border-b border-slate-700">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Teknisi</p>
                        <p class="text-slate-200 mt-2">{{ $ticket->technician?->name ?? 'Belum ditugaskan' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Tipe Laporan</p>
                        <p class="text-slate-200 mt-2 capitalize">{{ $ticket->type ?? 'Gangguan' }}</p>
                    </div>
                </div>

                <div class="py-6">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Deskripsi Masalah</p>
                    <p class="text-slate-200 mt-3 leading-relaxed whitespace-pre-line">{{ $ticket->description ?: 'Tidak ada deskripsi.' }}</p>
                </div>

                @if ($ticket->notes || $ticket->technical_notes || $ticket->final_technician_notes)
                    <div class="border-t border-slate-700 pt-6">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Catatan Penanganan</p>
                        <p class="text-slate-200 mt-3 leading-relaxed whitespace-pre-line">{{ $ticket->technical_notes ?: $ticket->final_technician_notes ?: $ticket->notes }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
