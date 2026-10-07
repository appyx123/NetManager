<x-app-layout>
    <div class="py-10 bg-slate-950 min-h-screen selection:bg-amber-500/30">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-white to-slate-400 tracking-tight">Pusat Bantuan (Pengaduan)</h2>
                    <p class="text-slate-400 mt-1 text-sm font-medium">Lacak status laporan kendala internet Anda di sini.</p>
                </div>
                <div>
                    <a href="{{ route('client.tickets.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 text-sm font-bold rounded-xl shadow-lg shadow-amber-500/20 transform hover:-translate-y-0.5 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        Buat Pengaduan Baru
                    </a>
                </div>
            </div>

            <div class="bg-slate-900/80 backdrop-blur-md rounded-2xl shadow-xl border border-slate-800 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-800/50 text-xs font-semibold text-slate-400 uppercase tracking-wider border-b border-slate-700/50">
                            <tr>
                                <th class="px-6 py-4">ID Pengaduan / Subjek</th>
                                <th class="px-6 py-4">Kategori</th>
                                <th class="px-6 py-4">Tanggal Lapor</th>
                                <th class="px-6 py-4 text-center">Status</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60 text-sm">
                            @forelse($tickets ?? [] as $ticket)
                                <tr>
                                    <td class="px-6 py-4 text-white font-medium">#{{ $ticket->id }} - {{ $ticket->subject ?? 'Pengaduan Layanan' }}</td>
                                    <td class="px-6 py-4 text-slate-300 capitalize">{{ $ticket->type }}</td>
                                    <td class="px-6 py-4 text-slate-400">{{ $ticket->created_at?->format('d M Y H:i') }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-slate-800 text-slate-300 border border-slate-700">{{ $ticket->status }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('client.tickets.show', $ticket) }}" class="text-amber-400 hover:text-amber-300 font-bold text-xs">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-16 h-16 bg-slate-800/50 text-slate-500 rounded-full flex items-center justify-center mb-4 border border-slate-700/50">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            </div>
                                            <p class="text-slate-400 font-medium text-sm">Belum ada riwayat pengaduan.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
