<x-app-layout>
    <div class="py-10 bg-slate-950 min-h-screen selection:bg-indigo-500/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="fade-in">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2 bg-indigo-500/10 rounded-lg border border-indigo-500/20">
                            <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        </div>
                        <span class="text-[10px] font-black text-indigo-500 uppercase tracking-[0.2em]">Analytical Intelligence</span>
                    </div>
                    <h1 class="text-4xl font-black text-white tracking-tighter">Laporan <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-cyan-300">Kinerja Marketing</span></h1>
                    <p class="text-slate-400 mt-2 font-medium">Analisis mendalam mengenai konversi prospek, pertumbuhan revenue, dan efektivitas tim.</p>
                </div>
                <div class="flex items-center gap-3">
                    {{-- Dropdown Periode Bulan --}}
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.outside="open = false" type="button"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-900 border border-slate-800 hover:border-slate-700 text-slate-200 text-xs font-bold rounded-xl transition-all shadow-sm">
                            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span>{{ $currentPeriodLabel ?? 'Semua Waktu' }}</span>
                            <svg class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>

                        <div x-show="open" x-transition x-cloak
                            class="absolute left-0 md:left-auto md:right-0 mt-2 w-48 bg-slate-900 border border-slate-800 rounded-xl shadow-2xl z-50 py-1.5 backdrop-blur-md">
                            @foreach ($periodLabels as $key => $label)
                                <a href="{{ route('marketing.reports.index', ['period' => $key]) }}"
                                    class="flex items-center justify-between px-4 py-2.5 text-xs font-semibold transition-colors {{ ($period ?? 'all') === $key ? 'bg-indigo-600/10 text-indigo-400 font-bold border-l-2 border-indigo-500' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white' }}">
                                    <span>{{ $label }}</span>
                                    @if (($period ?? 'all') === $key)
                                        <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>

                    {{-- Tombol Export Report --}}
                    <a href="{{ route('marketing.reports.export', ['period' => $period ?? 'all']) }}"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-black uppercase tracking-widest rounded-xl shadow-lg shadow-indigo-500/20 hover:shadow-indigo-500/40 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        <span>Export Report</span>
                    </a>
                </div>
            </div>

            {{-- KPI Matrix Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                
                <div class="bg-slate-900/80 backdrop-blur-md border border-slate-800 rounded-[2rem] p-7 relative overflow-hidden group hover:border-indigo-500/50 transition-all duration-500">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-indigo-500/5 rounded-full blur-2xl group-hover:bg-indigo-500/10 transition-all"></div>
                    <div class="flex items-center justify-between mb-6">
                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Leads Acquired</p>
                        <div class="p-2 bg-indigo-500/10 rounded-xl text-indigo-400 border border-indigo-500/20">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.123-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                    </div>
                    <h3 class="text-4xl font-black text-white tracking-tighter">{{ $kpis['total_leads'] }}</h3>
                    <div class="flex items-center gap-1.5 mt-3 text-indigo-400 font-bold text-xs">
                        <span>Total prospek terdaftar</span>
                    </div>
                </div>

                <div class="bg-slate-900/80 backdrop-blur-md border border-slate-800 rounded-[2rem] p-7 relative overflow-hidden group hover:border-amber-500/50 transition-all duration-500">
                    <div class="flex items-center justify-between mb-6">
                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Total Conversions</p>
                        <div class="p-2 bg-amber-500/10 rounded-xl text-amber-400 border border-amber-500/20">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                    <h3 class="text-4xl font-black text-white tracking-tighter">{{ $kpis['conversions'] }}</h3>
                    <div class="flex items-center gap-1.5 mt-3 text-emerald-400 font-bold text-xs">
                        <span>Pelanggan aktif</span>
                    </div>
                </div>

                <div class="bg-slate-900/80 backdrop-blur-md border border-slate-800 rounded-[2rem] p-7 relative overflow-hidden group hover:border-emerald-500/50 transition-all duration-500">
                    <div class="flex items-center justify-between mb-6">
                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Success Rate</p>
                        <div class="p-2 bg-emerald-500/10 rounded-xl text-emerald-400 border border-emerald-500/20">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                        </div>
                    </div>
                    <h3 class="text-4xl font-black text-white tracking-tighter">{{ $kpis['conversion_rate'] }}<span class="text-xl text-slate-500 ml-1">%</span></h3>
                    <div class="flex items-center gap-1.5 mt-3 text-slate-400 font-bold text-xs">
                        <span>Rasio konversi closing</span>
                    </div>
                </div>

                <div class="bg-slate-900/80 backdrop-blur-md border border-slate-800 rounded-[2rem] p-7 relative overflow-hidden group hover:border-purple-500/50 transition-all duration-500">
                    <div class="flex items-center justify-between mb-6">
                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Est. MRR Generated</p>
                        <div class="p-2 bg-purple-500/10 rounded-xl text-purple-400 border border-purple-500/20">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                    <h3 class="text-3xl font-black text-white tracking-tighter">Rp {{ number_format($kpis['revenue'], 0, ',', '.') }}</h3>
                    <div class="flex items-center gap-1.5 mt-3 text-emerald-400 font-bold text-xs">
                        <span>Pendapatan bulanan berulang</span>
                    </div>
                </div>
            </div>

            {{-- Charts & Progress Section --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">

                {{-- Trend Visualization --}}
                <div class="bg-slate-900/50 border border-slate-800 rounded-[2.5rem] p-10">
                    <div class="flex items-center justify-between mb-10">
                        <h4 class="text-lg font-black text-white tracking-tight uppercase">Tren Perolehan Lead</h4>
                        <div class="text-[10px] font-black text-slate-500 uppercase tracking-widest bg-slate-800 px-3 py-1 rounded-lg">3 Bulan Terakhir</div>
                    </div>
                    <div class="space-y-10">
                        @foreach ($monthlyTrends as $trend)
                            <div class="relative">
                                <div class="flex justify-between items-center mb-3">
                                    <span class="text-xs font-black text-slate-400 uppercase tracking-widest">{{ $trend['month'] }}</span>
                                    <span class="text-sm font-black text-white font-mono">{{ $trend['count'] }} <span class="text-[10px] text-slate-500 font-bold uppercase">Leads</span></span>
                                </div>
                                <div class="w-full bg-slate-800 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-indigo-600 to-cyan-400 h-2 rounded-full shadow-[0_0_10px_rgba(79,70,229,0.3)] transition-all duration-1000" style="width: {{ $trend['percentage'] }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Conversion Funnel --}}
                <div class="bg-slate-900/50 border border-slate-800 rounded-[2.5rem] p-10">
                    <div class="flex items-center justify-between mb-10">
                        <h4 class="text-lg font-black text-white tracking-tight uppercase">Status Pipeline</h4>
                        <div class="text-[10px] font-black text-slate-500 uppercase tracking-widest bg-slate-800 px-3 py-1 rounded-lg">Live Funnel</div>
                    </div>
                    <div class="space-y-8">
                        @php
                            $totalPipeline = array_sum(array_column($statuses, 'count'));
                            $maxPipeline = max(1, $totalPipeline);
                        @endphp
                        @foreach($statuses as $status)
                            @php $statusPerc = min(100, round(($status['count'] / $maxPipeline) * 100)); @endphp
                            <div class="group">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-xs font-black text-slate-400 uppercase tracking-widest">{{ $status['label'] }}</span>
                                    <span class="text-sm font-black text-white font-mono">{{ $status['count'] }}</span>
                                </div>
                                <div class="w-full bg-slate-800 rounded-full h-4 p-1">
                                    <div class="h-full rounded-full transition-all duration-1000
                                        @if($status['color'] == 'indigo') bg-gradient-to-r from-indigo-600 to-indigo-400 shadow-[0_0_10px_rgba(79,70,229,0.4)] @endif
                                        @if($status['color'] == 'amber') bg-gradient-to-r from-amber-600 to-amber-400 shadow-[0_0_10px_rgba(245,158,11,0.4)] @endif
                                        @if($status['color'] == 'purple') bg-gradient-to-r from-purple-600 to-purple-400 shadow-[0_0_10px_rgba(147,51,234,0.4)] @endif
                                        @if($status['color'] == 'green' || $status['color'] == 'emerald') bg-gradient-to-r from-emerald-600 to-emerald-400 shadow-[0_0_10px_rgba(16,185,129,0.4)] @endif" 
                                        style="width: {{ $statusPerc }}%">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

            {{-- Daily Breakdown Table --}}
            <div class="bg-slate-900/80 backdrop-blur-md rounded-[2.5rem] shadow-2xl border border-slate-800 overflow-hidden relative mb-12"
                 x-data="{ showAll: {{ request('history') === 'all' ? 'true' : 'false' }} }">
                <div class="p-6 sm:p-8 border-b border-slate-800/60 bg-gradient-to-r from-slate-900 to-transparent flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="p-2 bg-slate-800 rounded-xl text-indigo-400 border border-slate-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h4 class="text-lg font-black text-white tracking-tight uppercase" x-text="showAll ? 'Log Aktivitas Seluruh Riwayat' : 'Log Aktivitas 10 Hari Terakhir'">Log Aktivitas 10 Hari Terakhir</h4>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-800/50 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] border-b border-slate-800/60">
                            <tr>
                                <th class="px-8 py-5">Tanggal</th>
                                <th class="px-6 py-5 text-center">New Leads</th>
                                <th class="px-6 py-5 text-center">Follow-ups</th>
                                <th class="px-6 py-5 text-center">Conversions</th>
                                <th class="px-6 py-5 text-center text-cyan-400">Rate (%)</th>
                                <th class="px-8 py-5 text-right text-emerald-400">Revenue (Estimated)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60 text-sm font-medium">
                            @forelse ($dailyBreakdown as $index => $row)
                                <tr class="hover:bg-slate-800/40 transition-all duration-300 group"
                                    x-show="showAll || {{ $index }} < 10"
                                    x-cloak>
                                    <td class="px-8 py-6 text-white font-bold tracking-tight">
                                        {{ $row['date']->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-6 text-center">
                                        <span class="inline-flex px-3 py-1 bg-indigo-500/10 text-indigo-400 rounded-lg text-xs font-black">{{ $row['leads'] }}</span>
                                    </td>
                                    <td class="px-6 py-6 text-center">
                                        <span class="inline-flex px-3 py-1 bg-amber-500/10 text-amber-400 rounded-lg text-xs font-black">{{ $row['followup'] }}</span>
                                    </td>
                                    <td class="px-6 py-6 text-center">
                                        <span class="inline-flex px-3 py-1 bg-emerald-500/10 text-emerald-400 rounded-lg text-xs font-black">{{ $row['conversions'] }}</span>
                                    </td>
                                    <td class="px-6 py-6 text-center font-mono font-bold text-cyan-400 tracking-tighter">{{ $row['rate'] }}%</td>
                                    <td class="px-8 py-6 text-right font-mono font-bold text-slate-300">Rp {{ number_format($row['revenue'], 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-8 py-12 text-center text-slate-400">Belum ada riwayat aktivitas harian.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-5 sm:px-8 py-4 sm:py-6 bg-slate-900/50 border-t border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-3">
                    <span class="text-[10px] text-slate-500 font-black uppercase tracking-widest text-center sm:text-left"
                          x-text="showAll ? 'Menampilkan riwayat lengkap ({{ count($dailyBreakdown) }} hari)' : 'Showing last 10 audit logs'">
                        Showing last 10 audit logs
                    </span>
                    <button type="button"
                            @click="showAll = !showAll"
                            class="px-6 py-2.5 bg-slate-800 hover:bg-indigo-600 border border-slate-700 hover:border-indigo-500 text-white text-[10px] font-black uppercase tracking-widest rounded-xl transition-all shadow-sm active:scale-95 cursor-pointer w-full sm:w-auto text-center"
                            x-text="showAll ? 'Tampilkan 10 Hari' : 'View All History'">
                        View All History
                    </button>
                </div>
            </div>

            <div class="text-center py-10 border-t border-slate-900">
                <p class="text-[10px] font-black text-slate-700 uppercase tracking-[0.4em]">NetManagement Reporting Engine &bull; Version 2.0 &bull; 2026</p>
            </div>

        </div>
    </div>
</x-app-layout>