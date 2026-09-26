<x-app-layout>
    <div class="py-10 bg-slate-950 min-h-screen selection:bg-amber-500/30">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-10 px-4 sm:px-0 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-white to-slate-400 tracking-tight">Manajemen Lead Marketing</h2>
                    <p class="text-slate-400 mt-2 font-medium">Kelola semua prospek dan saluran penjualan (Sales Pipeline)</p>
                </div>
                <a href="{{ route('admin.leads.create') }}" class="inline-flex items-center px-5 py-2.5 bg-amber-600 hover:bg-amber-500 text-white text-sm font-bold rounded-xl shadow-[0_0_15px_rgba(217,119,6,0.3)] hover:shadow-[0_0_20px_rgba(217,119,6,0.5)] transform hover:-translate-y-0.5 transition-all duration-200 border border-amber-500/50">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Buat Lead Baru
                </a>
            </div>

            <div class="bg-slate-900/80 backdrop-blur-sm rounded-2xl shadow-xl border border-slate-800 p-6 mb-8 mx-4 sm:mx-0">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Pencarian Nama/No.</label>
                        <input type="text" name="search" placeholder="Cari nama / kontak..." value="{{ request('search') }}"
                            class="w-full px-4 py-2.5 bg-slate-800/50 text-slate-200 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/50 transition-all placeholder-slate-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Status Lead</label>
                        <select name="status" class="w-full px-4 py-2.5 bg-slate-800/50 text-slate-200 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/50 transition-all appearance-none cursor-pointer">
                            <option value="" class="bg-slate-800">Semua Status</option>
                            <option value="prospect" @selected(request('status') === 'prospect') class="bg-slate-800">Prospek</option>
                            <option value="contacted" @selected(request('status') === 'contacted') class="bg-slate-800">Sudah Dihubungi</option>
                            <option value="qualified" @selected(request('status') === 'qualified') class="bg-slate-800">Qualified</option>
                            <option value="proposal_sent" @selected(request('status') === 'proposal_sent') class="bg-slate-800">Penawaran Dikirim</option>
                            <option value="negotiation" @selected(request('status') === 'negotiation') class="bg-slate-800">Negosiasi</option>
                            <option value="converted" @selected(request('status') === 'converted') class="bg-slate-800">Berhasil (Konversi)</option>
                            <option value="lost" @selected(request('status') === 'lost') class="bg-slate-800">Gagal (Hilang)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Marketing</label>
                        <select name="marketing_id" class="w-full px-4 py-2.5 bg-slate-800/50 text-slate-200 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/50 transition-all appearance-none cursor-pointer">
                            <option value="" class="bg-slate-800">Semua Marketing</option>
                            @foreach ($marketers as $marketer)
                                <option value="{{ $marketer->id }}" @selected(request('marketing_id') === (string)$marketer->id) class="bg-slate-800">
                                    {{ $marketer->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-end gap-3">
                        <button type="submit" class="flex-1 px-4 py-2.5 bg-amber-600 text-white font-bold rounded-xl hover:bg-amber-500 shadow-[0_0_10px_rgba(217,119,6,0.3)] transition-all">
                            Filter
                        </button>
                        <a href="{{ route('admin.leads.index') }}" class="px-4 py-2.5 bg-slate-800 text-slate-300 font-bold rounded-xl border border-slate-700 hover:bg-slate-700 transition-all text-center">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <div class="bg-slate-900/80 backdrop-blur-sm rounded-2xl shadow-xl border border-slate-800 overflow-hidden mx-4 sm:mx-0">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-800/50 text-xs font-semibold text-slate-400 uppercase tracking-wider border-b border-slate-700/50">
                            <tr>
                                <th class="px-6 py-4">Nama Pelanggan</th>
                                <th class="px-6 py-4">Kontak</th>
                                <th class="px-6 py-4">Minat Paket</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Marketing</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60 text-sm">
                            @forelse ($leads as $lead)
                                @php
                                    $statusColor = match($lead->status) { 'prospect' => 'sky', 'contacted' => 'blue', 'qualified' => 'indigo', 'proposal_sent' => 'purple', 'negotiation' => 'amber', 'converted' => 'emerald', 'lost' => 'rose', default => 'slate' };
                                    $statusText = match($lead->status) { 'prospect' => 'Prospek', 'contacted' => 'Dihubungi', 'qualified' => 'Qualified', 'proposal_sent' => 'Penawaran', 'negotiation' => 'Negosiasi', 'converted' => 'Konversi', 'lost' => 'Hilang', default => 'Unknown' };
                                @endphp
                                <tr class="hover:bg-slate-800/40 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-200 group-hover:text-white transition-colors text-base">{{ $lead->name }}</div>
                                        <div class="text-xs text-slate-500 mt-1 uppercase tracking-wider font-semibold">{{ $lead->customer_type ?? 'Tipe Reguler' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-1.5 font-medium text-slate-300">
                                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                            {{ $lead->phone ?? '-' }}
                                        </div>
                                        @if($lead->email)
                                            <div class="text-xs text-slate-400 mt-1 flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                                {{ $lead->email }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($lead->package)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-slate-800 border border-slate-700 text-amber-400">
                                                {{ $lead->package->name }}
                                            </span>
                                        @else
                                            <span class="text-slate-500 text-xs italic font-medium">Belum Dipilih</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-{{ $statusColor }}-500/10 text-{{ $statusColor }}-400 border border-{{ $statusColor }}-500/20">
                                            @if(in_array($lead->status, ['prospect', 'negotiation', 'contacted']))
                                                <span class="w-1.5 h-1.5 rounded-full bg-{{ $statusColor }}-500 mr-1.5 animate-pulse"></span>
                                            @endif
                                            {{ $statusText }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="text-slate-200 font-semibold">{{ $lead->marketing->name ?? '-' }}</span>
                                            <span class="text-[10px] text-slate-400 font-medium uppercase mt-1">Follow up: {{ $lead->survey_date ? \Carbon\Carbon::parse($lead->survey_date)->format('d M') : '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-end gap-2 font-semibold">
                                            <a href="{{ route('admin.leads.show', $lead) }}" class="text-sky-400 hover:text-sky-300 transition-colors bg-sky-400/10 hover:bg-sky-400/20 px-3 py-1.5 rounded-lg border border-sky-400/20">Detail</a>
                                            <a href="{{ route('admin.leads.edit', $lead) }}" class="text-amber-400 hover:text-amber-300 transition-colors bg-amber-400/10 hover:bg-amber-400/20 px-3 py-1.5 rounded-lg border border-amber-400/20">Edit</a>
                                            
                                            <form id="delete-form-{{ $lead->id }}" action="{{ route('admin.leads.destroy', $lead) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" onclick="confirmDelete({{ $lead->id }})" class="text-rose-400 hover:text-rose-300 transition-colors bg-rose-400/10 hover:bg-rose-400/20 px-3 py-1.5 rounded-lg border border-rose-400/20">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center justify-center text-slate-400">
                                            <div class="w-16 h-16 mb-4 bg-slate-800/50 border border-slate-700/50 rounded-full flex items-center justify-center">
                                                <svg class="w-8 h-8 text-amber-500/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                            </div>
                                            <p class="font-bold text-slate-300">Belum Ada Data Lead</p>
                                            <p class="text-sm mt-1">Saluran prospek Anda masih kosong saat ini.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-8 px-4 sm:px-0">
                @if($leads instanceof \Illuminate\Pagination\Paginator)
                    {{ $leads->links() }}
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.10.1/sweetalert2.all.min.js"></script>
    <script>
        @if(session('success'))
            const Toast = Swal.mixin({
                toast: true, position: 'top-end', showConfirmButton: false, timer: 4000,
                timerProgressBar: true, background: '#0f172a', color: '#34d399', iconColor: '#34d399',
                customClass: { popup: 'border border-slate-700 rounded-xl shadow-xl' }
            });
            Toast.fire({ icon: 'success', title: '{{ session("success") }}' });
        @endif

        function confirmDelete(leadId) {
            Swal.fire({
                title: 'Hapus Lead?',
                text: "Data prospek pelanggan ini akan dihapus permanen!",
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