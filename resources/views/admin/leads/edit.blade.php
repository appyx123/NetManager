<x-app-layout>
    <div class="py-10 bg-slate-950 min-h-screen selection:bg-amber-500/30">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <a href="{{ route('admin.leads.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-400 hover:text-white transition-colors mb-4 group">
                    <svg class="w-4 h-4 mr-1.5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Batal
                </a>
                <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-white to-slate-400 tracking-tight">Ubah Data Prospek</h2>
            </div>

            <div class="bg-slate-900/80 backdrop-blur-md rounded-2xl shadow-xl border border-slate-800 p-6 sm:p-8">
                <form action="{{ route('admin.leads.update', $lead) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    @method('PUT')

                    <div>
                        <h3 class="text-lg font-bold text-white mb-6 flex items-center gap-2 border-b border-slate-800/60 pb-3">
                            <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Informasi Calon Pelanggan
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-slate-300 mb-2">Nama Calon Pelanggan <span class="text-rose-500">*</span></label>
                                <input type="text" name="name" value="{{ $lead->name }}" required
                                    class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all placeholder-slate-500">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-300 mb-2">No. HP / WhatsApp <span class="text-rose-500">*</span></label>
                                <input type="tel" name="phone" value="{{ $lead->phone }}" required
                                    class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all placeholder-slate-500">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-300 mb-2">Email</label>
                                <input type="email" name="email" value="{{ $lead->email }}"
                                    class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all placeholder-slate-500">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-300 mb-2">Jenis Pelanggan</label>
                                <select name="customer_type" class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all appearance-none cursor-pointer">
                                    <option value="residential" @selected($lead->customer_type === 'residential') class="bg-slate-800">Rumah/Residensial</option>
                                    <option value="business" @selected($lead->customer_type === 'business') class="bg-slate-800">Kantor/Bisnis</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-300 mb-2">Nama Ibu Kandung</label>
                                <input type="text" name="mother_name" value="{{ $lead->mother_name }}"
                                    class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all placeholder-slate-500">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-300 mb-2">Nama Bisnis (Khusus Kantor)</label>
                                <input type="text" name="business_name" value="{{ $lead->business_name }}"
                                    class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all placeholder-slate-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <label class="block text-sm font-bold text-slate-300 mb-2">Alamat KTP</label>
                                <textarea name="address_ktp" rows="2" class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all placeholder-slate-500 resize-y">{{ $lead->address_ktp }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-300 mb-2">Alamat Instalasi Pemasangan</label>
                                <textarea name="address_installation" rows="2" class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all placeholder-slate-500 resize-y">{{ $lead->address_installation }}</textarea>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-6">
                            <div>
                                <label class="block text-sm font-bold text-slate-300 mb-2">RT/RW</label>
                                <input type="text" name="rt_rw" value="{{ $lead->rt_rw }}"
                                    class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-300 mb-2">Kelurahan</label>
                                <input type="text" name="village" value="{{ $lead->village }}"
                                    class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-300 mb-2">Kecamatan</label>
                                <input type="text" name="district" value="{{ $lead->district }}"
                                    class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-300 mb-2">Kota</label>
                                <input type="text" name="city" value="{{ $lead->city }}"
                                    class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all">
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
                                <label class="block text-sm font-bold text-slate-300 mb-2">Paket Pilihan <span class="text-rose-500">*</span></label>
                                <select name="package_id" required class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all appearance-none cursor-pointer">
                                    <option value="" class="bg-slate-800">Pilih Paket</option>
                                    @foreach ($packages as $pkg)
                                        <option value="{{ $pkg->id }}" @selected($lead->package_id === $pkg->id) class="bg-slate-800">
                                            {{ $pkg->name }} - Rp {{ number_format($pkg->price, 0, ',', '.') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-300 mb-2">Status Penjualan (Pipeline)</label>
                                <select name="status" class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all appearance-none cursor-pointer">
                                    <option value="prospek" @selected($lead->status === 'prospek' || $lead->status === 'prospect') class="bg-slate-800">Prospek</option>
                                    <option value="survey" @selected($lead->status === 'survey') class="bg-slate-800">Survei Lokasi</option>
                                    <option value="instalasi" @selected($lead->status === 'instalasi') class="bg-slate-800">Instalasi Fisik</option>
                                    <option value="aktif" @selected($lead->status === 'aktif' || $lead->status === 'converted') class="bg-slate-800">Aktif (Terpasang)</option>
                                    <option value="batal" @selected($lead->status === 'batal' || $lead->status === 'lost') class="bg-slate-800">Batal</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-300 mb-2">Kode Promo Khusus</label>
                                <input type="text" name="promo_code" value="{{ $lead->promo_code }}"
                                    class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-300 mb-2">Sumber Prospek</label>
                                <select name="source" class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all appearance-none cursor-pointer">
                                    <option value="online" @selected($lead->source === 'online') class="bg-slate-800">Online (Web/Sosmed)</option>
                                    <option value="offline" @selected($lead->source === 'offline') class="bg-slate-800">Offline (Brosur/Pameran)</option>
                                    <option value="referral" @selected($lead->source === 'referral') class="bg-slate-800">Referral Pelanggan</option>
                                    <option value="cold_call" @selected($lead->source === 'cold_call') class="bg-slate-800">Telepon Langsung</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <label class="block text-sm font-bold text-slate-300 mb-2">Rencana Tanggal Survei</label>
                                <input type="date" name="survey_date" value="{{ $lead->survey_date }}"
                                    class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-300 mb-2">Jam Janji Temu</label>
                                <input type="time" name="preferred_time" value="{{ $lead->preferred_time }}"
                                    class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all">
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-800/60 mt-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-sky-400 mb-2">Catatan Ringkas</label>
                                <textarea name="notes_summary" rows="3" class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-sky-500/50 focus:border-sky-500 transition-all resize-y">{{ $lead->notes_summary }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-rose-400 mb-2">Hambatan Negosiasi</label>
                                <textarea name="notes_obstacle" rows="3" class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-500/50 focus:border-rose-500 transition-all resize-y">{{ $lead->notes_obstacle }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-purple-400 mb-2">Permintaan Khusus</label>
                                <textarea name="notes_special" rows="3" class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500/50 focus:border-purple-500 transition-all resize-y">{{ $lead->notes_special }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 pt-6 mt-4">
                        <button type="submit" class="px-8 py-3 bg-amber-600 text-white font-bold rounded-xl hover:bg-amber-500 shadow-[0_0_15px_rgba(217,119,6,0.3)] hover:shadow-[0_0_20px_rgba(217,119,6,0.5)] transition-all duration-200">
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.leads.show', $lead) }}" class="px-8 py-3 bg-slate-800 text-slate-300 font-bold rounded-xl border border-slate-700 hover:bg-slate-700 hover:text-white transition-all text-center">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>