<x-app-layout>
    <div class="py-10 bg-slate-950 min-h-screen selection:bg-amber-500/30">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-8">
                <a href="{{ route('admin.leads.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-400 hover:text-white transition-colors mb-4 group">
                    <svg class="w-4 h-4 mr-1.5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Pipeline
                </a>
                <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-white to-slate-400 tracking-tight">Detail Lead Prospek</h2>
            </div>

            <div class="bg-slate-900/80 backdrop-blur-md rounded-2xl shadow-xl border border-slate-800 p-8">
                
                @php
                    $statusColor = match($lead->status) { 'prospect' => 'sky', 'contacted' => 'blue', 'qualified' => 'indigo', 'proposal_sent' => 'purple', 'negotiation' => 'amber', 'converted' => 'emerald', 'lost' => 'rose', default => 'slate' };
                    $statusText = match($lead->status) { 'prospect' => 'Prospek', 'contacted' => 'Sudah Dihubungi', 'qualified' => 'Qualified', 'proposal_sent' => 'Penawaran Dikirim', 'negotiation' => 'Tahap Negosiasi', 'converted' => 'Berhasil (Konversi)', 'lost' => 'Gagal (Hilang)', default => 'Unknown' };
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-6">
                        <div class="bg-slate-800/30 p-5 rounded-xl border border-slate-700/30">
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                Calon Pelanggan
                            </h3>
                            <p class="text-2xl font-bold text-white">{{ $lead->name }}</p>
                            <p class="inline-flex mt-2 items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-700 text-slate-300 uppercase tracking-wider">
                                Tipe: {{ $lead->customer_type ?? 'Reguler' }}
                            </p>
                        </div>

                        <div>
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Informasi Kontak</h3>
                            <p class="text-slate-200 font-medium flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 21.0594L13.146 20.0881L16.511 21.8211C16.924 22.0311 17.433 21.8481 17.653 21.4171L18.423 19.8641L21.895 19.4311C22.358 19.3731 22.68 18.9491 22.622 18.4861L22.138 14.6141L24.364 11.9681C24.664 11.6141 24.622 11.0821 24.269 10.7811L21.365 8.30911L21.353 4.39811C21.348 3.93511 20.969 3.56411 20.506 3.56911L16.634 3.61211L14.498 0.697113C14.225 0.327113 13.693 0.237113 13.323 0.510113L10.158 2.84611L6.46002 1.76411C6.01502 1.63411 5.55002 1.88711 5.42102 2.33211L4.35402 6.00211L0.865018 7.35411C0.432018 7.52211 0.217018 8.00911 0.384018 8.44211L1.75802 11.9961L0.0120181 15.3411C-0.203982 15.7531 -0.0409819 16.2651 0.370018 16.4801L3.75302 18.2561L3.92202 22.1551C3.94202 22.6171 4.33302 22.9751 4.79502 22.9541L8.69402 22.7841L12.031 21.0594Z" fill="#34d399"/></svg>
                                {{ $lead->phone }}
                            </p>
                            @if ($lead->email)
                                <p class="text-slate-400 text-sm mt-1">{{ $lead->email }}</p>
                            @endif
                        </div>

                        <div>
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Alamat Instalasi</h3>
                            <p class="text-slate-200 text-sm leading-relaxed">{{ $lead->address_installation ?? 'Belum ada detail alamat' }}</p>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="bg-slate-800/30 p-5 rounded-xl border border-slate-700/30">
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Status Pipeline
                            </h3>
                            <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-bold bg-{{ $statusColor }}-500/10 text-{{ $statusColor }}-400 border border-{{ $statusColor }}-500/20">
                                {{ $statusText }}
                            </span>
                        </div>

                        <div>
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Minat Paket</h3>
                            <p class="text-white font-bold">{{ $lead->package->name ?? 'Belum Ditentukan' }}</p>
                            @if ($lead->package)
                                <p class="text-sm font-medium text-amber-400 mt-1">Rp {{ number_format($lead->package->price, 0, ',', '.') }} <span class="text-slate-500 text-xs">/ bulan</span></p>
                            @endif
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Marketing</h3>
                                <p class="text-slate-200 font-medium">{{ $lead->marketing->name ?? '-' }}</p>
                            </div>
                            <div>
                                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Sumber Leads</h3>
                                <p class="text-slate-200 font-medium capitalize">{{ str_replace('_', ' ', $lead->source ?? '-') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($lead->notes_summary || $lead->notes_obstacle || $lead->notes_special)
                    <div class="border-t border-slate-800/60 mt-8 pt-8">
                        <h3 class="text-lg font-bold text-white mb-5">Catatan Marketing</h3>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                            @if ($lead->notes_summary)
                                <div class="p-5 bg-sky-500/5 rounded-xl border border-sky-500/20">
                                    <h4 class="font-bold text-sky-400 flex items-center gap-2 mb-2 text-sm uppercase tracking-wider">📝 Ringkasan</h4>
                                    <p class="text-sky-100/80 text-sm leading-relaxed">{{ $lead->notes_summary }}</p>
                                </div>
                            @endif

                            @if ($lead->notes_obstacle)
                                <div class="p-5 bg-rose-500/5 rounded-xl border border-rose-500/20">
                                    <h4 class="font-bold text-rose-400 flex items-center gap-2 mb-2 text-sm uppercase tracking-wider">⚠️ Hambatan</h4>
                                    <p class="text-rose-100/80 text-sm leading-relaxed">{{ $lead->notes_obstacle }}</p>
                                </div>
                            @endif

                            @if ($lead->notes_special)
                                <div class="p-5 bg-purple-500/5 rounded-xl border border-purple-500/20">
                                    <h4 class="font-bold text-purple-400 flex items-center gap-2 mb-2 text-sm uppercase tracking-wider">✨ Info Khusus</h4>
                                    <p class="text-purple-100/80 text-sm leading-relaxed">{{ $lead->notes_special }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="flex flex-col sm:flex-row gap-4 mt-8 pt-8 border-t border-slate-800/60">
                    <a href="{{ route('admin.leads.edit', $lead) }}" class="px-8 py-3 bg-amber-600 text-white font-bold rounded-xl hover:bg-amber-500 shadow-[0_0_15px_rgba(217,119,6,0.3)] hover:shadow-[0_0_20px_rgba(217,119,6,0.5)] transition-all duration-200 text-center">
                        Edit Data Lead
                    </a>
                    <form id="delete-form-{{ $lead->id }}" action="{{ route('admin.leads.destroy', $lead) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="button" onclick="confirmDelete({{ $lead->id }})" class="w-full px-8 py-3 bg-slate-800 text-rose-400 font-bold rounded-xl border border-rose-500/30 hover:bg-rose-500/10 transition-all text-center">
                            Hapus Lead Permanen
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.10.1/sweetalert2.all.min.js"></script>
    <script>
        function confirmDelete(leadId) {
            Swal.fire({
                title: 'Hapus Lead?',
                text: "Semua riwayat prospek dan catatan marketing akan hilang!",
                icon: 'error', background: '#0f172a', color: '#f8fafc',
                showCancelButton: true, confirmButtonColor: '#e11d48', cancelButtonColor: '#1e293b',
                confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal',
                customClass: { popup: 'border border-slate-700 rounded-2xl', cancelButton: 'border border-slate-700 hover:bg-slate-700 text-white transition-colors' }
            }).then((result) => {
                if (result.isConfirmed) document.getElementById('delete-form-' + leadId).submit();
            });
        }
    </script>
</x-app-layout>