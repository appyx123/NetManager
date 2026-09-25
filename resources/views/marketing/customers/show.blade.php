<x-app-layout>
    <div class="py-10 bg-slate-950 min-h-screen selection:bg-indigo-500/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="fade-in">
                    <a href="{{ route('marketing.customers.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-white transition-colors mb-4 group">
                        <svg class="w-4 h-4 mr-1.5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali ke Daftar Pelanggan
                    </a>
                    <h1 class="text-4xl font-black text-white tracking-tighter">Profil <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-cyan-300">Pelanggan</span></h1>
                </div>
                <div class="flex gap-3">
                    <button class="p-3 bg-slate-800 text-slate-400 hover:text-indigo-400 hover:bg-slate-700 rounded-xl transition-all border border-slate-700 shadow-sm" title="Edit Data">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </button>
                    <button class="p-3 bg-slate-800 text-slate-400 hover:text-rose-400 hover:bg-slate-700 rounded-xl transition-all border border-slate-700 shadow-sm" title="Isolir/Nonaktifkan">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <div class="lg:col-span-2 space-y-8">

                    <div class="bg-slate-900/80 backdrop-blur-md rounded-[2.5rem] shadow-2xl border border-slate-800 overflow-hidden relative">
                        <div class="absolute -right-20 -top-20 w-72 h-72 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>

                        <div class="bg-gradient-to-r from-indigo-600/20 to-transparent px-10 py-8 border-b border-slate-800/60 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
                            <div class="flex items-center gap-6">
                                <div class="w-16 h-16 bg-slate-800 rounded-full flex items-center justify-center text-indigo-400 border border-slate-700 shadow-[0_0_15px_rgba(79,70,229,0.2)]">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-black text-white tracking-tight">{{ $customer->user?->name ?? 'Pelanggan #' . $customer->id }}</h3>
                                    <p class="text-[10px] text-slate-400 font-black uppercase tracking-[0.2em] mt-1">{{ $customer->customer_code }}</p>
                                </div>
                            </div>
                            <div>
                                @if(!$customer->is_isolated)
                                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500/10 border border-emerald-500/20 rounded-xl text-emerald-400 text-xs font-black uppercase tracking-widest shadow-inner">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                        Status Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-rose-500/10 border border-rose-500/20 rounded-xl text-rose-400 text-xs font-black uppercase tracking-widest shadow-inner">
                                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                        Nonaktif / Isolir
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="p-10 space-y-8">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-1">
                                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest flex items-center gap-2">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg> Email
                                    </p>
                                    <p class="text-sm font-bold text-white">{{ $customer->user?->email ?? '-' }}</p>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest flex items-center gap-2">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg> Telepon / WhatsApp
                                    </p>
                                    <p class="text-sm font-bold text-white">{{ $customer->phone_number ?? '-' }}</p>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest flex items-center gap-2">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg> Nomor Induk Kependudukan
                                    </p>
                                    <p class="text-sm font-bold text-slate-300 font-mono tracking-widest">{{ $customer->nik ?? '-' }}</p>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest flex items-center gap-2">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> Tanggal Konversi
                                    </p>
                                    <p class="text-sm font-bold text-white">{{ $customer->created_at->format('d F Y') }}</p>
                                </div>
                            </div>

                            <div class="h-px bg-slate-800/60 w-full"></div>

                            <div>
                                <h4 class="text-xs font-black text-slate-500 uppercase tracking-[0.2em] mb-4 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg> 
                                    Lokasi Instalasi & Titik Koordinat
                                </h4>
                                <div class="bg-slate-800/30 border border-slate-700/50 rounded-2xl p-6">
                                    <p class="text-white font-bold text-sm leading-relaxed mb-1">{{ $customer->address_installation ?? '-' }}</p>
                                    <div class="mt-4 inline-flex items-center gap-2 px-3 py-1.5 bg-slate-800 rounded-lg border border-slate-700">
                                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                                        <p class="text-slate-400 font-mono text-[10px] tracking-widest">{{ $customer->coordinates ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="h-px bg-slate-800/60 w-full"></div>

                            <div>
                                <h4 class="text-xs font-black text-slate-500 uppercase tracking-[0.2em] mb-4 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> 
                                    Konfigurasi Teknis Layanan
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="bg-slate-800/30 border border-slate-700/50 rounded-2xl p-5 relative overflow-hidden group">
                                        <div class="absolute inset-0 bg-indigo-500/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1 relative z-10">Username PPPoE</p>
                                        <p class="text-indigo-400 font-mono font-bold text-sm tracking-widest relative z-10">{{ $customer->pppoe_username ?? '-' }}</p>
                                    </div>
                                    <div class="bg-slate-800/30 border border-slate-700/50 rounded-2xl p-5 relative overflow-hidden group">
                                        <div class="absolute inset-0 bg-indigo-500/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1 relative z-10">Password Akses</p>
                                        <p class="text-slate-400 font-mono font-bold text-sm tracking-widest relative z-10">••••••••••</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-900/50 border border-slate-800 rounded-[2rem] p-8 relative">
                        <h4 class="text-xs font-black text-slate-500 uppercase tracking-[0.2em] mb-8 flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Jejak Tiket & Penanganan
                        </h4>
                        
                        <div class="relative pl-4 space-y-8">
                            <div class="absolute left-[1.35rem] top-2 bottom-2 w-0.5 bg-slate-800/80"></div>
                            
                            @forelse ($customer->tickets as $ticket)
                                <div class="relative flex items-start gap-6 group">
                                    <div class="relative z-10 flex-shrink-0 mt-0.5">
                                        <div class="w-8 h-8 rounded-full border-4 border-slate-950 flex items-center justify-center transition-colors duration-300 bg-indigo-500 text-white">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                    </div>
                                    <div class="flex-grow">
                                        <p class="text-sm font-bold text-white group-hover:text-indigo-400 transition-colors">
                                            {{ ucfirst($ticket->type ?? 'Tiket') }}: {{ $ticket->title ?? $ticket->ticket_code }}
                                        </p>
                                        <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-1">
                                            {{ $ticket->created_at->format('d M Y, H:i') }} &bull; Status: {{ ucfirst($ticket->status) }}
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <div class="text-slate-500 text-xs italic pl-4">
                                    Belum ada catatan tiket keluhan atau perbaikan khusus.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="space-y-8">
                    @php
                        $sub = $customer->subscriptions->first();
                        $pkgName = $sub?->package?->name ?? ($customer->lead?->package?->name ?? 'Paket Broadband');
                        $pkgPrice = $sub?->price ?? ($customer->lead?->package?->price ?? 0);
                    @endphp
                    <div class="bg-slate-900/50 border border-slate-800 rounded-[2rem] p-8">
                        <h4 class="text-xs font-black text-slate-500 uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
                            <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            Statistik Layanan
                        </h4>
                        <div class="space-y-5">
                            <div>
                                <p class="text-[10px] text-slate-500 font-black uppercase tracking-widest mb-1.5">Paket Aktif</p>
                                <div class="px-3 py-1.5 bg-indigo-500/10 border border-indigo-500/20 rounded-xl inline-flex items-center">
                                    <span class="text-xs font-bold text-indigo-400">{{ $pkgName }}</span>
                                </div>
                            </div>
                            <div class="h-px bg-slate-800/60 w-full"></div>
                            <div class="flex items-center justify-between">
                                <p class="text-[10px] text-slate-500 font-black uppercase tracking-widest">Durasi Berlangganan</p>
                                <p class="text-sm font-bold text-white tracking-tight">{{ $customer->created_at->diffInDays(now()) }} Hari</p>
                            </div>
                            <div class="h-px bg-slate-800/60 w-full"></div>
                            <div>
                                <p class="text-[10px] text-slate-500 font-black uppercase tracking-widest mb-1">Tagihan Bulanan</p>
                                <p class="text-2xl font-black text-amber-400 tracking-tighter">Rp {{ number_format($pkgPrice, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-900/50 border border-slate-800 rounded-[2rem] p-8">
                        <h4 class="text-xs font-black text-slate-500 uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            Aksi Cepat
                        </h4>
                        <div class="space-y-3">
                            @if($customer->phone_number)
                                <a href="tel:{{ $customer->phone_number }}" class="w-full px-5 py-4 bg-indigo-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-indigo-500 transition-all flex items-center justify-center gap-3 shadow-[0_0_15px_rgba(79,70,229,0.3)]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                    Hubungi Pelanggan
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="bg-indigo-500/10 border border-indigo-500/20 rounded-[2rem] p-6 relative overflow-hidden group">
                        <div class="absolute -right-4 -bottom-4 text-indigo-500/10 group-hover:text-indigo-500/20 transition-colors">
                            <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm-1-11v6h2v-6h-2zm0-4v2h2V7h-2z"></path></svg>
                        </div>
                        <h4 class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.2em] mb-2 relative z-10">Sistem Informasi</h4>
                        <p class="text-xs text-slate-400 leading-relaxed font-medium relative z-10">
                            @if(!$customer->is_isolated)
                                Pelanggan ini dalam status aktif dan terdaftar di bawah pengelolaan marketing Anda.
                            @else
                                Pelanggan ini sedang diisolir. Silakan koordinasi dengan billing/admin untuk tindak lanjut tagihan.
                            @endif
                        </p>
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>