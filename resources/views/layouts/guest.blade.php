<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- tsParticles CDN -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tsparticles/2.12.0/tsparticles.bundle.min.js"></script>

        <!-- Styles -->
        @livewireStyles
        
        <style>
            #particles {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: 5;
                pointer-events: none;
            }

            .fade-in-up {
                opacity: 0;
                transform: translateY(20px);
                animation: fadeInUp 0.8s ease-out forwards;
            }

            @keyframes fadeInUp {
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        </style>
    </head>
    <body class="bg-slate-950 text-slate-300 font-sans antialiased selection:bg-amber-500 selection:text-white">
        <x-global-banner />
        <x-toast />

        <!-- Guest Navigation Bar -->
        @if (!request()->routeIs('login') && !request()->is('login'))
        <nav
            class="fixed w-full z-50 transition-all duration-300 bg-slate-900/95 backdrop-blur-md shadow-lg border-b border-slate-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    <div class="flex items-center gap-3 cursor-pointer" onclick="window.location.href='{{ url('/') }}'">
                        <a href="{{ url('/') }}" class="flex items-center">
                            <img src="{{ asset('storage/img/LOGOMGD.png') }}" alt="Logo PT MGD"
                                class="h-12 w-auto object-contain">
                        </a>
                        <span class="font-black text-xl tracking-tight text-white hidden sm:block">PT. MANDIRI GLOBAL
                            DATA</span>
                    </div>

                    <div class="hidden md:flex space-x-8">
                        <a href="{{ url('/') }}#beranda"
                            class="text-sm font-bold text-white hover:text-amber-500 transition">Beranda</a>
                        <a href="{{ url('/') }}#tentang"
                            class="text-sm font-bold text-slate-300 hover:text-amber-500 transition">Tentang</a>
                        <a href="{{ url('/') }}#layanan"
                            class="text-sm font-bold text-slate-300 hover:text-amber-500 transition">Layanan</a>
                        <a href="{{ url('/') }}#mengapa-kami"
                            class="text-sm font-bold text-slate-300 hover:text-amber-500 transition">Mengapa Kami</a>
                    </div>

                    <div class="flex items-center space-x-4">
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}"
                                class="px-5 py-2.5 text-sm font-bold text-amber-400 border-2 border-amber-500/50 rounded-lg hover:bg-amber-500/10 transition">Log
                                in Portal</a>
                        @endif
                    </div>
                </div>
            </div>
        </nav>
        @endif

        <div id="particles"></div>
        <div class="font-sans text-white antialiased relative z-10">
            {{ $slot }}
        </div>

        @livewireScripts
        
        <script>
            tsParticles.load("particles", {
                fpsLimit: 120,
                interactivity: {
                    events: {
                        onClick: {
                            enable: false,
                        },
                        onHover: {
                            enable: false,
                        },
                        resize: true,
                    },
                },
                particles: {
                    color: {
                        value: "#FCD34D",
                    },
                    links: {
                        color: "#FCD34D",
                        distance: 150,
                        enable: true,
                        opacity: 0.35,
                        width: 1,
                    },
                    move: {
                        direction: "none",
                        enable: true,
                        outModes: {
                            default: "out",
                        },
                        random: false,
                        speed: 1.5,
                        straight: false,
                    },
                    number: {
                        density: {
                            enable: true,
                            area: 800,
                        },
                        value: 65,
                    },
                    opacity: {
                        value: 0.45
                    },
                    shape: {
                        type: "circle"
                    },
                    size: {
                        value: {
                            min: 1,
                            max: 2.5
                        }
                    },
                },
                detectRetina: true,
            });
        </script>
    </body>
</html>
