<x-app-layout>
    <div class="py-10 bg-slate-950 min-h-screen selection:bg-purple-500/30">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <a href="{{ route('admin.packages.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-400 hover:text-white transition-colors mb-4 group">
                    <svg class="w-4 h-4 mr-1.5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali ke Daftar Paket
                </a>
                <div class="mt-2">
                    <p class="text-xs font-black uppercase tracking-[0.2em] text-purple-400">Edit Produk</p>
                    <h1 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-white to-slate-400 tracking-tight mt-1">Edit Paket: {{ $package->name }}</h1>
                    <p class="text-slate-400 text-sm mt-1">Perbarui tarif, bandwidth, atau status ketersediaan paket.</p>
                </div>
            </div>

            <div class="bg-slate-900/80 rounded-2xl shadow-2xl border border-slate-800 backdrop-blur-md p-6 sm:p-8">
                <form action="{{ route('admin.packages.update', $package) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Nama Paket <span class="text-rose-400">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $package->name) }}" required
                            class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700/80 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm">
                        @error('name')
                            <p class="text-rose-400 text-xs mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Harga / Bulan (Rp) <span class="text-rose-400">*</span></label>
                            <input type="number" name="price" value="{{ old('price', $package->price) }}" step="1000" min="0" required
                                class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700/80 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm">
                            @error('price')
                                <p class="text-rose-400 text-xs mt-1.5 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Kecepatan (Mbps) <span class="text-rose-400">*</span></label>
                            <input type="number" name="speed_mbps" value="{{ old('speed_mbps', $package->speed_mbps) }}" step="0.1" min="0" required
                                class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700/80 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm">
                            @error('speed_mbps')
                                <p class="text-rose-400 text-xs mt-1.5 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Biaya Instalasi / Pasang (Rp) <span class="text-rose-400">*</span></label>
                        <input type="number" name="installation_fee" value="{{ old('installation_fee', $package->installation_fee) }}" step="1000" min="0" required
                            class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700/80 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm">
                        @error('installation_fee')
                            <p class="text-rose-400 text-xs mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Deskripsi & Fitur</label>
                        <textarea name="description" rows="3"
                            class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700/80 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm">{{ old('description', $package->description) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Status Publikasi <span class="text-rose-400">*</span></label>
                        <select name="is_active" required
                            class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700/80 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm">
                            <option value="1" @selected(old('is_active', $package->is_active) == '1')>Aktif (Dapat dipilih pelanggan & marketing)</option>
                            <option value="0" @selected(old('is_active', $package->is_active) == '0')>Nonaktif (Disembunyikan)</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-3 pt-4 border-t border-slate-800">
                        <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white font-bold text-sm rounded-xl shadow-lg shadow-purple-600/30 transition-all">
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.packages.index') }}" class="px-5 py-2.5 border border-slate-700/80 text-slate-300 hover:text-white hover:bg-slate-800 rounded-xl font-bold text-sm transition-all">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
