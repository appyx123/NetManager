<x-app-layout>
    <div class="py-10 bg-slate-950 min-h-screen selection:bg-sky-500/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="fade-in">
                    <a href="{{ route('marketing.leads.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-white transition-colors mb-4 group">
                        <svg class="w-4 h-4 mr-1.5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali ke Daftar Prospek
                    </a>
                    <h1 class="text-4xl font-black text-white tracking-tighter">Detail <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-400 to-indigo-400">Prospek</span></h1>
                </div>

                @if ($lead->status !== 'converted' && $lead->status !== 'aktif')
                    <div class="flex-shrink-0">
                        <a href="{{ route('marketing.leads.edit', $lead->id) }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-amber-600 text-white text-xs font-black uppercase tracking-widest rounded-2xl shadow-[0_0_15px_rgba(217,119,6,0.3)] hover:bg-amber-500 hover:shadow-[0_0_25px_rgba(217,119,6,0.5)] transition-all duration-300 transform hover:-translate-y-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Edit Data Prospek
                        </a>
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-2 space-y-8">
                    
                    <div class="bg-slate-900/80 backdrop-blur-md rounded-[2.5rem] shadow-2xl border border-slate-800 overflow-hidden relative">
                        <div class="absolute -right-20 -top-20 w-64 h-64 bg-sky-500/10 rounded-full blur-3xl pointer-events-none"></div>

                        <div class="bg-gradient-to-r from-sky-600/20 to-transparent px-10 py-6 border-b border-slate-800/60 flex justify-between items-center gap-4">
                            <div class="flex items-center gap-4">
                                <div class="p-2.5 bg-sky-500 rounded-2xl shadow-[0_0_20px_rgba(14,165,233,0.3)] text-white">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </div>
                                <h2 class="text-xl font-black text-white tracking-tight uppercase">Identitas Pelanggan</h2>
                            </div>
                            
                            @php
                                $statusTheme = match ($lead->status) {
                                    'prospek' => ['bg' => 'bg-blue-500/10', 'text' => 'text-blue-400', 'border' => 'border-blue-500/20', 'dot' => 'bg-blue-500'],
                                    'survey' => ['bg' => 'bg-amber-500/10', 'text' => 'text-amber-400', 'border' => 'border-amber-500/20', 'dot' => 'bg-amber-500'],
                                    'instalasi' => ['bg' => 'bg-indigo-500/10', 'text' => 'text-indigo-400', 'border' => 'border-indigo-500/20', 'dot' => 'bg-indigo-500'],
                                    'aktif', 'converted' => ['bg' => 'bg-emerald-500/10', 'text' => 'text-emerald-400', 'border' => 'border-emerald-500/20', 'dot' => 'bg-emerald-500'],
                                    'batal' => ['bg' => 'bg-rose-500/10', 'text' => 'text-rose-400', 'border' => 'border-rose-500/20', 'dot' => 'bg-rose-500'],
                                    default => ['bg' => 'bg-slate-800', 'text' => 'text-slate-400', 'border' => 'border-slate-700', 'dot' => 'bg-slate-500'],
                                };
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest border {{ $statusTheme['bg'] }} {{ $statusTheme['text'] }} {{ $statusTheme['border'] }} shadow-inner">
                                <span class="w-1.5 h-1.5 rounded-full {{ $statusTheme['dot'] }} {{ in_array($lead->status, ['aktif', 'converted']) ? 'animate-pulse' : '' }}"></span>
                                {{ $lead->status }}
                            </span>
                        </div>

                        <div class="p-10 space-y-8">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-1">
                                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Nama Lengkap</p>
                                    <p class="text-xl font-bold text-white tracking-tight">{{ $lead->name }}</p>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Jenis Pelanggan</p>
                                    <p class="text-sm font-bold text-white uppercase tracking-wide">
                                        {{ ucfirst($lead->customer_type) }}
                                        @if($lead->business_name)
                                            <span class="text-sky-400 normal-case tracking-normal">({{ $lead->business_name }})</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Nama Ibu Kandung</p>
                                    <p class="text-sm font-bold text-white">{{ $lead->mother_name ?? '-' }}</p>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Terdaftar Sejak</p>
                                    <p class="text-sm font-bold text-white">{{ $lead->created_at->format('d M Y') }}</p>
                                </div>
                            </div>

                            <div class="h-px bg-slate-800/60 w-full"></div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-2">
                                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest flex items-center gap-2">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                        No. WhatsApp / Telepon
                                    </p>
                                    <div class="inline-flex px-4 py-2 bg-emerald-500/10 border border-emerald-500/20 rounded-xl">
                                        <p class="text-lg font-black text-emerald-400 tracking-wider">{{ $lead->phone }}</p>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest flex items-center gap-2">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                        Alamat Email
                                    </p>
                                    <p class="text-sm font-bold text-white">{{ $lead->email ?? '-' }}</p>
                                </div>
                                <div class="space-y-1 md:col-span-2">
                                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest flex items-center gap-2">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Kontak Darurat
                                    </p>
                                    <div class="bg-slate-800/30 border border-slate-700/50 rounded-2xl p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                        <div>
                                            <p class="text-white font-bold">{{ $lead->emergency_name ?? '-' }}</p>
                                            <p class="text-xs text-slate-400 font-medium">Relasi: {{ $lead->emergency_relation ?? '-' }}</p>
                                        </div>
                                        <p class="text-sm font-black text-sky-400 tracking-wider">{{ $lead->emergency_phone ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-900/80 backdrop-blur-md rounded-[2.5rem] shadow-2xl border border-slate-800 overflow-hidden relative">
                        <div class="p-10 space-y-8">
                            <h3 class="text-lg font-black text-white tracking-tight uppercase flex items-center gap-3 border-b border-slate-800/60 pb-6">
                                <div class="p-2 bg-indigo-500/10 text-indigo-400 rounded-xl border border-indigo-500/20">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                                Lokasi Pemasangan & Domisili
                            </h3>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div class="bg-sky-500/5 border border-sky-500/20 p-6 rounded-2xl">
                                    <span class="block text-[10px] font-black text-sky-400 uppercase tracking-widest mb-2">Alamat Instalasi (Domisili)</span>
                                    <p class="text-white font-medium leading-relaxed text-sm">{{ $lead->address_installation }}</p>
                                </div>
                                <div class="bg-slate-800/30 border border-slate-700/50 p-6 rounded-2xl">
                                    <span class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Alamat Sesuai KTP</span>
                                    <p class="text-slate-300 font-medium leading-relaxed text-sm">{{ $lead->address_ktp ?? 'Sama dengan alamat pemasangan' }}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 md:grid-cols-3 gap-6 p-6 bg-slate-800/30 border border-slate-700/50 rounded-2xl">
                                <div><span class="block text-slate-500 text-[10px] font-black uppercase tracking-widest mb-1">RT/RW</span> <span class="text-sm font-bold text-white">{{ $lead->rt_rw ?? '-' }}</span></div>
                                <div><span class="block text-slate-500 text-[10px] font-black uppercase tracking-widest mb-1">Kelurahan</span> <span class="text-sm font-bold text-white">{{ $lead->village ?? '-' }}</span></div>
                                <div><span class="block text-slate-500 text-[10px] font-black uppercase tracking-widest mb-1">Kecamatan</span> <span class="text-sm font-bold text-white">{{ $lead->district ?? '-' }}</span></div>
                                <div><span class="block text-slate-500 text-[10px] font-black uppercase tracking-widest mb-1">Kota/Kabupaten</span> <span class="text-sm font-bold text-white">{{ $lead->city ?? '-' }}</span></div>
                                <div><span class="block text-slate-500 text-[10px] font-black uppercase tracking-widest mb-1">Provinsi</span> <span class="text-sm font-bold text-white">{{ $lead->province ?? '-' }}</span></div>
                                <div><span class="block text-slate-500 text-[10px] font-black uppercase tracking-widest mb-1">Kode Pos</span> <span class="text-sm font-bold text-white">{{ $lead->postal_code ?? '-' }}</span></div>
                            </div>

                            <div class="bg-indigo-500/10 p-6 rounded-2xl border border-indigo-500/20 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                <div>
                                    <span class="block text-[10px] text-indigo-400 font-black uppercase tracking-widest mb-1.5 flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                                        Koordinat Peta
                                    </span>
                                    <p class="text-white font-mono text-sm font-bold tracking-widest">{{ $lead->coordinates ?? 'Belum ada koordinat' }}</p>
                                </div>
                                @if ($lead->coordinates)
                                    <a href="https://www.google.com/maps/search/?api=1&query={{ $lead->coordinates }}" target="_blank" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-black uppercase tracking-widest rounded-xl transition-all shadow-[0_0_15px_rgba(79,70,229,0.3)] whitespace-nowrap">
                                        Buka di Maps
                                    </a>
                                @endif
                            </div>

                            <div class="space-y-2">
                                <span class="block text-[10px] font-black text-slate-500 uppercase tracking-widest">Patokan / Landmark</span>
                                <div class="bg-slate-800/30 border border-slate-700/50 p-4 rounded-xl">
                                    <p class="text-slate-300 font-medium text-sm leading-relaxed">{{ $lead->landmark ?? 'Tidak ada patokan khusus' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-900/80 backdrop-blur-md rounded-[2.5rem] shadow-2xl border border-slate-800 overflow-hidden relative">
                        <div class="p-10 space-y-8">
                            <h3 class="text-lg font-black text-white tracking-tight uppercase flex items-center gap-3 border-b border-slate-800/60 pb-6">
                                <div class="p-2 bg-amber-500/10 text-amber-400 rounded-xl border border-amber-500/20">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                </div>
                                Pemilihan Paket & Jadwal
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="bg-gradient-to-br from-indigo-900/40 to-slate-900 border border-indigo-500/30 p-6 rounded-2xl relative overflow-hidden group">
                                    <div class="absolute -right-4 -bottom-4 text-indigo-500/10 group-hover:text-indigo-500/20 transition-colors">
                                        <svg class="w-32 h-32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                    </div>
                                    <span class="block text-[10px] text-indigo-400 uppercase font-black tracking-widest mb-2 relative z-10">Layanan Diminati</span>
                                    <p class="text-2xl font-black text-white tracking-tight mb-1 relative z-10">{{ $lead->package->name ?? 'Belum Memilih Paket' }}</p>
                                    <div class="flex items-center gap-2 mb-4 relative z-10">
                                        <span class="text-sm font-bold text-amber-400">{{ $lead->package->speed_mbps ?? 0 }} Mbps</span>
                                        <span class="text-slate-500 text-xs">&bull;</span>
                                        <span class="text-sm font-bold text-emerald-400">Rp {{ number_format($lead->package->price ?? 0, 0, ',', '.') }}</span>
                                    </div>
                                    @if ($lead->promo_code)
                                        <div class="inline-flex px-3 py-1.5 bg-rose-500/10 border border-rose-500/20 text-rose-400 text-[10px] font-black uppercase tracking-widest rounded-lg relative z-10">
                                            Kode Promo: {{ $lead->promo_code }}
                                        </div>
                                    @endif
                                </div>

                                <div class="space-y-4">
                                    <div class="flex items-center justify-between pb-4 border-b border-slate-800/60">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-slate-800 flex items-center justify-center text-slate-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg></div>
                                            <span class="text-xs font-black text-slate-400 uppercase tracking-widest">Jadwal Survey</span>
                                        </div>
                                        <span class="font-bold text-white text-sm">{{ $lead->survey_date ? $lead->survey_date->format('d M Y') : 'Menunggu' }}</span>
                                    </div>
                                    <div class="flex items-center justify-between pb-4 border-b border-slate-800/60">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-slate-800 flex items-center justify-center text-slate-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path></svg></div>
                                            <span class="text-xs font-black text-slate-400 uppercase tracking-widest">Jadwal Instalasi</span>
                                        </div>
                                        <span class="font-bold text-white text-sm">{{ $lead->installation_date ? $lead->installation_date->format('d M Y') : 'Menunggu' }}</span>
                                    </div>
                                    <div class="flex items-center justify-between pb-4 border-b border-slate-800/60">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-slate-800 flex items-center justify-center text-slate-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                                            <span class="text-xs font-black text-slate-400 uppercase tracking-widest">Waktu Pilihan</span>
                                        </div>
                                        <span class="font-bold text-white text-sm">{{ $lead->preferred_time ?? 'Bebas' }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-slate-800 flex items-center justify-center text-slate-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></div>
                                            <span class="text-xs font-black text-slate-400 uppercase tracking-widest">Sumber Leads</span>
                                        </div>
                                        <span class="font-bold text-white text-sm uppercase tracking-wide">{{ $lead->source ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1 space-y-8">
                    
                    <div class="bg-slate-900/50 border border-slate-800 rounded-[2rem] p-8">
                        <h3 class="text-xs font-black text-slate-500 uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Log Catatan Internal
                        </h3>
                        <div class="space-y-5">
                            <div>
                                <span class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Ringkasan Komunikasi</span>
                                <div class="p-4 bg-slate-800/30 border border-slate-700/50 rounded-xl">
                                    <p class="text-sm text-slate-300 font-medium leading-relaxed">{{ $lead->notes_summary ?? 'Tidak ada catatan komunikasi.' }}</p>
                                </div>
                            </div>
                            <div>
                                <span class="block text-[10px] font-black text-rose-500 uppercase tracking-widest mb-2">Kendala / Hambatan Lapangan</span>
                                <div class="p-4 bg-rose-500/5 border border-rose-500/20 rounded-xl">
                                    <p class="text-sm text-rose-400 font-medium leading-relaxed">{{ $lead->notes_obstacle ?? 'Tidak ada laporan kendala.' }}</p>
                                </div>
                            </div>
                            <div>
                                <span class="block text-[10px] font-black text-amber-500 uppercase tracking-widest mb-2">Catatan Khusus (Notes)</span>
                                <div class="p-4 bg-amber-500/5 border border-amber-500/20 rounded-xl">
                                    <p class="text-sm text-amber-400/90 font-medium leading-relaxed">{{ $lead->notes_special ?? 'Tidak ada instruksi khusus.' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-900/50 border border-slate-800 rounded-[2rem] p-8">
                        <h3 class="text-xs font-black text-slate-500 uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
                            <svg class="w-4 h-4 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Berkas Lampiran Digital
                        </h3>
                        <div class="space-y-5">
                            <div class="space-y-2">
                                <span class="block text-[10px] font-black text-slate-500 uppercase tracking-widest">Identitas (KTP)</span>
                                @if ($lead->ktp_image_path)
                                    <a href="{{ route('documents.ktp', $lead) }}" target="_blank" class="block group relative h-32 rounded-2xl overflow-hidden border border-slate-700 shadow-md">
                                        <img src="{{ route('documents.ktp', $lead) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                        <div class="absolute inset-0 bg-slate-950/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-sm">
                                            <span class="px-4 py-2 bg-slate-800 text-white text-xs font-black uppercase tracking-widest rounded-lg border border-slate-600 shadow-xl">Lihat Berkas</span>
                                        </div>
                                    </a>
                                @else
                                    <div class="h-24 bg-slate-800/30 border-2 border-dashed border-slate-700 rounded-2xl flex flex-col items-center justify-center text-slate-500">
                                        <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 21h7a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v11m0 5l4.879-4.879m0 0a3 3 0 104.243-4.242 3 3 0 00-4.243 4.242z"></path></svg>
                                        <span class="text-[10px] font-bold uppercase tracking-widest">Kosong</span>
                                    </div>
                                @endif
                            </div>

                            <div class="space-y-2">
                                <span class="block text-[10px] font-black text-slate-500 uppercase tracking-widest">Dokumentasi Rumah</span>
                                @if ($lead->house_image_path)
                                    <a href="{{ Storage::url($lead->house_image_path) }}" target="_blank" class="block group relative h-32 rounded-2xl overflow-hidden border border-slate-700 shadow-md">
                                        <img src="{{ Storage::url($lead->house_image_path) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                        <div class="absolute inset-0 bg-slate-950/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-sm">
                                            <span class="px-4 py-2 bg-slate-800 text-white text-xs font-black uppercase tracking-widest rounded-lg border border-slate-600 shadow-xl">Lihat Berkas</span>
                                        </div>
                                    </a>
                                @else
                                    <div class="h-24 bg-slate-800/30 border-2 border-dashed border-slate-700 rounded-2xl flex flex-col items-center justify-center text-slate-500">
                                        <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                        <span class="text-[10px] font-bold uppercase tracking-widest">Kosong</span>
                                    </div>
                                @endif
                            </div>

                            <div class="space-y-2">
                                <span class="block text-[10px] font-black text-slate-500 uppercase tracking-widest">Wajah Pelanggan</span>
                                @if ($lead->customer_image_path)
                                    <a href="{{ Storage::url($lead->customer_image_path) }}" target="_blank" class="block group relative h-32 rounded-2xl overflow-hidden border border-slate-700 shadow-md">
                                        <img src="{{ Storage::url($lead->customer_image_path) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                        <div class="absolute inset-0 bg-slate-950/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-sm">
                                            <span class="px-4 py-2 bg-slate-800 text-white text-xs font-black uppercase tracking-widest rounded-lg border border-slate-600 shadow-xl">Lihat Berkas</span>
                                        </div>
                                    </a>
                                @else
                                    <div class="h-24 bg-slate-800/30 border-2 border-dashed border-slate-700 rounded-2xl flex flex-col items-center justify-center text-slate-500">
                                        <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <span class="text-[10px] font-bold uppercase tracking-widest">Kosong</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if ($lead->status !== 'converted' && $lead->status !== 'aktif')
                        <div class="p-6 bg-rose-500/5 border border-rose-500/20 rounded-[2rem] text-center">
                            <form action="{{ route('marketing.leads.destroy', $lead->id) }}" method="POST" onsubmit="return confirm('Peringatan: Seluruh data, log, dan berkas digital prospek ini akan dihapus secara permanen dari sistem. Anda yakin ingin melanjutkan?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="flex items-center justify-center gap-2 w-full text-[10px] font-black text-rose-500 hover:text-rose-400 uppercase tracking-widest transition-colors py-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    Hapus Prospek Permanen
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>