<x-app-layout>
    <div class="py-10 bg-slate-950 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-extrabold text-white mb-8">Data Master Sistem</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                
                <!-- Master Areas -->
                <div class="bg-slate-900 rounded-xl shadow-sm border border-slate-800 p-6">
                    <h3 class="text-xl font-bold text-white mb-4">Master Area / Wilayah Cakupan</h3>

                    <div class="space-y-2 mb-6 max-h-64 overflow-y-auto pr-2">
                        @forelse ($masterAreas as $area)
                            <div class="flex justify-between items-center p-3 bg-slate-950 border border-slate-800 rounded-lg">
                                <div>
                                    <p class="font-semibold text-white">{{ $area->name }}</p>
                                    <p class="text-xs text-slate-400">Kode: <span class="text-amber-500">{{ $area->code }}</span></p>
                                </div>
                                <!-- Pastikan method DELETE untuk penghapusan yang aman -->
                                <form action="{{ route('superadmin.master.destroyArea', $area->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="text-red-500 hover:text-red-400 text-sm font-medium transition" onclick="confirmDelete(this.closest('form'), 'Area {{ addslashes($area->name) }}')">Hapus</button>
                                </form>
                            </div>
                        @empty
                            <div class="p-4 bg-slate-950/50 rounded border border-slate-800 text-center">
                                <p class="text-slate-400 text-sm">Belum ada master area yang terdaftar.</p>
                            </div>
                        @endforelse
                    </div>

                    @if ($masterAreas->hasPages())
                        <div class="mb-4">
                            {{ $masterAreas->links() }}
                        </div>
                    @endif

                    <form action="{{ route('superadmin.master.storeArea') }}" method="POST" class="space-y-3">
                        @csrf
                        <input type="text" name="name" placeholder="Nama Area (Contoh: BTP Blok M)" required
                            class="w-full px-3 py-2 border border-slate-700 bg-slate-800 text-white rounded-lg text-sm focus:border-amber-500 focus:ring focus:ring-amber-500/20">
                        <input type="text" name="code" placeholder="Kode Unik (Contoh: BTP-M)" required
                            class="w-full px-3 py-2 border border-slate-700 bg-slate-800 text-white rounded-lg text-sm focus:border-amber-500 focus:ring focus:ring-amber-500/20">
                        <button type="submit" class="w-full px-4 py-2.5 bg-amber-600 text-white font-bold rounded-lg hover:bg-amber-500 transition">
                            Simpan Area Baru
                        </button>
                    </form>
                </div>

                <!-- Informasi Manajemen Pegawai -->
                <div class="bg-slate-900/40 rounded-xl border border-slate-800 p-8 flex flex-col justify-center text-center items-center">
                    <div class="w-16 h-16 bg-purple-500/10 rounded-full flex items-center justify-center mb-6 border border-purple-500/20">
                        <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold text-white mb-3">Pusat Manajemen Pegawai</h4>
                    <p class="text-sm text-slate-400 leading-relaxed mb-6 max-w-sm">
                        Untuk menjaga integritas dan keamanan sistem, penambahan akun <strong class="text-amber-500">Teknisi</strong> dan <strong class="text-amber-500">Marketing</strong> kini dilakukan secara terpusat.
                    </p>
                    <a href="{{ route('superadmin.users.index') }}" class="inline-flex justify-center items-center px-6 py-2.5 bg-purple-600 text-white text-sm font-bold rounded-lg hover:bg-purple-500 transition shadow-lg shadow-purple-500/20">
                        Buka Kelola Pegawai &rarr;
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>