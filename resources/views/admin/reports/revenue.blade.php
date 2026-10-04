<x-app-layout>
    <div class="py-10 bg-slate-950 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Back --}}
            <div class="mb-6">
                <a href="{{ route('admin.reports.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-400 hover:text-white transition-colors group">
                    <svg class="w-4 h-4 mr-1.5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Pusat Laporan
                </a>
            </div>

            {{-- Header & Filter --}}
            <div class="mb-8">
                <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-indigo-400 tracking-tight">Laporan Pendapatan</h2>
                <p class="text-slate-400 mt-2 font-medium">Kalkulasi total pendapatan dari invoice yang sudah lunas dalam periode tertentu.</p>
            </div>

            <form method="GET" class="bg-slate-900/80 rounded-2xl border border-slate-800 p-6 mb-8 flex flex-col md:flex-row items-end gap-4">
                <div class="w-full md:w-1/3">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Dari Tanggal</label>
                    <input type="date" name="from_date" value="{{ $fromDate }}" style="color-scheme: dark;"
                        class="w-full px-4 py-3 bg-slate-800/50 text-slate-300 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500/50 focus:border-purple-500 transition-all">
                </div>
                <div class="w-full md:w-1/3">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Sampai Tanggal</label>
                    <input type="date" name="to_date" value="{{ $toDate }}" style="color-scheme: dark;"
                        class="w-full px-4 py-3 bg-slate-800/50 text-slate-300 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500/50 focus:border-purple-500 transition-all">
                </div>
                <div class="flex gap-3 w-full md:w-auto">
                    <button type="submit" class="flex-1 md:flex-none px-6 py-3 bg-purple-600 text-white font-bold rounded-xl hover:bg-purple-500 transition-all">Filter</button>
                    <a href="{{ route('admin.reports.revenue') }}" class="flex-1 md:flex-none px-6 py-3 bg-slate-800 text-slate-300 font-bold rounded-xl border border-slate-700 hover:bg-slate-700 transition-all text-center">Reset</a>
                </div>
            </form>

            {{-- Summary Card --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="md:col-span-1 bg-gradient-to-br from-purple-900/40 to-indigo-900/40 rounded-2xl border border-purple-500/30 p-6">
                    <p class="text-xs font-bold text-purple-300 uppercase tracking-widest mb-2">Total Pendapatan</p>
                    <h3 class="text-3xl font-black text-white">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                    <p class="text-slate-400 text-sm mt-2">{{ $fromDate }} s/d {{ $toDate }}</p>
                </div>
                <div class="md:col-span-1 bg-slate-900/60 rounded-2xl border border-slate-800 p-6">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Jumlah Tagihan Lunas</p>
                    <h3 class="text-3xl font-black text-white">{{ $revenue->count() }}</h3>
                    <p class="text-slate-400 text-sm mt-2">Total transaksi dalam periode ini</p>
                </div>
                <div class="md:col-span-1 bg-slate-900/60 rounded-2xl border border-slate-800 p-6">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Rata-rata per Tagihan</p>
                    <h3 class="text-3xl font-black text-white">Rp {{ $revenue->count() > 0 ? number_format($totalRevenue / $revenue->count(), 0, ',', '.') : 0 }}</h3>
                    <p class="text-slate-400 text-sm mt-2">Nilai rata-rata transaksi lunas</p>
                </div>
            </div>

            {{-- Table --}}
            <div class="bg-slate-900/80 rounded-2xl border border-slate-800 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-700/50 bg-slate-800/30">
                    <h3 class="font-bold text-white text-lg">Detail Transaksi Lunas</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-800/50 text-xs font-semibold text-slate-400 uppercase tracking-wider border-b border-slate-700/50">
                            <tr>
                                <th class="px-6 py-4">No. Tagihan</th>
                                <th class="px-6 py-4">Paket Layanan</th>
                                <th class="px-6 py-4">Nominal</th>
                                <th class="px-6 py-4">Tanggal Bayar</th>
                                <th class="px-6 py-4">Metode</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60">
                            @forelse($revenue as $inv)
                                <tr class="hover:bg-slate-800/40 transition-colors">
                                    <td class="px-6 py-4 font-mono font-bold text-purple-400">{{ $inv->invoice_number }}</td>
                                    <td class="px-6 py-4 text-slate-300">{{ $inv->subscription->package->name ?? '-' }}</td>
                                    <td class="px-6 py-4 text-white font-bold">Rp {{ number_format($inv->amount, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-slate-400 font-mono text-xs">{{ $inv->paid_at ? $inv->paid_at->format('d M Y, H:i') : '-' }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 bg-slate-800 text-slate-300 text-xs font-bold rounded border border-slate-700">
                                            {{ $inv->payment_method ?? 'Manual' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center text-slate-400">
                                            <svg class="w-12 h-12 mb-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                            <p class="font-bold text-slate-300">Tidak ada data pendapatan</p>
                                            <p class="text-sm mt-1">Belum ada invoice yang lunas pada periode ini.</p>
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
