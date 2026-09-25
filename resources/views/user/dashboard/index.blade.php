<x-app-layout>
    <div class="py-10 bg-slate-950 min-h-screen selection:bg-emerald-500/30">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @php
                /** @var \App\Models\Customer $customer */
                /** @var \App\Models\Subscription $subscription */
                
                $unpaidCount = count($unpaidInvoices ?? []);
                $totalUnpaid = 0;
                $nearestDueDate = null;

                if ($unpaidCount > 0) {
                    foreach ($unpaidInvoices as $inv) {
                        $totalUnpaid += $inv->amount;
                        if (!$nearestDueDate || $inv->due_date < $nearestDueDate) {
                            $nearestDueDate = $inv->due_date;
                        }
                    }
                }
            @endphp

            {{-- Welcome Header --}}
            <div class="mb-8 px-4 sm:px-0 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-white via-slate-200 to-slate-400 tracking-tight">Halo, {{ Auth::user()->name }}! 👋</h2>
                    <p class="text-slate-400 mt-1 font-medium">Selamat datang di Ringkasan Layanan NetManagement Anda.</p>
                </div>
                <div>
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-slate-900/80 backdrop-blur-sm border border-slate-800 shadow-sm text-slate-300">
                        ID Pelanggan: <span class="text-amber-400 ml-1.5 font-mono">{{ $customer->customer_code ?? 'Menunggu Aktivasi' }}</span>
                    </span>
                </div>
            </div>

            {{-- Unpaid Invoice Warning Alert --}}
            @if($unpaidCount > 0)
                <div class="mb-8 px-4 sm:px-0">
                    <div class="bg-rose-500/10 border-l-4 border-rose-500 p-5 rounded-r-2xl border border-rose-500/20 backdrop-blur-md shadow-lg flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div class="flex items-start">
                            <div class="p-2.5 bg-rose-500/20 rounded-xl mr-4 mt-0.5 text-rose-400 shrink-0 border border-rose-500/30">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-white font-bold text-lg">Peringatan Pembayaran</h4>
                                <p class="text-sm text-rose-300/90 mt-1">Anda memiliki <strong class="text-white">{{ $unpaidCount }} tagihan</strong> sebesar <strong class="text-white">Rp {{ number_format($totalUnpaid, 0, ',', '.') }}</strong> yang belum dibayar. Segera lakukan pembayaran agar layanan internet Anda tidak terputus.</p>
                            </div>
                        </div>
                        <a href="{{ route('client.billing.index') }}" class="px-6 py-2.5 bg-rose-600 hover:bg-rose-500 text-white font-bold rounded-xl shadow-lg shadow-rose-600/30 transition-all duration-200 whitespace-nowrap text-sm">
                            Bayar Sekarang
                        </a>
                    </div>
                </div>
            @endif

            {{-- Recent Complaints Section --}}
            <div class="mb-8 px-4 sm:px-0">
                <div class="bg-slate-900/80 backdrop-blur-md rounded-2xl p-6 shadow-xl border border-slate-800">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-bold text-white tracking-wide">Laporan Kerusakan Terbaru</h3>
                            <p class="text-sm text-slate-400">Pantau laporan yang Anda kirim ke tim teknisi.</p>
                        </div>
                        <a href="{{ route('client.complaints.index') }}" class="text-sm font-bold text-amber-400 hover:text-amber-300 transition-colors flex items-center gap-1">
                            Lihat Semua &rarr;
                        </a>
                    </div>
                    <div class="divide-y divide-slate-800/80">
                        @forelse($recentTickets as $ticket)
                            <div class="flex items-center justify-between py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 rounded-full {{ $ticket->status === 'closed' ? 'bg-emerald-400' : 'bg-amber-400 animate-pulse' }}"></div>
                                    <span class="font-semibold text-slate-200 text-sm">{{ $ticket->subject }}</span>
                                </div>
                                <span class="text-xs font-bold uppercase tracking-wider px-2.5 py-1 rounded-md {{ $ticket->status === 'closed' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-amber-500/10 text-amber-400 border border-amber-500/20' }}">
                                    {{ $ticket->status }}
                                </span>
                            </div>
                        @empty
                            <p class="pt-3 text-sm text-slate-500">Belum ada laporan kerusakan.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- 4 Stat Metric Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 px-4 sm:px-0 mb-8">
                
                {{-- Card 1: Status Internet --}}
                <div class="bg-slate-900/80 backdrop-blur-md rounded-2xl p-6 shadow-xl border {{ isset($subscription) && $subscription->status === 'active' ? 'border-emerald-500/30 hover:border-emerald-500/50' : 'border-rose-500/30 hover:border-rose-500/50' }} transition-all flex flex-col justify-between group">
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-3 {{ isset($subscription) && $subscription->status === 'active' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20' }} rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <span class="px-2.5 py-1 text-[11px] font-black rounded-lg {{ isset($subscription) && $subscription->status === 'active' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20' }} uppercase tracking-wider">
                            {{ isset($subscription) && $subscription->status === 'active' ? 'Koneksi Aktif' : 'Terisolir' }}
                        </span>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Status Internet</p>
                        <h3 class="text-2xl font-black {{ isset($subscription) && $subscription->status === 'active' ? 'text-emerald-400' : 'text-rose-400' }} mt-1">
                            {{ isset($subscription) && $subscription->status === 'active' ? 'Online' : 'Offline' }}
                        </h3>
                    </div>
                </div>

                {{-- Card 2: Paket Saat Ini --}}
                <div class="bg-slate-900/80 backdrop-blur-md rounded-2xl p-6 shadow-xl border border-sky-500/20 hover:border-sky-500/40 transition-all flex flex-col justify-between group">
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-3 bg-sky-500/10 text-sky-400 rounded-xl border border-sky-500/20">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <span class="px-2.5 py-1 text-[11px] font-bold text-sky-400 bg-sky-500/10 rounded-lg border border-sky-500/20 uppercase">{{ $subscription->package->speed_mbps ?? 0 }} Mbps</span>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Paket Saat Ini</p>
                        <h3 class="text-xl font-black text-white mt-1 truncate" title="{{ $subscription->package->name ?? 'Belum ada paket' }}">
                            {{ $subscription->package->name ?? 'Belum ada paket' }}
                        </h3>
                    </div>
                </div>

                {{-- Card 3: Status Tagihan --}}
                <div class="bg-slate-900/80 backdrop-blur-md rounded-2xl p-6 shadow-xl border {{ $unpaidCount > 0 ? 'border-amber-500/30 hover:border-amber-500/50' : 'border-slate-800 hover:border-slate-700' }} transition-all flex flex-col justify-between group">
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-3 {{ $unpaidCount > 0 ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : 'bg-slate-800 text-slate-400 border border-slate-700' }} rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Status Tagihan</p>
                        @if($unpaidCount > 0)
                            <h3 class="text-2xl font-black text-amber-400 mt-1">{{ $unpaidCount }} Tunggakan</h3>
                        @else
                            <h3 class="text-2xl font-black text-white mt-1">Lunas Semua</h3>
                        @endif
                    </div>
                </div>

                {{-- Card 4: Jatuh Tempo Terdekat --}}
                <div class="bg-slate-900/80 backdrop-blur-md rounded-2xl p-6 shadow-xl border border-indigo-500/20 hover:border-indigo-500/40 transition-all flex flex-col justify-between group">
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-3 bg-indigo-500/10 text-indigo-400 rounded-xl border border-indigo-500/20">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        @if($nearestDueDate && \Carbon\Carbon::parse($nearestDueDate)->isPast())
                            <span class="px-2 py-0.5 text-[10px] font-black bg-rose-500/10 text-rose-400 border border-rose-500/20 rounded-md uppercase tracking-wider">Terlewat</span>
                        @endif
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Jatuh Tempo Terdekat</p>
                        <h3 class="text-xl font-black {{ $nearestDueDate && \Carbon\Carbon::parse($nearestDueDate)->isPast() ? 'text-rose-400' : 'text-white' }} mt-1">
                            @if($nearestDueDate)
                                {{ \Carbon\Carbon::parse($nearestDueDate)->format('d M Y') }}
                            @else
                                Tgl {{ $subscription->billing_due_date ?? 5 }} Depan
                            @endif
                        </h3>
                    </div>
                </div>

            </div>

            {{-- Bottom Section: Lokasi & Pusat Bantuan --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 px-4 sm:px-0">
                {{-- Card Left: Lokasi --}}
                <div class="bg-slate-900/80 backdrop-blur-md shadow-xl rounded-2xl border border-slate-800 p-6 flex flex-col justify-between">
                    <div>
                        <h3 class="font-bold text-white mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Lokasi Pemasangan Layanan
                        </h3>
                        <div class="bg-slate-950/60 p-4 rounded-xl border border-slate-800/80">
                            <p class="text-sm font-medium text-slate-300 leading-relaxed">{{ $customer->address_installation ?? 'Alamat belum tersedia.' }}</p>
                        </div>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-800/80 flex items-center justify-between text-sm text-slate-400">
                        <span>Tanggal Pasang:</span>
                        <span class="font-semibold text-slate-200">{{ isset($subscription->installation_date) ? \Carbon\Carbon::parse($subscription->installation_date)->format('d F Y') : '-' }}</span>
                    </div>
                </div>

                {{-- Card Right: Pusat Bantuan & Kontak Layanan --}}
                <div class="bg-slate-900/80 backdrop-blur-md shadow-xl rounded-2xl border border-slate-800 p-6 text-white relative overflow-hidden flex flex-col justify-between">
                    <div class="absolute -right-8 -top-8 w-32 h-32 bg-emerald-500/10 rounded-full blur-2xl pointer-events-none"></div>

                    <div>
                        <h3 class="font-bold text-white mb-3 flex items-center relative z-10">
                            <svg class="w-5 h-5 mr-2 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            Pusat Bantuan & Layanan
                        </h3>
                        <p class="text-sm text-slate-400 mb-4 leading-relaxed">Mengalami kendala koneksi internet atau ada pertanyaan tagihan? Tim kami siap membantu Anda.</p>
                        
                        <div class="bg-slate-950/60 p-3.5 rounded-xl border border-slate-800/80 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></div>
                                <span class="text-xs font-bold text-slate-300 uppercase tracking-wider">Customer Care</span>
                            </div>
                            <span class="text-xs font-mono font-bold text-emerald-400">0811-1222-333</span>
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-slate-800/80 flex items-center gap-3">
                        <a href="{{ route('client.complaints.create') }}" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 font-bold rounded-xl border border-emerald-500/20 transition-colors text-sm gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            Lapor Gangguan
                        </a>
                        <a href="{{ route('client.billing.index') }}" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold rounded-xl border border-slate-700 transition-colors text-sm gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Lihat Tagihan
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
