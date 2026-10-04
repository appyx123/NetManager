<x-guest-layout>
    <!-- Gradient Background Overlay -->
    <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
        <div class="absolute inset-0 bg-black"></div>
        <div class="absolute top-0 right-1/3 w-96 h-96 bg-yellow-400/5 rounded-full mix-blend-screen filter blur-3xl opacity-40"></div>
        <div class="absolute bottom-0 left-1/3 w-96 h-96 bg-yellow-500/5 rounded-full mix-blend-screen filter blur-3xl opacity-40"></div>
        <div class="absolute top-1/2 right-0 w-full h-1 bg-gradient-to-l from-yellow-500/20 to-transparent"></div>
    </div>

    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="space-y-2 mb-6">
            <h2 class="text-3xl font-bold text-white text-center">Buat Kata Sandi Baru</h2>
            <p class="text-center text-gray-400 text-sm">Masukkan email dan kata sandi baru Anda</p>
        </div>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="space-y-2">
                <x-label for="email" value="Alamat Email" class="text-gray-200 font-medium text-sm" />
                <x-input 
                    id="email" 
                    class="block w-full px-4 py-3 rounded-lg bg-gray-900/50 border border-yellow-500/20 text-white placeholder-gray-500 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/30 focus:bg-gray-900/80 transition duration-300 shadow-lg shadow-yellow-500/5" 
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
                <x-label for="password" value="Kata Sandi Baru" class="text-gray-200 font-medium text-sm" />
                <x-input 
                    id="password" 
                    class="block w-full px-4 py-3 rounded-lg bg-gray-900/50 border border-yellow-500/20 text-white placeholder-gray-500 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/30 focus:bg-gray-900/80 transition duration-300 shadow-lg shadow-yellow-500/5" 
                    type="password" 
                    name="password" 
                    placeholder="••••••••"
                    required 
                    autocomplete="new-password" 
                />
            </div>

            <div class="space-y-2">
                <x-label for="password_confirmation" value="Konfirmasi Kata Sandi" class="text-gray-200 font-medium text-sm" />
                <x-input 
                    id="password_confirmation" 
                    class="block w-full px-4 py-3 rounded-lg bg-gray-900/50 border border-yellow-500/20 text-white placeholder-gray-500 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/30 focus:bg-gray-900/80 transition duration-300 shadow-lg shadow-yellow-500/5" 
                    type="password" 
                    name="password_confirmation" 
                    placeholder="••••••••"
                    required 
                    autocomplete="new-password" 
                />
            </div>

            <x-button class="w-full mt-6 py-3 px-4 bg-gradient-to-r from-yellow-400 to-yellow-500 hover:from-yellow-500 hover:to-yellow-600 text-black font-bold rounded-lg shadow-lg hover:shadow-2xl hover:shadow-yellow-400/50 transform hover:scale-105 transition-all duration-200 active:scale-95">
                Simpan Kata Sandi Baru
            </x-button>
        </form>

        <!-- Back to Login -->
        <div class="mt-6 text-center border-t border-gray-800 pt-6">
            <a href="{{ route('login') }}" class="text-yellow-400 hover:text-yellow-300 font-semibold text-sm transition duration-200">
                Kembali ke Halaman Login
            </a>
        </div>
    </x-authentication-card>
</x-guest-layout>
