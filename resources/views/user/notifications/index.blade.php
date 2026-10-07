<x-app-layout>
    <div class="py-10 bg-slate-950 min-h-screen selection:bg-purple-500/30">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-8">
                <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-white to-slate-400 tracking-tight">Notifikasi Sistem</h2>
                <p class="text-slate-400 mt-1 text-sm font-medium">Pemberitahuan terkait tagihan, status jaringan, dan info terbaru.</p>
            </div>

            <div class="bg-slate-900/80 backdrop-blur-md rounded-2xl shadow-xl border border-slate-800 overflow-hidden divide-y divide-slate-800/60">
                
                <div class="p-6 hover:bg-slate-800/30 transition flex items-start">
                    <div class="w-10 h-10 rounded-xl bg-purple-500/10 text-purple-400 border border-purple-500/20 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div class="ml-4">
                        <h4 class="font-bold text-white text-base">Selamat Datang di NetManagement!</h4>
                        <p class="text-sm text-slate-400 mt-1">Terima kasih telah bergabung. Layanan internet Anda telah aktif dan siap digunakan.</p>
                        <span class="text-xs text-slate-500 mt-2 block font-medium">Baru saja</span>
                    </div>
                </div>

                <div class="p-6 hover:bg-slate-800/30 transition flex items-start bg-rose-500/5">
                    <div class="w-10 h-10 rounded-xl bg-rose-500/10 text-rose-400 border border-rose-500/20 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div class="ml-4">
                        <h4 class="font-bold text-rose-300 text-base">Tagihan Baru Telah Terbit</h4>
                        <p class="text-sm text-slate-400 mt-1">Tagihan bulan ini telah tersedia. Silakan periksa menu tagihan untuk menyelesaikan pembayaran sebelum jatuh tempo.</p>
                        <span class="text-xs text-slate-500 mt-2 block font-medium">1 hari yang lalu</span>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
