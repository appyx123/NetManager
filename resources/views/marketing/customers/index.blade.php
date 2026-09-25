<x-app-layout>
    <div class="py-10 bg-slate-950 min-h-screen selection:bg-indigo-500/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="fade-in">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2 bg-indigo-500/10 rounded-lg border border-indigo-500/20">
                            <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <span class="text-[10px] font-black text-indigo-500 uppercase tracking-[0.2em]">Customer Database</span>
                    </div>
                    <h1 class="text-4xl font-black text-white tracking-tighter">Pelanggan <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-cyan-300">Saya</span></h1>
                    <p class="text-slate-400 mt-2 font-medium">Kelola data pelanggan yang telah sukses dikonversi dari prospek awal.</p>
                </div>
            </div>

            <div class="bg-slate-900/80 backdrop-blur-md rounded-[2rem] shadow-2xl border border-slate-800 p-8 mb-10 relative overflow-hidden">
                <div class="absolute -left-20 -top-20 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>

                <form method="GET" action="{{ route('marketing.customers.index') }}" class="relative z-10 grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-[0.2em] ml-1">Cari Pelanggan</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500 group-focus-within:text-indigo-400 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama atau No. Telepon..." 
                                class="w-full pl-12 pr-4 py-4 bg-slate-800/50 border border-slate-700 text-white rounded-2xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all placeholder-slate-600 font-medium">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-[0.2em] ml-1">Status Layanan</label>
                        <div class="relative group">
                            <select name="status" class="w-full px-5 py-4 bg-slate-800/50 border border-slate-700 text-white rounded-2xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all appearance-none cursor-pointer font-bold">
                                <option value="" class="bg-slate-900">Semua Status</option>
                                <option value="aktif" class="bg-slate-900 text-emerald-400" @selected(request('status') === 'aktif')>Aktif</option>
                                <option value="nonaktif" class="bg-slate-900 text-rose-400" @selected(request('status') === 'nonaktif')>Nonaktif (Terisolir)</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-5 flex items-center pointer-events-none text-slate-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 py-4 bg-indigo-600 text-white font-black rounded-2xl shadow-[0_0_20px_rgba(79,70,229,0.3)] hover:bg-indigo-500 hover:shadow-[0_0_30px_rgba(79,70,229,0.5)] transform hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-2 tracking-widest uppercase text-xs">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            Cari Data
                        </button>
                        @if(request()->hasAny(['search', 'status']))
                            <a href="{{ route('marketing.customers.index') }}" class="px-5 py-4 bg-slate-800 text-slate-400 hover:text-white rounded-2xl border border-slate-700 font-bold text-xs flex items-center justify-center">Reset</a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="hidden md:block bg-slate-900/80 backdrop-blur-md rounded-[2.5rem] shadow-2xl border border-slate-800 overflow-hidden relative mb-10">
                <div class="absolute -right-20 -top-20 w-72 h-72 bg-cyan-500/5 rounded-full blur-3xl pointer-events-none"></div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-800/50 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] border-b border-slate-800/60">
                            <tr>
                                <th class="px-8 py-5">Kode / Identitas</th>
                                <th class="px-6 py-5">Informasi Kontak</th>
                                <th class="px-6 py-5">Alamat Pemasangan</th>
                                <th class="px-6 py-5 text-center">Status</th>
                                <th class="px-8 py-5 text-right">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60 text-sm">
                            @forelse ($customers as $customer)
                                <tr class="hover:bg-slate-800/40 transition-all duration-300 group">
                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 bg-indigo-500/10 rounded-xl flex items-center justify-center text-indigo-400 border border-indigo-500/20 group-hover:bg-indigo-500 group-hover:text-white transition-colors duration-300">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                            </div>
                                            <div>
                                                <div class="font-bold text-white group-hover:text-indigo-400 transition-colors">{{ $customer->user?->name ?? 'Pelanggan #' . $customer->id }}</div>
                                                <div class="text-[10px] text-slate-500 font-black uppercase tracking-widest mt-0.5">{{ $customer->customer_code }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-6">
                                        <div class="text-slate-300 font-medium flex items-center gap-2 mb-1">
                                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                            {{ $customer->phone_number ?? '-' }}
                                        </div>
                                        <div class="text-[10px] text-slate-500 font-bold tracking-widest uppercase">
                                            Join: {{ $customer->created_at->format('d M Y') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-6">
                                        <div class="text-sm font-medium text-slate-300 max-w-xs truncate" title="{{ $customer->address_installation }}">
                                            {{ $customer->address_installation ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-6 text-center">
                                        @if(!$customer->is_isolated)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-[10px] font-black bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 uppercase tracking-widest">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-[10px] font-black bg-rose-500/10 text-rose-400 border border-rose-500/20 uppercase tracking-widest">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                Nonaktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <a href="{{ route('marketing.customers.show', $customer) }}" 
                                            class="inline-flex items-center justify-center px-5 py-2.5 bg-slate-800 border border-slate-700 rounded-xl text-xs font-black text-slate-300 uppercase tracking-widest hover:bg-indigo-600 hover:text-white hover:border-indigo-500 transition-all duration-300 shadow-sm">
                                            Detail &rarr;
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-8 py-16 text-center text-slate-400">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-12 h-12 text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                            <p class="font-bold text-slate-300 text-base">Belum Ada Pelanggan</p>
                                            <p class="text-xs text-slate-500 mt-1">Belum ada prospek yang dikonversi menjadi pelanggan oleh Anda.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="md:hidden space-y-4">
                @forelse ($customers as $customer)
                    <div class="bg-slate-900/80 backdrop-blur-md rounded-[2rem] border border-slate-800 overflow-hidden shadow-xl">
                        <div class="p-6 border-b border-slate-800/60 flex justify-between items-start">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-indigo-500/10 rounded-xl flex items-center justify-center text-indigo-400 border border-indigo-500/20">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-white text-lg tracking-tight">{{ $customer->user?->name ?? 'Pelanggan #' . $customer->id }}</h4>
                                    <p class="text-[10px] text-slate-500 font-black uppercase tracking-widest mt-0.5">{{ $customer->customer_code }}</p>
                                </div>
                            </div>
                            <div>
                                @if(!$customer->is_isolated)
                                    <span class="inline-flex px-2.5 py-1 rounded-md text-[9px] font-black bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 uppercase tracking-widest">Aktif</span>
                                @else
                                    <span class="inline-flex px-2.5 py-1 rounded-md text-[9px] font-black bg-rose-500/10 text-rose-400 border border-rose-500/20 uppercase tracking-widest">Nonaktif</span>
                                @endif
                            </div>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex items-start gap-3">
                                <div class="mt-0.5 text-slate-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-black uppercase tracking-widest">Telepon</p>
                                    <p class="text-slate-300 font-medium text-sm mt-0.5">{{ $customer->phone_number ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="mt-0.5 text-slate-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-black uppercase tracking-widest">Alamat</p>
                                    <p class="text-slate-300 font-medium text-sm mt-0.5">{{ $customer->address_installation ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-4 bg-slate-800/30 border-t border-slate-800/60 text-center">
                            <a href="{{ route('marketing.customers.show', $customer) }}" class="inline-block w-full py-3 bg-slate-800 border border-slate-700 hover:border-indigo-500 hover:text-white rounded-xl text-xs font-black text-slate-300 uppercase tracking-widest transition-all duration-300">
                                Buka Detail Pelanggan
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-400 bg-slate-900 rounded-2xl border border-slate-800">
                        Belum ada pelanggan.
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $customers->links() }}
            </div>

            <div class="mt-8 text-center hidden md:block">
                <p class="text-[10px] font-black text-slate-600 uppercase tracking-[0.3em]">
                    NetManagement &bull; Customer Database
                </p>
            </div>

        </div>
    </div>
</x-app-layout>