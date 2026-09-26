<x-app-layout>
    <div class="py-10 bg-slate-950 min-h-screen selection:bg-indigo-500/30">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-10 px-4 sm:px-0">
                <a href="javascript:history.back()" class="inline-flex items-center text-sm font-semibold text-slate-400 hover:text-white transition-colors mb-4 group">
                    <svg class="w-4 h-4 mr-1.5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali
                </a>
                <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-white to-slate-400 tracking-tight">Pengaturan Profil</h2>
                <p class="text-slate-400 mt-2 font-medium">Kelola informasi pribadi, kredensial keamanan, dan status akun Anda.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 px-4 sm:px-0">
                
                <div class="lg:col-span-2 space-y-8">
                    
                    <div class="bg-slate-900/80 backdrop-blur-md rounded-2xl shadow-xl border border-slate-800 p-6 sm:p-8">
                        <div class="flex items-center gap-3 mb-6 border-b border-slate-800/60 pb-4">
                            <div class="p-2 bg-indigo-500/10 rounded-lg border border-indigo-500/20">
                                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <h3 class="text-xl font-bold text-white tracking-wide">Informasi Profil</h3>
                        </div>

                        <form action="{{ route('admin.profile.updateProfile') }}" method="POST" class="space-y-6">
                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Nama Lengkap <span class="text-rose-500">*</span></label>
                                    <input type="text" name="name" value="{{ $user->name }}" required
                                        class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all placeholder-slate-500">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Alamat Email <span class="text-rose-500">*</span></label>
                                    <input type="email" name="email" value="{{ $user->email }}" required
                                        class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all placeholder-slate-500">
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">No. Telepon / WhatsApp</label>
                                    <input type="text" name="phone_number" value="{{ $user->phone_number ?? '' }}" placeholder="08XXXXXXXXXX"
                                        class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all placeholder-slate-500">
                                </div>
                            </div>

                            <div class="pt-4">
                                <button type="submit" class="px-8 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-500 shadow-[0_0_15px_rgba(79,70,229,0.3)] hover:shadow-[0_0_20px_rgba(79,70,229,0.5)] transition-all duration-200">
                                    Perbarui Profil
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="bg-slate-900/80 backdrop-blur-md rounded-2xl shadow-xl border border-slate-800 p-6 sm:p-8">
                        <div class="flex items-center gap-3 mb-6 border-b border-slate-800/60 pb-4">
                            <div class="p-2 bg-rose-500/10 rounded-lg border border-rose-500/20">
                                <svg class="w-5 h-5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </div>
                            <h3 class="text-xl font-bold text-white tracking-wide">Keamanan & Password</h3>
                        </div>

                        <form action="{{ route('admin.profile.updatePassword') }}" method="POST" class="space-y-6">
                            @csrf

                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Password Lama <span class="text-rose-500">*</span></label>
                                <input type="password" name="current_password" required placeholder="Masukkan password saat ini"
                                    class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-500/50 focus:border-rose-500 transition-all placeholder-slate-500">
                                @error('current_password')
                                    <p class="text-rose-400 text-xs font-medium mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Password Baru <span class="text-rose-500">*</span></label>
                                    <input type="password" name="new_password" required placeholder="Minimal 8 karakter"
                                        class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-500/50 focus:border-rose-500 transition-all placeholder-slate-500">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Konfirmasi Password Baru <span class="text-rose-500">*</span></label>
                                    <input type="password" name="new_password_confirmation" required placeholder="Ulangi password baru"
                                        class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-500/50 focus:border-rose-500 transition-all placeholder-slate-500">
                                </div>
                            </div>

                            <div class="pt-4">
                                <button type="submit" class="px-8 py-3 bg-rose-600 text-white font-bold rounded-xl hover:bg-rose-500 shadow-[0_0_15px_rgba(225,29,72,0.3)] hover:shadow-[0_0_20px_rgba(225,29,72,0.5)] transition-all duration-200">
                                    Perbarui Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-slate-900/80 backdrop-blur-md rounded-2xl shadow-xl border border-slate-800 p-8 relative overflow-hidden">
                        <div class="absolute -right-10 -top-10 w-40 h-40 bg-indigo-500/20 rounded-full blur-3xl"></div>

                        <div class="flex flex-col items-center text-center mb-8 relative z-10">
                            <div class="w-24 h-24 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 p-1 shadow-[0_0_20px_rgba(79,70,229,0.4)] mb-4">
                                <div class="w-full h-full bg-slate-900 rounded-full flex items-center justify-center">
                                    <span class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-400">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </span>
                                </div>
                            </div>
                            <h3 class="text-xl font-bold text-white">{{ $user->name }}</h3>
                            <p class="text-slate-400 text-sm mt-1">{{ $user->email }}</p>
                        </div>

                        <div class="space-y-6 relative z-10 border-t border-slate-800/60 pt-6">
                            <div>
                                <label class="flex items-center gap-2 text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">
                                    <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                    Role / Hak Akses
                                </label>
                                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-bold bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 capitalize shadow-inner">
                                    {{ str_replace('_', ' ', $user->role) }}
                                </span>
                            </div>

                            <div>
                                <label class="flex items-center gap-2 text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">
                                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Status Akun
                                </label>
                                @if ($user->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 shadow-inner">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        Aktif Beroperasi
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-bold bg-rose-500/10 text-rose-400 border border-rose-500/20 shadow-inner">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        Nonaktif / Diblokir
                                    </span>
                                @endif
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Bergabung Sejak</label>
                                    <p class="text-slate-300 font-semibold text-sm">{{ $user->created_at->format('d M Y') }}</p>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Update Terakhir</label>
                                    <p class="text-slate-300 font-semibold text-sm">{{ $user->updated_at->format('d M Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.10.1/sweetalert2.all.min.js"></script>
    <script>
        // Notifikasi Pop-up (Toast) saat Profil atau Password Berhasil Diubah
        @if(session('success'))
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                background: '#0f172a', // slate-900
                color: '#34d399',      // emerald-400
                iconColor: '#34d399',
                customClass: {
                    popup: 'border border-slate-700 rounded-xl shadow-xl'
                }
            });

            Toast.fire({
                icon: 'success',
                title: '{{ session("success") }}'
            });
        @endif
        
        // Notifikasi Error (jika password lama salah)
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Pembaruan Gagal',
                text: '{{ session("error") }}',
                background: '#0f172a',
                color: '#f8fafc',
                confirmButtonColor: '#e11d48',
                customClass: {
                    popup: 'border border-slate-700 rounded-2xl'
                }
            });
        @endif
    </script>
</x-app-layout>