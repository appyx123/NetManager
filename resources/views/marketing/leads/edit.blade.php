<x-app-layout>
    <div class="py-10 bg-slate-950 min-h-screen selection:bg-amber-500/30">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="fade-in">
                    <a href="{{ route('marketing.leads.show', $lead->id) }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-white transition-colors mb-4 group">
                        <svg class="w-4 h-4 mr-1.5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali ke Detail Prospek
                    </a>
                    <h1 class="text-4xl font-black text-white tracking-tighter">Ubah <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-yellow-200">Data Prospek</span></h1>
                    <p class="text-slate-400 mt-2 font-medium">Perbarui parameter informasi untuk: <span class="text-amber-400 font-bold underline decoration-amber-500/30 underline-offset-4">{{ $lead->name }}</span></p>
                </div>
            </div>

            @if ($errors->any())
                <div class="mb-8 p-5 bg-rose-500/10 border border-rose-500/20 rounded-[2rem] flex items-start gap-4 animate-shake">
                    <div class="p-2 bg-rose-500 rounded-xl text-white shadow-[0_0_15px_rgba(225,29,72,0.4)]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <div>
                        <p class="text-rose-400 font-black text-xs uppercase tracking-widest mb-2">Terdapat Kendala Validasi:</p>
                        <ul class="list-disc ml-4 text-sm text-rose-300/80 space-y-1 font-medium">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form action="{{ route('marketing.leads.update', $lead->id) }}" method="POST" enctype="multipart/form-data" class="space-y-10">
                @csrf
                @method('PUT')

                <div class="bg-slate-900/80 backdrop-blur-md rounded-[2.5rem] shadow-2xl border border-slate-800 overflow-hidden relative">
                    <div class="absolute -right-20 -top-20 w-72 h-72 bg-amber-500/5 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="bg-gradient-to-r from-amber-600/20 to-transparent px-10 py-6 border-b border-slate-800/60 flex items-center gap-4">
                        <div class="p-2.5 bg-amber-500 rounded-2xl shadow-[0_0_20px_rgba(245,158,11,0.3)] text-slate-950">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <h2 class="text-xl font-black text-white tracking-tight uppercase">Data Identitas Calon Pelanggan</h2>
                    </div>

                    <div class="p-10 space-y-8">
                        <div x-data="{ customerType: '{{ old('customer_type', $lead->customer_type ?? 'personal') }}' }" class="space-y-4">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-[0.2em] ml-1">Kategori Akun <span class="text-rose-500">*</span></label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <label class="relative flex items-center p-5 cursor-pointer bg-slate-800/30 border-2 rounded-2xl transition-all duration-300 group overflow-hidden"
                                    :class="customerType == 'personal' ? 'border-amber-500 bg-amber-500/5' : 'border-slate-700 hover:border-slate-600'">
                                    <input type="radio" name="customer_type" value="personal" x-model="customerType" class="hidden">
                                    <div class="w-5 h-5 rounded-full border-2 border-slate-600 flex items-center justify-center" :class="customerType == 'personal' ? 'border-amber-500' : ''">
                                        <div class="w-2.5 h-2.5 rounded-full bg-amber-500 transition-opacity" :class="customerType == 'personal' ? 'opacity-100' : 'opacity-0'"></div>
                                    </div>
                                    <span class="ml-4 font-bold uppercase text-xs tracking-widest transition-colors" :class="customerType == 'personal' ? 'text-amber-400' : 'text-slate-400'">Perorangan</span>
                                </label>
                                <label class="relative flex items-center p-5 cursor-pointer bg-slate-800/30 border-2 rounded-2xl transition-all duration-300 group overflow-hidden"
                                    :class="customerType == 'business' ? 'border-amber-500 bg-amber-500/5' : 'border-slate-700 hover:border-slate-600'">
                                    <input type="radio" name="customer_type" value="business" x-model="customerType" class="hidden">
                                    <div class="w-5 h-5 rounded-full border-2 border-slate-600 flex items-center justify-center" :class="customerType == 'business' ? 'border-amber-500' : ''">
                                        <div class="w-2.5 h-2.5 rounded-full bg-amber-500 transition-opacity" :class="customerType == 'business' ? 'opacity-100' : 'opacity-0'"></div>
                                    </div>
                                    <span class="ml-4 font-bold uppercase text-xs tracking-widest transition-colors" :class="customerType == 'business' ? 'text-amber-400' : 'text-slate-400'">Bisnis / Usaha</span>
                                </label>
                            </div>

                            <div x-show="customerType == 'business'" class="mt-6 space-y-2 animate-fade-in">
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Nama Usaha / Instansi</label>
                                <input type="text" name="business_name" value="{{ old('business_name', $lead->business_name) }}"
                                    class="w-full px-5 py-4 bg-slate-800/50 border border-slate-700 text-white rounded-2xl focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all font-bold tracking-tight">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-[0.2em] ml-1">Nama Lengkap (Sesuai KTP) <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $lead->name) }}" required
                                class="w-full px-5 py-4 bg-slate-800/50 border border-slate-700 text-white rounded-2xl focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all font-bold tracking-tight text-lg">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label class="block text-xs font-black text-slate-500 uppercase tracking-[0.2em] ml-1">WhatsApp Utama <span class="text-rose-500">*</span></label>
                                <input type="text" name="phone" value="{{ old('phone', $lead->phone) }}" required
                                    class="w-full px-5 py-4 bg-slate-800/50 border border-slate-700 text-white rounded-2xl focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all font-mono tracking-widest">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-xs font-black text-slate-500 uppercase tracking-[0.2em] ml-1">Nomor Cadangan</label>
                                <input type="text" name="phone_backup" value="{{ old('phone_backup', $lead->phone_backup ?? '') }}"
                                    class="w-full px-5 py-4 bg-slate-800/50 border border-slate-700 text-white rounded-2xl focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all font-mono tracking-widest">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-xs font-black text-slate-500 uppercase tracking-[0.2em] ml-1">Email</label>
                                <input type="email" name="email" value="{{ old('email', $lead->email) }}"
                                    class="w-full px-5 py-4 bg-slate-800/50 border border-slate-700 text-white rounded-2xl focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-xs font-black text-slate-500 uppercase tracking-[0.2em] ml-1">Nama Ibu Kandung</label>
                                <input type="text" name="mother_name" value="{{ old('mother_name', $lead->mother_name) }}"
                                    class="w-full px-5 py-4 bg-slate-800/50 border border-slate-700 text-white rounded-2xl focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all font-medium">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-6 border-t border-slate-800/60">
                            @foreach(['ktp_image' => 'Identitas (KTP)', 'house_image' => 'Lokasi Rumah', 'customer_image' => 'Wajah Pelanggan'] as $key => $label)
                            <div class="space-y-3">
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">{{ $label }}</label>
                                @php $pathKey = $key . '_path'; @endphp
                                @if ($lead->$pathKey)
                                    @php
                                        $previewUrl = match($key) {
                                            'ktp_image' => route('documents.ktp', $lead),
                                            'customer_image' => route('documents.customer_photo', $lead),
                                            default => \Illuminate\Support\Facades\Storage::url($lead->$pathKey),
                                        };
                                    @endphp
                                    <div class="flex items-center gap-3 mb-2 px-3 py-2 bg-emerald-500/10 border border-emerald-500/20 rounded-xl">
                                        <div class="p-1 bg-emerald-500 rounded-lg text-white">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path></svg>
                                        </div>
                                        <span class="text-[10px] font-black text-emerald-400 uppercase tracking-widest">Tersedia</span>
                                        <a href="{{ $previewUrl }}" target="_blank" class="ml-auto text-[9px] font-black text-white hover:text-emerald-300 uppercase underline">Preview</a>
                                    </div>
                                @endif
                                <input type="file" name="{{ $key }}" accept="image/*"
                                    class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:tracking-widest file:bg-slate-800 file:text-white hover:file:bg-slate-700 transition-all cursor-pointer">
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="bg-slate-900/80 backdrop-blur-md rounded-[2.5rem] shadow-2xl border border-slate-800 overflow-hidden relative">
                    <div class="bg-gradient-to-r from-indigo-600/20 to-transparent px-10 py-6 border-b border-slate-800/60 flex items-center gap-4">
                        <div class="p-2.5 bg-indigo-500 rounded-2xl shadow-[0_0_20px_rgba(79,70,229,0.3)] text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                        </div>
                        <h2 class="text-xl font-black text-white tracking-tight uppercase">Update Lokasi Pemasangan</h2>
                    </div>
                    <div class="p-10 space-y-8">
                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-[0.2em] ml-1">Alamat Lengkap <span class="text-rose-500">*</span></label>
                            <textarea name="address" rows="3" required
                                class="w-full px-5 py-4 bg-slate-800/50 border border-slate-700 text-white rounded-2xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all font-medium">{{ old('address', $lead->address) }}</textarea>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-[0.2em] ml-1">Alamat Sesuai KTP (Opsional)</label>
                            <textarea name="address_ktp" rows="2"
                                class="w-full px-5 py-4 bg-slate-800/50 border border-slate-700 text-white rounded-2xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all">{{ old('address_ktp', $lead->address_ktp) }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">RT / RW</label>
                                <input type="text" name="rt_rw" value="{{ old('rt_rw', $lead->rt_rw) }}"
                                    class="w-full px-5 py-4 bg-slate-800/50 border border-slate-700 text-white rounded-2xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Kelurahan / Desa <span class="text-rose-500">*</span></label>
                                <input type="text" name="village" value="{{ old('village', $lead->village) }}" required
                                    class="w-full px-5 py-4 bg-slate-800/50 border border-slate-700 text-white rounded-2xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Kecamatan <span class="text-rose-500">*</span></label>
                                <input type="text" name="district" value="{{ old('district', $lead->district) }}" required
                                    class="w-full px-5 py-4 bg-slate-800/50 border border-slate-700 text-white rounded-2xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Kota / Kabupaten <span class="text-rose-500">*</span></label>
                                <input type="text" name="city" value="{{ old('city', $lead->city) }}" required
                                    class="w-full px-5 py-4 bg-slate-800/50 border border-slate-700 text-white rounded-2xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Provinsi</label>
                                <input type="text" name="province" value="{{ old('province', $lead->province) }}"
                                    class="w-full px-5 py-4 bg-slate-800/50 border border-slate-700 text-white rounded-2xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Kode Pos</label>
                                <input type="text" name="postal_code" value="{{ old('postal_code', $lead->postal_code) }}"
                                    class="w-full px-5 py-4 bg-slate-800/50 border border-slate-700 text-white rounded-2xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Patokan Lokasi</label>
                                <input type="text" name="landmark" value="{{ old('landmark', $lead->landmark) }}"
                                    class="w-full px-5 py-4 bg-slate-800/50 border border-slate-700 text-white rounded-2xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Titik Koordinat Peta</label>
                                <input type="text" name="coordinates" value="{{ old('coordinates', $lead->coordinates) }}"
                                    class="w-full px-5 py-4 bg-slate-800/50 border border-slate-700 text-sky-400 rounded-2xl focus:ring-2 focus:ring-sky-500/50 focus:border-sky-500 transition-all font-mono tracking-widest">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-900/80 backdrop-blur-md rounded-[2.5rem] shadow-2xl border border-slate-800 overflow-hidden">
                    <div class="bg-gradient-to-r from-emerald-600/20 to-transparent px-10 py-6 border-b border-slate-800/60 flex items-center gap-4">
                        <div class="p-2.5 bg-emerald-500 rounded-2xl shadow-[0_0_20px_rgba(16,185,129,0.3)] text-slate-950">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"></path></svg>
                        </div>
                        <h2 class="text-xl font-black text-white tracking-tight uppercase">Paket Layanan Langganan</h2>
                    </div>
                    <div class="p-10 space-y-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label class="block text-xs font-black text-slate-500 uppercase tracking-[0.2em] ml-1">Pilih Produk Internet <span class="text-rose-500">*</span></label>
                                <select name="package_id" required class="w-full px-5 py-4 bg-slate-800/50 border border-slate-700 text-white rounded-2xl focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 appearance-none cursor-pointer font-bold">
                                    @foreach ($packages as $package)
                                        <option value="{{ $package->id }}" @selected(old('package_id', $lead->package_id) == $package->id) class="bg-slate-900">
                                            {{ $package->name }} - Rp {{ number_format($package->price, 0, ',', '.') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-xs font-black text-slate-500 uppercase tracking-[0.2em] ml-1">Biaya Pemasangan (Registration Fee)</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none font-bold text-slate-500">Rp</div>
                                    <input type="number" name="installation_fee" value="{{ old('installation_fee', $lead->installation_fee ?? 0) }}"
                                        class="w-full pl-12 pr-5 py-4 bg-slate-800/50 border border-slate-700 text-emerald-400 rounded-2xl focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all font-bold">
                                </div>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-[0.2em] ml-1">Kode Voucher / Penawaran</label>
                            <input type="text" name="promo_code" value="{{ old('promo_code', $lead->promo_code) }}" placeholder="KOSONGKAN JIKA TIDAK ADA"
                                class="w-full px-5 py-4 bg-slate-800/50 border border-slate-700 text-white rounded-2xl focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all font-mono tracking-widest uppercase">
                        </div>
                    </div>
                </div>

                <div class="bg-slate-900/80 backdrop-blur-md rounded-[2.5rem] shadow-2xl border border-slate-800 overflow-hidden">
                    <div class="p-10 grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-[0.2em] ml-1">Perbarui Status Prospek <span class="text-rose-500">*</span></label>
                            <select name="status" required class="w-full px-5 py-4 bg-slate-800/50 border border-slate-700 text-white rounded-2xl focus:ring-2 focus:ring-sky-500/50 focus:border-sky-500 font-bold uppercase text-xs tracking-widest">
                                <option value="prospek" @selected(old('status', $lead->status) == 'prospek')>Prospek</option>
                                <option value="survey" @selected(old('status', $lead->status) == 'survey')>Siap Disurvei</option>
                                <option value="instalasi" @selected(old('status', $lead->status) == 'instalasi')>Antrean Instalasi</option>
                                <option value="aktif" @selected(old('status', $lead->status) == 'aktif')>Akun Aktif</option>
                                <option value="batal" @selected(old('status', $lead->status) == 'batal')>Batal</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-[0.2em] ml-1">Ubah Sumber Perolehan <span class="text-rose-500">*</span></label>
                            <select name="source" required class="w-full px-5 py-4 bg-slate-800/50 border border-slate-700 text-white rounded-2xl focus:ring-2 focus:ring-sky-500/50 focus:border-sky-500 font-bold uppercase text-xs tracking-widest">
                                <option value="iklan" @selected(old('source', $lead->source) == 'iklan')>Iklan Digital (FB/IG/Google Ads)</option>
                                <option value="referensi" @selected(old('source', $lead->source) == 'referensi')>Referensi Pelanggan</option>
                                <option value="sosial_media" @selected(old('source', $lead->source) == 'sosial_media')>Media Sosial Organik</option>
                                <option value="walk_in" @selected(old('source', $lead->source) == 'walk_in')>Kunjungan Langsung (Walk-in)</option>
                                <option value="telepon" @selected(old('source', $lead->source) == 'telepon')>Panggilan Telepon</option>
                                <option value="lainnya" @selected(old('source', $lead->source) == 'lainnya')>Kanal Lainnya</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-900/80 backdrop-blur-md rounded-[2.5rem] shadow-2xl border border-slate-800 overflow-hidden relative">
                    <div class="bg-gradient-to-r from-sky-600/20 to-transparent px-10 py-6 border-b border-slate-800/60 flex items-center gap-4">
                        <div class="p-2.5 bg-sky-500 rounded-2xl shadow-[0_0_20px_rgba(14,165,233,0.3)] text-slate-950">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <h2 class="text-xl font-black text-white tracking-tight uppercase">Perbarui Penjadwalan</h2>
                    </div>
                    <div class="p-10 space-y-8">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Tanggal Registrasi</label>
                                <input type="date" name="registered_date" value="{{ old('registered_date', $lead->registered_date?->format('Y-m-d')) }}"
                                    class="w-full px-5 py-4 bg-slate-800/50 border border-slate-700 text-white rounded-2xl focus:ring-2 focus:ring-sky-500/50 focus:border-sky-500 transition-all font-medium">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Jadwal Survei</label>
                                <input type="date" name="survey_date" value="{{ old('survey_date', $lead->survey_date?->format('Y-m-d')) }}"
                                    class="w-full px-5 py-4 bg-slate-800/50 border border-slate-700 text-white rounded-2xl focus:ring-2 focus:ring-sky-500/50 focus:border-sky-500 transition-all font-medium">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Target Instalasi</label>
                                <input type="date" name="installation_date" value="{{ old('installation_date', $lead->installation_date?->format('Y-m-d')) }}"
                                    class="w-full px-5 py-4 bg-slate-800/50 border border-slate-700 text-amber-400 rounded-2xl focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all font-bold">
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-[0.2em] ml-1">Preferensi Waktu Pelanggan</label>
                            <textarea name="preferred_time" rows="2"
                                class="w-full px-5 py-4 bg-slate-800/50 border border-slate-700 text-white rounded-2xl focus:ring-2 focus:ring-sky-500/50 focus:border-sky-500 transition-all">{{ old('preferred_time', $lead->preferred_time) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-900/80 backdrop-blur-md rounded-[2.5rem] shadow-2xl border border-slate-800 overflow-hidden relative">
                    <div class="p-10 space-y-8">
                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-[0.2em] ml-1">Ringkasan Komunikasi</label>
                            <textarea name="notes_summary" rows="2"
                                class="w-full px-5 py-4 bg-slate-800/50 border border-slate-700 text-white rounded-2xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all">{{ old('notes_summary', $lead->notes_summary) }}</textarea>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-xs font-black text-rose-500 uppercase tracking-[0.2em] ml-1">Hambatan Lapangan (Critical)</label>
                            <textarea name="notes_obstacle" rows="2"
                                class="w-full px-5 py-4 bg-rose-500/5 border border-rose-500/20 text-rose-400 rounded-2xl focus:ring-2 focus:ring-rose-500/50 focus:border-rose-500 transition-all">{{ old('notes_obstacle', $lead->notes_obstacle) }}</textarea>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-[0.2em] ml-1">Catatan Khusus Penanganan</label>
                            <textarea name="notes_special" rows="2"
                                class="w-full px-5 py-4 bg-slate-800/50 border border-slate-700 text-white rounded-2xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all">{{ old('notes_special', $lead->notes_special) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-4">
                    <button type="submit" class="flex-[2] px-8 py-5 bg-amber-600 text-white font-black rounded-3xl shadow-[0_0_25px_rgba(217,119,6,0.3)] hover:bg-amber-500 hover:shadow-[0_0_35px_rgba(217,119,6,0.5)] transform hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-3 text-lg tracking-tight uppercase">
                        Simpan Perubahan Data
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                    <a href="{{ route('marketing.leads.show', $lead->id) }}" class="flex-1 px-8 py-5 bg-slate-900 text-slate-400 font-bold rounded-3xl border border-slate-800 hover:bg-slate-800 hover:text-white transition-all duration-300 flex items-center justify-center text-lg text-center leading-none uppercase tracking-widest">
                        Batal
                    </a>
                </div>

            </form>

            <div class="mt-12 text-center">
                <p class="text-[10px] font-black text-slate-600 uppercase tracking-[0.3em]">
                    PT. Mandiri Global Data &bull; Sales Force Management &bull; 2026
                </p>
            </div>
        </div>
    </div>
</x-app-layout>