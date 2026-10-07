<x-app-layout>
    <div class="py-10 bg-slate-950 min-h-screen selection:bg-amber-500/30">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center pt-20">
            <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-white to-slate-400 tracking-tight">Detail Pengaduan</h2>
            <p class="text-slate-400 mt-2 text-sm">Halaman ini akan terisi otomatis saat Anda sudah memilih salah satu pengaduan dari daftar.</p>
            <a href="{{ route('client.tickets.index') }}" class="inline-block mt-6 px-6 py-2.5 bg-slate-800 hover:bg-slate-700 font-bold rounded-xl text-slate-300 hover:text-white border border-slate-700 transition-colors text-sm">Kembali</a>
        </div>
    </div>
</x-app-layout>
