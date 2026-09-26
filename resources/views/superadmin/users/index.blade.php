<x-app-layout>
    <div class="py-10 bg-slate-950 min-h-screen selection:bg-purple-500/30">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-10 px-4 sm:px-0 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-white to-slate-400 tracking-tight">Manajemen Akun Staf</h2>
                    <p class="text-slate-400 mt-2 font-medium">Kelola kredensial dan akses semua pegawai/staf</p>
                </div>
                <a href="{{ route('superadmin.users.create') }}" class="inline-flex items-center px-5 py-2.5 bg-purple-600 hover:bg-purple-500 text-white text-sm font-bold rounded-xl shadow-[0_0_15px_rgba(147,51,234,0.3)] hover:shadow-[0_0_20px_rgba(147,51,234,0.5)] transform hover:-translate-y-0.5 transition-all duration-200 border border-purple-500/50">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah User
                </a>
            </div>

            <div class="bg-slate-900/80 backdrop-blur-sm rounded-2xl shadow-xl border border-slate-800 overflow-hidden mx-4 sm:mx-0">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-800/50 text-xs font-semibold text-slate-400 uppercase tracking-wider border-b border-slate-700/50">
                            <tr>
                                <th class="px-6 py-4">Nama</th>
                                <th class="px-6 py-4">Email</th>
                                <th class="px-6 py-4">Role</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60 text-sm">
                            @forelse ($users as $user)
                                <tr class="hover:bg-slate-800/40 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-slate-700 to-slate-800 border border-slate-600 flex items-center justify-center flex-shrink-0">
                                                <span class="text-white font-bold text-xs">{{ substr($user->name, 0, 1) }}</span>
                                            </div>
                                            <span class="font-bold text-slate-200 group-hover:text-white transition-colors">{{ $user->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-slate-400 group-hover:text-slate-300 transition-colors">{{ $user->email }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20 capitalize">
                                            {{ str_replace('_', ' ', $user->role) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($user->is_active)
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                                Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-rose-500/10 text-rose-400 border border-rose-500/20">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                Nonaktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-end gap-3 font-semibold">
                                            <a href="{{ route('superadmin.users.edit', $user) }}" class="text-amber-400 hover:text-amber-300 transition-colors bg-amber-400/10 hover:bg-amber-400/20 px-3 py-1.5 rounded-lg border border-amber-400/20">Edit</a>
                                            
                                            <form id="reset-form-{{ $user->id }}" action="{{ route('superadmin.users.resetPassword', $user) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="button" onclick="confirmReset({{ $user->id }})" class="text-purple-400 hover:text-purple-300 transition-colors bg-purple-400/10 hover:bg-purple-400/20 px-3 py-1.5 rounded-lg border border-purple-400/20">Reset PW</button>
                                            </form>
                                            
                                            <form id="delete-form-{{ $user->id }}" action="{{ route('superadmin.users.destroy', $user) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" onclick="confirmDelete({{ $user->id }})" class="text-rose-400 hover:text-rose-300 transition-colors bg-rose-400/10 hover:bg-rose-400/20 px-3 py-1.5 rounded-lg border border-rose-400/20">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center text-slate-400">
                                            <svg class="w-12 h-12 mb-3 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                            <p class="font-medium">Belum ada akun staf yang terdaftar.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-8 px-4 sm:px-0">
                {{ $users->links() }}
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.10.1/sweetalert2.all.min.js"></script>
    
    <script>
        // Notifikasi Pop-up (Toast) saat Berhasil Tambah/Edit User
        @if(session('success'))
            // Deteksi apakah ini pesan Reset Password
            @if(Str::contains(session('success'), 'Temporary password:'))
                Swal.fire({
                    title: 'Berhasil Reset Password!',
                    html: `
                        <p class="text-slate-300 text-sm mb-4">Harap salin dan berikan password sementara ini kepada pegawai bersangkutan. Password ini tidak akan ditampilkan lagi.</p>
                        <div class="bg-slate-950 p-5 rounded-xl border border-slate-700 shadow-inner">
                            <span class="text-3xl font-mono font-bold text-emerald-400 tracking-widest select-all">{{ explode('Temporary password: ', session('success'))[1] }}</span>
                        </div>
                    `,
                    icon: 'success',
                    background: '#0f172a',
                    color: '#f8fafc',
                    confirmButtonColor: '#9333ea',
                    confirmButtonText: 'Tutup & Selesai',
                    allowOutsideClick: false, // Memaksa admin klik tombol tutup agar tidak terlewat
                    customClass: {
                        popup: 'border border-slate-700 rounded-2xl shadow-2xl'
                    }
                });
            @else
                // Notifikasi reguler (Toast) untuk aksi Tambah/Edit/Hapus
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true,
                    background: '#0f172a', 
                    color: '#34d399',      
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
        @endif

        // Pop-up Konfirmasi Reset Password
        function confirmReset(userId) {
            Swal.fire({
                title: 'Reset Password?',
                text: "Password akun ini akan dikembalikan ke default.",
                icon: 'warning',
                background: '#0f172a', 
                color: '#f8fafc',      
                showCancelButton: true,
                confirmButtonColor: '#9333ea', 
                cancelButtonColor: '#1e293b',  
                confirmButtonText: 'Ya, Reset!',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'border border-slate-700 rounded-2xl',
                    cancelButton: 'border border-slate-700 hover:bg-slate-700 text-white transition-colors'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('reset-form-' + userId).submit();
                }
            });
        }

        // Pop-up Konfirmasi Hapus Data
        function confirmDelete(userId) {
            Swal.fire({
                title: 'Hapus User?',
                text: "Data staf yang dihapus tidak dapat dikembalikan!",
                icon: 'error',
                background: '#0f172a',
                color: '#f8fafc',
                showCancelButton: true,
                confirmButtonColor: '#e11d48', 
                cancelButtonColor: '#1e293b',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'border border-slate-700 rounded-2xl',
                    cancelButton: 'border border-slate-700 hover:bg-slate-700 text-white transition-colors'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + userId).submit();
                }
            });
        }
    </script>
</x-app-layout>