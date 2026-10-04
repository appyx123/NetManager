<x-app-layout>
    <div class="py-10 bg-slate-950 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-6 flex justify-between items-center">
                <a href="{{ route('admin.billing.index') }}" class="text-amber-400 hover:text-amber-300 font-bold flex items-center transition text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Daftar Tagihan
                </a>
            </div>

            <div class="bg-slate-900/80 backdrop-blur-md shadow-2xl rounded-2xl border border-slate-800 overflow-hidden relative p-5 sm:p-8">
                
                {{-- Header: Tagihan & Status Pill --}}
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-800/80 pb-5 mb-5">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="p-2.5 bg-amber-500/10 text-amber-400 rounded-xl border border-amber-500/20 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div class="min-w-0">
                            <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block">Detail Tagihan Pelanggan</span>
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
                        <span class="text-slate-500 font-bold block uppercase tracking-wider text-[10px]">Kode Pelanggan</span>
                        <span class="text-amber-400 font-mono font-bold mt-0.5 block">{{ $invoice->subscription->customer->customer_code ?? '-' }}</span>
                    </div>
                </div>

                {{-- Bipartite Info: Ditagihkan & Info Layanan --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5 mb-5">
                    <div class="bg-slate-800/40 p-4 rounded-xl border border-slate-700/50 text-xs">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Ditagihkan Kepada</p>
                        <p class="text-sm font-bold text-white">{{ $invoice->subscription->customer->user->name ?? ($invoice->subscription->customer->lead->name ?? '-') }}</p>
                        <p class="text-slate-400 mt-1 leading-relaxed">{{ $invoice->subscription->customer->address_installation ?? '-' }}</p>
                        <p class="text-indigo-400 font-semibold mt-1.5">{{ $invoice->subscription->customer->phone_number ?? '-' }}</p>
                    </div>
                    <div class="bg-slate-800/40 p-4 rounded-xl border border-slate-700/50 text-xs flex flex-col justify-between">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Info Layanan</p>
                            <p class="text-sm font-bold text-white">{{ $invoice->subscription->package->name ?? '-' }}</p>
                            <p class="text-slate-400 mt-0.5 font-mono text-[11px]">PPPoE: {{ $invoice->subscription->pppoe_username ?? '-' }}</p>
                        </div>
                        <div class="mt-2.5 pt-2 border-t border-slate-700/50 flex items-center justify-between text-[11px]">
                            <span class="text-slate-400">Status Layanan:</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $invoice->subscription->status === 'active' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-amber-500/10 text-amber-400 border border-amber-500/20' }}">
                                {{ ucfirst($invoice->subscription->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Breakdown Table --}}
                <div class="border border-slate-700/60 rounded-xl overflow-hidden mb-6 shadow-sm bg-slate-800/20 text-xs">
                    <table class="min-w-full divide-y divide-slate-700/50">
                        <thead class="bg-slate-800/60 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                            <tr>
                                <th class="px-4 py-2.5 text-left">Deskripsi Layanan</th>
                                <th class="px-4 py-2.5 text-right">Nominal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700/30">
                            <tr>
                                <td class="px-4 py-3 text-slate-200">
                                    <span class="font-semibold text-white">Biaya Berlangganan Internet - {{ $invoice->subscription->package->name ?? '-' }}</span>
                                </td>
                                <td class="px-4 py-3 text-white font-bold text-right whitespace-nowrap">
                                    Rp {{ number_format($invoice->subscription->package->price ?? 0, 0, ',', '.') }}
                                </td>
                            </tr>
                            @if($invoice->amount > ($invoice->subscription->package->price ?? 0))
                                <tr>
                                    <td class="px-4 py-3 text-slate-200">
                                        <span class="font-semibold text-white">Biaya Instalasi / Penyesuaian</span>
                                    </td>
                                    <td class="px-4 py-3 text-white font-bold text-right whitespace-nowrap">
                                        Rp {{ number_format($invoice->amount - ($invoice->subscription->package->price ?? 0), 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                        <tfoot class="bg-slate-950/80 border-t border-slate-700/80">
                            <tr>
                                <td class="px-4 py-3 font-bold text-slate-400 uppercase tracking-wider text-[11px]">Total Keseluruhan</td>
                                <td class="px-4 py-3 text-right font-black text-lg text-amber-400 whitespace-nowrap">
                                    Rp {{ number_format($invoice->amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                {{-- Action / Status Section --}}
                <div class="border-t border-dashed border-slate-800 pt-6 mt-4 flex flex-col items-center gap-3">
                    @if($invoice->status === 'unpaid')
                        <p class="text-xs text-slate-400 text-center">Tagihan ini belum dibayar. Anda dapat menandainya sebagai lunas secara manual di sini.</p>
                        
                        <form id="mark-paid-form" action="{{ route('admin.billing.markAsPaid', $invoice) }}" method="POST" class="w-full sm:w-auto" onsubmit="event.preventDefault(); confirmMarkPaid(this);">
                            @csrf
                            <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-black rounded-xl shadow-lg shadow-amber-500/20 transition-all text-sm cursor-pointer active:scale-95">
                                Tandai Sebagai LUNAS
                            </button>
                        </form>
                    @else
                        <div class="w-full max-w-md bg-emerald-500/10 border border-emerald-500/20 rounded-xl p-5 text-center">
                            <div class="w-10 h-10 bg-emerald-500/20 text-emerald-400 rounded-full flex items-center justify-center mx-auto mb-2 border border-emerald-500/30">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <h3 class="text-base font-black text-emerald-400">Tagihan Telah Lunas</h3>
                            <p class="text-xs text-slate-400 mt-0.5">
                                {{ $invoice->paid_at ? 'Dibayar pada: ' . $invoice->paid_at->format('d M Y, H:i') : 'Pembayaran telah terverifikasi.' }}
                            </p>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    @if($invoice->status === 'unpaid')
        <script>
            function confirmMarkPaid(form) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Konfirmasi Pelunasan',
                        text: 'Tandai tagihan #{{ $invoice->invoice_number }} sebesar Rp {{ number_format($invoice->amount, 0, ',', '.') }} sebagai LUNAS? Layanan pelanggan akan otomatis diaktifkan di router.',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#10b981',
                        cancelButtonColor: '#334155',
                        confirmButtonText: 'Ya, Tandai Lunas',
                        cancelButtonText: 'Batal',
                        background: '#0f172a',
                        color: '#f8fafc'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                } else if (confirm('Tandai tagihan #{{ $invoice->invoice_number }} sebagai LUNAS?')) {
                    form.submit();
                }
            }
        </script>
    @endif
</x-app-layout>
