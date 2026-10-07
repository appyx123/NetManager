<x-app-layout>
    <div class="py-10 bg-slate-950 min-h-screen selection:bg-amber-500/30">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-8">
                <a href="{{ route('client.tickets.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-400 hover:text-white transition-colors mb-4 group">
                    <svg class="w-4 h-4 mr-1.5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali ke Daftar Pengaduan
                </a>
                <div class="mt-2">
                    <p class="text-xs font-black uppercase tracking-[0.2em] text-amber-400">Pusat Bantuan</p>
                    <h1 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-white to-slate-400 tracking-tight mt-1">Buat Pengaduan Baru</h1>
                    <p class="text-slate-400 text-sm mt-1">Sampaikan kendala Anda, tim teknisi kami akan segera membantu.</p>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-800 bg-slate-900/80 p-6 sm:p-8 shadow-xl backdrop-blur-md">
                <form action="#" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Kategori Kendala <span class="text-rose-500">*</span></label>
                        <select name="type" class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700/80 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="repair">Internet Mati Total (LOS Merah)</option>
                            <option value="repair">Internet Lambat / Putus-putus</option>
                            <option value="billing">Kendala Tagihan & Pembayaran</option>
                            <option value="other">Pertanyaan Lainnya</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Judul Pengaduan <span class="text-rose-500">*</span></label>
                        <input type="text" name="subject" class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700/80 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm" placeholder="Contoh: Lampu LOS di modem kedap-kedip merah" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Detail Pengaduan <span class="text-rose-500">*</span></label>
                        <textarea name="description" rows="5" class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700/80 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm" placeholder="Jelaskan secara detail kendala yang Anda alami..." required></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Lampiran Foto (Opsional)</label>
                        <input type="file" name="attachment" class="w-full px-4 py-2 bg-slate-950 border border-slate-700/80 rounded-xl text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-slate-800 file:text-slate-300 hover:file:bg-slate-700 text-sm">
                        <p class="text-xs text-slate-500 mt-2">Upload foto indikator lampu modem atau screenshot hasil speedtest (Max 2MB).</p>
                    </div>

                    <div class="pt-4 border-t border-slate-800 flex justify-end">
                        <button type="button" onclick="alert('Fitur simpan pengaduan segera aktif dalam pembaruan berikutnya.')" class="px-6 py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-black text-sm rounded-xl shadow-lg shadow-amber-500/20 transition-all">
                            Kirim Pengaduan
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
