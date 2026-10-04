<x-app-layout>
    <div class="py-10 bg-slate-950 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.2em] text-purple-400">Katalog Produk & Layanan</p>
                    <h1 class="mt-2 text-3xl font-black text-white">Manajemen Paket</h1>
                    <p class="text-slate-400 mt-1 text-sm">Kelola katalog produk internet, spesifikasi kecepatan bandwidth, dan tarif langganan.</p>
                </div>
                <div>
                    <a href="{{ route('admin.packages.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white text-sm font-bold rounded-xl shadow-[0_0_20px_rgba(147,51,234,0.3)] hover:shadow-[0_0_25px_rgba(147,51,234,0.5)] transform hover:-translate-y-0.5 transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        Tambah Paket
                    </a>
                </div>
            </div>

            @if ($message = Session::get('success'))
                <div class="mb-6 rounded-2xl border border-emerald-500/20 bg-emerald-500/10 p-4 text-sm font-semibold text-emerald-300 flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ $message }}</span>
                </div>
            @endif

            <!-- Packages Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($packages as $package)
                    <div class="rounded-2xl border border-slate-800 bg-slate-900/80 shadow-xl backdrop-blur-md p-6 hover:border-slate-700/80 transition-all flex flex-col justify-between group relative overflow-hidden">
                        <div class="absolute -right-10 -top-10 w-32 h-32 bg-purple-500/5 rounded-full blur-2xl pointer-events-none group-hover:bg-purple-500/10 transition-colors"></div>

                        <div>
                            <div class="flex justify-between items-start gap-3 mb-4">
                                <div class="flex-1 min-w-0">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold uppercase tracking-wider bg-purple-500/10 text-purple-400 border border-purple-500/20 mb-2">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                        {{ $package->speed_mbps }} Mbps
                                    </span>
                                    <h3 class="text-xl font-black text-white truncate group-hover:text-purple-300 transition-colors">{{ $package->name }}</h3>
                                </div>
                                @if ($package->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold tracking-wider uppercase bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 shadow-[0_0_6px_rgba(52,211,153,0.8)]"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold tracking-wider uppercase bg-slate-800 text-slate-400 border border-slate-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span>
                                        Nonaktif
                                    </span>
                                @endif
                            </div>

                            <div class="mb-4 pb-4 border-b border-slate-800/80">
                                <div class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white via-slate-100 to-purple-200">
                                    Rp {{ number_format($package->price, 0, ',', '.') }}
                                    <span class="text-xs font-medium text-slate-400 tracking-normal">/bulan</span>
                                </div>
                                <div class="mt-1 text-xs text-slate-400">
                                    Biaya Pasang: <span class="font-semibold text-slate-300">Rp {{ number_format($package->installation_fee, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            @if ($package->description)
                                <p class="text-sm text-slate-400 mb-6 leading-relaxed line-clamp-3">{{ $package->description }}</p>
                            @else
                                <p class="text-sm text-slate-500 italic mb-6">Tidak ada deskripsi tambahan.</p>
                            @endif
                        </div>

                        <div class="flex items-center gap-2 pt-2 border-t border-slate-800/60">
                            <a href="{{ route('admin.packages.edit', $package) }}" 
                                class="flex-1 inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 hover:text-white text-xs font-bold rounded-xl border border-slate-700 transition-all">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Ubah
                            </a>
                            <form action="{{ route('admin.packages.destroy', $package) }}" method="POST" class="flex-1"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket {{ $package->name }}? Tindakan ini tidak dapat dibatalkan.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-rose-500/10 hover:bg-rose-500 text-rose-400 hover:text-white border border-rose-500/20 hover:border-rose-500 text-xs font-bold rounded-xl transition-all">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full rounded-2xl border border-slate-800 bg-slate-900/60 p-12 text-center">
                        <div class="w-16 h-16 mx-auto rounded-2xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-400 mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-1">Belum Ada Paket Layanan</h3>
                        <p class="text-sm text-slate-400 max-w-md mx-auto mb-6">Mulai dengan menambahkan paket internet atau layanan pertama yang akan ditawarkan kepada pelanggan.</p>
                        <a href="{{ route('admin.packages.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-purple-600 hover:bg-purple-500 text-white text-xs font-bold rounded-xl transition-all shadow-lg shadow-purple-600/30">
                            + Tambah Paket Pertama
                        </a>
                    </div>
                @endforelse
            </div>

            @if($packages->hasPages())
                <div class="mt-8 bg-slate-900/80 backdrop-blur-sm rounded-2xl border border-slate-800 px-6 shadow-xl">
                    {{ $packages->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
