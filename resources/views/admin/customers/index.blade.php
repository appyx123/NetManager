<x-app-layout>
    <div class="py-10 bg-slate-950 min-h-screen selection:bg-purple-500/30">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-10 px-4 sm:px-0 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-white to-slate-400 tracking-tight">Manajemen Pelanggan</h2>
                    <p class="text-slate-400 mt-2 font-medium">Kelola status layanan, aktivasi, dan isolir pelanggan ISP Anda.</p>
                </div>
            </div>

            <div class="mb-8 px-4 sm:px-0 max-w-xl">
                <form action="{{ route('admin.customers.index') }}" method="GET" class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-500 group-focus-within:text-purple-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" placeholder="Cari ID, Nama, atau No. HP Pelanggan..." 
                        class="w-full pl-11 pr-32 py-3.5 bg-slate-900/80 backdrop-blur-sm text-slate-100 border border-slate-700 rounded-2xl focus:outline-none focus:ring-2 focus:ring-purple-500/50 focus:border-purple-500 transition-all shadow-lg placeholder-slate-500"
                        value="{{ request('search') }}">
                    <button type="submit" class="absolute inset-y-1.5 right-1.5 px-6 bg-purple-600 text-white font-bold rounded-xl hover:bg-purple-500 shadow-[0_0_10px_rgba(147,51,234,0.3)] transition-all">
                        Cari
                    </button>
                </form>
            </div>

            <div class="bg-slate-900/80 backdrop-blur-sm rounded-2xl shadow-xl border border-slate-800 overflow-hidden mx-4 sm:mx-0">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-800/50 text-xs font-semibold text-slate-400 uppercase tracking-wider border-b border-slate-700/50">
                            <tr>
                                <th class="px-6 py-4">ID Pelanggan</th>
                                <th class="px-6 py-4">Informasi Kontak</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60 text-sm">
                            @forelse ($customers as $customer)
                                <tr class="hover:bg-slate-800/40 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="px-3 py-1.5 bg-slate-800 border border-slate-700 rounded-lg inline-block text-slate-300 font-bold text-xs tracking-wider">
                                            {{ $customer->customer_code }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-200 group-hover:text-white transition-colors text-base">{{ $customer->user->name }}</div>
                                        <div class="text-slate-400 text-xs mt-1.5 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                            {{ $customer->phone_number }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($customer->is_isolated)
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-rose-500/10 text-rose-400 border border-rose-500/20">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                Terisolir
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                                Aktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-end gap-2 font-semibold">
                                            <a href="{{ route('admin.customers.show', $customer) }}" class="text-sky-400 hover:text-sky-300 transition-colors bg-sky-400/10 hover:bg-sky-400/20 px-3 py-1.5 rounded-lg border border-sky-400/20">Detail</a>
                                            <a href="{{ route('admin.customers.edit', $customer) }}" class="text-amber-400 hover:text-amber-300 transition-colors bg-amber-400/10 hover:bg-amber-400/20 px-3 py-1.5 rounded-lg border border-amber-400/20">Edit</a>
                                            
                                            @if (!$customer->is_isolated)
                                                <form id="isolate-form-{{ $customer->id }}" action="{{ route('admin.customers.isolate', $customer) }}" method="POST" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="reason" value="Diisolir cepat melalui halaman Daftar Pelanggan">
                                                    <button type="button" onclick="confirmIsolateQuick({{ $customer->id }})" class="text-rose-400 hover:text-rose-300 transition-colors bg-rose-400/10 hover:bg-rose-400/20 px-3 py-1.5 rounded-lg border border-rose-400/20">Isolir</button>
                                                </form>
                                            @else
                                                <form id="activate-form-{{ $customer->id }}" action="{{ route('admin.customers.activate', $customer) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="button" onclick="confirmActivateQuick({{ $customer->id }})" class="text-emerald-400 hover:text-emerald-300 transition-colors bg-emerald-400/10 hover:bg-emerald-400/20 px-3 py-1.5 rounded-lg border border-emerald-400/20">Aktifkan</button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center justify-center text-slate-400">
                                            <div class="w-16 h-16 mb-4 bg-slate-800/50 border border-slate-700/50 rounded-full flex items-center justify-center">
                                                <svg class="w-8 h-8 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                            </div>
                                            <p class="font-bold text-slate-300">Pencarian Tidak Ditemukan</p>
                                            <p class="text-sm mt-1">Coba sesuaikan kata kunci pencarian Anda.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-8 px-4 sm:px-0">
                {{ $customers->links() }}
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.10.1/sweetalert2.all.min.js"></script>
    <script>
        // Notifikasi Pop-up (Toast) saat Berhasil
        @if(session('success'))
            const Toast = Swal.mixin({
                toast: true, position: 'top-end', showConfirmButton: false, timer: 4000,
                timerProgressBar: true, background: '#0f172a', color: '#34d399', iconColor: '#34d399',
                customClass: { popup: 'border border-slate-700 rounded-xl shadow-xl' }
            });
            Toast.fire({ icon: 'success', title: '{{ session("success") }}' });
        @endif

        // Pop-up Konfirmasi Isolir Cepat
        function confirmIsolateQuick(customerId) {
            Swal.fire({
                title: 'Isolir Pelanggan?',
                text: "Koneksi internet akan diputus secara instan.",
                icon: 'warning',
                background: '#0f172a', color: '#f8fafc', showCancelButton: true,
                confirmButtonColor: '#e11d48', cancelButtonColor: '#1e293b',
                confirmButtonText: 'Ya, Isolir!', cancelButtonText: 'Batal',
                customClass: { popup: 'border border-slate-700 rounded-2xl' }
            }).then((result) => {
                if (result.isConfirmed) document.getElementById('isolate-form-' + customerId).submit();
            });
        }

        // Pop-up Konfirmasi Aktifkan Cepat
        function confirmActivateQuick(customerId) {
            Swal.fire({
                title: 'Aktifkan Kembali?',
                text: "Koneksi internet akan kembali normal.",
                icon: 'question',
                background: '#0f172a', color: '#f8fafc', showCancelButton: true,
                confirmButtonColor: '#059669', cancelButtonColor: '#1e293b',
                confirmButtonText: 'Ya, Aktifkan!', cancelButtonText: 'Batal',
                customClass: { popup: 'border border-slate-700 rounded-2xl' }
            }).then((result) => {
                if (result.isConfirmed) document.getElementById('activate-form-' + customerId).submit();
            });
        }
    </script>
</x-app-layout>