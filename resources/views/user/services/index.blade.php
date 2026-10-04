<x-app-layout>
    <div class="py-10 bg-slate-50 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-8">
                <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Kelola Layanan</h2>
                <p class="text-slate-500 mt-1">Pusat kontrol untuk paket internet dan status koneksi Anda.</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-6">
                <div class="px-6 py-5 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
                    <h3 class="font-bold text-slate-800 text-lg flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        Paket Saat Ini
                    </h3>
                    <span class="bg-green-100 text-green-800 text-xs font-bold px-3 py-1 rounded-full uppercase">Aktif</span>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Nama Paket</p>
                        <p class="text-xl font-black text-slate-800">{{ Auth::user()->customer->subscriptions->first()->package->name ?? 'Belum ada paket' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Biaya Bulanan</p>
                        <p class="text-xl font-black text-amber-400">Rp {{ number_format(Auth::user()->customer->subscriptions->first()->package->price ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col items-center text-center hover:shadow-md transition">
                    <div class="w-16 h-16 bg-indigo-50 text-indigo-500 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    </div>
                    <h4 class="font-bold text-slate-800 text-lg">Edit Paket Internet</h4>
                    <p class="text-sm text-slate-500 mt-2 mb-6">Ingin kecepatan lebih tinggi (Upgrade) atau lebih hemat (Downgrade)?</p>
                    <button class="w-full py-3 bg-indigo-50 text-indigo-700 font-bold rounded-xl hover:bg-indigo-100 transition mt-auto">Ajukan Perubahan Paket</button>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col items-center text-center hover:shadow-md transition">
                    <div class="w-16 h-16 bg-orange-50 text-orange-500 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h4 class="font-bold text-slate-800 text-lg">Nonaktif Sementara</h4>
                    <p class="text-sm text-slate-500 mt-2 mb-6">Sedang keluar kota dalam waktu lama? Ajukan pemberhentian tagihan sementara (Cuti).</p>
                    <button class="w-full py-3 bg-orange-50 text-orange-700 font-bold rounded-xl hover:bg-orange-100 transition mt-auto">Ajukan Cuti Layanan</button>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
