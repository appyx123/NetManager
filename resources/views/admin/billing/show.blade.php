<x-app-layout>
    <div class="py-10 bg-slate-950 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-6 flex justify-between items-center">
                <a href="{{ route('admin.billing.index') }}" class="text-amber-400 hover:text-amber-300 font-bold flex items-center transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Daftar Tagihan
                </a>
            </div>

            <div class="bg-slate-900 shadow-xl rounded-2xl border border-slate-800 overflow-hidden relative">
                
                @if($invoice->status === 'paid')
                    <div class="absolute top-0 right-0 -mr-10 mt-6 transform rotate-45 bg-green-600 text-white font-bold py-1 px-12 shadow-md uppercase tracking-widest text-sm z-10">
                        LUNAS
                    </div>
                @else
                    <div class="absolute top-0 right-0 -mr-10 mt-6 transform rotate-45 bg-red-600 text-white font-bold py-1 px-12 shadow-md uppercase tracking-widest text-sm z-10">
                        BELUM BAYAR
                    </div>
                @endif

                <div class="p-8 md:p-12">
                    <div class="flex justify-between items-start border-b border-slate-800 pb-8 mb-8">
                        <div>
                            <h1 class="text-3xl font-black text-amber-400 tracking-tighter uppercase">TAGIHAN</h1>
                            <p class="text-slate-400 mt-1 font-mono font-bold">#{{ $invoice->invoice_number }}</p>
                        </div>
                        <div class="text-right text-sm text-slate-400 space-y-1">
                            <p><strong>Tanggal Terbit:</strong> {{ $invoice->created_at->format('d F Y') }}</p>
                            <p><strong>Jatuh Tempo:</strong> <span class="{{ $invoice->due_date < now() && $invoice->status == 'unpaid' ? 'text-red-400 font-bold' : '' }}">{{ $invoice->due_date ? $invoice->due_date->format('d F Y') : '-' }}</span></p>
                            @if($invoice->status === 'paid')
                                <p class="text-green-400 font-bold mt-2">Dibayar pada: {{ $invoice->paid_at ? $invoice->paid_at->format('d F Y, H:i') : '-' }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                        <div>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Ditagihkan Kepada:</span>
                            <h3 class="text-xl font-bold text-white mt-2">{{ $invoice->subscription->customer->user->name ?? '-' }}</h3>
                            <p class="text-slate-400 text-sm mt-1 leading-relaxed">{{ $invoice->subscription->customer->address_installation ?? '-' }}</p>
                            <p class="text-slate-400 text-sm font-bold mt-1">{{ $invoice->subscription->customer->phone_number ?? '-' }}</p>
                            <p class="text-slate-400 text-sm font-mono mt-1">Kode Pelanggan: {{ $invoice->subscription->customer->customer_code ?? '-' }}</p>
                        </div>
                        <div class="md:text-right">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Info Layanan:</span>
                            <p class="text-white font-bold mt-2 text-lg">{{ $invoice->subscription->package->name ?? '-' }}</p>
                            <p class="text-slate-400 text-sm font-mono mt-1">PPPoE: {{ $invoice->subscription->pppoe_username ?? '-' }}</p>
                            <p class="text-slate-400 text-sm mt-2">
                                <span class="px-2 py-1 bg-blue-900/20 text-blue-400 rounded text-xs font-bold">
                                    Status: {{ ucfirst($invoice->subscription->status) }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="border border-slate-800 rounded-xl overflow-hidden mb-8">
                        <table class="min-w-full divide-y divide-slate-800">
                            <thead class="bg-slate-800">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-300 uppercase">Deskripsi Layanan</th>
                                    <th class="px-6 py-3 text-right text-xs font-bold text-slate-300 uppercase">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody class="bg-slate-900 divide-y divide-slate-800">
                                <tr>
                                    <td class="px-6 py-4 text-sm text-slate-300 font-medium">Biaya Berlangganan Internet - {{ $invoice->subscription->package->name ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-white font-bold text-right">Rp {{ number_format($invoice->subscription->package->price ?? 0, 0, ',', '.') }}</td>
                                </tr>
                                @if($invoice->amount > ($invoice->subscription->package->price ?? 0))
                                <tr>
                                    <td class="px-6 py-4 text-sm text-slate-300 font-medium">Biaya Instalasi / Penyesuaian / Denda</td>
                                    <td class="px-6 py-4 text-sm text-white font-bold text-right">Rp {{ number_format($invoice->amount - ($invoice->subscription->package->price ?? 0), 0, ',', '.') }}</td>
                                </tr>
                                @endif
                            </tbody>
                            <tfoot class="bg-amber-900/20">
                                <tr>
                                    <td class="px-6 py-4 text-right font-black text-amber-400 uppercase">Total Keseluruhan</td>
                                    <td class="px-6 py-4 text-right font-black text-xl text-amber-400">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="border-t border-dashed border-slate-700 pt-8 mt-8 flex flex-col items-center gap-4">
                        @if($invoice->status === 'unpaid')
                            <p class="text-sm text-slate-400 mb-2 text-center">Tagihan ini belum dibayar. Anda dapat menandainya sebagai lunas secara manual di sini.</p>
                            
                            <form action="{{ route('admin.billing.markAsPaid', $invoice) }}" method="POST" class="w-full sm:w-auto">
                                @csrf
                                @method('POST')
                                <button type="submit" class="w-full sm:w-auto px-10 py-3 bg-gradient-to-r from-amber-600 to-amber-700 text-white font-bold rounded-xl shadow-lg hover:from-amber-700 hover:to-amber-800 transition">
                                    Tandai Sebagai LUNAS
                                </button>
                            </form>
                        @else
                            <div class="w-full sm:w-auto flex items-center justify-center text-green-400 font-black text-lg bg-green-900/20 px-8 py-4 rounded-xl border border-green-800">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                TAGIHAN TELAH LUNAS
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
