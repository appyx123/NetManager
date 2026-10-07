<x-app-layout>
    <div class="py-10 bg-slate-950 min-h-screen selection:bg-purple-500/30">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-8">
                <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-white to-slate-400 tracking-tight">Akun & Profil Saya</h2>
                <p class="text-slate-400 mt-1 text-sm font-medium">Kelola informasi pribadi dan data kontak Anda.</p>
            </div>

            <div class="bg-slate-900/80 backdrop-blur-md rounded-2xl shadow-xl border border-slate-800 overflow-hidden">
                <div class="bg-slate-800/60 border-b border-slate-700/50 px-8 py-8 text-white flex flex-col md:flex-row items-center gap-6">
                    <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-purple-600 to-indigo-600 flex items-center justify-center text-3xl font-black text-white shadow-lg shadow-purple-600/30">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="text-center md:text-left">
                        <h3 class="text-2xl font-bold text-white">{{ Auth::user()->name }}</h3>
                        <p class="text-slate-400 text-sm mt-0.5">{{ Auth::user()->email }}</p>
                        <span class="inline-flex items-center gap-1.5 mt-2 px-3 py-1 bg-emerald-500/10 text-emerald-400 text-xs font-bold rounded-full border border-emerald-500/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                            Pelanggan Aktif
                        </span>
                    </div>
                </div>

                <div class="p-6 sm:p-8">
                    <div class="flex justify-between items-center border-b border-slate-800 pb-4 mb-6">
                        <h4 class="text-lg font-bold text-white">Informasi Kontak & Pemasangan</h4>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Nama Lengkap</label>
                            <p class="text-white font-medium">{{ Auth::user()->name }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Email (Login)</label>
                            <p class="text-white font-medium">{{ Auth::user()->email }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Nomor WhatsApp / HP</label>
                            <p class="text-white font-medium">{{ Auth::user()->customer->phone_number ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">ID Pelanggan</label>
                            <p class="text-purple-400 font-mono font-bold">{{ Auth::user()->customer->customer_code ?? '-' }}</p>
                        </div>
                        <div class="md:col-span-2 mt-2">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Alamat Pemasangan Internet</label>
                            <div class="p-4 bg-slate-950 rounded-xl border border-slate-800 mt-1">
                                <p class="text-slate-300 text-sm">{{ Auth::user()->customer->address_installation ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
