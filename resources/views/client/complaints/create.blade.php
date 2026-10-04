<x-app-layout>
    <div class="py-10 bg-slate-950 min-h-screen selection:bg-emerald-500/30">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-8">
                <a href="{{ route('client.complaints.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-400 hover:text-white transition-colors mb-4 group">
                    <svg class="w-4 h-4 mr-1.5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Daftar Pengaduan
                </a>
                <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-white to-slate-400 tracking-tight">Buat Pengaduan Bantuan Baru</h2>
                <p class="text-slate-400 mt-2 font-medium">Silakan detailkan masalah atau permintaan Anda agar teknisi kami dapat segera menangani.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-2">
                    <div class="bg-slate-900/80 backdrop-blur-md rounded-3xl shadow-xl border border-slate-800 overflow-hidden relative">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

                        <div class="p-8 relative z-10">
                            <form action="{{ route('client.complaints.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                                @csrf

                                <div>
    <label class="block text-sm font-bold text-slate-300 mb-3 flex items-center gap-2">
        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
        Kategori Pengaduan <span class="text-rose-500">*</span>
    </label>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        
        <label class="relative flex flex-col p-4 cursor-pointer bg-slate-800/50 border border-slate-700/50 rounded-xl hover:bg-slate-800 hover:border-emerald-500/50 transition-all duration-200 group">
            <input type="radio" name="category" value="network_slow" class="absolute right-4 top-4 text-emerald-500 bg-slate-900 border-slate-700 focus:ring-emerald-500 focus:ring-offset-slate-900" checked>
            <div class="p-2 bg-emerald-500/10 rounded-lg w-fit mb-3 border border-emerald-500/20 group-hover:bg-emerald-500/20 transition-colors">
                <svg class="w-5 h-5 text-emerald-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.906 14.142 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"></path></svg>
            </div>
            <span class="text-slate-200 font-bold text-sm">Internet Lambat / Putus</span>
        </label>
        
        <label class="relative flex flex-col p-4 cursor-pointer bg-slate-800/50 border border-slate-700/50 rounded-xl hover:bg-slate-800 hover:border-emerald-500/50 transition-all duration-200 group">
            <input type="radio" name="category" value="installation_issue" class="absolute right-4 top-4 text-emerald-500 bg-slate-900 border-slate-700 focus:ring-emerald-500 focus:ring-offset-slate-900">
            <div class="p-2 bg-emerald-500/10 rounded-lg w-fit mb-3 border border-emerald-500/20 group-hover:bg-emerald-500/20 transition-colors">
                <svg class="w-5 h-5 text-emerald-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 5.758a3 3 0 10-4.243-4.243 3 3 0 004.243 4.243z"></path></svg>
            </div>
            <span class="text-slate-200 font-bold text-sm">Masalah Fisik / Kabel</span>
        </label>

        <label class="relative flex flex-col p-4 cursor-pointer bg-slate-800/50 border border-slate-700/50 rounded-xl hover:bg-slate-800 hover:border-emerald-500/50 transition-all duration-200 group">
            <input type="radio" name="category" value="billing_issue" class="absolute right-4 top-4 text-emerald-500 bg-slate-900 border-slate-700 focus:ring-emerald-500 focus:ring-offset-slate-900">
            <div class="p-2 bg-emerald-500/10 rounded-lg w-fit mb-3 border border-emerald-500/20 group-hover:bg-emerald-500/20 transition-colors">
                <svg class="w-5 h-5 text-emerald-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <span class="text-slate-200 font-bold text-sm">Pertanyaan Tagihan</span>
        </label>

        <label class="relative flex flex-col p-4 cursor-pointer bg-slate-800/50 border border-slate-700/50 rounded-xl hover:bg-slate-800 hover:border-emerald-500/50 transition-all duration-200 group">
            <input type="radio" name="category" value="other" class="absolute right-4 top-4 text-emerald-500 bg-slate-900 border-slate-700 focus:ring-emerald-500 focus:ring-offset-slate-900">
            <div class="p-2 bg-emerald-500/10 rounded-lg w-fit mb-3 border border-emerald-500/20 group-hover:bg-emerald-500/20 transition-colors">
                <svg class="w-5 h-5 text-emerald-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <span class="text-slate-200 font-bold text-sm">Lain-lain</span>
        </label>

    </div>
</div>

                                <div>
                                    <label for="title" class="block text-sm font-bold text-slate-300 mb-2">Judul Ringkasan <span class="text-rose-500">*</span></label>
                                    <input type="text" id="title" name="title" placeholder="Contoh: Koneksi internet merah dari jam 8 pagi" 
                                        class="w-full px-4 py-3 bg-slate-800/50 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/50 transition-all" required>
                                </div>

                                <div>
                                    <label for="priority" class="block text-sm font-bold text-slate-300 mb-2">Tingkat Prioritas (Urgensi) <span class="text-rose-500">*</span></label>
                                    <select id="priority" name="priority" 
                                        class="w-full px-4 py-3 bg-slate-800/50 border border-slate-700 rounded-xl text-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/50 transition-all appearance-none cursor-pointer">
                                        <option value="low" class="bg-slate-800">Normal (Dapat ditangani kapan saja)</option>
                                        <option value="medium" selected class="bg-slate-800">Sedang (Perlu ditangani dalam beberapa hari)</option>
                                        <option value="high" class="bg-slate-800">Tinggi (Perlu ditangani segera)</option>
                                        <option value="urgent" class="bg-slate-800 text-rose-400 font-bold">Sangat Mendesak (Internet mati total / Kabel putus)</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="description" class="block text-sm font-bold text-slate-300 mb-2">Jelaskan Detail Permasalahannya <span class="text-rose-500">*</span></label>
                                    <textarea id="description" name="description" rows="5" 
                                        placeholder="Ceritakan:&#10;1. Kapan tepatnya masalah ini mulai muncul?&#10;2. Lampu apa yang menyala di Router/Modem Anda?&#10;3. Apakah sudah dicoba direstart/cabut colok?"
                                        class="w-full px-4 py-3 bg-slate-800/50 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/50 transition-all resize-y" required></textarea>
                                </div>

                                <div>
                                    <label for="photo" class="block text-sm font-bold text-slate-300 mb-2 flex items-center justify-between">
                                        Lampirkan Bukti Foto (Opsional)
                                        <span class="text-[10px] font-medium text-slate-500 bg-slate-800 px-2 py-0.5 rounded border border-slate-700">Maks. 5 MB</span>
                                    </label>
                                    <div class="border-2 border-dashed border-slate-700/80 bg-slate-800/20 rounded-2xl p-8 text-center hover:bg-slate-800/40 hover:border-emerald-500/50 transition-all cursor-pointer group" onclick="document.getElementById('photo').click()">
                                        <div class="w-14 h-14 bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-3 border border-slate-700 group-hover:border-emerald-500/30 group-hover:text-emerald-400 transition-colors">
                                            <svg class="w-6 h-6 text-slate-400 group-hover:text-emerald-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                        </div>
                                        <p class="text-slate-300 font-bold">Klik untuk memilih foto dari perangkat Anda</p>
                                        <p class="text-slate-500 text-xs mt-1">Format didukung: JPG, PNG, JPEG</p>
                                    </div>
                                    <input type="file" id="photo" name="photo" accept="image/*" class="hidden">
                                </div>

                                <div class="pt-4 border-t border-slate-800/60 flex flex-col sm:flex-row gap-4">
                                    <button type="submit" 
                                        class="flex-1 px-8 py-3.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl shadow-[0_0_15px_rgba(16,185,129,0.3)] hover:shadow-[0_0_25px_rgba(16,185,129,0.5)] transform hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center text-lg">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                        Kirim Pengaduan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-sky-500/10 border border-sky-500/20 rounded-3xl p-6 relative overflow-hidden group hover:border-sky-500/30 transition-colors">
                    <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-sky-500/20 rounded-full blur-2xl pointer-events-none group-hover:bg-sky-500/30 transition-colors"></div>
                    
                    <div class="w-10 h-10 bg-sky-500/20 rounded-xl flex items-center justify-center mb-4 border border-sky-500/30">
                        <svg class="w-6 h-6 text-sky-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    
                    <h3 class="font-black text-sky-400 text-lg mb-2 relative z-10">Tips Pengaduan</h3>
                    <p class="text-sky-100/70 text-sm leading-relaxed relative z-10">
                        Sertakan foto lampu indikator pada Router/Modem Anda. Hal ini sangat membantu teknisi kami mendiagnosa masalah jaringan Anda dari jarak jauh dengan lebih cepat.
                    </p>
                </div>

                    <div class="bg-slate-900/80 backdrop-blur-md border border-slate-800 rounded-3xl p-6 relative overflow-hidden group">
                        <div class="w-10 h-10 bg-slate-800 rounded-xl flex items-center justify-center mb-4 border border-slate-700">
                            <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <h3 class="font-bold text-white text-lg mb-4 relative z-10">Butuh Bantuan Langsung?</h3>
                        
                        <div class="space-y-4 relative z-10">
                            <a href="https://wa.me/62821XXXXXXXX" target="_blank" class="flex items-center gap-3 p-3 rounded-xl bg-slate-800/50 border border-slate-700/50 hover:border-emerald-500/50 hover:bg-slate-800 transition-colors group/link">
                                <div class="w-8 h-8 rounded-lg bg-emerald-500/20 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-emerald-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 21.0594L13.146 20.0881L16.511 21.8211C16.924 22.0311 17.433 21.8481 17.653 21.4171L18.423 19.8641L21.895 19.4311C22.358 19.3731 22.68 18.9491 22.622 18.4861L22.138 14.6141L24.364 11.9681C24.664 11.6141 24.622 11.0821 24.269 10.7811L21.365 8.30911L21.353 4.39811C21.348 3.93511 20.969 3.56411 20.506 3.56911L16.634 3.61211L14.498 0.697113C14.225 0.327113 13.693 0.237113 13.323 0.510113L10.158 2.84611L6.46002 1.76411C6.01502 1.63411 5.55002 1.88711 5.42102 2.33211L4.35402 6.00211L0.865018 7.35411C0.432018 7.52211 0.217018 8.00911 0.384018 8.44211L1.75802 11.9961L0.0120181 15.3411C-0.203982 15.7531 -0.0409819 16.2651 0.370018 16.4801L3.75302 18.2561L3.92202 22.1551C3.94202 22.6171 4.33302 22.9751 4.79502 22.9541L8.69402 22.7841L12.031 21.0594Z" fill="#34d399"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-0.5">WhatsApp Center</p>
                                    <p class="text-sm font-bold text-slate-300 group-hover/link:text-white transition-colors">+62 821-XXXX-XXXX</p>
                                </div>
                            </a>

                            <div class="p-3">
                                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5 flex items-center gap-1.5"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Jam Kerja Support</p>
                                <p class="text-sm font-medium text-slate-400">Senin - Jumat, <span class="text-slate-300 font-bold">08:00 - 17:00</span> WITA</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>