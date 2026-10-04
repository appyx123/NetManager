<x-app-layout>
    <div class="py-10 bg-slate-950 min-h-screen selection:bg-indigo-500/30">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-10">
                <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-white to-slate-400 tracking-tight">Tagihan & Pembayaran</h2>
                <p class="text-slate-400 mt-2 font-medium">Pantau riwayat tagihan dan kelola pembayaran layanan internet Anda.</p>
            </div>

            <div class="bg-slate-900/80 backdrop-blur-md rounded-2xl shadow-xl border border-slate-800 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-700/50 bg-slate-800/30 flex items-center gap-3">
                    <div class="p-2 bg-indigo-500/10 rounded-lg border border-indigo-500/20">
                        <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h3 class="font-bold text-white text-lg tracking-wide">Riwayat Tagihan</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-800/50 text-xs font-semibold text-slate-400 uppercase tracking-wider border-b border-slate-700/50">
                            <tr>
                                <th class="px-6 py-4">Informasi Tagihan</th>
                                <th class="px-6 py-4">Total Tagihan</th>
                                <th class="px-6 py-4">Jatuh Tempo</th>
                                <th class="px-6 py-4 text-center">Status</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60 text-sm">
                            @forelse($invoices as $inv)
                                <tr class="hover:bg-slate-800/40 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="font-mono text-sm font-bold text-indigo-400 bg-indigo-500/10 inline-block px-2 py-0.5 rounded border border-indigo-500/20">
                                            {{ $inv->invoice_number }}
                                        </div>
                                        <div class="text-xs text-slate-500 mt-1.5 font-medium flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            Terbit: {{ $inv->created_at->format('d M Y') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-black text-white text-base">
                                            Rp {{ number_format($inv->amount, 0, ',', '.') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($inv->due_date)
                                            <div class="font-medium {{ $inv->due_date < now() && $inv->status == 'unpaid' ? 'text-rose-400 font-bold bg-rose-500/10 px-2 py-0.5 rounded inline-block border border-rose-500/20' : 'text-slate-300' }}">
                                                {{ \Carbon\Carbon::parse($inv->due_date)->format('d M Y') }}
                                            </div>
                                            @if($inv->due_date < now() && $inv->status == 'unpaid')
                                                <div class="text-[10px] text-rose-500 uppercase font-bold mt-1 tracking-widest">Terlambat</div>
                                            @endif
                                        @else
                                            <span class="text-slate-500">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if ($inv->status === 'paid')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 shadow-inner uppercase tracking-wider">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Lunas
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold bg-rose-500/10 text-rose-400 border border-rose-500/20 shadow-inner uppercase tracking-wider">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                                                Belum Bayar
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('client.billing.show', $inv->id) }}"
                                            class="inline-flex items-center px-4 py-2 bg-slate-800 border border-slate-700 shadow-sm text-xs font-bold rounded-xl text-slate-300 hover:bg-slate-700 hover:text-white transition-colors duration-200">
                                            Lihat Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center justify-center text-slate-400">
                                            <div class="w-16 h-16 mb-4 bg-slate-800/50 border border-slate-700/50 rounded-full flex items-center justify-center">
                                                <svg class="w-8 h-8 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            </div>
                                            <p class="font-bold text-slate-300 text-lg">Belum Ada Tagihan</p>
                                            <p class="text-sm mt-1">Anda belum memiliki riwayat tagihan pada akun ini.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($invoices->hasPages())
                    <div class="px-6 border-t border-slate-800/80 bg-slate-900/40">
                        {{ $invoices->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>