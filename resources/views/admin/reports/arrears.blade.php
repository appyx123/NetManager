<x-app-layout>
    <div class="py-10 bg-slate-950 min-h-screen selection:bg-purple-500/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-8">
                <a href="{{ route('admin.reports.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-400 hover:text-white transition-colors mb-4 group">
                    <svg class="w-4 h-4 mr-1.5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Pusat Laporan
                </a>
                <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-white to-slate-400 tracking-tight">Laporan Rekap Tunggakan</h2>
            </div>

            <div class="bg-slate-900/80 backdrop-blur-md rounded-2xl shadow-xl border border-slate-800 p-6 mb-8">
                <form action="{{ route('admin.reports.arrears') }}" method="GET" class="flex flex-col sm:flex-row items-end gap-5">
                    <div class="w-full sm:w-1/3">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Tampilkan Tunggakan Sampai Tanggal</label>
                        <input type="date" name="to_date" value="{{ $toDate }}" class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-500/50 focus:border-rose-500 transition-all text-slate-300">
                    </div>
                    <div class="w-full sm:w-auto">
                        <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-rose-600 text-white font-bold rounded-xl hover:bg-rose-500 shadow-[0_0_15px_rgba(225,29,72,0.3)] hover:shadow-[0_0_20px_rgba(225,29,72,0.5)] transition-all flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                            Terapkan Filter
                        </button>
                    </div>
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-slate-900/60 backdrop-blur-md rounded-2xl border border-rose-500/20 p-6 flex flex-col justify-center relative overflow-hidden group hover:border-rose-500/40 transition-colors">
                    <div class="absolute -right-4 -bottom-4 text-rose-500/5 group-hover:text-rose-500/10 transition-colors">
                        <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                    </div>
                    <p class="text-rose-400/80 text-xs font-bold uppercase tracking-wider mb-2">Total Nilai Tunggakan</p>
                    <p class="text-3xl font-black text-rose-400 relative z-10">Rp {{ number_format($totalArrears, 0, ',', '.') }}</p>
                </div>
                
                <div class="bg-slate-900/60 backdrop-blur-md rounded-2xl border border-slate-800 p-6 flex flex-col justify-center relative overflow-hidden group hover:border-slate-700 transition-colors">
                    <div class="absolute -right-4 -bottom-4 text-slate-600/10 group-hover:text-slate-500/20 transition-colors">
                        <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                    </div>
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-2">Jumlah Tagihan Menunggak</p>
                    <p class="text-3xl font-black text-white relative z-10">{{ $arrears->count() }} <span class="text-sm font-medium text-slate-500">Lembar</span></p>
                </div>
                
                <div class="bg-slate-900/60 backdrop-blur-md rounded-2xl border border-amber-500/20 p-6 flex flex-col justify-center relative overflow-hidden group hover:border-amber-500/40 transition-colors">
                    <div class="absolute -right-4 -bottom-4 text-amber-500/5 group-hover:text-amber-500/10 transition-colors">
                        <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M16 11V3H8v6H2v12h20V11h-6zm-6-6h4v14h-4V5zm-6 6h4v8H4v-8zm16 8h-4v-6h4v6z"/></svg>
                    </div>
                    <p class="text-amber-400/80 text-xs font-bold uppercase tracking-wider mb-2">Rata-Rata per Tagihan</p>
                    <p class="text-3xl font-black text-amber-400 relative z-10">Rp {{ number_format($arrears->count() > 0 ? $totalArrears / $arrears->count() : 0, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="bg-slate-900/80 backdrop-blur-md rounded-2xl shadow-xl border border-slate-800 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-700/50 bg-slate-800/30 flex items-center gap-3">
                    <div class="p-1.5 bg-rose-500/20 rounded-lg">
                        <svg class="w-5 h-5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="font-bold text-white text-lg tracking-wide">Daftar Tagihan Menunggak <span class="text-rose-400 font-medium text-sm ml-2">(Sampai {{ \Carbon\Carbon::parse($toDate)->format('d M Y') }})</span></h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-800/50 text-xs font-semibold text-slate-400 uppercase tracking-wider border-b border-slate-700/50">
                            <tr>
                                <th class="px-6 py-4">No. Tagihan</th>
                                <th class="px-6 py-4">Informasi Pelanggan</th>
                                <th class="px-6 py-4">Total Tunggakan</th>
                                <th class="px-6 py-4">Tgl. Jatuh Tempo</th>
                                <th class="px-6 py-4 text-right">Keterlambatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60 text-sm">
                            @forelse($arrears as $invoice)
                                <tr class="hover:bg-slate-800/40 transition-colors group">
                                    <td class="px-6 py-4">
                                        <span class="font-mono text-amber-400 font-bold bg-amber-500/10 px-2.5 py-1 rounded border border-amber-500/20">
                                            {{ $invoice->invoice_number }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-200 group-hover:text-white transition-colors">{{ $invoice->subscription->customer->user->name }}</div>
                                        <div class="text-xs text-slate-500 font-medium tracking-wider uppercase mt-1">{{ $invoice->subscription->customer->customer_code ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-white font-bold text-base">
                                        Rp {{ number_format($invoice->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-slate-300 font-medium">
                                        {{ $invoice->due_date->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md text-xs font-bold bg-rose-500/10 text-rose-400 border border-rose-500/20 shadow-sm shadow-rose-500/10">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Lewat {{ (int) ceil(now()->diffInDays($invoice->due_date)) }} Hari
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center justify-center text-slate-400">
                                            <div class="w-16 h-16 mb-4 bg-emerald-500/10 border border-emerald-500/20 rounded-full flex items-center justify-center">
                                                <svg class="w-8 h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"></path></svg>
                                            </div>
                                            <p class="font-bold text-emerald-400 text-lg">Kabar Baik!</p>
                                            <p class="text-sm mt-1">Tidak ada catatan tunggakan invoice sampai tanggal tersebut.</p>
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