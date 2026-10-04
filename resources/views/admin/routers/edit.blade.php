<x-app-layout>
    <div class="py-10 bg-slate-950 min-h-screen selection:bg-indigo-500/30">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-8">
                <a href="{{ route('admin.routers.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-400 hover:text-white transition-colors mb-4 group">
                    <svg class="w-4 h-4 mr-1.5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Batal Ubah
                </a>
                <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-white to-slate-400 tracking-tight">Ubah Perangkat Jaringan</h2>
                <p class="text-slate-400 mt-1 text-sm">Perbarui parameter konfigurasi, kredensial integrasi, atau data fisik perangkat.</p>
            </div>

            <div class="bg-slate-900/80 backdrop-blur-md rounded-2xl shadow-xl border border-slate-800 p-6 sm:p-8"
                 x-data="{ deviceType: '{{ old('type', $router->type) }}' }">
                
                @if ($errors->any())
                    <div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-sm">
                        <div class="font-bold mb-1 flex items-center gap-2">
                            <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Terdapat kesalahan pengisian form:
                        </div>
                        <ul class="list-disc list-inside space-y-0.5 text-xs text-rose-300/90 pl-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.routers.update', $router) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-300 mb-2">Nama Perangkat <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $router->name) }}" required placeholder="Contoh: Router Pusat BTP"
                                class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all placeholder-slate-500">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-300 mb-2">Tipe Perangkat <span class="text-rose-500">*</span></label>
                            <select name="type" x-model="deviceType" required class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all appearance-none cursor-pointer">
                                <option value="Router" @selected($router->type === 'Router')>Router / MikroTik Core</option>
                                <option value="OLT" @selected($router->type === 'OLT')>OLT (Optical Line Terminal)</option>
                                <option value="AP" @selected($router->type === 'AP')>AP (Access Point Nirkabel)</option>
                                <option value="ODP" @selected($router->type === 'ODP')>ODP (Optical Distribution Point - Pasif)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-300 mb-2">Brand / Merek <span class="text-rose-500">*</span></label>
                            <input type="text" name="brand" value="{{ old('brand', $router->brand) }}" required placeholder="MikroTik, ZTE, Huawei..."
                                class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all placeholder-slate-500">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-300 mb-2">Lokasi / POP <span class="text-rose-500">*</span></label>
                            <input type="text" name="location" value="{{ old('location', $router->location) }}" required placeholder="Contoh: POP Antang"
                                class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all placeholder-slate-500">
                        </div>

                        <!-- IP Address (Aktif untuk Router, OLT, AP; Dinonaktifkan/Opsional saat ODP) -->
                        <div x-show="deviceType !== 'ODP'" x-transition>
                            <label class="block text-sm font-bold text-slate-300 mb-2">IP Address <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                                </div>
                                <input type="text" name="ip_address" :required="deviceType !== 'ODP'" :disabled="deviceType === 'ODP'" value="{{ old('ip_address', $router->ip_address) }}" placeholder="192.168.x.x"
                                    class="w-full pl-11 pr-4 py-3 bg-slate-800/50 text-sky-400 font-mono font-bold border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all placeholder-slate-500">
                            </div>
                            <p class="text-[11px] text-slate-500 mt-1">IP Gateway atau IP Manajemen perangkat.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-300 mb-2">Status Operasional</label>
                            <select name="is_active" required class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all appearance-none cursor-pointer">
                                <option value="1" @selected(old('is_active', $router->is_active ? '1' : '0') == '1') class="bg-slate-800">Aktif (Operasional)</option>
                                <option value="0" @selected(old('is_active', $router->is_active ? '1' : '0') == '0') class="bg-slate-800">Nonaktif (Pemeliharaan / Offline)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Panel Kredensial API MikroTik / OLT (Hanya tampil untuk Router & OLT) -->
                    <div x-show="deviceType === 'Router' || deviceType === 'OLT'" x-transition class="pt-4 border-t border-slate-800/60">
                        <div class="mb-4">
                            <h3 class="text-base font-bold text-indigo-400 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                                Parameter Integrasi API (Multi-Router)
                            </h3>
                            <p class="text-xs text-slate-400 mt-0.5">Digunakan oleh sistem untuk otomatisasi PPPoE, isolir/unisolir, dan pemantauan socket.</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-slate-300 mb-2">API Username <span class="text-rose-500">*</span></label>
                                <input type="text" name="api_username" :required="deviceType === 'Router' || deviceType === 'OLT'" :disabled="deviceType === 'ODP' || deviceType === 'AP'" value="{{ old('api_username', $router->api_username) }}" placeholder="admin"
                                    class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all font-mono text-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-300 mb-2">API Password</label>
                                <input type="password" name="api_password" :disabled="deviceType === 'ODP' || deviceType === 'AP'" placeholder="Kosongkan jika tidak diubah"
                                    class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all text-sm">
                                <p class="text-[11px] text-slate-500 mt-1">Kosongkan jika tidak ingin mengganti password lama.</p>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-300 mb-2">Port API <span class="text-rose-500">*</span></label>
                                <input type="number" name="api_port" :required="deviceType === 'Router' || deviceType === 'OLT'" :disabled="deviceType === 'ODP' || deviceType === 'AP'" value="{{ old('api_port', $router->api_port ?? 8728) }}" placeholder="8728"
                                    class="w-full px-4 py-3 bg-slate-800/50 text-sky-400 font-mono font-bold border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all text-sm">
                                <p class="text-[11px] text-slate-500 mt-1">Default MikroTik: 8728 / SSL: 8729.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Panel Khusus ODP (Optical Distribution Point) -->
                    <div x-show="deviceType === 'ODP'" x-transition class="pt-4 border-t border-slate-800/60">
                        <div class="mb-4">
                            <h3 class="text-base font-bold text-amber-400 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                Spesifikasi Fisik ODP (Splitter Pasif)
                            </h3>
                            <p class="text-xs text-slate-400 mt-0.5">ODP tidak memerlukan koneksi IP/API. Masukkan kapasitas port splitter dan koordinat tiang.</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-slate-300 mb-2">Kapasitas Port Splitter <span class="text-rose-500">*</span></label>
                                <input type="number" name="port_capacity" :required="deviceType === 'ODP'" :disabled="deviceType !== 'ODP'" value="{{ old('port_capacity', $router->port_capacity ?? 8) }}" min="1" max="128" placeholder="Contoh: 8 atau 16"
                                    class="w-full px-4 py-3 bg-slate-800/50 text-amber-400 font-bold border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all text-sm">
                                <p class="text-[11px] text-slate-500 mt-1">Jumlah port drop cable yang dapat disambungkan ke rumah pelanggan.</p>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-300 mb-2">Koordinat Fisik (GPS)</label>
                                <input type="text" name="coordinates" :disabled="deviceType !== 'ODP'" value="{{ old('coordinates', $router->coordinates) }}" placeholder="Contoh: -5.135321, 119.423789"
                                    class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all font-mono text-sm placeholder-slate-500">
                                <p class="text-[11px] text-slate-500 mt-1">Latitude, Longitude titik tiang untuk panduan teknisi.</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 pt-6 mt-8 border-t border-slate-800/60">
                        <button type="submit" class="px-8 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-500 shadow-[0_0_15px_rgba(79,70,229,0.3)] hover:shadow-[0_0_20px_rgba(79,70,229,0.5)] transition-all duration-200 text-center">
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.routers.index') }}" class="px-8 py-3 bg-slate-800 text-slate-300 font-bold rounded-xl border border-slate-700 hover:bg-slate-700 hover:text-white transition-all text-center">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>