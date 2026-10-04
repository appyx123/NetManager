<x-app-layout>
    <div class="py-10 bg-slate-950 min-h-screen selection:bg-purple-500/30">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-8">
                <a href="{{ route('admin.tickets.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-400 hover:text-white transition-colors mb-4 group">
                    <svg class="w-4 h-4 mr-1.5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali
                </a>
                <div class="flex items-center gap-4">
                    <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-white to-slate-400 tracking-tight">Detail Tiket</h2>
                    <div class="px-3 py-1 bg-slate-800 border border-slate-700 rounded-lg text-slate-300 font-bold text-lg tracking-wider mt-1">
                        #{{ $ticket->id }}
                    </div>
                </div>
            </div>

            <div class="bg-slate-900/80 backdrop-blur-md rounded-2xl shadow-xl border border-slate-800 p-8">
                
                @php
                    $typeColor = match($ticket->type) { 'survey' => 'blue', 'installation' => 'emerald', 'troubleshoot', 'repair' => 'amber', default => 'slate' };
                    $statusColor = match($ticket->status) { 'open' => 'rose', 'assigned' => 'indigo', 'in_progress' => 'amber', 'resolved' => 'emerald', 'closed' => 'slate', default => 'slate' };
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-6">
                        <div class="bg-slate-800/30 p-5 rounded-xl border border-slate-700/30">
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                Pelanggan
                            </h3>
                            <p class="text-xl font-bold text-white">{{ $ticket->customer->user->name }}</p>
                            <p class="text-sm font-medium text-slate-400 mt-1">{{ $ticket->customer->phone_number }}</p>
                        </div>

                        <div>
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Tipe Tiket</h3>
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold bg-{{ $typeColor }}-500/10 text-{{ $typeColor }}-400 border border-{{ $typeColor }}-500/20 uppercase">
                                {{ $ticket->type }}
                            </span>
                        </div>

                        <div>
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Subjek</h3>
                            <p class="text-slate-200 font-medium">{{ $ticket->subject }}</p>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="bg-slate-800/30 p-5 rounded-xl border border-slate-700/30">
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Status Tiket
                            </h3>
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-bold bg-{{ $statusColor }}-500/10 text-{{ $statusColor }}-400 border border-{{ $statusColor }}-500/20 capitalize">
                                {{ str_replace('_', ' ', $ticket->status) }}
                            </span>
                        </div>

                        <div>
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Teknisi Bertugas</h3>
                            <p class="text-white mt-1">
                                @if ($ticket->technician)
                                    <span class="inline-flex items-center gap-2 font-bold text-slate-200">
                                        <div class="w-7 h-7 rounded-full bg-indigo-500/20 flex items-center justify-center border border-indigo-500/30">
                                            <span class="text-indigo-300 text-xs">{{ substr($ticket->technician->name, 0, 1) }}</span>
                                        </div>
                                        {{ $ticket->technician->name }}
                                    </span>
                                @else
                                    <span class="text-slate-500 font-medium italic">Belum ditugaskan</span>
                                @endif
                            </p>
                        </div>

                        <div>
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Dibuat Pada</h3>
                            <p class="text-slate-300 font-medium">{{ $ticket->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                @if ($ticket->survey_notes || $ticket->installation_notes)
                    <div class="border-t border-slate-800/60 mt-8 pt-8">
                        <h3 class="text-lg font-bold text-white mb-5">Detail Laporan Pekerjaan</h3>
                        
                        @if ($ticket->survey_notes)
                            <div class="mb-4 p-5 bg-blue-500/5 rounded-xl border border-blue-500/20">
                                <h4 class="font-bold text-blue-400 flex items-center gap-2 mb-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                    Catatan Survey:
                                </h4>
                                <p class="text-blue-100/80 leading-relaxed">{{ $ticket->survey_notes }}</p>
                            </div>
                        @endif

                        @if ($ticket->installation_notes)
                            <div class="p-5 bg-emerald-500/5 rounded-xl border border-emerald-500/20">
                                <h4 class="font-bold text-emerald-400 flex items-center gap-2 mb-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    Catatan Instalasi/Perbaikan:
                                </h4>
                                <p class="text-emerald-100/80 leading-relaxed">{{ $ticket->installation_notes }}</p>
                            </div>
                        @endif
                    </div>
                @endif

                <div class="flex flex-col sm:flex-row gap-4 mt-8 pt-8 border-t border-slate-800/60">
                    <a href="{{ route('admin.tickets.edit', $ticket) }}" class="px-8 py-3 bg-purple-600 text-white font-bold rounded-xl hover:bg-purple-500 shadow-[0_0_15px_rgba(147,51,234,0.3)] hover:shadow-[0_0_20px_rgba(147,51,234,0.5)] transition-all duration-200 text-center">
                        Edit Tiket Ini
                    </a>
                    <form id="delete-form-{{ $ticket->id }}" action="{{ route('admin.tickets.destroy', $ticket) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="button" onclick="confirmDelete({{ $ticket->id }})" class="w-full px-8 py-3 bg-slate-800 text-rose-400 font-bold rounded-xl border border-rose-500/30 hover:bg-rose-500/10 transition-all text-center">
                            Hapus Tiket Permanen
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.10.1/sweetalert2.all.min.js"></script>
    <script>
        function confirmDelete(ticketId) {
            Swal.fire({
                title: 'Hapus Tiket?',
                text: "Data dan laporan tiket ini akan dihapus permanen!",
                icon: 'error', background: '#0f172a', color: '#f8fafc',
                showCancelButton: true, confirmButtonColor: '#e11d48', cancelButtonColor: '#1e293b',
                confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal',
                customClass: { popup: 'border border-slate-700 rounded-2xl', cancelButton: 'border border-slate-700 hover:bg-slate-700 text-white transition-colors' }
            }).then((result) => {
                if (result.isConfirmed) document.getElementById('delete-form-' + ticketId).submit();
            });
        }
    </script>
</x-app-layout>