<x-app-layout>
    <div class="py-10 bg-slate-950 min-h-screen selection:bg-purple-500/30">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-8 px-4 sm:px-0">
                <a href="{{ route('admin.customers.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-400 hover:text-white transition-colors mb-4 group">
                    <svg class="w-4 h-4 mr-1.5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Daftar Pelanggan
                </a>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-600 to-blue-600 flex items-center justify-center shadow-[0_0_15px_rgba(147,51,234,0.4)]">
                        <span class="text-white font-bold text-xl">{{ substr($customer->user->name, 0, 1) }}</span>
                    </div>
                    <div>
                        <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-white to-slate-400 tracking-tight">{{ $customer->user->name }}</h2>
                        <p class="text-slate-400 text-sm font-medium">ID: {{ $customer->customer_code }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 px-4 sm:px-0">
                <div class="lg:col-span-2 bg-slate-900/80 backdrop-blur-md rounded-2xl shadow-xl border border-slate-800 p-6 sm:p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-2 bg-blue-500/10 rounded-lg border border-blue-500/20">
                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-white tracking-wide">Informasi Pelanggan</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-8">
                        <div class="bg-slate-800/30 p-4 rounded-xl border border-slate-700/30">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-wider">Email</label>
                            <p class="text-slate-200 font-medium mt-1">{{ $customer->user->email }}</p>
                        </div>
                        <div class="bg-slate-800/30 p-4 rounded-xl border border-slate-700/30">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-wider">No. KTP</label>
                            <p class="text-slate-200 font-medium mt-1">{{ $customer->nik }}</p>
                        </div>
                        <div class="bg-slate-800/30 p-4 rounded-xl border border-slate-700/30">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-wider">No. HP / WhatsApp</label>
                            <p class="text-slate-200 font-medium mt-1 flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 21.0594L13.146 20.0881L16.511 21.8211C16.924 22.0311 17.433 21.8481 17.653 21.4171L18.423 19.8641L21.895 19.4311C22.358 19.3731 22.68 18.9491 22.622 18.4861L22.138 14.6141L24.364 11.9681C24.664 11.6141 24.622 11.0821 24.269 10.7811L21.365 8.30911L21.353 4.39811C21.348 3.93511 20.969 3.56411 20.506 3.56911L16.634 3.61211L14.498 0.697113C14.225 0.327113 13.693 0.237113 13.323 0.510113L10.158 2.84611L6.46002 1.76411C6.01502 1.63411 5.55002 1.88711 5.42102 2.33211L4.35402 6.00211L0.865018 7.35411C0.432018 7.52211 0.217018 8.00911 0.384018 8.44211L1.75802 11.9961L0.0120181 15.3411C-0.203982 15.7531 -0.0409819 16.2651 0.370018 16.4801L3.75302 18.2561L3.92202 22.1551C3.94202 22.6171 4.33302 22.9751 4.79502 22.9541L8.69402 22.7841L12.031 21.0594Z" fill="#34d399"/></svg>
                                {{ $customer->phone_number }}
                            </p>
                        </div>
                        <div class="bg-slate-800/30 p-4 rounded-xl border border-slate-700/30">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-wider">Alamat Instalasi</label>
                            <p class="text-slate-200 font-medium mt-1 leading-relaxed">{{ $customer->address_installation }}</p>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-slate-800/60">
                        <a href="{{ route('admin.customers.edit', $customer) }}" class="inline-flex items-center px-6 py-2.5 bg-amber-500/10 hover:bg-amber-500/20 text-amber-400 font-bold rounded-xl border border-amber-500/20 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            Edit Data Pelanggan
                        </a>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-slate-900/80 backdrop-blur-md rounded-2xl shadow-xl border border-slate-800 p-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="p-2 bg-emerald-500/10 rounded-lg border border-emerald-500/20">
                                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <h3 class="text-lg font-bold text-white tracking-wide">Status Layanan</h3>
                        </div>

                        @if ($customer->is_isolated)
                            <div class="bg-rose-500/10 border border-rose-500/20 rounded-xl p-4 mb-6 flex items-center justify-between">
                                <div>
                                    <p class="text-rose-400 font-bold text-sm">Status Saat Ini</p>
                                    <p class="text-rose-300 font-black text-xl tracking-wider uppercase mt-1">Terisolir</p>
                                </div>
                                <span class="relative flex h-4 w-4">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-4 w-4 bg-rose-500"></span>
                                </span>
                            </div>
                            <form id="activate-form" action="{{ route('admin.customers.activate', $customer) }}" method="POST">
                                @csrf
                                <button type="button" onclick="confirmActivate()" class="w-full flex justify-center items-center px-4 py-3 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-500 shadow-[0_0_15px_rgba(52,211,153,0.3)] hover:shadow-[0_0_20px_rgba(52,211,153,0.5)] transition-all">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                    Aktifkan Layanan
                                </button>
                            </form>
                        @else
                            <div class="bg-emerald-500/10 border border-emerald-500/20 rounded-xl p-4 mb-6 flex items-center justify-between">
                                <div>
                                    <p class="text-emerald-400 font-bold text-sm">Status Saat Ini</p>
                                    <p class="text-emerald-300 font-black text-xl tracking-wider uppercase mt-1">Aktif Normal</p>
                                </div>
                                <span class="relative flex h-4 w-4">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-4 w-4 bg-emerald-500"></span>
                                </span>
                            </div>
                            <form id="isolate-form" action="{{ route('admin.customers.isolate', $customer) }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label class="block text-xs font-bold text-slate-400 mb-2">Alasan Isolir</label>
                                    <textarea name="reason" placeholder="Contoh: Menunggak tagihan 2 bulan..." required class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-500/50 focus:border-rose-500 transition-all placeholder-slate-500 resize-none h-24"></textarea>
                                </div>
                                <button type="button" onclick="confirmIsolate()" class="w-full flex justify-center items-center px-4 py-3 bg-rose-600 text-white font-bold rounded-xl hover:bg-rose-500 shadow-[0_0_15px_rgba(225,29,72,0.3)] hover:shadow-[0_0_20px_rgba(225,29,72,0.5)] transition-all">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                    Isolir Pelanggan
                                </button>
                            </form>
                        @endif
                    </div>

                    <div class="bg-slate-900/80 backdrop-blur-md rounded-2xl shadow-xl border border-slate-800 p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-2 bg-amber-500/10 rounded-lg border border-amber-500/20">
                                <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                            </div>
                            <h3 class="text-lg font-bold text-white tracking-wide">Langganan Aktif</h3>
                        </div>
                        <div class="space-y-3">
                            @forelse ($customer->subscriptions as $sub)
                                <div class="p-4 bg-slate-800/40 border border-slate-700/50 rounded-xl flex items-center justify-between group hover:bg-slate-800/80 transition-colors">
                                    <div>
                                        <p class="font-bold text-slate-200 group-hover:text-white transition-colors">{{ $sub->package->name }}</p>
                                        <p class="text-xs text-slate-400 mt-1 font-medium">Kecepatan Maksimal</p>
                                    </div>
                                    <div class="px-3 py-1.5 bg-blue-500/10 border border-blue-500/20 rounded-lg text-blue-400 font-black text-sm">
                                        {{ $sub->package->speed_mbps }} Mbps
                                    </div>
                                </div>
                            @empty
                                <div class="p-4 bg-slate-800/30 rounded-xl border border-slate-700/30 text-center">
                                    <p class="text-slate-400 font-medium text-sm">Belum ada paket langganan aktif.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.10.1/sweetalert2.all.min.js"></script>
    <script>
        // Pop-up Konfirmasi Isolir
        function confirmIsolate() {
            const reasonInput = document.querySelector('textarea[name="reason"]').value;
            if(!reasonInput) {
                Swal.fire({
                    title: 'Alasan Kosong!',
                    text: 'Harap isi alasan isolir terlebih dahulu.',
                    icon: 'error',
                    background: '#0f172a', color: '#f8fafc',
                    confirmButtonColor: '#e11d48'
                });
                return;
            }

            Swal.fire({
                title: 'Isolir Pelanggan?',
                text: "Pelanggan ini tidak akan bisa mengakses internet.",
                icon: 'warning',
                background: '#0f172a', color: '#f8fafc',
                showCancelButton: true,
                confirmButtonColor: '#e11d48', cancelButtonColor: '#1e293b',
                confirmButtonText: 'Ya, Isolir!', cancelButtonText: 'Batal',
                customClass: { popup: 'border border-slate-700 rounded-2xl' }
            }).then((result) => {
                if (result.isConfirmed) document.getElementById('isolate-form').submit();
            });
        }

        // Pop-up Konfirmasi Aktifkan
        function confirmActivate() {
            Swal.fire({
                title: 'Aktifkan Kembali?',
                text: "Koneksi internet pelanggan ini akan dibuka kembali.",
                icon: 'question',
                background: '#0f172a', color: '#f8fafc',
                showCancelButton: true,
                confirmButtonColor: '#059669', cancelButtonColor: '#1e293b',
                confirmButtonText: 'Ya, Aktifkan!', cancelButtonText: 'Batal',
                customClass: { popup: 'border border-slate-700 rounded-2xl' }
            }).then((result) => {
                if (result.isConfirmed) document.getElementById('activate-form').submit();
            });
        }
    </script>
</x-app-layout>