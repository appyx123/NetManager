<x-app-layout>
    <div class="min-h-screen bg-slate-950 py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <p class="text-xs font-black uppercase tracking-[0.2em] text-sky-400">Portal Teknisi</p>
                <h1 class="mt-2 text-3xl font-black tracking-tight text-white">Bursa Tugas</h1>
                <p class="mt-2 text-slate-400">Pilih tiket terbuka yang siap Anda kerjakan.</p>
            </div>

            @if (session('error'))
                <div class="mb-6 rounded-2xl border border-rose-500/20 bg-rose-500/10 p-4 text-sm font-semibold text-rose-300">{{ session('error') }}</div>
            @endif

            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                @forelse ($tickets as $ticket)
                    @php
                        $type = ucfirst($ticket->type ?? 'Tugas');
                        $typeClass = match ($ticket->type) {
                            'survey' => 'text-amber-400 bg-amber-500/10 border-amber-500/20',
                            'installation' => 'text-indigo-400 bg-indigo-500/10 border-indigo-500/20',
                            'repair' => 'text-rose-400 bg-rose-500/10 border-rose-500/20',
                            default => 'text-sky-400 bg-sky-500/10 border-sky-500/20',
                        };
                    @endphp
                    <article class="flex flex-col rounded-2xl border border-slate-800 bg-slate-900/80 p-6 shadow-xl backdrop-blur-md">
                        <div class="flex items-start justify-between gap-4">
                            <span class="rounded-full border px-3 py-1 text-xs font-bold uppercase {{ $typeClass }}">{{ $type }}</span>
                            <span class="text-xs font-bold text-slate-500">#{{ $ticket->id }}</span>
                        </div>
                        <h2 class="mt-5 text-xl font-bold text-white">{{ $ticket->customer?->user?->name ?? 'Pelanggan' }}</h2>
                        <p class="mt-2 text-sm text-slate-400">{{ $ticket->customer?->address_installation ?? 'Alamat belum tersedia' }}</p>
                        <p class="mt-4 line-clamp-3 text-sm leading-6 text-slate-300">{{ $ticket->description ?: 'Tidak ada deskripsi gangguan.' }}</p>
                        <a href="{{ route('technician.ticket.show', $ticket) }}" class="mt-6 inline-flex items-center justify-center rounded-xl bg-sky-500/10 px-4 py-3 text-sm font-bold text-sky-300 transition hover:bg-sky-500 hover:text-white">Lihat Rincian</a>
                    </article>
                @empty
                    <div class="rounded-2xl border border-slate-800 bg-slate-900/80 p-10 text-center text-slate-400 md:col-span-2 xl:col-span-3">Belum ada tiket terbuka saat ini.</div>
                @endforelse
            </div>

            @if($tickets->hasPages())
                <div class="mt-8 bg-slate-900/80 backdrop-blur-sm rounded-2xl border border-slate-800 px-6 shadow-xl">
                    {{ $tickets->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>