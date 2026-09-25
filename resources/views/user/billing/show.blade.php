<x-app-layout>
    <div class="py-10 bg-slate-950 min-h-screen selection:bg-indigo-500/30">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-8 px-4 sm:px-0">
                <a href="{{ route('client.billing.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-400 hover:text-white transition-colors mb-4 group">
                    <svg class="w-4 h-4 mr-1.5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Daftar Tagihan
                </a>
            </div>

            <div class="bg-slate-900/80 backdrop-blur-md shadow-2xl sm:rounded-3xl border border-slate-800 overflow-hidden relative mx-4 sm:mx-0">
                
                @if($invoice->status === 'paid')
                    <div class="absolute top-0 right-0 -mr-10 mt-6 transform rotate-45 bg-emerald-600/90 text-white font-black py-1 px-14 shadow-lg border-y border-emerald-400/50 uppercase tracking-widest text-xs z-10 text-center">
                        LUNAS
                    </div>
                @else
                    <div class="absolute top-0 right-0 -mr-10 mt-6 transform rotate-45 bg-rose-600/90 text-white font-black py-1 px-14 shadow-lg border-y border-rose-400/50 uppercase tracking-widest text-xs z-10 text-center">
                        UNPAID
                    </div>
                @endif

                <div class="p-8 md:p-12 relative z-0">
                    <div class="absolute -left-20 -top-20 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>

                    <div class="flex flex-col sm:flex-row justify-between items-start border-b border-slate-800/60 pb-8 mb-10 relative">
                        <div class="mb-6 sm:mb-0">
                            <h1 class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-400 tracking-tighter uppercase drop-shadow-sm">INVOICE</h1>
                            <p class="text-indigo-300 mt-2 font-mono font-bold bg-indigo-500/10 inline-block px-3 py-1 rounded-lg border border-indigo-500/20 shadow-inner">#{{ $invoice->invoice_number }}</p>
                        </div>
                        <div class="text-left sm:text-right text-sm text-slate-400 space-y-2">
                            <p><span class="font-bold text-slate-300">Tanggal Terbit:</span> <br class="sm:hidden">{{ $invoice->created_at->format('d F Y') }}</p>
                            <p><span class="font-bold text-slate-300">Jatuh Tempo:</span> <br class="sm:hidden"><span class="{{ $invoice->due_date < now() && $invoice->status == 'unpaid' ? 'text-rose-400 font-bold bg-rose-500/10 px-2 py-0.5 rounded border border-rose-500/20' : 'text-white font-medium' }}">{{ $invoice->due_date ? $invoice->due_date->format('d F Y') : '-' }}</span></p>
                            @if($invoice->status === 'paid')
                                <p class="text-emerald-400 font-bold mt-3 bg-emerald-500/10 px-3 py-1.5 rounded-lg border border-emerald-500/20 inline-block">Dibayar pada: {{ $invoice->paid_at ? $invoice->paid_at->format('d M Y, H:i') : '-' }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mb-12 relative">
                        <div class="bg-slate-800/30 p-6 rounded-2xl border border-slate-700/50">
                            <span class="text-xs font-black text-slate-500 uppercase tracking-widest flex items-center gap-2 mb-4">
                                <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                Ditagihkan Kepada
                            </span>
                            <h3 class="text-xl font-bold text-white">{{ Auth::user()->name }}</h3>
                            <p class="text-slate-400 text-sm mt-2 leading-relaxed">{{ $invoice->subscription->customer->address_installation ?? 'Alamat instalasi belum tersedia' }}</p>
                            <p class="text-indigo-300 text-sm font-bold mt-3">{{ $invoice->subscription->customer->phone_number ?? '-' }}</p>
                        </div>
                        <div class="bg-slate-800/30 p-6 rounded-2xl border border-slate-700/50 md:text-right flex flex-col md:items-end">
                            <span class="text-xs font-black text-slate-500 uppercase tracking-widest flex items-center md:flex-row-reverse gap-2 mb-4">
                                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                                Info Layanan
                            </span>
                            <p class="text-white font-bold mt-1 text-xl">{{ $invoice->subscription->package->name ?? '-' }}</p>
                            <p class="text-slate-400 text-sm mt-1">Kecepatan Optimal Tanpa Batas</p>
                            <div class="mt-auto pt-4 w-full md:w-auto">
                                <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">ID Pelanggan</p>
                                <p class="text-amber-400 font-mono font-bold bg-slate-950 px-3 py-1 rounded border border-slate-700 inline-block">{{ $invoice->subscription->customer->customer_code ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="border border-slate-700/60 rounded-2xl overflow-hidden mb-12 shadow-lg bg-slate-800/20">
                        <table class="min-w-full divide-y divide-slate-700/50">
                            <thead class="bg-slate-800/80">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-black text-slate-400 uppercase tracking-widest">Deskripsi Layanan / Item</th>
                                    <th class="px-6 py-4 text-right text-xs font-black text-slate-400 uppercase tracking-widest">Nominal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-700/30">
                                <tr class="hover:bg-slate-800/40 transition-colors">
                                    <td class="px-6 py-5 text-sm text-slate-200 font-medium">Biaya Berlangganan Internet - <span class="text-indigo-300">{{ $invoice->subscription->package->name ?? '-' }}</span></td>
                                    <td class="px-6 py-5 text-base text-white font-bold text-right">Rp {{ number_format($invoice->subscription->package->price ?? 0, 0, ',', '.') }}</td>
                                </tr>
                                @if($invoice->amount > ($invoice->subscription->package->price ?? 0))
                                <tr class="hover:bg-slate-800/40 transition-colors">
                                    <td class="px-6 py-5 text-sm text-slate-200 font-medium">Biaya Instalasi / Penyesuaian / Denda Keterlambatan</td>
                                    <td class="px-6 py-5 text-base text-white font-bold text-right">Rp {{ number_format($invoice->amount - ($invoice->subscription->package->price ?? 0), 0, ',', '.') }}</td>
                                </tr>
                                @endif
                            </tbody>
                            <tfoot class="bg-gradient-to-r from-indigo-900/40 to-purple-900/40 border-t-2 border-indigo-500/30">
                                <tr>
                                    <td class="px-6 py-6 text-right font-black text-indigo-300 uppercase tracking-widest text-sm">Total Tagihan Keseluruhan</td>
                                    <td class="px-6 py-6 text-right font-black text-2xl text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-400 drop-shadow-md">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="flex flex-col items-center">

                        {{-- Flash: Info (sudah lunas / notifikasi) --}}
                        @if(session('info'))
                            <div class="w-full max-w-md mb-4 px-4 py-3 bg-blue-500/10 border border-blue-500/20 rounded-xl text-blue-400 font-semibold text-sm text-center">
                                {{ session('info') }}
                            </div>
                        @endif

                        {{-- Flash: Error --}}
                        @if(session('error'))
                            <div class="w-full max-w-md mb-4 px-4 py-3 bg-rose-500/10 border border-rose-500/20 rounded-xl text-rose-400 font-semibold text-sm text-center">
                                {{ session('error') }}
                            </div>
                        @endif

                        {{-- Flash: Payment Info (instruksi pembayaran manual) --}}
                        @if(session('payment_info'))
                            @php $pi = session('payment_info'); @endphp
                            <div class="w-full max-w-md mb-6 bg-indigo-500/10 border border-indigo-500/20 rounded-2xl p-6 text-left">
                                <h4 class="font-bold text-indigo-300 mb-3 text-center">Instruksi Pembayaran</h4>
                                <div class="space-y-2 text-sm text-slate-300">
                                    <p><span class="text-slate-500">No. Invoice:</span> <strong class="font-mono">{{ $pi['invoice_number'] }}</strong></p>
                                    <p><span class="text-slate-500">Jumlah:</span> <strong>Rp {{ number_format($pi['amount'], 0, ',', '.') }}</strong></p>
                                    <p><span class="text-slate-500">Jatuh Tempo:</span> <strong>{{ \Carbon\Carbon::parse($pi['due_date'])->format('d F Y') }}</strong></p>
                                </div>
                                <p class="mt-4 text-xs text-slate-400 border-t border-slate-700 pt-3">Silakan transfer ke rekening yang tertera dalam perjanjian langganan Anda, lalu hubungi tim kami untuk konfirmasi pembayaran.</p>
                            </div>
                        @endif

                        @if($invoice->status === 'unpaid')
                            <div class="bg-rose-500/10 border border-rose-500/20 rounded-xl p-4 mb-6 w-full max-w-md text-center">
                                <p class="text-sm font-semibold text-rose-400">Silakan selesaikan pembayaran sebelum tanggal jatuh tempo agar layanan internet Anda tidak terputus.</p>
                            </div>

                            @if($invoice->snap_token)
                                <button type="button" id="pay-button" class="w-full sm:w-auto px-12 py-4 bg-indigo-600 text-white font-bold rounded-xl shadow-[0_0_20px_rgba(79,70,229,0.4)] hover:bg-indigo-500 hover:shadow-[0_0_30px_rgba(79,70,229,0.6)] transition-all duration-300 transform hover:-translate-y-1 flex items-center justify-center text-lg cursor-pointer">
                                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                    Bayar Tagihan Sekarang
                                </button>
                            @else
                                <form action="{{ route('client.billing.pay', $invoice) }}" method="POST" class="w-full sm:w-auto">
                                    @csrf
                                    <button type="submit" id="pay-button" class="w-full sm:w-auto px-12 py-4 bg-indigo-600 text-white font-bold rounded-xl shadow-[0_0_20px_rgba(79,70,229,0.4)] hover:bg-indigo-500 hover:shadow-[0_0_30px_rgba(79,70,229,0.6)] transition-all duration-300 transform hover:-translate-y-1 flex items-center justify-center text-lg cursor-pointer">
                                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                        Bayar Tagihan Sekarang
                                    </button>
                                </form>
                            @endif

                            <form id="check-status-form" action="{{ route('client.billing.checkStatus', $invoice) }}" method="POST" class="mt-4">
                                @csrf
                                <button type="submit" class="text-sm text-indigo-400 hover:text-indigo-300 font-semibold flex items-center justify-center gap-2 transition-colors py-1.5 px-4 rounded-lg bg-indigo-500/10 border border-indigo-500/20 hover:bg-indigo-500/20">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                    Sudah bayar? Cek & Sinkronkan Status
                                </button>
                            </form>
                        @else
                            <div class="w-full bg-emerald-500/10 border-2 border-dashed border-emerald-500/30 rounded-2xl p-8 flex flex-col items-center justify-center relative overflow-hidden group">
                                <div class="absolute inset-0 bg-emerald-500/5 group-hover:bg-emerald-500/10 transition-colors"></div>
                                <div class="w-16 h-16 bg-emerald-500 rounded-full flex items-center justify-center mb-4 shadow-[0_0_20px_rgba(16,185,129,0.5)] relative z-10">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <h3 class="text-2xl font-black text-emerald-400 tracking-wider relative z-10">LUNAS</h3>
                                <p class="text-slate-400 text-sm mt-2 relative z-10 text-center">Terima kasih atas pembayaran Anda. Layanan internet Anda aktif dan siap digunakan.</p>
                            </div>
                        @endif
                    </div>

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