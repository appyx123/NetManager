<x-app-layout>
    <div class="py-10 bg-slate-950 min-h-screen selection:bg-purple-500/30">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <a href="{{ route('admin.tickets.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-400 hover:text-white transition-colors mb-4 group">
                    <svg class="w-4 h-4 mr-1.5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Batal Edit
                </a>
                <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-white to-slate-400 tracking-tight">Edit Tiket #{{ $ticket->id }}</h2>
            </div>

            <div class="bg-slate-900/80 backdrop-blur-md rounded-2xl shadow-xl border border-slate-800 p-6 sm:p-8">
                <form action="{{ route('admin.tickets.update', $ticket) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-300 mb-2">Pelanggan <span class="text-rose-500">*</span></label>
                            <select name="customer_id" required class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500/50 focus:border-purple-500 transition-all appearance-none cursor-pointer">
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}" @selected($ticket->customer_id === $customer->id) class="bg-slate-800">
                                        {{ $customer->user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-300 mb-2">Tipe Tiket <span class="text-rose-500">*</span></label>
                            <select name="type" required class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500/50 focus:border-purple-500 transition-all appearance-none cursor-pointer">
                                <option value="survey" @selected($ticket->type === 'survey') class="bg-slate-800">Survey</option>
                                <option value="installation" @selected($ticket->type === 'installation') class="bg-slate-800">Instalasi</option>
                                <option value="repair" @selected($ticket->type === 'repair') class="bg-slate-800">Perbaikan</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-300 mb-2">Subjek <span class="text-rose-500">*</span></label>
                        <input type="text" name="subject" value="{{ $ticket->subject }}" required
                            class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500/50 focus:border-purple-500 transition-all placeholder-slate-500">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-300 mb-2">Status <span class="text-rose-500">*</span></label>
                            <select name="status" required class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500/50 focus:border-purple-500 transition-all appearance-none cursor-pointer">
                                <option value="open" @selected($ticket->status === 'open') class="bg-slate-800">Buka</option>
                                <option value="assigned" @selected($ticket->status === 'assigned') class="bg-slate-800">Ditugaskan</option>
                                <option value="in_progress" @selected($ticket->status === 'in_progress') class="bg-slate-800">Sedang Dikerjakan</option>
                                <option value="resolved" @selected($ticket->status === 'resolved') class="bg-slate-800">Selesai</option>
                                <option value="closed" @selected($ticket->status === 'closed') class="bg-slate-800">Ditutup</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-300 mb-2">Teknisi Bertugas</label>
                            <select name="technician_id" class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500/50 focus:border-purple-500 transition-all appearance-none cursor-pointer">
                                <option value="" class="bg-slate-800">Belum Ditugaskan</option>
                                @foreach ($technicians as $tech)
                                    <option value="{{ $tech->id }}" @selected($ticket->technician_id === $tech->id) class="bg-slate-800">{{ $tech->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    @if ($ticket->type === 'survey')
                        <div class="border-t border-slate-800/60 pt-6 mt-6">
                            <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                Detail Survey
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-bold text-slate-300 mb-2">Tanggal Survey</label>
                                    <input type="date" name="survey_date" value="{{ $ticket->survey_date }}"
                                        class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500/50 transition-all">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-300 mb-2">Status Survey</label>
                                    <input type="text" name="survey_status" value="{{ $ticket->survey_status }}" placeholder="Bisa pasang / Titik buta..."
                                        class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500/50 transition-all placeholder-slate-500">
                                </div>
                            </div>
                            <div class="mt-6">
                                <label class="block text-sm font-bold text-slate-300 mb-2">Catatan Survey</label>
                                <textarea name="survey_notes" rows="4" placeholder="Kondisi lapangan, tiang terdekat..."
                                    class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500/50 transition-all placeholder-slate-500 resize-y">{{ $ticket->survey_notes }}</textarea>
                            </div>
                        </div>
                    @endif

                    @if ($ticket->type === 'installation')
                        <div class="border-t border-slate-800/60 pt-6 mt-6">
                            <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                Detail Instalasi
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-sm font-bold text-slate-300 mb-2">Tanggal Instalasi</label>
                                    <input type="date" name="installation_date" value="{{ $ticket->installation_date }}"
                                        class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500/50 transition-all">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-300 mb-2">Tipe Koneksi</label>
                                    <input type="text" name="connection_type" value="{{ $ticket->connection_type }}" placeholder="FTTH, Coaxial, dll"
                                        class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500/50 transition-all placeholder-slate-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-300 mb-2">Panjang Kabel (m)</label>
                                    <input type="number" name="cable_length" value="{{ $ticket->cable_length }}" placeholder="Contoh: 150"
                                        class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500/50 transition-all placeholder-slate-500">
                                </div>
                                <div class="md:col-span-2 lg:col-span-3">
                                    <label class="block text-sm font-bold text-slate-300 mb-2">Status Instalasi</label>
                                    <select name="installation_status" class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500/50 transition-all appearance-none cursor-pointer">
                                        <option value="" class="bg-slate-800">Pilih Status Laporan</option>
                                        <option value="pending" @selected($ticket->installation_status === 'pending') class="bg-slate-800">Pending</option>
                                        <option value="ongoing" @selected($ticket->installation_status === 'ongoing') class="bg-slate-800">Sedang Berjalan</option>
                                        <option value="completed" @selected($ticket->installation_status === 'completed') class="bg-slate-800">Selesai (Menunggu QC)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mt-6">
                                <label class="block text-sm font-bold text-slate-300 mb-2">Catatan Instalasi</label>
                                <textarea name="installation_notes" rows="4" placeholder="Detail teknis, redaman optik..."
                                    class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500/50 transition-all placeholder-slate-500 resize-y">{{ $ticket->installation_notes }}</textarea>
                            </div>
                        </div>
                    @endif

                    <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-slate-800/60 mt-8">
                        <button type="submit" class="px-8 py-3 bg-purple-600 text-white font-bold rounded-xl hover:bg-purple-500 shadow-[0_0_15px_rgba(147,51,234,0.3)] hover:shadow-[0_0_20px_rgba(147,51,234,0.5)] transition-all duration-200">
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.tickets.show', $ticket) }}" class="px-8 py-3 bg-slate-800 text-slate-300 font-bold rounded-xl border border-slate-700 hover:bg-slate-700 hover:text-white transition-all text-center">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>