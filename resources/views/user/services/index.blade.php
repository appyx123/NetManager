<x-app-layout>
    <div class="py-10 bg-slate-950 min-h-screen selection:bg-indigo-500/30">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-8">
                <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-white to-slate-400 tracking-tight">Kelola Layanan</h2>
                <p class="text-slate-400 mt-1 text-sm font-medium">Pusat kontrol untuk paket internet dan status koneksi Anda.</p>
            </div>

            <div class="bg-slate-900/80 backdrop-blur-md rounded-2xl shadow-xl border border-slate-800 overflow-hidden mb-6">
                <div class="px-6 py-5 border-b border-slate-700/50 bg-slate-800/30 flex justify-between items-center">
                    <h3 class="font-bold text-white text-lg flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        Paket Saat Ini
                    </h3>
                    <span class="bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-xs font-bold px-3 py-1 rounded-full uppercase">Aktif</span>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Nama Paket</p>
                        <p class="text-xl font-black text-white">{{ Auth::user()->customer->subscriptions->first()->package->name ?? 'Belum ada paket' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Biaya Bulanan</p>
                        <p class="text-xl font-black text-amber-400">Rp {{ number_format(Auth::user()->customer->subscriptions->first()->package->price ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-slate-900/80 p-6 rounded-2xl border border-slate-800 shadow-xl flex flex-col items-center text-center hover:border-slate-700 transition">
                    <div class="w-14 h-14 bg-indigo-500/10 text-indigo-400 rounded-2xl border border-indigo-500/20 flex items-center justify-center mb-4">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    </div>
                    <h4 class="font-bold text-white text-lg">Edit Paket Internet</h4>
                    <p class="text-sm text-slate-400 mt-2 mb-6">Ingin kecepatan lebih tinggi (Upgrade) atau lebih hemat (Downgrade)?</p>
                    <button class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl transition mt-auto text-sm shadow-lg shadow-indigo-600/20">Ajukan Perubahan Paket</button>
                </div>

                <div class="bg-slate-900/80 p-6 rounded-2xl border border-slate-800 shadow-xl flex flex-col items-center text-center hover:border-slate-700 transition">
                    <div class="w-14 h-14 bg-amber-500/10 text-amber-400 rounded-2xl border border-amber-500/20 flex items-center justify-center mb-4">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h4 class="font-bold text-white text-lg">Nonaktif Sementara</h4>
                    <p class="text-sm text-slate-400 mt-2 mb-6">Sedang keluar kota dalam waktu lama? Ajukan pemberhentian tagihan sementara (Cuti).</p>
                    <button class="w-full py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white font-bold rounded-xl border border-slate-700 transition mt-auto text-sm">Ajukan Cuti Layanan</button>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
