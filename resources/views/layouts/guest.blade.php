<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

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
                z-index: 1;
            }
        </style>
    </head>
    <body class="bg-black">
        <x-global-banner />
        <x-toast />

        <!-- Guest Navigation Bar -->
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

        <div id="particles"></div>
        <div class="font-sans text-white antialiased relative z-10">
            {{ $slot }}
        </div>

        @livewireScripts
        
        <script>
            tsParticles.load("particles", {
                particles: {
                    number: {
                        value: 60,
                        density: {
                            enable: true,
                            value_area: 800
                        }
                    },
                    color: {
                        value: ["#fbbf24", "#f59e0b", "#d97706", "#ffffff"]
                    },
                    shape: {
                        type: "circle"
                    },
                    opacity: {
                        value: 0.5,
                        random: true,
                        anim: {
                            enable: true,
                            speed: 1,
                            opacity_min: 0.1,
                            sync: false
                        }
                    },
                    size: {
                        value: 3,
                        random: true,
                        anim: {
                            enable: true,
                            speed: 2,
                            size_min: 0.5,
                            sync: false
                        }
                    },
                    line_linked: {
                        enable: true,
                        distance: 150,
                        color: "#fbbf24",
                        opacity: 0.3,
                        width: 1
                    },
                    move: {
                        enable: true,
                        speed: 2,
                        direction: "none",
                        random: false,
                        straight: false,
                        out_mode: "bounce",
                        bounce: true,
                        attract: {
                            enable: false,
                            rotateX: 600,
                            rotateY: 1200
                        }
                    }
                },
                interactivity: {
                    detect_on: "canvas",
                    events: {
                        onhover: {
                            enable: true,
                            mode: "grab"
                        },
                        onclick: {
                            enable: true,
                            mode: "push"
                        },
                        resize: true
                    },
                    modes: {
                        grab: {
                            distance: 200,
                            line_linked: {
                                opacity: 0.5
                            }
                        },
                        push: {
                            particles_nb: 4
                        }
                    }
                },
                retina_detect: true,
                background: {
                    color: "#000000"
                }
            });
        </script>
    </body>
</html>
