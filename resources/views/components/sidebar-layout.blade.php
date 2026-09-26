<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'NetManagement') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles
        <style>
            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="font-sans antialiased bg-slate-950 text-slate-300" x-data="{ sidebarOpen: false }" x-cloak>
        <x-global-banner />
        <x-banner />
        <x-toast />

        <div class="flex h-screen bg-slate-950 overflow-hidden relative">
            
            <x-sidebar />

            <button @click="sidebarOpen = !sidebarOpen" 
                    class="md:hidden fixed top-4 right-6 z-[70] text-white hover:text-slate-200 w-10 h-10 flex items-center justify-center focus:outline-none bg-slate-800 border border-slate-700 rounded-lg transition-all duration-300 shadow-[0_0_15px_rgba(0,0,0,0.5)] backdrop-blur-md">
                
                <svg class="w-6 h-6 absolute transition-all duration-300 transform"
                    :class="sidebarOpen ? 'opacity-0 rotate-90 scale-50' : 'opacity-100 rotate-0 scale-100'"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                
                <svg class="w-6 h-6 absolute transition-all duration-300 transform text-rose-400"
                    :class="sidebarOpen ? 'opacity-100 rotate-0 scale-100' : 'opacity-0 -rotate-90 scale-50'"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <div class="flex-1 flex flex-col overflow-hidden">
                
                <header class="bg-slate-900 border-b border-slate-800 shadow-lg relative z-10">
                    <div class="flex justify-between items-center px-6 py-4">
                        
                        <div>
                            @if (isset($header))
                                <h2 class="text-2xl font-extrabold text-white tracking-tight">{{ $header }}</h2>
                            @else
                                <h2 class="text-2xl font-extrabold text-transparent select-none">&nbsp;</h2>
                            @endif
                        </div>

                        <div class="flex items-center gap-4">
                            
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                        <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-purple-500 transition-all shadow-sm">
                                            <img class="w-9 h-9 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                        </button>
                                    @else
                                        <span class="inline-flex rounded-md">
                                            <button type="button" class="inline-flex items-center px-3 py-2 border border-slate-700 text-sm leading-4 font-bold rounded-lg text-slate-300 bg-slate-800 hover:text-white hover:bg-slate-700 focus:outline-none transition-all duration-200 ease-in-out shadow-sm">
                                                {{ Auth::user()->name }}
                                                <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                                </svg>
                                            </button>
                                        </span>
                                    @endif
                                </x-slot>

                                <x-slot name="content">
                                    <div class="block px-4 py-2 text-[10px] font-black text-slate-500 uppercase tracking-widest">
                                        {{ __('Kelola Akun') }}
                                    </div>
                                    <x-dropdown-link href="{{ route('profile.show') }}" class="font-medium text-slate-300 hover:text-white">
                                        {{ __('Profil Saya') }}
                                    </x-dropdown-link>
                                    
                                    @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                        <x-dropdown-link href="{{ route('api-tokens.index') }}">
                                            {{ __('API Tokens') }}
                                        </x-dropdown-link>
                                    @endif

                                    <div class="border-t border-slate-700/50 my-1"></div>
                                    
                                    <form id="logout-form-header" method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link href="#" onclick="event.preventDefault(); confirmLogout('header');" class="font-bold text-rose-400 hover:text-rose-300 hover:bg-rose-500/10">
                                            {{ __('Keluar Sistem') }}
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>

                            <div class="w-10 h-10 md:hidden"></div>

                        </div>
                    </div>
                </header>

                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-950 scrollbar-thin scrollbar-thumb-slate-800 scrollbar-track-transparent">
                    {{ $slot }}
                </main>
            </div>
        </div>

        @stack('modals')
        @livewireScripts
        @stack('scripts')

        <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.10.1/sweetalert2.all.min.js"></script>
        <script>
            function confirmLogout(source) {
                Swal.fire({
                    title: 'Keluar Sistem?',
                    text: "Sesi Anda akan diakhiri dan harus login kembali.",
                    icon: 'warning',
                    background: '#0f172a',
                    color: '#f8fafc',
                    showCancelButton: true,
                    confirmButtonColor: '#e11d48',
                    cancelButtonColor: '#1e293b',
                    confirmButtonText: 'Ya, Keluar!',
                    cancelButtonText: 'Batal',
                    customClass: {
                        popup: 'border border-slate-700 rounded-2xl',
                        cancelButton: 'border border-slate-700 hover:bg-slate-700 text-white transition-colors'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Submit form berdasarkan tombol mana yang diklik
                        if (source === 'sidebar') {
                            document.getElementById('logout-form-sidebar').submit();
                        } else if (source === 'header') {
                            document.getElementById('logout-form-header').submit();
                        }
                    }
                });
            }
        </script>
    </body>
</html>