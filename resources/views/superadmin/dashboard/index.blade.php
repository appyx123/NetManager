<x-app-layout>
    <div class="py-8 bg-slate-950 min-h-screen selection:bg-purple-500/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-white to-slate-400 tracking-tight">Dashboard Super Admin</h1>
                    <p class="text-slate-400 mt-2 text-lg font-medium">Sistem teknis, statistik global, dan analisis mendalam</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <span class="inline-flex items-center px-5 py-2.5 rounded-full text-sm font-bold bg-slate-900/80 backdrop-blur-sm text-purple-300 border border-purple-500/30 shadow-[0_0_15px_rgba(168,85,247,0.15)]">
                        <span class="flex w-2.5 h-2.5 bg-purple-400 rounded-full mr-3 animate-pulse shadow-[0_0_8px_rgba(168,85,247,0.8)]"></span>
                        SUPER ADMIN PANEL
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-slate-900/60 backdrop-blur-md rounded-2xl p-6 border border-blue-500/20 hover:border-blue-500/50 hover:-translate-y-1 hover:shadow-2xl hover:shadow-blue-500/10 transition-all duration-300 group">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-blue-400/80 uppercase tracking-wider group-hover:text-blue-400 transition-colors">Total User</p>
                            <h3 class="text-3xl font-black text-white mt-2">{{ $stats['total_users'] }}</h3>
                        </div>
                        <div class="p-3 bg-blue-500/10 rounded-xl group-hover:bg-blue-500/20 transition-colors border border-blue-500/10">
                            <svg class="w-8 h-8 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-900/60 backdrop-blur-md rounded-2xl p-6 border border-amber-500/20 hover:border-amber-500/50 hover:-translate-y-1 hover:shadow-2xl hover:shadow-amber-500/10 transition-all duration-300 group">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-amber-400/80 uppercase tracking-wider group-hover:text-amber-400 transition-colors">Staf/Pegawai</p>
                            <h3 class="text-3xl font-black text-white mt-2">{{ $stats['total_staffs'] }}</h3>
                        </div>
                        <div class="p-3 bg-amber-500/10 rounded-xl group-hover:bg-amber-500/20 transition-colors border border-amber-500/10">
                            <svg class="w-8 h-8 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-900/60 backdrop-blur-md rounded-2xl p-6 border border-emerald-500/20 hover:border-emerald-500/50 hover:-translate-y-1 hover:shadow-2xl hover:shadow-emerald-500/10 transition-all duration-300 group">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-emerald-400/80 uppercase tracking-wider group-hover:text-emerald-400 transition-colors">Total Pelanggan</p>
                            <h3 class="text-3xl font-black text-white mt-2">{{ $stats['total_customers'] }}</h3>
                        </div>
                        <div class="p-3 bg-emerald-500/10 rounded-xl group-hover:bg-emerald-500/20 transition-colors border border-emerald-500/10">
                            <svg class="w-8 h-8 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-900/60 backdrop-blur-md rounded-2xl p-6 border border-rose-500/20 hover:border-rose-500/50 hover:-translate-y-1 hover:shadow-2xl hover:shadow-rose-500/10 transition-all duration-300 group">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-rose-400/80 uppercase tracking-wider group-hover:text-rose-400 transition-colors">Pendapatan</p>
                            <h3 class="text-2xl font-black text-white mt-2">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</h3>
                        </div>
                        <div class="p-3 bg-rose-500/10 rounded-xl group-hover:bg-rose-500/20 transition-colors border border-rose-500/10">
                            <svg class="w-8 h-8 text-rose-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M8.16 2.75a.75.75 0 00-.328 1.456l3.492 1.746-4.8-1.2a.75.75 0 10-.368 1.456l6 1.5a.75.75 0 00.368-.056l2-1a.75.75 0 10-.672-1.344l-1.185.592-3.492-1.746a.75.75 0 00-.615-.178zM2.75 9.25a.75.75 0 000 1.5h14.5a.75.75 0 000-1.5H2.75z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="bg-slate-900/80 backdrop-blur-sm rounded-2xl border border-slate-800 p-6 hover:border-slate-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                    <h3 class="text-lg font-bold text-white mb-6 flex items-center gap-2">
                        <div class="p-2 bg-purple-500/10 rounded-lg border border-purple-500/20">
                            <svg class="w-5 h-5 text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" />
                            </svg>
                        </div>
                        Distribusi User Berdasarkan Role
                    </h3>
                    <div style="position: relative; height: 300px;">
                        <canvas id="userRoleChart"></canvas>
                    </div>
                </div>

                <div class="bg-slate-900/80 backdrop-blur-sm rounded-2xl border border-slate-800 p-6 hover:border-slate-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                    <h3 class="text-lg font-bold text-white mb-6 flex items-center gap-2">
                        <div class="p-2 bg-emerald-500/10 rounded-lg border border-emerald-500/20">
                            <svg class="w-5 h-5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v2a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a2 2 0 002 2h8a2 2 0 002-2V6H6v1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        Status Langganan
                    </h3>
                    <div style="position: relative; height: 300px;">
                        <canvas id="subscriptionChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="bg-slate-900/80 backdrop-blur-sm rounded-2xl border border-slate-800 p-6 hover:border-slate-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                    <h3 class="text-lg font-bold text-white mb-6 flex items-center gap-2">
                        <div class="p-2 bg-rose-500/10 rounded-lg border border-rose-500/20">
                            <svg class="w-5 h-5 text-rose-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z" />
                            </svg>
                        </div>
                        Tren Pendapatan (12 Bulan)
                    </h3>
                    <div style="position: relative; height: 300px;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>

                <div class="bg-slate-900/80 backdrop-blur-sm rounded-2xl border border-slate-800 p-6 hover:border-slate-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                    <h3 class="text-lg font-bold text-white mb-6 flex items-center gap-2">
                        <div class="p-2 bg-blue-500/10 rounded-lg border border-blue-500/20">
                            <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                            </svg>
                        </div>
                        Pertumbuhan User (7 Hari)
                    </h3>
                    <div style="position: relative; height: 300px;">
                        <canvas id="userGrowthChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="bg-slate-900/80 backdrop-blur-sm rounded-2xl border border-slate-800 p-6 hover:border-slate-700 transition-all duration-300 shadow-lg hover:shadow-xl flex flex-col justify-center">
                    <h3 class="font-bold text-white mb-6 text-lg tracking-wide">Status Sistem</h3>
                    <div class="space-y-6">
                        <div>
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-sm font-medium text-slate-300">Kesehatan Server</span>
                                <span class="text-sm font-bold text-emerald-400 drop-shadow-[0_0_8px_rgba(52,211,153,0.5)]">{{ $stats['system_health'] }}%</span>
                            </div>
                            <div class="w-full h-4 bg-slate-800/80 rounded-full overflow-hidden border border-slate-700/50 p-0.5">
                                <div class="h-full bg-gradient-to-r from-emerald-500 to-green-400 rounded-full shadow-[0_0_10px_rgba(52,211,153,0.4)] relative" style="width: {{ $stats['system_health'] }}%">
                                    <div class="absolute top-0 right-0 bottom-0 left-0 bg-[linear-gradient(45deg,rgba(255,255,255,0.15)_25%,transparent_25%,transparent_50%,rgba(255,255,255,0.15)_50%,rgba(255,255,255,0.15)_75%,transparent_75%,transparent)] bg-[length:1rem_1rem] animate-[progress_1s_linear_infinite]"></div>
                                </div>
                            </div>
                        </div>
                        <div class="pt-5 space-y-4 border-t border-slate-800">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-slate-400 flex items-center gap-2"><div class="w-1.5 h-1.5 rounded-full bg-slate-500"></div> Database Size</span>
                                <span class="text-sm font-bold text-slate-200 bg-slate-800 px-3 py-1 rounded-md">{{ $stats['database_size'] }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-slate-400 flex items-center gap-2"><div class="w-1.5 h-1.5 rounded-full bg-slate-500"></div> Active Sessions</span>
                                <span class="text-sm font-bold text-slate-200 bg-slate-800 px-3 py-1 rounded-md">{{ $stats['active_sessions'] }} sesi</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-slate-400 flex items-center gap-2"><div class="w-1.5 h-1.5 rounded-full bg-slate-500"></div> Last Updated</span>
                                <span class="text-sm font-bold text-slate-200 bg-slate-800 px-3 py-1 rounded-md">{{ now()->format('H:i:s') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-900/80 backdrop-blur-sm rounded-2xl border border-slate-800 p-6 hover:border-slate-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                    <h3 class="font-bold text-white mb-6 text-lg tracking-wide">Layanan Pihak Ketiga</h3>
                    <div class="space-y-3">
                        @foreach ($servicesStatus as $service => $status)
                            <div class="flex justify-between items-center p-4 bg-slate-800/40 hover:bg-slate-800/80 rounded-xl border border-slate-700/50 transition-colors">
                                <span class="text-sm text-slate-200 font-medium capitalize">{{ str_replace('_', ' ', $service) }}</span>
                                <div class="flex items-center gap-2.5">
                                    <div class="w-2.5 h-2.5 rounded-full {{ $status === 'operational' || $status === 'connected' || $status === 'active' ? 'bg-emerald-500 animate-pulse shadow-[0_0_8px_rgba(52,211,153,0.8)]' : 'bg-rose-500 shadow-[0_0_8px_rgba(244,63,94,0.8)]' }}"></div>
                                    <span class="text-xs font-bold {{ $status === 'operational' || $status === 'connected' || $status === 'active' ? 'text-emerald-400' : 'text-rose-400' }} uppercase tracking-wider">
                                        {{ ucfirst($status) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="bg-slate-900/80 backdrop-blur-sm rounded-2xl border border-slate-800 overflow-hidden hover:border-slate-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                <div class="p-6 border-b border-slate-800 flex items-center gap-3">
                    <div class="p-2 bg-amber-500/10 rounded-lg border border-amber-500/20">
                        <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 1 1 0 000-2H6a6 6 0 100 12H4a1 1 0 100 2 2 2 0 01-2-2V5zm15 7a1 1 0 11-2 0 1 1 0 012 0z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-white tracking-wide">Audit Log Terbaru</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-slate-800/50 text-xs text-slate-400 uppercase tracking-wider font-semibold">
                            <tr>
                                <th class="px-6 py-4">User</th>
                                <th class="px-6 py-4">Aksi</th>
                                <th class="px-6 py-4 text-right">Waktu</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60">
                            @forelse ($recentLogs as $log)
                                <tr class="hover:bg-slate-800/40 transition-colors group">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            @php
                                                $userName = $log->user?->name ?? 'System';
                                            @endphp
                                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center flex-shrink-0 shadow-lg shadow-amber-500/20 group-hover:scale-105 transition-transform">
                                                <span class="text-white font-black text-xs">{{ substr($userName, 0, 1) }}</span>
                                            </div>
                                            <span class="font-medium text-slate-200 group-hover:text-white transition-colors">{{ $userName }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-slate-300">{{ $log->action }}</td>
                                    <td class="px-6 py-4 text-slate-400 text-xs text-right whitespace-nowrap">
                                        <span class="bg-slate-800 px-2.5 py-1 rounded-md">{{ $log->created_at->diffForHumans() }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-10 h-10 text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                            <span class="text-slate-400 font-medium">Belum ada activity log terbaru.</span>
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

    @push('scripts')
    <style>
        @keyframes progress {
            0% { background-position: 1rem 0; }
            100% { background-position: 0 0; }
        }
    </style>
    
    <script>
        // Chart.js Configuration
        Chart.defaults.color = '#94a3b8';
        Chart.defaults.borderColor = '#1e293b'; // Warna border chart yang lebih gelap agar menyatu
        Chart.defaults.font.family = "'Figtree', system-ui, sans-serif";

        // Script chart tetap sama seperti sebelumnya (tidak ada yang dihapus)
        
        // 1. User by Role Chart (Doughnut)
        const userRoleCtx = document.getElementById('userRoleChart');
        if (userRoleCtx) {
            new Chart(userRoleCtx, {
                type: 'doughnut',
                data: {
                    labels: @json($userRoleChart['labels']),
                    datasets: [{
                        data: @json($userRoleChart['data']),
                        backgroundColor: @json($userRoleChart['backgroundColor']),
                        borderColor: '#0f172a', // Outline chart sesuai background slate-900
                        borderWidth: 3,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { padding: 20, font: { size: 12, weight: 'bold' } } }
                    },
                    cutout: '70%'
                }
            });
        }

        // 2. Subscription Status Chart (Pie)
        const subscriptionCtx = document.getElementById('subscriptionChart');
        if (subscriptionCtx) {
            new Chart(subscriptionCtx, {
                type: 'pie',
                data: {
                    labels: @json($subscriptionChart['labels']),
                    datasets: [{
                        data: @json($subscriptionChart['data']),
                        backgroundColor: @json($subscriptionChart['backgroundColor']),
                        borderColor: '#0f172a',
                        borderWidth: 3,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { padding: 20, font: { size: 12, weight: 'bold' } } }
                    }
                }
            });
        }

        // 3. Revenue Trend Chart (Line)
        const revenueCtx = document.getElementById('revenueChart');
        if (revenueCtx) {
            new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: @json($revenueChart['labels']),
                    datasets: [{
                        label: 'Pendapatan',
                        data: @json($revenueChart['data']),
                        backgroundColor: @json($revenueChart['backgroundColor']),
                        borderColor: @json($revenueChart['borderColor']),
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4, // Kurva yang lebih mulus
                        pointBackgroundColor: '#0f172a',
                        pointBorderColor: @json($revenueChart['borderColor']),
                        pointBorderWidth: 3,
                        pointRadius: 4,
                        pointHoverRadius: 8,
                        pointHoverBackgroundColor: @json($revenueChart['borderColor']),
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { display: false } // Sembunyikan legend untuk tampilan lebih bersih
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#1e293b', drawBorder: false },
                            ticks: { callback: function(value) { return 'Rp ' + (value/1000000) + ' Jt'; } } // Disingkat agar rapi
                        },
                        x: { grid: { display: false, drawBorder: false } }
                    }
                }
            });
        }

        // 4. User Growth Chart (Bar)
        const userGrowthCtx = document.getElementById('userGrowthChart');
        if (userGrowthCtx) {
            new Chart(userGrowthCtx, {
                type: 'bar',
                data: {
                    labels: @json($userGrowthChart['labels']),
                    datasets: [{
                        label: 'User Baru',
                        data: @json($userGrowthChart['data']),
                        backgroundColor: @json($userGrowthChart['backgroundColor']),
                        borderColor: 'transparent',
                        borderRadius: 6,
                        barPercentage: 0.6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: '#1e293b', drawBorder: false } },
                        x: { grid: { display: false, drawBorder: false } }
                    }
                }
            });
        }
    </script>
    @endpush
</x-app-layout>