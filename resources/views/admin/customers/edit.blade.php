<x-app-layout>
    <div class="py-10 bg-slate-950 min-h-screen selection:bg-purple-500/30">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-8">
                <a href="{{ route('admin.customers.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-400 hover:text-white transition-colors mb-4 group">
                    <svg class="w-4 h-4 mr-1.5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Batal Edit
                </a>
                <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-white to-slate-400 tracking-tight">Edit Data Pelanggan</h2>
            </div>

            <div class="bg-slate-900/80 backdrop-blur-md rounded-2xl shadow-xl border border-slate-800 p-5 sm:p-8">
                <form action="{{ route('admin.customers.update', $customer) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-bold text-slate-300 mb-2">Nama Pelanggan (Dari Akun Induk)</label>
                        <input type="text" value="{{ $customer->user->name }}" disabled
                            class="w-full px-4 py-3 bg-slate-950/50 text-slate-500 border border-slate-800 rounded-xl cursor-not-allowed">
                        <p class="text-xs text-slate-500 mt-2 font-medium">Nama hanya bisa diubah melalui menu pengaturan akun pengguna.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-300 mb-2">No. HP / WhatsApp <span class="text-rose-500">*</span></label>
                            <input type="text" name="phone_number" value="{{ $customer->phone_number }}" required
                                class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500/50 focus:border-purple-500 transition-all placeholder-slate-500">
                            @error('phone_number')
                                <p class="text-rose-400 text-xs font-medium mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-300 mb-2">Titik Koordinat (Opsional)</label>
                            <input type="text" name="coordinates" placeholder="-6.1234, 106.5678" value="{{ $customer->coordinates }}"
                                class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500/50 focus:border-purple-500 transition-all placeholder-slate-500 font-mono text-sm">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-300 mb-2">Alamat Lengkap Instalasi <span class="text-rose-500">*</span></label>
                        <textarea name="address_installation" required rows="4" placeholder="Detail alamat, RT/RW, Patokan rumah..."
                            class="w-full px-4 py-3 bg-slate-800/50 text-slate-100 border border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500/50 focus:border-purple-500 transition-all placeholder-slate-500 resize-y min-h-[100px]">{{ $customer->address_installation }}</textarea>
                        @error('address_installation')
                            <p class="text-rose-400 text-xs font-medium mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-slate-800/60 mt-8">
                        <button type="submit" class="px-8 py-3 bg-purple-600 text-white font-bold rounded-xl hover:bg-purple-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 focus:ring-offset-slate-900 shadow-[0_0_15px_rgba(147,51,234,0.3)] hover:shadow-[0_0_20px_rgba(147,51,234,0.5)] transition-all duration-200">
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.customers.index') }}" class="px-8 py-3 bg-slate-800 text-slate-300 font-bold rounded-xl border border-slate-700 hover:bg-slate-700 hover:text-white transition-all text-center">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>