<x-app-layout>
    <div class="py-10 bg-gradient-to-b from-slate-900 to-slate-950 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="mb-8 px-4 sm:px-0">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-4xl font-black text-white">Pusat Pengajuan</h1>
                        <p class="text-slate-400 mt-2">Laporkan masalah jaringan atau buat pengajuan baru</p>
                    </div>
                    <a href="{{ route('client.complaints.create') }}" dusk="create-complaint" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-amber-500 to-amber-600 text-slate-900 font-bold rounded-lg hover:from-amber-400 hover:to-amber-500 shadow-lg transform hover:-translate-y-0.5 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Buat Pengajuan Baru
                    </a>
                </div>
            </div>

            <!-- Info Alert -->
            <div class="mb-6 px-4 sm:px-0">
                <div class="bg-blue-900/40 border border-blue-500/50 rounded-lg p-4 flex items-start">
                    <svg class="w-5 h-5 text-blue-400 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2z" clip-rule="evenodd"/></svg>
                    <div>
                        <p class="text-blue-200 font-semibold">Tim support kami siap membantu Anda</p>
                        <p class="text-blue-300 text-sm mt-1">Laporkan setiap masalah yang terjadi dan kami akan merespons dengan cepat</p>
                    </div>
                </div>
            </div>

            <!-- Complaints List -->
            <div class="space-y-4">
                @forelse($tickets as $ticket)
                <div class="bg-slate-800 border border-slate-700 rounded-lg p-6 hover:border-amber-500 transition">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-900/30 text-green-300 border border-green-700">
                                    {{ ucfirst($ticket->status) }}
                                </span>
                                <p class="text-slate-400 text-sm">#{{ $ticket->id }} • {{ $ticket->created_at->format('d M Y') }}</p>
                            </div>
                            <h3 class="text-lg font-bold text-white mt-3">{{ $ticket->subject }}</h3>
                            <p class="text-slate-400 mt-2 text-sm">{{ $ticket->description }}</p>
                        </div>
                        <a href="{{ route('client.complaints.show', $ticket) }}" class="text-amber-400 hover:text-amber-300 font-semibold text-sm">
                            Lihat Detail
                        </a>
                    </div>
                    <div class="flex items-center gap-4 text-xs text-slate-400 border-t border-slate-700 pt-4">
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zm-8 8a4 4 0 00-8 0v3h8v-3z" clip-rule="evenodd"></path></svg>
                            Teknisi: {{ $ticket->technician?->name ?? 'Belum ditugaskan' }}
                        </div>
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" clip-rule="evenodd"></path></svg>
                            Status: {{ ucfirst($ticket->status) }}
                        </div>
                    </div>
                </div>
                @empty
                <!-- Empty State -->
                <div class="bg-slate-800 border border-slate-700 rounded-lg p-12 text-center">
                    <div class="w-24 h-24 bg-amber-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-12 h-12 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <p class="text-slate-300 font-semibold text-lg">Belum ada pengajuan lainnya</p>
                    <p class="text-slate-400 text-sm mt-2">Jika ada masalah, silakan buat pengajuan baru melalui tombol di atas</p>
                </div>
                @endforelse
            </div>

            <!-- Quick Actions -->
            <div class="mt-10 px-4 sm:px-0 grid grid-cols-1 md:grid-cols-2 gap-6">
                <a href="{{ route('client.billing.index') }}" class="bg-slate-800 border border-slate-700 rounded-lg p-6 hover:border-amber-500 transition">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-green-900/30 rounded-lg flex items-center justify-center text-green-400 mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <p class="font-bold text-white">Lihat Pembayaran</p>
                            <p class="text-sm text-slate-400">Tagihan dan riwayat transaksi</p>
                        </div>
                    </div>
                </a>

                <a href="mailto:support@netmanager.com" class="bg-slate-800 border border-slate-700 rounded-lg p-6 hover:border-amber-500 transition">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-900/30 rounded-lg flex items-center justify-center text-blue-400 mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <p class="font-bold text-white">Hubungi Support</p>
                            <p class="text-sm text-slate-400">support@netmanager.com</p>
                        </div>
                    </div>
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
