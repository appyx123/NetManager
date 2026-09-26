<x-app-layout>
    <div class="py-10 bg-slate-950 min-h-screen selection:bg-purple-500/30">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-10 px-4 sm:px-0 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-white to-slate-400 tracking-tight">Manajemen Tiket Teknisi</h2>
                    <p class="text-slate-400 mt-2 font-medium">Kelola semua tiket survey, instalasi, dan perbaikan</p>
                </div>
                <a href="{{ route('admin.tickets.create') }}" class="inline-flex items-center px-5 py-2.5 bg-purple-600 hover:bg-purple-500 text-white text-sm font-bold rounded-xl shadow-[0_0_15px_rgba(147,51,234,0.3)] hover:shadow-[0_0_20px_rgba(147,51,234,0.5)] transform hover:-translate-y-0.5 transition-all duration-200 border border-purple-500/50">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Buat Tiket Baru
                </a>
            </div>

            <div class="bg-slate-900/80 backdrop-blur-sm rounded-2xl shadow-xl border border-slate-800 p-6 mb-8 mx-4 sm:mx-0">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Status</label>
                        <select name="status" class="w-full px-4 py-2.5 bg-slate-800/50 text-slate-200 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500/50 transition-all appearance-none cursor-pointer">
                            <option value="" class="bg-slate-800">Semua Status</option>
                            @foreach ($statuses as $status)
                                <option value="{{ $status }}" @selected(request('status') === $status) class="bg-slate-800">{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Tipe</label>
                        <select name="type" class="w-full px-4 py-2.5 bg-slate-800/50 text-slate-200 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500/50 transition-all appearance-none cursor-pointer">
                            <option value="" class="bg-slate-800">Semua Tipe</option>
                            @foreach ($types as $type)
                                <option value="{{ $type }}" @selected(request('type') === $type) class="bg-slate-800">{{ ucfirst($type) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Pelanggan</label>
                        <select name="customer_id" class="w-full px-4 py-2.5 bg-slate-800/50 text-slate-200 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500/50 transition-all appearance-none cursor-pointer">
                            <option value="" class="bg-slate-800">Semua Pelanggan</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}" @selected(request('customer_id') === (string)$customer->id) class="bg-slate-800">
                                    {{ $customer->user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-end gap-3">
                        <button type="submit" class="flex-1 px-4 py-2.5 bg-purple-600 text-white font-bold rounded-xl hover:bg-purple-500 shadow-[0_0_10px_rgba(147,51,234,0.3)] transition-all">
                            Filter
                        </button>
                        <a href="{{ route('admin.tickets.index') }}" class="px-4 py-2.5 bg-slate-800 text-slate-300 font-bold rounded-xl border border-slate-700 hover:bg-slate-700 transition-all text-center">
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
                                <th class="px-6 py-4">ID</th>
                                <th class="px-6 py-4">Pelanggan</th>
                                <th class="px-6 py-4">Tipe & Subjek</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Teknisi</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60 text-sm">
                            @forelse ($tickets as $ticket)
                                @php
                                    // Menentukan warna badge menggunakan match() agar kode rapi
                                    $typeColor = match($ticket->type) { 'survey' => 'blue', 'installation' => 'emerald', 'troubleshoot', 'repair' => 'amber', default => 'slate' };
                                    $statusColor = match($ticket->status) { 'open' => 'rose', 'assigned' => 'indigo', 'in_progress' => 'amber', 'resolved' => 'emerald', 'closed' => 'slate', default => 'slate' };
                                @endphp
                                <tr class="hover:bg-slate-800/40 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="px-2.5 py-1 bg-slate-800 border border-slate-700 rounded-md inline-block text-slate-300 font-bold text-xs tracking-wider">
                                            #{{ $ticket->id }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-200 group-hover:text-white transition-colors">{{ $ticket->customer->user->name }}</div>
                                        <div class="text-xs text-slate-400 flex items-center gap-1 mt-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                            {{ $ticket->customer->phone_number }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-{{ $typeColor }}-500/10 text-{{ $typeColor }}-400 border border-{{ $typeColor }}-500/20 uppercase mb-1.5">
                                            {{ $ticket->type }}
                                        </span>
                                        <div class="text-slate-300 font-medium">{{ $ticket->subject }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-{{ $statusColor }}-500/10 text-{{ $statusColor }}-400 border border-{{ $statusColor }}-500/20 capitalize">
                                            {{ str_replace('_', ' ', $ticket->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($ticket->technician)
                                            <span class="inline-flex items-center gap-1.5 font-semibold text-slate-200">
                                                <div class="w-5 h-5 rounded-full bg-indigo-500/20 flex items-center justify-center border border-indigo-500/30">
                                                    <span class="text-indigo-300 text-[10px]">{{ substr($ticket->technician->name, 0, 1) }}</span>
                                                </div>
                                                {{ $ticket->technician->name }}
                                            </span>
                                        @else
                                            <span class="text-slate-500 text-xs font-medium italic">Belum ditugaskan</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-end gap-2 font-semibold">
                                            <a href="{{ route('admin.tickets.show', $ticket) }}" class="text-sky-400 hover:text-sky-300 transition-colors bg-sky-400/10 hover:bg-sky-400/20 px-3 py-1.5 rounded-lg border border-sky-400/20">Detail</a>
                                            <a href="{{ route('admin.tickets.edit', $ticket) }}" class="text-amber-400 hover:text-amber-300 transition-colors bg-amber-400/10 hover:bg-amber-400/20 px-3 py-1.5 rounded-lg border border-amber-400/20">Edit</a>
                                            
                                            <form id="delete-form-{{ $ticket->id }}" action="{{ route('admin.tickets.destroy', $ticket) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" onclick="confirmDelete({{ $ticket->id }})" class="text-rose-400 hover:text-rose-300 transition-colors bg-rose-400/10 hover:bg-rose-400/20 px-3 py-1.5 rounded-lg border border-rose-400/20">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center justify-center text-slate-400">
                                            <svg class="w-12 h-12 mb-3 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                            <p class="font-medium">Tidak ada data tiket ditemukan.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-8 px-4 sm:px-0">
                {{ $tickets->links() }}
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

        function confirmDelete(ticketId) {
            Swal.fire({
                title: 'Hapus Tiket?',
                text: "Data tiket ini akan dihapus permanen!",
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