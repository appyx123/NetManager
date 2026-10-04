<x-app-layout>
    <div class="py-10 bg-slate-50 min-h-screen">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-6">
                <a href="{{ route('client.tickets.index') }}" class="text-slate-600 hover:text-slate-900 font-bold flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Daftar Pengaduan
                </a>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-200 bg-slate-50">
                    <h3 class="font-bold text-slate-800 text-xl">Buat Pengaduan Baru</h3>
                    <p class="text-sm text-slate-500 mt-1">Sampaikan kendala Anda, tim teknisi kami akan segera membantu.</p>
                </div>
                
                <form action="#" method="POST" enctype="multipart/form-data" class="p-6 md:p-8 space-y-6">
                    @csrf
                    
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Kategori Kendala <span class="text-red-500">*</span></label>
                        <select name="type" class="w-full border-slate-700 rounded-xl shadow-sm focus:ring-amber-500 focus:border-amber-500" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="repair">Internet Mati Total (LOS Merah)</option>
                            <option value="repair">Internet Lambat / Putus-putus</option>
                            <option value="billing">Kendala Tagihan & Pembayaran</option>
                            <option value="other">Pertanyaan Lainnya</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Judul Pengaduan <span class="text-red-500">*</span></label>
                        <input type="text" name="subject" class="w-full border-slate-700 rounded-xl shadow-sm focus:ring-amber-500 focus:border-amber-500" placeholder="Contoh: Lampu LOS di modem kedap-kedip merah" required>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Detail Pengaduan <span class="text-red-500">*</span></label>
                        <textarea name="description" rows="5" class="w-full border-slate-700 rounded-xl shadow-sm focus:ring-amber-500 focus:border-amber-500" placeholder="Jelaskan secara detail kendala yang Anda alami..." required></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Lampiran Foto (Opsional)</label>
                        <input type="file" name="attachment" class="w-full border-slate-700 rounded-xl shadow-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="text-xs text-slate-500 mt-2">Upload foto indikator lampu modem atau screenshot hasil speedtest (Max 2MB).</p>
                    </div>

                    <div class="pt-4 border-t border-slate-200 flex justify-end">
                        <button type="button" onclick="alert('Ini baru tampilan (UI). Fitur simpan akan diaktifkan setelah Controller dibuat!')" class="px-8 py-3 bg-amber-600 text-white font-bold rounded-xl shadow-lg hover:bg-amber-700 transition">
                            Kirim Pengaduan
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
