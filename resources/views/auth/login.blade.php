<x-guest-layout>
    <!-- Landing Page Style Background Overlay -->
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <img src="https://images.unsplash.com/photo-1477959858617-67f85cf4f1df?q=80&w=2000&auto=format&fit=crop"
            alt="Background Kota Metropolitan MGD" class="w-full h-full object-cover opacity-20">
        <div class="absolute inset-0 bg-gradient-to-b from-slate-900/80 via-slate-950/85 to-slate-950"></div>
        <!-- Ambient Animated Glows matching landing page amber & blue brand palette -->
        <div class="absolute -top-32 -left-32 w-96 h-96 bg-blue-600/10 rounded-full filter blur-3xl animate-pulse"></div>
        <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-amber-500/10 rounded-full filter blur-3xl animate-pulse" style="animation-duration: 4s;"></div>
    </div>

    <x-authentication-card>
        <div class="flex justify-center mb-6">
            <x-authentication-card-logo />
        </div>

        <div class="space-y-1 mb-6">
            <h2 class="text-3xl font-bold text-white text-center">Welcome Back</h2>
            <p class="text-center text-slate-400 text-sm">Sign in to your NetManager account</p>
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
            <div class="mb-4 p-3 rounded-lg bg-emerald-500/10 border border-emerald-500/30 font-medium text-sm text-emerald-400 animate-fade-in">
                {{ $value }}
            </div>
        @endsession

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div class="space-y-2">
                <x-label for="email" value="{{ __('Email Address') }}" class="text-slate-300 font-medium text-sm" />
                <x-input
                    id="email"
                    class="block w-full px-4 py-3 rounded-lg bg-slate-950/60 border border-slate-700/80 text-white placeholder-slate-500 focus:border-amber-400 focus:ring-2 focus:ring-amber-400/25 focus:bg-slate-950/90 transition duration-300 shadow-inner"
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
                <x-label for="password" value="{{ __('Password') }}" class="text-slate-300 font-medium text-sm" />
                <div class="relative">
                    <x-input
                        id="password"
                        class="block w-full px-4 py-3 pr-12 rounded-lg bg-slate-950/60 border border-slate-700/80 text-white placeholder-slate-500 focus:border-amber-400 focus:ring-2 focus:ring-amber-400/25 focus:bg-slate-950/90 transition duration-300 shadow-inner"
                        type="password"
                        name="password"
                        placeholder="••••••••"
                        required
                        autocomplete="current-password"
                    />
                    <button
                        id="password-toggle"
                        type="button"
                        class="absolute inset-y-0 right-0 inline-flex items-center px-3 text-slate-400 hover:text-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-400 rounded-r-lg transition-colors"
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
                    <x-checkbox id="remember_me" name="remember" class="accent-amber-400" />
                    <span class="ms-2 text-sm text-slate-400 group-hover:text-amber-400 transition">{{ __('Remember me') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm text-amber-400 hover:text-amber-300 font-medium transition duration-200" href="{{ route('password.request') }}">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>

            <x-button class="w-full justify-center text-center mt-6 py-3 px-4 bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-500 hover:to-amber-600 text-slate-950 font-bold rounded-lg shadow-lg shadow-amber-500/20 hover:shadow-2xl hover:shadow-amber-500/30 transform hover:scale-[1.02] transition-all duration-200 active:scale-95">
                {{ __('Sign In') }}
            </x-button>
        </form>

        <!-- Back to Home -->
        <div class="mt-6 text-center border-t border-slate-800/80 pt-6">
            <a href="{{ route('home') }}" class="inline-flex items-center text-sm text-slate-400 hover:text-amber-400 font-medium transition duration-200">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Beranda
            </a>
        </div>
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
