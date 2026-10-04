<x-app-layout>
    <div class="min-h-screen bg-slate-950 py-10">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <a href="javascript:history.back()" class="inline-flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali
            </a>
            <div class="mt-6 rounded-2xl border border-slate-800 bg-slate-900/80 p-6 sm:p-8 shadow-2xl backdrop-blur-md">
                <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-start">
                    <div>
                        <p class="text-xs font-black uppercase tracking-[0.2em] text-sky-400">Detail Tiket #{{ $ticket->id }}</p>
                        <h1 class="mt-2 text-2xl sm:text-3xl font-black text-white">{{ $ticket->subject ?: 'Tugas Lapangan' }}</h1>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="rounded-full bg-slate-800 border border-slate-700 px-3 py-1 text-xs font-bold uppercase text-slate-300">{{ str_replace('_', ' ', $ticket->status) }}</span>
                        <span class="rounded-full bg-sky-500/10 border border-sky-500/20 px-3 py-1 text-xs font-bold uppercase text-sky-400">{{ $ticket->type }}</span>
                    </div>
                </div>

                <div class="mt-8 grid gap-6 sm:grid-cols-2">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Pelanggan</p>
                        <p class="mt-2 font-bold text-white text-base">{{ $ticket->customer?->user?->name ?? '-' }}</p>
                        @if($ticket->customer?->phone)
                            <p class="mt-1.5 text-xs text-slate-400 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                {{ $ticket->customer->phone }}
                            </p>
                        @endif
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Alamat Pemasangan</p>
                        <p class="mt-2 text-slate-300 leading-relaxed">{{ $ticket->customer?->address_installation ?? '-' }}</p>
                    </div>
                </div>

                <div class="mt-8 border-t border-slate-800 pt-8">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Deskripsi Gangguan / Kebutuhan</p>
                    <p class="mt-3 whitespace-pre-line leading-7 text-slate-300 bg-slate-950/50 p-4 rounded-xl border border-slate-800/80">{{ $ticket->description ?: 'Tidak ada deskripsi.' }}</p>
                </div>

                @if($ticket->status === 'open')
                    <form method="POST" action="{{ route('technician.ticket.take', $ticket) }}" class="mt-8">
                        @csrf
                        <button type="submit" class="w-full rounded-xl bg-sky-500 px-5 py-3.5 font-bold text-white transition hover:bg-sky-400 shadow-lg shadow-sky-500/20">Ambil Tugas</button>
                    </form>
                @elseif(in_array($ticket->status, ['assigned', 'in_progress']) && $ticket->technician_id === auth()->id())
                    <div class="mt-8">
                        <a href="{{ route('technician.process.show', $ticket) }}" class="block text-center w-full rounded-xl bg-indigo-600 hover:bg-indigo-500 px-5 py-3.5 font-bold text-white transition shadow-lg shadow-indigo-600/30">
                            Lanjutkan Pengerjaan (Kerjakan)
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>