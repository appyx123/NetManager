<x-guest-layout>
    <!-- Landing Page Style Background Overlay -->
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <img src="https://images.unsplash.com/photo-1477959858617-67f85cf4f1df?q=80&w=2000&auto=format&fit=crop"
            alt="Background Kota Metropolitan MGD" class="w-full h-full object-cover opacity-20">
        <div class="absolute inset-0 bg-gradient-to-b from-slate-900/80 via-slate-950/85 to-slate-950"></div>
        <!-- Ambient Glows matching landing page amber & blue brand palette (hardware accelerated) -->
        <div class="absolute -top-32 -left-32 w-96 h-96 rounded-full pointer-events-none"
            style="background: radial-gradient(circle, rgba(37, 99, 235, 0.15) 0%, transparent 70%);"></div>
        <div class="absolute -bottom-32 -right-32 w-96 h-96 rounded-full pointer-events-none"
            style="background: radial-gradient(circle, rgba(245, 158, 11, 0.15) 0%, transparent 70%);"></div>
    </div>

    <x-authentication-card>
        <div class="flex justify-center mb-6">
            <x-authentication-card-logo />
        </div>

        <div class="space-y-1 mb-6">
            <h2 class="text-3xl font-bold text-white text-center">Buat Kata Sandi Baru</h2>
            <p class="text-center text-slate-400 text-sm">Masukkan email dan kata sandi baru akun Anda</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 p-3 rounded-lg bg-rose-500/10 border border-rose-500/30 text-sm text-rose-300" role="alert">
                <p class="font-semibold">Pembaruan gagal</p>
                <p class="mt-1">{{ $errors->first() }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="space-y-2">
                <x-label for="email" value="Alamat Email" class="text-slate-300 font-medium text-sm" />
                <x-input
                    id="email"
                    class="block w-full px-4 py-3 rounded-lg bg-slate-950/60 border border-slate-700/80 text-white placeholder-slate-500 focus:border-amber-400 focus:ring-2 focus:ring-amber-400/25 focus:bg-slate-950/90 transition duration-300 shadow-inner"
                    type="email"
                    name="email"
                    :value="old('email', $request->email)"
                    placeholder="email@anda.com"
                    required
                    autofocus
                    autocomplete="username"
                />
            </div>

            <div class="space-y-2">
                <x-label for="password" value="Kata Sandi Baru" class="text-slate-300 font-medium text-sm" />
                <x-input
                    id="password"
                    class="block w-full px-4 py-3 rounded-lg bg-slate-950/60 border border-slate-700/80 text-white placeholder-slate-500 focus:border-amber-400 focus:ring-2 focus:ring-amber-400/25 focus:bg-slate-950/90 transition duration-300 shadow-inner"
                    type="password"
                    name="password"
                    placeholder="••••••••"
                    required
                    autocomplete="new-password"
                />
            </div>

            <div class="space-y-2">
                <x-label for="password_confirmation" value="Konfirmasi Kata Sandi" class="text-slate-300 font-medium text-sm" />
                <x-input
                    id="password_confirmation"
                    class="block w-full px-4 py-3 rounded-lg bg-slate-950/60 border border-slate-700/80 text-white placeholder-slate-500 focus:border-amber-400 focus:ring-2 focus:ring-amber-400/25 focus:bg-slate-950/90 transition duration-300 shadow-inner"
                    type="password"
                    name="password_confirmation"
                    placeholder="••••••••"
                    required
                    autocomplete="new-password"
                />
            </div>

            <button type="submit" class="w-full flex items-center justify-center gap-2 py-3.5 px-4 bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-300 hover:to-amber-400 text-slate-950 font-bold rounded-lg shadow-lg shadow-amber-500/20 hover:shadow-xl hover:shadow-amber-500/30 transition-all duration-200 cursor-pointer text-sm">
                <span>Simpan Kata Sandi Baru</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
            </button>
        </form>

        <!-- Back to Login -->
        <div class="pt-6 border-t border-slate-800/80 text-center mt-6">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-400 hover:text-amber-400 transition-colors group">
                <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Halaman Login
            </a>
        </div>
    </x-authentication-card>
</x-guest-layout>
