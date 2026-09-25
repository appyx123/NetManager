<x-app-layout>
    <div class="py-10 bg-slate-950 min-h-screen selection:bg-indigo-500/30">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-2">
                    <span class="px-3 py-1 bg-indigo-500/10 border border-indigo-500/20 rounded-full text-indigo-400 text-[10px] font-black uppercase tracking-[0.2em]">Technician Account</span>
                </div>
                <h1 class="text-3xl font-black text-white tracking-tight">Profil Teknisi Lapangan</h1>
                <p class="text-slate-400 text-sm mt-1">Informasi akun dan data penugasan operasional Anda.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- Profile Card --}}
                <div class="md:col-span-1 bg-slate-900/80 backdrop-blur-md rounded-2xl border border-slate-800 p-6 text-center">
                    <div class="w-24 h-24 mx-auto mb-4 rounded-full bg-indigo-600/20 border-2 border-indigo-500/40 flex items-center justify-center text-indigo-400 font-black text-2xl shadow-lg">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                    <h2 class="text-lg font-bold text-white tracking-tight">{{ Auth::user()->name }}</h2>
                    <p class="text-xs text-indigo-400 font-bold uppercase tracking-wider mt-0.5">Teknisi Lapangan</p>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 uppercase tracking-widest mt-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Akun Aktif
                    </span>

                    <div class="mt-6 pt-6 border-t border-slate-800/80 text-left space-y-3">
                        <div>
                            <span class="block text-[10px] font-black text-slate-500 uppercase tracking-widest">Email</span>
                            <span class="text-xs font-semibold text-slate-300">{{ Auth::user()->email }}</span>
                        </div>
                        <div>
                            <span class="block text-[10px] font-black text-slate-500 uppercase tracking-widest">Nomor HP</span>
                            <span class="text-xs font-semibold text-slate-300">{{ Auth::user()->phone_number ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="block text-[10px] font-black text-slate-500 uppercase tracking-widest">Terdaftar Sejak</span>
                            <span class="text-xs font-semibold text-slate-300">{{ Auth::user()->created_at?->format('d M Y') ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Detail & Pengaturan --}}
                <div class="md:col-span-2 space-y-6">
                    <div class="bg-slate-900/80 backdrop-blur-md rounded-2xl border border-slate-800 p-6">
                        <h3 class="text-base font-bold text-white mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Panduan Operasional Lapangan
                        </h3>
                        <div class="space-y-3 text-sm text-slate-300">
                            <p class="p-3 bg-slate-800/40 rounded-xl border border-slate-800 text-xs leading-relaxed">
                                🦺 <strong>Standar K3:</strong> Wajib memakai helm keselamatan, rompi reflektif, dan sabuk pengaman saat instalasi tiang ODP.
                            </p>
                            <p class="p-3 bg-slate-800/40 rounded-xl border border-slate-800 text-xs leading-relaxed">
                                📶 <strong>Standar Redaman:</strong> Pastikan redaman optik (dBm) pada titik ONU pelanggan berada pada batas aman -16 dBm s/d -23 dBm.
                            </p>
                            <p class="p-3 bg-slate-800/40 rounded-xl border border-slate-800 text-xs leading-relaxed">
                                📸 <strong>Dokumentasi:</strong> Selalu upload foto bukti instalasi, foto barcode perangkat ONU, dan speed test sebelum menutup tiket.
                            </p>
                        </div>
                    </div>

                    <div class="bg-slate-900/80 backdrop-blur-md rounded-2xl border border-slate-800 p-6 flex items-center justify-between">
                        <div>
                            <h4 class="font-bold text-white text-sm">Pengaturan Akun & Keamanan</h4>
                            <p class="text-xs text-slate-400 mt-0.5">Kelola kata sandi dan keamanan akun Anda di portal utama.</p>
                        </div>
                        <a href="{{ route('profile.show') }}" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-black uppercase tracking-wider rounded-xl transition-all shadow-md">
                            Kelola Akun &rarr;
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
