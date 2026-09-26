<x-app-layout>
    <div class="py-10 bg-slate-950 min-h-screen selection:bg-indigo-500/30">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-10 px-4 sm:px-0 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-white to-slate-400 tracking-tight">Manajemen Perangkat Jaringan</h2>
                    <p class="text-slate-400 mt-2 font-medium">Kelola inventaris Router, OLT, AP, dan ODP sistem Anda.</p>
                </div>
                <a href="{{ route('admin.routers.create') }}" class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold rounded-xl shadow-[0_0_15px_rgba(79,70,229,0.3)] hover:shadow-[0_0_20px_rgba(79,70,229,0.5)] transform hover:-translate-y-0.5 transition-all duration-200 border border-indigo-500/50">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Perangkat
                </a>
            </div>

            <div class="bg-slate-900/80 backdrop-blur-sm rounded-2xl shadow-xl border border-slate-800 overflow-hidden mx-4 sm:mx-0">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-800/50 text-xs font-semibold text-slate-400 uppercase tracking-wider border-b border-slate-700/50">
                            <tr>
                                <th class="px-6 py-4">Perangkat & Brand</th>
                                <th class="px-6 py-4">Lokasi</th>
                                <th class="px-6 py-4">IP Address</th>
                                <th class="px-6 py-4">Tipe</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60 text-sm">
                            @forelse ($routers as $router)
                                @php
                                    // Variasi warna badge berdasarkan tipe perangkat
                                    $typeColor = match($router->type) { 
                                        'OLT' => 'purple', 
                                        'Router' => 'sky', 
                                        'AP' => 'emerald', 
                                        'ODP' => 'amber', 
                                        default => 'slate' 
                                    };
                                @endphp
                                <tr class="hover:bg-slate-800/40 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-slate-800 border border-slate-700 flex items-center justify-center flex-shrink-0 shadow-inner">
                                                <svg class="w-5 h-5 text-slate-400 group-hover:text-indigo-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path></svg>
                                            </div>
                                            <div>
                                                <div class="font-bold text-slate-200 group-hover:text-white transition-colors text-base">{{ $router->name }}</div>
                                                <div class="text-xs text-slate-500 mt-0.5 font-medium uppercase tracking-wider">{{ $router->brand ?? 'Unknown Brand' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-1.5 font-medium text-slate-300">
                                            <svg class="w-4 h-4 text-rose-500/70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                            {{ $router->location }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="px-3 py-1.5 bg-slate-950 border border-slate-700 rounded-lg inline-flex items-center text-sky-400 font-mono text-xs tracking-wider shadow-inner">
                                            {{ $router->ip_address }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-{{ $typeColor }}-500/10 text-{{ $typeColor }}-400 border border-{{ $typeColor }}-500/20">
                                            {{ $router->type }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($router->is_active)
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                                Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-slate-800/80 text-slate-400 border border-slate-700">
                                                <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span>
                                                Nonaktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-end gap-2 font-semibold">
                                            <a href="{{ route('admin.routers.edit', $router) }}" class="text-amber-400 hover:text-amber-300 transition-colors bg-amber-400/10 hover:bg-amber-400/20 px-3 py-1.5 rounded-lg border border-amber-400/20">Edit</a>
                                            
                                            <form id="delete-form-{{ $router->id }}" action="{{ route('admin.routers.destroy', $router) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" onclick="confirmDelete({{ $router->id }})" class="text-rose-400 hover:text-rose-300 transition-colors bg-rose-400/10 hover:bg-rose-400/20 px-3 py-1.5 rounded-lg border border-rose-400/20">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center justify-center text-slate-400">
                                            <div class="w-16 h-16 mb-4 bg-slate-800/50 border border-slate-700/50 rounded-full flex items-center justify-center">
                                                <svg class="w-8 h-8 text-indigo-500/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                            </div>
                                            <p class="font-bold text-slate-300">Inventaris Jaringan Kosong</p>
                                            <p class="text-sm mt-1">Belum ada perangkat jaringan yang ditambahkan.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-8 px-4 sm:px-0">
                {{ $routers->links() }}
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.10.1/sweetalert2.all.min.js"></script>
    <script>
        @if(session('success'))
            const Toast = Swal.mixin({
                toast: true, position: 'top-end', showConfirmButton: false, timer: 4000,
                timerProgressBar: true, background: '#0f172a', color: '#34d399', iconColor: '#34d399',
                customClass: { popup: 'border border-slate-700 rounded-xl shadow-xl' }
            });
            Toast.fire({ icon: 'success', title: '{{ session("success") }}' });
        @endif

        function confirmDelete(deviceId) {
            Swal.fire({
                title: 'Hapus Perangkat?',
                text: "Konfigurasi perangkat ini akan dihapus permanen dari sistem!",
                icon: 'error', background: '#0f172a', color: '#f8fafc',
                showCancelButton: true, confirmButtonColor: '#e11d48', cancelButtonColor: '#1e293b',
                confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal',
                customClass: { popup: 'border border-slate-700 rounded-2xl', cancelButton: 'border border-slate-700 hover:bg-slate-700 text-white transition-colors' }
            }).then((result) => {
                if (result.isConfirmed) document.getElementById('delete-form-' + deviceId).submit();
            });
        }
    </script>
</x-app-layout>