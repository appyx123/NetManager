<x-app-layout>
    <div class="py-10 bg-slate-950 min-h-screen selection:bg-purple-500/30">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-8">
                <a href="{{ route('superadmin.users.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-400 hover:text-white transition-colors mb-4 group">
                    <svg class="w-4 h-4 mr-1.5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali
                </a>
                <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-white to-slate-400 tracking-tight">Tambah Pegawai Baru</h2>
            </div>

            <div class="bg-slate-900/80 backdrop-blur-md rounded-2xl shadow-xl border border-slate-800 p-5 sm:p-8">
                <form action="{{ route('superadmin.users.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-300 mb-2">Nama <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" required placeholder="Masukkan nama lengkap"
                                class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500/50 focus:border-purple-500 transition-all placeholder-slate-500">
                            @error('name')
                                <p class="text-rose-400 text-xs font-medium mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-300 mb-2">Email <span class="text-rose-500">*</span></label>
                            <input type="email" name="email" required placeholder="email@perusahaan.com"
                                class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500/50 focus:border-purple-500 transition-all placeholder-slate-500">
                            @error('email')
                                <p class="text-rose-400 text-xs font-medium mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-300 mb-2">Kata Sandi <span class="text-rose-500">*</span></label>
                            <input type="password" name="password" required placeholder="Minimal 8 karakter"
                                class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500/50 focus:border-purple-500 transition-all placeholder-slate-500">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-300 mb-2">Role <span class="text-rose-500">*</span></label>
                            <select name="role" required
                                class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500/50 focus:border-purple-500 transition-all appearance-none cursor-pointer">
                                <option value="" disabled selected class="bg-slate-800 text-slate-400">Pilih Role Akses</option>
                                @foreach ($roles as $role)
                                    @php
                                        $roleLabel = match($role) {
                                            'technician' => 'Teknisi',
                                            'customer' => 'Pelanggan',
                                            'admin' => 'Admin',
                                            'superadmin' => 'Super Admin',
                                            'marketing' => 'Marketing',
                                            default => ucwords(str_replace('_', ' ', $role))
                                        };
                                    @endphp
                                    <option value="{{ $role }}" class="bg-slate-800">{{ $roleLabel }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-slate-300 mb-2">No. Telepon</label>
                            <input type="text" name="phone_number" placeholder="Contoh: 08123456789"
                                class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500/50 focus:border-purple-500 transition-all placeholder-slate-500">
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-800/60 mt-6">
                        <label class="flex items-center cursor-pointer group w-max">
                            <input type="checkbox" name="is_active" value="1" checked 
                                class="w-5 h-5 rounded bg-slate-800/50 border-slate-600 text-emerald-500 focus:ring-emerald-500/50 focus:ring-offset-slate-900 transition-colors cursor-pointer">
                            <span class="ml-3 text-sm font-bold text-slate-300 group-hover:text-white transition-colors">Langsung Aktifkan Akun</span>
                        </label>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 pt-6">
                        <button type="submit" class="px-8 py-3 bg-purple-600 text-white font-bold rounded-xl hover:bg-purple-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 focus:ring-offset-slate-900 shadow-[0_0_15px_rgba(147,51,234,0.3)] hover:shadow-[0_0_20px_rgba(147,51,234,0.5)] transition-all duration-200">
                            Simpan Data Pegawai
                        </button>
                        <a href="{{ route('superadmin.users.index') }}" class="px-8 py-3 bg-slate-800 text-slate-300 font-bold rounded-xl border border-slate-700 hover:bg-slate-700 hover:text-white transition-all text-center">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>