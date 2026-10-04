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
                            <p class="text-xs font-bold text-blue-400/80 uppercase tracking-wider group-hover:text-blue-400 transition-colors">Total Pengguna</p>
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
                    <div class="flex items-center justify-between gap-3">
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-bold text-rose-400/80 uppercase tracking-wider group-hover:text-rose-400 transition-colors truncate">Pendapatan</p>
                            <h3 class="text-lg xl:text-2xl font-black text-white mt-2 whitespace-nowrap tracking-tight">
                                <span class="text-xs sm:text-sm font-bold text-rose-400/90 mr-1">Rp</span>{{ number_format($stats['total_revenue'], 0, ',', '.') }}
                            </h3>
                        </div>
                        <div class="flex-shrink-0 p-3 bg-rose-500/10 rounded-xl group-hover:bg-rose-500/20 transition-colors border border-rose-500/10">
                            <svg class="w-8 h-8 text-rose-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd" />
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
                                <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z" />
                                <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z" />
                            </svg>
                        </div>
                        Distribusi Pengguna Berdasarkan Role
                    </h3>
                    <div style="position: relative; height: 300px;">
                        <canvas id="userRoleChart"></canvas>
                    </div>
                </div>

                <div class="bg-slate-900/80 backdrop-blur-sm rounded-2xl border border-slate-800 p-6 hover:border-slate-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                    <h3 class="text-lg font-bold text-white mb-6 flex items-center gap-2">
                        <div class="p-2 bg-emerald-500/10 rounded-lg border border-emerald-500/20">
                            <svg class="w-5 h-5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
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
                        Pertumbuhan Pengguna (7 Hari)
                    </h3>
                    <div style="position: relative; height: 300px;">
                        <canvas id="userGrowthChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Status Sistem -->
                <div class="bg-slate-900/80 backdrop-blur-sm rounded-2xl border border-slate-800 p-6 hover:border-slate-700 transition-all duration-300 shadow-lg hover:shadow-xl flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-5">
                            <h3 class="font-bold text-white text-lg tracking-wide">Status Sistem</h3>
                            @php
                                $score = $stats['system_health'] ?? 100;
                                $scoreBadgeColor = $score >= 80 ? 'text-emerald-400 bg-emerald-500/10 border-emerald-500/20' : ($score >= 60 ? 'text-amber-400 bg-amber-500/10 border-amber-500/20' : 'text-rose-400 bg-rose-500/10 border-rose-500/20');
                                $scoreBarGrad = $score >= 80 ? 'from-emerald-500 to-green-400 shadow-[0_0_10px_rgba(52,211,153,0.4)]' : ($score >= 60 ? 'from-amber-500 to-yellow-400 shadow-[0_0_10px_rgba(245,158,11,0.4)]' : 'from-rose-500 to-red-400 shadow-[0_0_10px_rgba(244,63,94,0.4)]');
                            @endphp
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full border {{ $scoreBadgeColor }}">
                                {{ $score >= 80 ? 'Optimal' : ($score >= 60 ? 'Perhatian' : 'Kritis') }}
                            </span>
                        </div>

                        <!-- Progress Bar Kesehatan -->
                        <div class="mb-5">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-xs font-medium text-slate-300">Kesehatan Server</span>
                                <span class="text-sm font-bold {{ $score >= 80 ? 'text-emerald-400 drop-shadow-[0_0_8px_rgba(52,211,153,0.5)]' : ($score >= 60 ? 'text-amber-400' : 'text-rose-400') }}">{{ $score }}%</span>
                            </div>
                            <div class="w-full h-3.5 bg-slate-800/80 rounded-full overflow-hidden border border-slate-700/50 p-0.5">
                                <div class="h-full bg-gradient-to-r {{ $scoreBarGrad }} rounded-full relative transition-all duration-700" style="width: {{ $score }}%">
                                    <div class="absolute top-0 right-0 bottom-0 left-0 bg-[linear-gradient(45deg,rgba(255,255,255,0.15)_25%,transparent_25%,transparent_50%,rgba(255,255,255,0.15)_50%,rgba(255,255,255,0.15)_75%,transparent_75%,transparent)] bg-[length:1rem_1rem] animate-[progress_1s_linear_infinite]"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Grid Metrik Server -->
                        <div class="grid grid-cols-2 gap-3 pt-3 border-t border-slate-800">
                            <div class="p-3 bg-slate-800/40 rounded-xl border border-slate-800/80">
                                <span class="text-xs text-slate-400 block mb-1 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2 1.5 3 3.5 3h9c2 0 3.5-1 3.5-3V7c0-2-1.5-3-3.5-3h-9C5.5 4 4 5 4 7z"/></svg>
                                    Ukuran Database
                                </span>
                                <span class="text-sm font-bold text-slate-200">{{ $stats['database_size'] }}</span>
                            </div>
                            <div class="p-3 bg-slate-800/40 rounded-xl border border-slate-800/80">
                                <span class="text-xs text-slate-400 block mb-1 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/></svg>
                                    RAM Server
                                </span>
                                <span class="text-sm font-bold text-slate-200">{{ $stats['server_health']['ram_display'] ?? ($stats['server_health']['memory_used'] . ' / ' . $stats['server_health']['memory_limit']) }}</span>
                            </div>
                            <div class="p-3 bg-slate-800/40 rounded-xl border border-slate-800/80">
                                <span class="text-xs text-slate-400 block mb-1 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 00-9.78 2.096A4.001 4.001 0 003 15z"/></svg>
                                    Storage Disk
                                </span>
                                <span class="text-sm font-bold text-slate-200">{{ $stats['server_health']['disk_free'] ?? 'N/A' }} Bebas <span class="text-xs font-normal text-slate-400">({{ $stats['server_health']['disk_used_percent'] ?? 0 }}% used)</span></span>
                            </div>
                            <div class="p-3 bg-slate-800/40 rounded-xl border border-slate-800/80">
                                <span class="text-xs text-slate-400 block mb-1 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                    Sesi Aktif
                                </span>
                                <span class="text-sm font-bold text-slate-200">{{ $stats['active_sessions'] }} sesi</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 mt-4 border-t border-slate-800/80 flex items-center justify-between text-xs text-slate-400">
                        <span class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span>
                            Terakhir Diperbarui
                        </span>
                        <span class="bg-slate-800 px-2.5 py-1 rounded-md font-mono text-slate-300">
                            {{ now()->format('H:i:s') }} WIB
                        </span>
                    </div>
                </div>

                <!-- Layanan Pihak Ketiga -->
                <div class="bg-slate-900/80 backdrop-blur-sm rounded-2xl border border-slate-800 p-6 hover:border-slate-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                    <div class="flex items-center justify-between mb-5">
                        <h3 class="font-bold text-white text-lg tracking-wide">Layanan Pihak Ketiga</h3>
                    </div>
                    <div class="space-y-3">
                        @foreach ($servicesStatus as $key => $service)
                            @php
                                $badge = $service['badge'] ?? strtoupper($service['status'] ?? 'UNKNOWN');
                                $color = $service['badge_color'] ?? ($service['is_healthy'] ? 'emerald' : 'rose');
                            @endphp
                            <div class="flex items-center justify-between p-3.5 bg-slate-800/40 hover:bg-slate-800/70 rounded-xl border border-slate-700/50 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg flex items-center justify-center 
                                        @if($key === 'mikrotik') bg-cyan-500/10 text-cyan-400 border border-cyan-500/20
                                        @elseif($key === 'whatsapp') bg-emerald-500/10 text-emerald-400 border border-emerald-500/20
                                        @elseif($key === 'midtrans') bg-blue-500/10 text-blue-400 border border-blue-500/20
                                        @else bg-purple-500/10 text-purple-400 border border-purple-500/20
                                        @endif">
                                        @if($key === 'mikrotik')
                                            <!-- Router icon -->
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/></svg>
                                        @elseif($key === 'whatsapp')
                                            <!-- WhatsApp / Chat icon -->
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                        @elseif($key === 'midtrans')
                                            <!-- Payment card icon -->
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                        @else
                                            <!-- Database icon -->
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/></svg>
                                        @endif
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-semibold text-slate-200">{{ $service['name'] }}</h4>
                                        <p class="text-xs text-slate-400">{{ $service['description'] }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    @if($color === 'emerald')
                                        <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse shadow-[0_0_8px_rgba(52,211,153,0.8)]"></div>
                                        <span class="text-xs font-bold text-emerald-400 bg-emerald-500/10 px-2.5 py-1 rounded-md border border-emerald-500/20 tracking-wider">
                                            {{ $badge }}
                                        </span>
                                    @elseif($color === 'amber')
                                        <div class="w-2 h-2 rounded-full bg-amber-500 animate-pulse shadow-[0_0_8px_rgba(245,158,11,0.8)]"></div>
                                        <span class="text-xs font-bold text-amber-400 bg-amber-500/10 px-2.5 py-1 rounded-md border border-amber-500/20 tracking-wider">
                                            {{ $badge }}
                                        </span>
                                    @else
                                        <div class="w-2 h-2 rounded-full bg-rose-500 shadow-[0_0_8px_rgba(244,63,94,0.8)]"></div>
                                        <span class="text-xs font-bold text-rose-400 bg-rose-500/10 px-2.5 py-1 rounded-md border border-rose-500/20 tracking-wider">
                                            {{ $badge }}
                                        </span>
                                    @endif
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
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-white tracking-wide">Audit Log Terbaru</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-slate-800/50 text-xs text-slate-400 uppercase tracking-wider font-semibold">
                            <tr>
                                <th class="px-6 py-4">Pengguna</th>
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
                                            <span class="text-slate-400 font-medium">Belum ada log aktivitas terbaru.</span>
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <style>
        @keyframes progress {
            0% { background-position: 1rem 0; }
            100% { background-position: 0 0; }
        }
    </style>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof Chart === 'undefined') {
                console.error('Chart.js library failed to load.');
                return;
            }

            // Global Chart Defaults
            Chart.defaults.color = '#94a3b8';
            Chart.defaults.borderColor = '#1e293b';
            Chart.defaults.font.family = "'Figtree', system-ui, sans-serif";

            // 1. User by Role Chart (Doughnut)
            const userRoleCtx = document.getElementById('userRoleChart');
            if (userRoleCtx) {
                const roleLabels = @json($userRoleChart['labels']);
                const roleData = @json($userRoleChart['data']);
                const roleColors = @json($userRoleChart['backgroundColor']);

                new Chart(userRoleCtx, {
                    type: 'doughnut',
                    data: {
                        labels: roleLabels,
                        datasets: [{
                            data: roleData,
                            backgroundColor: roleColors,
                            borderColor: '#0f172a',
                            borderWidth: 3,
                            hoverOffset: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 16,
                                    font: { size: 12, weight: 'bold' },
                                    usePointStyle: true,
                                    pointStyle: 'circle'
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return ' ' + context.label + ': ' + context.raw + ' akun';
                                    }
                                }
                            }
                        },
                        cutout: '68%'
                    }
                });
            }

            // 2. Subscription Status Chart (Pie)
            const subscriptionCtx = document.getElementById('subscriptionChart');
            if (subscriptionCtx) {
                const subLabels = @json($subscriptionChart['labels']);
                const subData = @json($subscriptionChart['data']);
                const subColors = @json($subscriptionChart['backgroundColor']);

                new Chart(subscriptionCtx, {
                    type: 'pie',
                    data: {
                        labels: subLabels,
                        datasets: [{
                            data: subData,
                            backgroundColor: subColors,
                            borderColor: '#0f172a',
                            borderWidth: 3,
                            hoverOffset: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 16,
                                    font: { size: 12, weight: 'bold' },
                                    usePointStyle: true,
                                    pointStyle: 'circle'
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return ' ' + context.label + ': ' + context.raw + ' langganan';
                                    }
                                }
                            }
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
                            tension: 0.35,
                            pointBackgroundColor: '#0f172a',
                            pointBorderColor: @json($revenueChart['borderColor']),
                            pointBorderWidth: 3,
                            pointRadius: 4,
                            pointHoverRadius: 7,
                            pointHoverBackgroundColor: @json($revenueChart['borderColor']),
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: { mode: 'index', intersect: false },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return ' Pendapatan: Rp ' + Number(context.raw || 0).toLocaleString('id-ID');
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: '#1e293b', drawBorder: false },
                                ticks: {
                                    callback: function(value) {
                                        if (value >= 1000000) {
                                            return 'Rp ' + (value / 1000000).toLocaleString('id-ID') + ' Jt';
                                        }
                                        return 'Rp ' + Number(value).toLocaleString('id-ID');
                                    }
                                }
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
                            label: 'Pengguna Baru',
                            data: @json($userGrowthChart['data']),
                            backgroundColor: @json($userGrowthChart['backgroundColor']),
                            borderColor: 'transparent',
                            borderRadius: 8,
                            barPercentage: 0.55
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return ' Pengguna Baru: ' + context.raw + ' orang';
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { precision: 0, stepSize: 1 },
                                grid: { color: '#1e293b', drawBorder: false }
                            },
                            x: { grid: { display: false, drawBorder: false } }
                        }
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>