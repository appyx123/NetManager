<x-app-layout>
    <div class="py-10 bg-slate-950 min-h-screen selection:bg-amber-500/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-white to-slate-400 tracking-tight">Manajemen Tagihan</h2>
                    <p class="text-slate-400 mt-2 font-medium">Pantau arus kas, verifikasi pelunasan pelanggan, dan kelola tagihan ISP Anda.</p>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-slate-900/80 backdrop-blur-sm rounded-2xl border border-slate-800 p-6 shadow-xl relative overflow-hidden group hover:border-slate-700 transition-all">
                    <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-emerald-500/10 rounded-full blur-xl pointer-events-none"></div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 text-xs font-bold uppercase tracking-wider">Total Pendapatan (Lunas)</span>
                        <span class="p-2.5 bg-emerald-500/10 text-emerald-400 rounded-xl border border-emerald-500/20 shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                        </span>
                    </div>
                    <p class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-300 mt-3 tracking-tight">
                        Rp {{ number_format($stats['paid_total'] ?? 0, 0, ',', '.') }}
                    </p>
                    <div class="mt-3 text-xs font-medium text-slate-400 flex items-center gap-1.5">
                        <span class="text-emerald-400 font-bold">Bulan Ini: Rp {{ number_format($stats['paid_this_month'] ?? 0, 0, ',', '.') }}</span>
                        <span>•</span>
                        <span>{{ $stats['paid_count'] ?? 0 }} Transaksi Lunas</span>
                    </div>
                </div>

                <div class="bg-slate-900/80 backdrop-blur-sm rounded-2xl border border-slate-800 p-6 shadow-xl relative overflow-hidden group hover:border-slate-700 transition-all">
                    <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-rose-500/10 rounded-full blur-xl pointer-events-none"></div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 text-xs font-bold uppercase tracking-wider">Total Piutang (Belum Bayar)</span>
                        <span class="p-2.5 bg-rose-500/10 text-rose-400 rounded-xl border border-rose-500/20 shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/></svg>
                        </span>
                    </div>
                    <p class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-rose-400 to-amber-300 mt-3 tracking-tight">
                        Rp {{ number_format($stats['unpaid_total'] ?? 0, 0, ',', '.') }}
                    </p>
                    <div class="mt-3 text-xs font-medium text-slate-400">
                        <span>Tertagih pada {{ $stats['unpaid_count'] ?? 0 }} Tagihan Pelanggan</span>
                    </div>
                </div>

                <div class="bg-slate-900/80 backdrop-blur-sm rounded-2xl border border-slate-800 p-6 shadow-xl relative overflow-hidden group hover:border-slate-700 transition-all">
                    <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-sky-500/10 rounded-full blur-xl pointer-events-none"></div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 text-xs font-bold uppercase tracking-wider">Volume Tagihan</span>
                        <span class="p-2.5 bg-sky-500/10 text-sky-400 rounded-xl border border-sky-500/20 shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>
                        </span>
                    </div>
                    <p class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-sky-400 to-indigo-300 mt-3 tracking-tight">
                        {{ ($stats['paid_count'] ?? 0) + ($stats['unpaid_count'] ?? 0) }}
                    </p>
                    <div class="mt-3 text-xs font-medium text-slate-400 flex items-center gap-1.5">
                        <span class="text-emerald-400 font-semibold">{{ $stats['paid_count'] ?? 0 }} Lunas</span>
                        <span>•</span>
                        <span class="text-rose-400 font-semibold">{{ $stats['unpaid_count'] ?? 0 }} Belum Bayar</span>
                    </div>
                </div>
            </div>

            <!-- Search & Filter Controls -->
            <div class="mb-6 flex flex-col sm:flex-row gap-4 justify-between items-stretch sm:items-center">
                <form action="{{ route('admin.billing.index') }}" method="GET" class="flex-1 max-w-xl relative group">
                    @if(request('status'))
                        <input type="hidden" name="status" value="{{ request('status') }}">
                    @endif
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-500 group-focus-within:text-amber-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" placeholder="Cari No. Tagihan, Nama, atau Kode Pelanggan..." 
                        class="w-full pl-11 pr-28 py-3 bg-slate-900/80 backdrop-blur-sm text-slate-100 border border-slate-700/80 rounded-2xl focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all shadow-lg placeholder-slate-500 text-sm"
                        value="{{ request('search') }}">
                    <button type="submit" class="absolute inset-y-1.5 right-1.5 px-5 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs rounded-xl shadow-md transition-all">
                        Cari
                    </button>
                </form>

                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.billing.index', request()->only('search')) }}" 
                        class="px-4 py-2.5 rounded-xl text-xs font-bold border transition-all {{ !request('status') ? 'bg-amber-500/10 text-amber-400 border-amber-500/30' : 'bg-slate-900 border-slate-800 text-slate-400 hover:text-slate-200' }}">
                        Semua
                    </a>
                    <a href="{{ route('admin.billing.index', array_merge(request()->only('search'), ['status' => 'paid'])) }}" 
                        class="px-4 py-2.5 rounded-xl text-xs font-bold border transition-all {{ request('status') === 'paid' ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30' : 'bg-slate-900 border-slate-800 text-slate-400 hover:text-slate-200' }}">
                        Lunas
                    </a>
                    <a href="{{ route('admin.billing.index', array_merge(request()->only('search'), ['status' => 'unpaid'])) }}" 
                        class="px-4 py-2.5 rounded-xl text-xs font-bold border transition-all {{ request('status') === 'unpaid' ? 'bg-rose-500/10 text-rose-400 border-rose-500/30' : 'bg-slate-900 border-slate-800 text-slate-400 hover:text-slate-200' }}">
                        Belum Bayar
                    </a>
                </div>
            </div>

            <!-- Table Container -->
            <div class="bg-slate-900/80 backdrop-blur-sm rounded-2xl shadow-xl border border-slate-800 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-800/80 bg-slate-900/50 flex justify-between items-center">
                    <h3 class="font-bold text-slate-200 text-base">Daftar Semua Tagihan</h3>
                    <span class="text-xs font-semibold text-slate-500">Menampilkan {{ $invoices->count() }} data</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-800/50 text-xs font-semibold text-slate-400 uppercase tracking-wider border-b border-slate-700/50">
                            <tr>
                                <th class="px-6 py-4">No. Tagihan</th>
                                <th class="px-6 py-4">Pelanggan</th>
                                <th class="px-6 py-4">Total Tagihan</th>
                                <th class="px-6 py-4">Jatuh Tempo</th>
                                <th class="px-6 py-4 text-center">Status</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60 text-sm">
                            @forelse($invoices as $inv)
                                <tr class="hover:bg-slate-800/40 transition-colors group">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-mono text-sm font-bold text-amber-400 group-hover:text-amber-300 transition-colors">
                                            {{ $inv->invoice_number }}
                                        </div>
                                        <div class="text-xs text-slate-400 mt-1 flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            {{ $inv->created_at->format('d M Y') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-bold text-slate-200 group-hover:text-white transition-colors text-base">
                                            {{ $inv->subscription->customer->user->name ?? 'Pelanggan #' . $inv->id }}
                                        </div>
                                        <div class="text-xs text-slate-400 mt-1 flex items-center gap-2">
                                            <span class="px-2 py-0.5 bg-slate-800 border border-slate-700 rounded text-[10px] font-bold text-slate-300">
                                                {{ $inv->subscription->customer->customer_code ?? 'REGULER' }}
                                            </span>
                                            @if($inv->subscription && $inv->subscription->package)
                                                <span class="text-slate-400 text-xs font-medium">
                                                    {{ $inv->subscription->package->name }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-bold text-slate-100 text-base">
                                            Rp {{ number_format($inv->amount, 0, ',', '.') }}
                                        </div>
                                        @if($inv->payment_method)
                                            <div class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider mt-1">
                                                Metode: {{ strtoupper(str_replace('_', ' ', $inv->payment_method)) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-semibold text-sm {{ $inv->due_date < now() && $inv->status === 'unpaid' ? 'text-rose-400' : 'text-slate-300' }}">
                                            {{ $inv->due_date ? \Carbon\Carbon::parse($inv->due_date)->format('d M Y') : '-' }}
                                        </div>
                                        @if($inv->status === 'paid')
                                            <div class="text-[10px] text-emerald-400 font-semibold mt-1">
                                                Lunas: {{ $inv->paid_at ? \Carbon\Carbon::parse($inv->paid_at)->format('d M Y') : $inv->updated_at->format('d M Y') }}
                                            </div>
                                        @elseif($inv->due_date < now())
                                            <div class="text-[10px] text-rose-500 font-bold uppercase tracking-wider mt-1 flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                                Lewat Tempo
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if ($inv->status === 'paid')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Lunas
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md text-xs font-bold bg-rose-500/10 text-rose-400 border border-rose-500/20">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                                                Belum Bayar
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="flex items-center justify-end gap-2 font-semibold">
                                            <a href="{{ route('admin.billing.show', $inv->id) }}"
                                                class="text-sky-400 hover:text-sky-300 transition-colors bg-sky-400/10 hover:bg-sky-400/20 px-3.5 py-1.5 rounded-lg border border-sky-400/20 text-xs font-bold">
                                                Lihat
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center justify-center text-slate-400">
                                            <div class="w-16 h-16 mb-4 bg-slate-800/50 border border-slate-700/50 rounded-full flex items-center justify-center">
                                                <svg class="w-8 h-8 text-amber-500/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            </div>
                                            <p class="font-bold text-slate-300">Tidak Ada Data Tagihan</p>
                                            <p class="text-xs text-slate-500 mt-1">Belum ada tagihan yang sesuai dengan kriteria filter pencarian.</p>
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
