<x-app-layout>
    <div class="py-10 bg-slate-950 min-h-screen selection:bg-indigo-500/30">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-8">
                <a href="{{ route('client.billing.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-400 hover:text-white transition-colors mb-4 group">
                    <svg class="w-4 h-4 mr-1.5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Daftar Tagihan
                </a>
            </div>

            <div class="bg-slate-900/80 backdrop-blur-md shadow-2xl rounded-2xl border border-slate-800 overflow-hidden relative p-5 sm:p-8">
                
                {{-- Card Header: Title & Status Badge --}}
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-800/80 pb-5 mb-5">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="p-2.5 bg-amber-500/10 text-amber-400 rounded-xl border border-amber-500/20 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div class="min-w-0">
                            <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block">Detail Tagihan</span>
                            <h1 class="text-base sm:text-xl font-black text-white font-mono tracking-tight truncate">{{ $invoice->invoice_number }}</h1>
                        </div>
                    </div>
                    <div class="shrink-0">
                        @if($invoice->status === 'paid')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 uppercase tracking-wider shadow-sm">
                                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                                Lunas
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-rose-500/10 text-rose-400 border border-rose-500/20 uppercase tracking-wider shadow-sm">
                                <span class="w-2 h-2 rounded-full bg-rose-400 animate-pulse"></span>
                                Belum Bayar
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Metadata Strip: Terbit, Jatuh Tempo, ID Pelanggan --}}
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 p-3.5 bg-slate-950/60 rounded-xl border border-slate-800/80 mb-5 text-xs">
                    <div>
                        <span class="text-slate-500 font-bold block uppercase tracking-wider text-[10px]">Tanggal Terbit</span>
                        <span class="text-slate-200 font-semibold mt-0.5 block">{{ $invoice->created_at->format('d M Y') }}</span>
                    </div>
                    <div>
                        <span class="text-slate-500 font-bold block uppercase tracking-wider text-[10px]">Jatuh Tempo</span>
                        <span class="font-semibold mt-0.5 block {{ $invoice->due_date < now() && $invoice->status == 'unpaid' ? 'text-rose-400 font-bold' : 'text-slate-200' }}">
                            {{ $invoice->due_date ? $invoice->due_date->format('d M Y') : '-' }}
                        </span>
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <span class="text-slate-500 font-bold block uppercase tracking-wider text-[10px]">ID Pelanggan</span>
                        <span class="text-amber-400 font-mono font-bold mt-0.5 block">{{ $invoice->subscription->customer->customer_code ?? '-' }}</span>
                    </div>
                </div>

                {{-- Bipartite Info: Ditagihkan & Info Layanan --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5 mb-5">
                    <div class="bg-slate-800/40 p-4 rounded-xl border border-slate-700/50 text-xs">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Ditagihkan Kepada</p>
                        <p class="text-sm font-bold text-white">{{ Auth::user()->name }}</p>
                        <p class="text-slate-400 mt-1 leading-relaxed">{{ $invoice->subscription->customer->address_installation ?? 'Alamat instalasi' }}</p>
                        <p class="text-indigo-400 font-semibold mt-1.5">{{ $invoice->subscription->customer->phone_number ?? '-' }}</p>
                    </div>
                    <div class="bg-slate-800/40 p-4 rounded-xl border border-slate-700/50 text-xs flex flex-col justify-between">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Layanan Internet</p>
                            <p class="text-sm font-bold text-white">{{ $invoice->subscription->package->name ?? '-' }}</p>
                            <p class="text-slate-400 mt-0.5">{{ $invoice->subscription->package->speed_mbps ?? '-' }} Mbps Kecepatan Tanpa Batas</p>
                        </div>
                        @if($invoice->status === 'paid' && $invoice->paid_at)
                            <div class="mt-2.5 pt-2 border-t border-slate-700/50 text-[11px] text-emerald-400 font-semibold">
                                ✓ Lunas pada {{ $invoice->paid_at->format('d M Y, H:i') }}
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Breakdown Table --}}
                <div class="border border-slate-700/60 rounded-xl overflow-hidden mb-6 shadow-sm bg-slate-800/20 text-xs">
                    <table class="min-w-full divide-y divide-slate-700/50">
                        <thead class="bg-slate-800/60 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                            <tr>
                                <th class="px-4 py-2.5 text-left">Rincian Item</th>
                                <th class="px-4 py-2.5 text-right">Nominal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700/30">
                            <tr>
                                <td class="px-4 py-3 text-slate-200">
                                    <span class="font-semibold text-white">Paket {{ $invoice->subscription->package->name ?? 'Internet' }}</span>
                                    <span class="text-[11px] text-slate-400 block mt-0.5">Biaya langganan internet</span>
                                </td>
                                <td class="px-4 py-3 text-white font-bold text-right whitespace-nowrap">
                                    Rp {{ number_format($invoice->subscription->package->price ?? 0, 0, ',', '.') }}
                                </td>
                            </tr>
                            @if($invoice->amount > ($invoice->subscription->package->price ?? 0))
                                <tr>
                                    <td class="px-4 py-3 text-slate-200">
                                        <span class="font-semibold text-white">Biaya Pemasangan & Registrasi</span>
                                        <span class="text-[11px] text-slate-400 block mt-0.5">Instalasi awal & penarikan kabel</span>
                                    </td>
                                    <td class="px-4 py-3 text-white font-bold text-right whitespace-nowrap">
                                        Rp {{ number_format($invoice->amount - ($invoice->subscription->package->price ?? 0), 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                        <tfoot class="bg-slate-950/80 border-t border-slate-700/80">
                            <tr>
                                <td class="px-4 py-3 font-bold text-slate-400 uppercase tracking-wider text-[11px]">Total Tagihan</td>
                                <td class="px-4 py-3 text-right font-black text-lg text-amber-400 whitespace-nowrap">
                                    Rp {{ number_format($invoice->amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                {{-- Action & Payment Section --}}
                <div class="flex flex-col items-center">
                    @if(session('info'))
                        <div class="w-full max-w-md mb-3 px-3 py-2 bg-blue-500/10 border border-blue-500/20 rounded-xl text-blue-400 font-semibold text-xs text-center">
                            {{ session('info') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="w-full max-w-md mb-3 px-3 py-2 bg-rose-500/10 border border-rose-500/20 rounded-xl text-rose-400 font-semibold text-xs text-center">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($invoice->status === 'unpaid')
                        <div class="w-full max-w-md flex flex-col items-center gap-2.5">
                            @if($invoice->snap_token)
                                <button type="button" id="pay-button" class="w-full py-3 px-6 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-black rounded-xl shadow-lg shadow-amber-500/20 transition-all flex items-center justify-center gap-2 text-sm cursor-pointer active:scale-95">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    Bayar Tagihan Sekarang
                                </button>
                            @else
                                <form action="{{ route('client.billing.pay', $invoice) }}" method="POST" class="w-full">
                                    @csrf
                                    <button type="submit" id="pay-button" class="w-full py-3 px-6 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-black rounded-xl shadow-lg shadow-amber-500/20 transition-all flex items-center justify-center gap-2 text-sm cursor-pointer active:scale-95">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                        Bayar Tagihan Sekarang
                                    </button>
                                </form>
                            @endif

                            <form id="check-status-form" action="{{ route('client.billing.checkStatus', $invoice) }}" method="POST" class="w-full">
                                @csrf
                                <button type="submit" class="w-full text-xs text-slate-400 hover:text-white font-semibold flex items-center justify-center gap-2 transition-colors py-2 px-4 rounded-xl bg-slate-800/80 border border-slate-700/80 hover:bg-slate-800">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                    Sudah bayar? Cek & Sinkronkan Status
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="w-full max-w-md bg-emerald-500/10 border border-emerald-500/20 rounded-xl p-5 text-center">
                            <div class="w-10 h-10 bg-emerald-500/20 text-emerald-400 rounded-full flex items-center justify-center mx-auto mb-2 border border-emerald-500/30">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <h3 class="text-base font-black text-emerald-400">Tagihan Telah Lunas</h3>
                            <p class="text-xs text-slate-400 mt-0.5">Terima kasih, pembayaran telah berhasil diverifikasi.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    @if($invoice->status === 'unpaid')
        <!-- Midtrans Snap JS SDK -->
        <script 
            src="{{ config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" 
            data-client-key="{{ config('services.midtrans.client_key') }}">
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const snapToken = "{{ $invoice->snap_token ?? session('snap_token') }}";

                function openSnapPopup(token) {
                    if (!window.snap) {
                        alert('Midtrans Snap SDK gagal dimuat. Periksa koneksi internet Anda.');
                        return;
                    }

                    window.snap.pay(token, {
                        onSuccess: function (result) {
                            console.log('Payment success:', result);
                            // Otomatis sinkronkan status ke backend via check-status
                            const checkForm = document.getElementById('check-status-form');
                            if (checkForm) {
                                checkForm.submit();
                            } else {
                                window.location.reload();
                            }
                        },
                        onPending: function (result) {
                            console.log('Payment pending:', result);
                            window.location.reload();
                        },
                        onError: function (result) {
                            console.error('Payment error:', result);
                            alert('Pembayaran gagal atau dibatalkan.');
                        },
                        onClose: function () {
                            console.log('Pelanggan menutup pop-up tanpa menyelesaikan pembayaran.');
                        }
                    });
                }

                // Otomatis buka pop-up Snap jika baru saja di-redirect dari metode pay()
                @if(session('snap_token'))
                    openSnapPopup("{{ session('snap_token') }}");
                @endif

                const payButton = document.getElementById('pay-button');
                if (payButton && payButton.type === 'button' && snapToken) {
                    payButton.addEventListener('click', function () {
                        openSnapPopup(snapToken);
                    });
                }
            });
        </script>
    @endif
</x-app-layout>