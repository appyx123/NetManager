<div x-show="sidebarOpen" 
     x-transition:enter="transition-opacity ease-linear duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-40 md:hidden" 
     @click="sidebarOpen = false" x-cloak></div>

<div class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-950 border-r border-slate-800/60 shadow-[4px_0_24px_rgba(0,0,0,0.5)] flex flex-col transition-transform duration-300 md:relative md:translate-x-0"
     :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
     x-data="{ open: {} }">
    
    <div class="p-6 border-b border-slate-800/60 bg-slate-950/50 backdrop-blur-xl relative overflow-hidden">
        <div class="absolute -left-4 -top-4 w-24 h-24 bg-indigo-500/20 rounded-full blur-2xl pointer-events-none"></div>
        
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group relative z-10">
            <div class="p-2 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl shadow-[0_0_15px_rgba(99,102,241,0.4)] group-hover:shadow-[0_0_25px_rgba(99,102,241,0.6)] transition-all duration-300">
                <x-application-mark class="w-6 h-6 text-white" />
            </div>
            <div class="flex-1">
                <div class="text-lg font-black text-transparent bg-clip-text bg-gradient-to-r from-white to-slate-300 tracking-tight group-hover:to-white transition-all">Net Management</div>
                <div class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest mt-0.5">Professional ISP</div>
            </div>
        </a>
    </div>

    <div class="px-5 py-5 border-b border-slate-800/60 bg-slate-900/20">
        <div class="flex items-center gap-3.5">
            <div class="relative">
                <div class="w-11 h-11 rounded-xl bg-slate-800 border border-slate-700 flex items-center justify-center flex-shrink-0 shadow-inner overflow-hidden">
                    <span class="text-slate-300 font-black text-lg">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                </div>
                <div class="absolute -bottom-1 -right-1 w-3.5 h-3.5 bg-emerald-500 border-2 border-slate-950 rounded-full"></div>
            </div>
            
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-white truncate">{{ Auth::user()->name }}</p>
                <div class="mt-1">
                    @if (Auth::user()->role === 'super_admin')
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[9px] font-black tracking-widest bg-purple-500/10 text-purple-400 border border-purple-500/20 uppercase">Super Admin</span>
                    @elseif (Auth::user()->role === 'admin')
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[9px] font-black tracking-widest bg-rose-500/10 text-rose-400 border border-rose-500/20 uppercase">Admin</span>
                    @elseif (Auth::user()->role === 'marketing')
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[9px] font-black tracking-widest bg-amber-500/10 text-amber-400 border border-amber-500/20 uppercase">Marketing</span>
                    @elseif (Auth::user()->role === 'technician')
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[9px] font-black tracking-widest bg-sky-500/10 text-sky-400 border border-sky-500/20 uppercase">Technician</span>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[9px] font-black tracking-widest bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 uppercase">Customer</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-2 scrollbar-thin scrollbar-thumb-slate-800 scrollbar-track-transparent">
        
        @if (Auth::user()->role === 'super_admin')
            <div class="mb-8">
                <div class="flex items-center gap-2 px-2 text-[10px] font-black text-purple-500 uppercase tracking-widest mb-3">
                    <span class="w-2 h-2 rounded-full bg-purple-500"></span> Super Admin
                </div>
                
                <div class="space-y-1">
                    <x-sidebar-link href="{{ route('superadmin.dashboard') }}" :active="request()->routeIs('superadmin.dashboard')" icon="chart-pie">
                        {{ __('Dashboard') }}
                    </x-sidebar-link>
                    
                    <x-sidebar-link href="{{ route('superadmin.users.index') }}" :active="request()->routeIs('superadmin.users.*')" icon="users">
                        {{ __('Kelola Pegawai') }}
                    </x-sidebar-link>
                    
                    <x-sidebar-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')" icon="monitor">
                        {{ __('Admin Dashboard') }}
                    </x-sidebar-link>
                </div>
            </div>
        @endif

        @if (in_array(Auth::user()->role, ['admin', 'super_admin']))
            <div class="mb-8">
                <div class="flex items-center gap-2 px-2 text-[10px] font-black text-rose-500 uppercase tracking-widest mb-3">
                    <span class="w-2 h-2 rounded-full bg-rose-500"></span> Operations
                </div>
                
                <div class="space-y-1">
                    <x-sidebar-link href="{{ route('admin.customers.index') }}" :active="request()->routeIs('admin.customers.*')" icon="users">
                        {{ __('Pelanggan') }}
                    </x-sidebar-link>
                    
                    <x-sidebar-link href="{{ route('admin.tickets.index') }}" :active="request()->routeIs('admin.tickets.*')" icon="ticket">
                        {{ __('Tiket Teknisi') }}
                    </x-sidebar-link>
                    
                    <x-sidebar-link href="{{ route('admin.leads.index') }}" :active="request()->routeIs('admin.leads.*')" icon="target">
                        {{ __('Lead Marketing') }}
                    </x-sidebar-link>
                    
                    <x-sidebar-link href="{{ route('admin.billing.index') }}" :active="request()->routeIs('admin.billing.*')" icon="wallet">
                        {{ __('Keuangan') }}
                    </x-sidebar-link>
                    
                    <x-sidebar-link href="{{ route('admin.routers.index') }}" :active="request()->routeIs('admin.routers.*')" icon="server">
                        {{ __('Jaringan') }}
                    </x-sidebar-link>
                    
                    <x-sidebar-link href="{{ route('admin.reports.index') }}" :active="request()->routeIs('admin.reports.*')" icon="document">
                        {{ __('Laporan') }}
                    </x-sidebar-link>
                </div>
            </div>
        @endif

        @if (Auth::user()->role === 'marketing')
            <div class="mb-8">
                <div class="flex items-center gap-2 px-2 text-[10px] font-black text-amber-500 uppercase tracking-widest mb-3">
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span> Marketing
                </div>
                
                <div class="space-y-1">
                    <x-sidebar-link href="{{ route('marketing.dashboard') }}" :active="request()->routeIs('marketing.dashboard')" icon="chart-pie">
                        {{ __('Dashboard') }}
                    </x-sidebar-link>
                    
                    <x-sidebar-link href="{{ route('marketing.leads.index') }}" :active="request()->routeIs('marketing.leads.*')" icon="target">
                        {{ __('Prospek') }}
                    </x-sidebar-link>
                    
                    <x-sidebar-link href="{{ route('marketing.customers.index') }}" :active="request()->routeIs('marketing.customers.*')" icon="users">
                        {{ __('Pelanggan') }}
                    </x-sidebar-link>
                    
                    <x-sidebar-link href="{{ route('marketing.reports.index') }}" :active="request()->routeIs('marketing.reports.*')" icon="document">
                        {{ __('Laporan') }}
                    </x-sidebar-link>
                </div>
            </div>
        @endif

        @if (Auth::user()->role === 'technician')
            <div class="mb-8">
                <div class="flex items-center gap-2 px-2 text-[10px] font-black text-sky-500 uppercase tracking-widest mb-3">
                    <span class="w-2 h-2 rounded-full bg-sky-500"></span> Technician
                </div>

                <div class="space-y-1">
                    <x-sidebar-link href="{{ route('technician.dashboard') }}" :active="request()->routeIs('technician.dashboard')" icon="chart-pie">
                        {{ __('Dashboard') }}
                    </x-sidebar-link>

                    <x-sidebar-link href="{{ route('technician.ticket.index') }}" :active="request()->routeIs('technician.ticket.*')" icon="search">
                        {{ __('Bursa Tugas') }}
                    </x-sidebar-link>

                    <x-sidebar-link href="{{ route('technician.process.index') }}" :active="request()->routeIs('technician.process.*')" icon="wrench">
                        {{ __('Meja Kerja') }}
                    </x-sidebar-link>

                    <x-sidebar-link href="{{ route('technician.history.index') }}" :active="request()->routeIs('technician.history.*')" icon="alert">
                        {{ __('Riwayat Pekerjaan') }}
                    </x-sidebar-link>
                </div>
            </div>
        @endif

        @if (Auth::user()->role === 'customer')
            <div class="mb-8">
                <div class="flex items-center gap-2 px-2 text-[10px] font-black text-emerald-500 uppercase tracking-widest mb-3">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Customer
                </div>
                
                <div class="space-y-1">
                    <x-sidebar-link href="{{ route('client.dashboard') }}" :active="request()->routeIs('client.dashboard')" icon="chart-pie">
                        {{ __('Dashboard') }}
                    </x-sidebar-link>

                    <x-sidebar-link href="{{ route('client.billing.index') }}" :active="request()->routeIs('client.billing.*')" icon="wallet">
                        {{ __('Pembayaran') }}
                    </x-sidebar-link>
                    
                    <x-sidebar-link href="{{ route('client.complaints.index') }}" :active="request()->routeIs('client.complaints.*')" icon="alert">
                        {{ __('Pengajuan') }}
                    </x-sidebar-link>
                </div>
            </div>
        @endif

    </nav>

<div class="p-4 border-t border-slate-800/60 bg-slate-950">
        <form id="logout-form-sidebar" method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button type="button" onclick="confirmLogout('sidebar')"
                class="w-full flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-bold text-rose-400 bg-rose-500/10 border border-rose-500/20 hover:bg-rose-500 hover:text-white rounded-xl transition-all duration-300 shadow-sm hover:shadow-[0_0_15px_rgba(225,29,72,0.4)]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                {{ __('Keluar Sistem') }}
            </button>
        </form>
    </div>
</div>