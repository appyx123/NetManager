<x-app-layout>
    <div class="py-10 bg-slate-950 min-h-screen selection:bg-amber-500/30">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <a href="{{ route('admin.leads.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-400 hover:text-white transition-colors mb-4 group">
                    <svg class="w-4 h-4 mr-1.5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Batal
                </a>
                <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-white to-slate-400 tracking-tight">Tambah Prospek Baru</h2>
            </div>

            <div class="bg-slate-900/80 backdrop-blur-md rounded-2xl shadow-xl border border-slate-800 p-6 sm:p-8">
                <form action="{{ route('admin.leads.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf

                    <div>
                        <h3 class="text-lg font-bold text-white mb-6 flex items-center gap-2 border-b border-slate-800/60 pb-3">
                            <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Informasi Calon Pelanggan
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-slate-300 mb-2">Nama Calon Pelanggan <span class="text-rose-500">*</span></label>
                                <input type="text" name="name" required placeholder="Sesuai KTP / PIC"
                                    class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all placeholder-slate-500">
                                @error('name') <p class="text-rose-400 text-xs font-medium mt-1.5">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-300 mb-2">No. HP / WhatsApp <span class="text-rose-500">*</span></label>
                                <input type="tel" name="phone" required placeholder="08XXXXXXXXXX"
                                    class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all placeholder-slate-500">
                                @error('phone') <p class="text-rose-400 text-xs font-medium mt-1.5">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-300 mb-2">Email Pribadi/Kantor</label>
                                <input type="email" name="email" placeholder="email@contoh.com"
                                    class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all placeholder-slate-500">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-300 mb-2">Jenis Pelanggan</label>
                                <select name="customer_type" class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all appearance-none cursor-pointer">
                                    <option value="" class="bg-slate-800 text-slate-500">Pilih Tipe</option>
                                    <option value="residential" class="bg-slate-800">Rumah/Residensial</option>
                                    <option value="business" class="bg-slate-800">Kantor/Bisnis</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-300 mb-2">Nama Ibu Kandung</label>
                                <input type="text" name="mother_name" placeholder="Untuk validasi identitas nanti"
                                    class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all placeholder-slate-500">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-300 mb-2">Nama Bisnis (Bila B2B)</label>
                                <input type="text" name="business_name" placeholder="PT / CV / Toko"
                                    class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all placeholder-slate-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <label class="block text-sm font-bold text-slate-300 mb-2">Alamat Lengkap KTP</label>
                                <textarea name="address_ktp" rows="2" placeholder="Alamat sesuai identitas asli" class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all placeholder-slate-500 resize-y"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-300 mb-2">Rencana Alamat Instalasi</label>
                                <textarea name="address_installation" rows="2" placeholder="Alamat dimana internet akan ditarik" class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all placeholder-slate-500 resize-y"></textarea>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-6">
                            <div>
                                <label class="block text-sm font-bold text-slate-300 mb-2">RT/RW</label>
                                <input type="text" name="rt_rw" placeholder="001/002"
                                    class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all placeholder-slate-500">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-300 mb-2">Kelurahan</label>
                                <input type="text" name="village" placeholder="Nama desa"
                                    class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all placeholder-slate-500">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-300 mb-2">Kecamatan</label>
                                <input type="text" name="district" placeholder="Kecamatan"
                                    class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all placeholder-slate-500">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-300 mb-2">Kota</label>
                                <input type="text" name="city" placeholder="Kota/Kab"
                                    class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all placeholder-slate-500">
                            </div>
                        </div>
                    </div>

                    <div class="pt-4">
                        <h3 class="text-lg font-bold text-white mb-6 flex items-center gap-2 border-b border-slate-800/60 pb-3">
                            <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            Penawaran & Status Sales
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-slate-300 mb-2">Minat Paket <span class="text-rose-500">*</span></label>
                                <select name="package_id" required class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all appearance-none cursor-pointer">
                                    <option value="" disabled selected class="bg-slate-800 text-slate-500">Pilih Paket Penawaran</option>
                                    @foreach ($packages as $pkg)
                                        <option value="{{ $pkg->id }}" class="bg-slate-800">
                                            {{ $pkg->name }} - Rp {{ number_format($pkg->price, 0, ',', '.') }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('package_id') <p class="text-rose-400 text-xs font-medium mt-1.5">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-300 mb-2">Status Awal Pipeline</label>
                                <select name="status" class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all appearance-none cursor-pointer">
                                    <option value="prospect" class="bg-slate-800">Prospek Masuk</option>
                                    <option value="contacted" class="bg-slate-800">Sudah Dihubungi</option>
                                    <option value="qualified" class="bg-slate-800">Prospek Terkualifikasi</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-300 mb-2">Daya Tarik Promo</label>
                                <input type="text" name="promo_code" placeholder="Kode bila dijanjikan promo"
                                    class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all placeholder-slate-500">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-300 mb-2">Sumber Prospek</label>
                                <select name="source" class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all appearance-none cursor-pointer">
                                    <option value="" disabled selected class="bg-slate-800 text-slate-500">Pilih Channel</option>
                                    <option value="online" class="bg-slate-800">Online (Ads/Sosmed)</option>
                                    <option value="offline" class="bg-slate-800">Offline (Brosur)</option>
                                    <option value="referral" class="bg-slate-800">Referral/Teman</option>
                                    <option value="cold_call" class="bg-slate-800">Cold Call</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <label class="block text-sm font-bold text-slate-300 mb-2">Rencana Tanggal Survei Lapangan</label>
                                <input type="date" name="survey_date"
                                    class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all text-slate-400">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-300 mb-2">Jam Janji Temu</label>
                                <input type="time" name="preferred_time"
                                    class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all text-slate-400">
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-800/60 mt-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-sky-400 mb-2">Catatan Ringkas</label>
                                <textarea name="notes_summary" rows="3" placeholder="Insight singkat dari telpon..." class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-sky-500/50 focus:border-sky-500 transition-all placeholder-slate-500/50 resize-y"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-rose-400 mb-2">Titik Berat / Keraguan</label>
                                <textarea name="notes_obstacle" rows="3" placeholder="Kendala harga, kompetitor..." class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-500/50 focus:border-rose-500 transition-all placeholder-slate-500/50 resize-y"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-purple-400 mb-2">Permintaan Khusus</label>
                                <textarea name="notes_special" rows="3" placeholder="Minta router tambahan, dll..." class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500/50 focus:border-purple-500 transition-all placeholder-slate-500/50 resize-y"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 pt-6 mt-4">
                        <button type="submit" class="px-8 py-3 bg-amber-600 text-white font-bold rounded-xl hover:bg-amber-500 shadow-[0_0_15px_rgba(217,119,6,0.3)] hover:shadow-[0_0_20px_rgba(217,119,6,0.5)] transition-all duration-200">
                            Simpan Prospek ke Pipeline
                        </button>
                        <a href="{{ route('admin.leads.index') }}" class="px-8 py-3 bg-slate-800 text-slate-300 font-bold rounded-xl border border-slate-700 hover:bg-slate-700 hover:text-white transition-all text-center">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>