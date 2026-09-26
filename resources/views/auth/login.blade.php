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

        <div class="space-y-1 mb-6">
            <h2 class="text-3xl font-bold text-white text-center">Welcome Back</h2>
            <p class="text-center text-gray-400 text-sm">Sign in to your NetManager account</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 p-3 rounded-lg bg-rose-500/10 border border-rose-500/30 text-sm text-rose-300" role="alert">
                <p class="font-semibold">Login gagal</p>
                <p class="mt-1">
                    @if ($errors->has('email') && str_contains($errors->first('email'), 'These credentials do not match our records.'))
                        Email atau password yang Anda masukkan salah. Silakan periksa kembali.
                    @else
                        {{ $errors->first() }}
                    @endif
                </p>
            </div>
        @endif

        @session('status')
            <div class="mb-4 p-3 rounded-lg bg-green-500/10 border border-green-500/30 font-medium text-sm text-green-400 animate-fade-in">
                {{ $value }}
            </div>
        @endsession

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div class="space-y-2">
                <x-label for="email" value="{{ __('Email Address') }}" class="text-gray-200 font-medium text-sm" />
                <x-input
                    id="email"
                    class="block w-full px-4 py-3 rounded-lg bg-gray-900/50 border border-yellow-500/20 text-white placeholder-gray-500 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/30 focus:bg-gray-900/80 transition duration-300 shadow-lg shadow-yellow-500/5"
                    type="email"
                    name="email"
                    :value="old('email')"
                    placeholder="your@email.com"
                    required
                    autofocus
                    autocomplete="username"
                />
            </div>

            <div class="space-y-2">
                <x-label for="password" value="{{ __('Password') }}" class="text-gray-200 font-medium text-sm" />
                <div class="relative">
                    <x-input
                        id="password"
                        class="block w-full px-4 py-3 pr-12 rounded-lg bg-gray-900/50 border border-yellow-500/20 text-white placeholder-gray-500 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/30 focus:bg-gray-900/80 transition duration-300 shadow-lg shadow-yellow-500/5"
                        type="password"
                        name="password"
                        placeholder="••••••••"
                        required
                        autocomplete="current-password"
                    />
                    <button
                        id="password-toggle"
                        type="button"
                        class="absolute inset-y-0 right-0 inline-flex items-center px-3 text-gray-400 hover:text-yellow-300 focus:outline-none focus:ring-2 focus:ring-yellow-400 rounded-r-lg"
                        aria-label="Tampilkan password"
                        aria-controls="password"
                        aria-pressed="false"
                    >
                        <svg data-eye aria-hidden="true" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 12s3.5-6.75 9.75-6.75S21.75 12 21.75 12 18.25 18.75 12 18.75 2.25 12 2.25 12Z" />
                            <circle cx="12" cy="12" r="3" stroke-width="2" />
                        </svg>
                        <svg data-eye-off aria-hidden="true" class="hidden h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m3 3 18 18M10.58 10.59a2 2 0 0 0 2.83 2.83M9.88 5.09A10.94 10.94 0 0 1 12 4.75c6.25 0 9.75 7.25 9.75 7.25a17.1 17.1 0 0 1-3.17 4.25M6.61 6.61C3.95 8.42 2.25 12 2.25 12s3.5 6.75 9.75 6.75c1.58 0 3.04-.42 4.33-1.1" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between pt-2">
                <label for="remember_me" class="flex items-center cursor-pointer group">
                    <x-checkbox id="remember_me" name="remember" class="accent-yellow-400" />
                    <span class="ms-2 text-sm text-gray-400 group-hover:text-yellow-400 transition">{{ __('Remember me') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm text-yellow-400 hover:text-yellow-300 font-medium transition duration-200" href="{{ route('password.request') }}">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>

            <x-button class="w-full justify-center text-center mt-6 py-3 px-4 bg-gradient-to-r from-yellow-400 to-yellow-500 hover:from-yellow-500 hover:to-yellow-600 text-black font-bold rounded-lg shadow-lg hover:shadow-2xl hover:shadow-yellow-400/50 transform hover:scale-105 transition-all duration-200 active:scale-95">
                {{ __('Sign In') }}
            </x-button>
        </form>

    </x-authentication-card>

    <script>
        const passwordInput = document.getElementById('password');
        const passwordToggle = document.getElementById('password-toggle');

        passwordToggle.addEventListener('click', () => {
            const showPassword = passwordInput.type === 'password';
            passwordInput.type = showPassword ? 'text' : 'password';
            passwordToggle.setAttribute('aria-pressed', String(showPassword));
            passwordToggle.setAttribute('aria-label', showPassword ? 'Sembunyikan password' : 'Tampilkan password');
            passwordToggle.querySelector('[data-eye]').classList.toggle('hidden', showPassword);
            passwordToggle.querySelector('[data-eye-off]').classList.toggle('hidden', !showPassword);
        });
    </script>
</x-guest-layout>
