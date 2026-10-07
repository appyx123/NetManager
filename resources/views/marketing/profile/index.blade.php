<x-app-layout>
    <div class="py-10 bg-slate-950 min-h-screen selection:bg-amber-500/30">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-8">
                <div class="flex items-center gap-3 mb-2">
                    <span class="px-3 py-1 bg-amber-500/10 border border-amber-500/20 rounded-full text-amber-400 text-[10px] font-black uppercase tracking-[0.2em]">Marketing Account</span>
                </div>
                <h1 class="text-3xl font-black text-white tracking-tight">Profil Tim Marketing</h1>
                <p class="text-slate-400 text-sm mt-1">Informasi akun dan pengaturan aktivitas pemasaran Anda.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Profile Card --}}
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-slate-900/80 backdrop-blur-md rounded-2xl border border-slate-800 p-6 text-center shadow-xl">
                        <div class="w-24 h-24 mx-auto mb-4 rounded-full bg-amber-500/20 border-2 border-amber-500/40 flex items-center justify-center text-amber-400 font-black text-2xl shadow-lg">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                        <h3 class="text-lg font-bold text-white tracking-tight">{{ auth()->user()->name }}</h3>
                        <p class="text-xs text-amber-400 font-bold uppercase tracking-wider mt-0.5">Marketing Officer</p>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 uppercase tracking-widest mt-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Akun Aktif
                        </span>

                        <div class="mt-6 pt-6 border-t border-slate-800 space-y-3 text-left">
                            <div class="flex items-center text-slate-300 text-xs">
                                <svg class="w-4 h-4 mr-3 text-slate-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <span class="truncate">{{ auth()->user()->email }}</span>
                            </div>
                            <div class="flex items-center text-slate-300 text-xs">
                                <svg class="w-4 h-4 mr-3 text-slate-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span>Bergabung: {{ auth()->user()->created_at?->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Quick Stats --}}
                    <div class="bg-slate-900/80 backdrop-blur-md rounded-2xl border border-slate-800 p-6 shadow-xl">
                        <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            Target Periode
                        </h4>
                        <div class="space-y-4">
                            <div>
                                <div class="flex justify-between items-center mb-1.5 text-xs">
                                    <span class="text-slate-400 font-medium">Prospek Terdaftar</span>
                                    <span class="text-amber-400 font-bold">Aktif</span>
                                </div>
                                <div class="w-full bg-slate-800 rounded-full h-2 overflow-hidden">
                                    <div class="bg-gradient-to-r from-amber-500 to-yellow-400 h-2 rounded-full" style="width: 85%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between items-center mb-1.5 text-xs">
                                    <span class="text-slate-400 font-medium">Konversi Pelanggan</span>
                                    <span class="text-emerald-400 font-bold">Tercapai</span>
                                </div>
                                <div class="w-full bg-slate-800 rounded-full h-2 overflow-hidden">
                                    <div class="bg-gradient-to-r from-emerald-500 to-teal-400 h-2 rounded-full" style="width: 75%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Profile Form --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Personal Information --}}
                    <div class="bg-slate-900/80 backdrop-blur-md rounded-2xl border border-slate-800 p-6 sm:p-8 shadow-xl">
                        <h4 class="text-lg font-bold text-white mb-6 border-b border-slate-800 pb-3">Informasi Personal</h4>
                        <form class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Nama Lengkap</label>
                                    <input type="text" value="{{ auth()->user()->name }}" readonly
                                        class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-slate-300 text-sm cursor-not-allowed">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Email</label>
                                    <input type="email" value="{{ auth()->user()->email }}" readonly
                                        class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-slate-300 text-sm cursor-not-allowed">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Peran Sistem</label>
                                    <input type="text" value="Marketing" readonly
                                        class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-slate-300 text-sm cursor-not-allowed">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Status Operasional</label>
                                    <input type="text" value="Aktif Bertugas" readonly
                                        class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-emerald-400 font-bold text-sm cursor-not-allowed">
                                </div>
                            </div>
                        </form>
                    </div>

                    {{-- Security & Password --}}
                    <div class="bg-slate-900/80 backdrop-blur-md rounded-2xl border border-slate-800 p-6 sm:p-8 shadow-xl">
                        <h4 class="text-lg font-bold text-white mb-4 border-b border-slate-800 pb-3">Keamanan Akun</h4>
                        <p class="text-sm text-slate-400 mb-6">Untuk mengubah kata sandi atau data autentikasi, silakan akses halaman pengaturan akun.</p>
                        <a href="{{ route('profile.show') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-white rounded-xl font-bold text-sm border border-slate-700 transition">
                            <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            Buka Pengaturan Akun & Password
                        </a>
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>
