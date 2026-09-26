<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PT. Mandiri Global Data</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600,700,900&display=swap" rel="stylesheet" />
    @vite('resources/css/app.css')

    <script src="https://cdn.jsdelivr.net/npm/tsparticles@2.12.0/tsparticles.bundle.min.js"></script>

    <style>
        /* Custom Animations */
        .fade-in-up {
            opacity: 0;
            transform: translateY(30px);
            animation: fadeInUp 0.8s ease-out forwards;
        }

        .delay-100 {
            animation-delay: 100ms;
        }

        .delay-200 {
            animation-delay: 200ms;
        }

        .delay-300 {
            animation-delay: 300ms;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Gradient Text */
        .text-gradient-gold {
            background: linear-gradient(to right, #F59E0B, #FCD34D);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Memastikan kontainer particles BENAR-BENAR TERKUNCI di Hero Section */
        #tsparticles {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 1;
        }
    </style>
</head>

<body class="antialiased bg-slate-900 text-slate-300 font-sans selection:bg-amber-500 selection:text-white">

    <nav
        class="fixed w-full z-50 transition-all duration-300 bg-slate-900/95 backdrop-blur-md shadow-lg border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center gap-3 cursor-pointer" onclick="window.scrollTo(0,0)">
                    <img src="/storage/img/LOGOMGD.png" alt="Logo PT MGD"
                        class="h-12 w-auto object-contain">
                    <span class="font-black text-xl tracking-tight text-white hidden sm:block">PT. MANDIRI GLOBAL
                        DATA</span>
                </div>

                <div class="hidden md:flex space-x-8">
                    <a href="#beranda" class="text-sm font-bold text-white hover:text-amber-500 transition">Beranda</a>
                    <a href="#tentang"
                        class="text-sm font-bold text-slate-300 hover:text-amber-500 transition">Tentang</a>
                    <a href="#layanan"
                        class="text-sm font-bold text-slate-300 hover:text-amber-500 transition">Layanan</a>
                    <a href="#mengapa-kami"
                        class="text-sm font-bold text-slate-300 hover:text-amber-500 transition">Mengapa Kami</a>
                </div>

                <div class="flex items-center space-x-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}"
                                class="px-5 py-2.5 text-sm font-bold text-slate-900 bg-amber-400 rounded-lg hover:bg-amber-300 hover:shadow-lg hover:shadow-amber-500/20 transition transform hover:-translate-y-0.5">Masuk
                                ke Sistem</a>
                        @else
                            <a href="{{ route('login') }}"
                                class="px-5 py-2.5 text-sm font-bold text-amber-400 border-2 border-amber-500/50 rounded-lg hover:bg-amber-500/10 transition">Log
                                in Portal</a>
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <section id="beranda"
        class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden bg-black flex items-center min-h-screen">

        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1477959858617-67f85cf4f1df?q=80&w=2000&auto=format&fit=crop"
                alt="Background Kota Metropolitan MGD" class="w-full h-full object-cover opacity-20 lazyload">
            <div class="absolute inset-0 bg-gradient-to-b from-slate-900/80 via-slate-950/80 to-slate-900"></div>
        </div>

        <div id="tsparticles"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center fade-in-up">
            <span
                class="inline-block py-1 px-3 rounded-full bg-amber-400/10 border border-amber-500/30 text-amber-400 text-sm font-bold tracking-wider uppercase mb-6 shadow-sm">
                Infrastruktur IT & Telekomunikasi Global
            </span>
            <h1 class="text-4xl md:text-5xl lg:text-7xl font-black text-white tracking-tight leading-tight mb-8">
                Menghubungkan Bisnis & Pekerjaan Anda <br class="hidden md:block">
                dengan Koneksi <span class="text-gradient-gold">Berkualitas.</span>
            </h1>

            <div class="flex flex-col sm:flex-row justify-center items-center gap-4 mt-10">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="w-full sm:w-auto px-8 py-4 text-base font-bold text-slate-900 bg-amber-400 rounded-xl hover:bg-amber-300 shadow-lg shadow-amber-500/20 transition transform hover:-translate-y-1">
                            Masuk ke Sistem
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="w-full sm:w-auto px-8 py-4 text-base font-bold text-slate-900 bg-amber-400 rounded-xl hover:bg-amber-300 shadow-lg shadow-amber-500/20 transition transform hover:-translate-y-1">
                            Log in Portal
                        </a>
                    @endauth
                @endif
                <a href="#tentang"
                    class="w-full sm:w-auto px-8 py-4 text-base font-bold text-white border-2 border-slate-600 rounded-xl hover:border-slate-400 hover:bg-slate-800/50 transition">
                    Pelajari Selengkapnya
                </a>
            </div>
        </div>
    </section>

    <section id="tentang" class="py-20 lg:py-32 bg-slate-900 relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div class="fade-in-up">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-1 bg-amber-500"></div>
                        <h2 class="text-sm font-black text-amber-500 uppercase tracking-widest">Tentang Perusahaan</h2>
                    </div>
                    <h3 class="text-3xl lg:text-4xl font-black text-white mb-6 leading-tight">
                        Dedikasi untuk Layanan Teknologi & Telekomunikasi Terpercaya
                    </h3>
                    <div class="space-y-4 text-slate-300 leading-relaxed text-lg">
                        <p>
                            <strong class="text-amber-400">PT. Mandiri Global Data</strong> merupakan perusahaan jasa
                            konstruksi yang fokus pada bidang penyedia layanan jasa pembangunan infrastruktur Teknologi
                            Informasi, Telekomunikasi dan Elektrikal yang berkantor di Kota Makassar dan berdiri sejak
                            26 Agustus 2020.
                        </p>
                        <p>
                            Disertai dengan pengalaman, keahlian dan legalitasnya, kami telah mengerjakan berbagai
                            proyek seperti membangun infrastruktur jaringan telekomunikasi berbasis Fiber Optic,
                            instalasi & penyedia jaringan internet, Instalasi CCTV, hingga menjadi perusahaan konsultan
                            IT di berbagai Instansi Pemerintah maupun Swasta.
                        </p>
                        <blockquote
                            class="border-l-4 border-amber-500 bg-slate-800 p-6 rounded-r-xl italic font-medium text-white my-6 shadow-sm">
                            "Merencanakan solusi secara jelas dan memberikan teknologi terkini adalah komitmen kami."
                        </blockquote>
                    </div>
                </div>
                <div class="relative fade-in-up delay-100">
                    <div
                        class="absolute inset-0 bg-gradient-to-tr from-blue-600 to-amber-500 rounded-3xl transform translate-x-4 translate-y-4 opacity-20">
                    </div>
                    <img src="https://images.unsplash.com/photo-1558494949-ef010cbdcc31?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80"
                        alt="Infrastruktur IT MGD"
                        class="relative rounded-3xl shadow-2xl object-cover h-[500px] w-full border-4 border-slate-800">
                </div>
            </div>
        </div>
    </section>

    <section id="layanan" class="py-20 lg:py-32 bg-slate-950 border-y border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 fade-in-up">
                <h2 class="text-sm font-black text-amber-500 uppercase tracking-widest mb-2">Bidang Keahlian</h2>
                <h3 class="text-3xl lg:text-4xl font-black text-white mb-6">Layanan Utama Kami</h3>
                <p class="text-slate-400 text-lg">Menyediakan solusi terintegrasi untuk kebutuhan infrastruktur jaringan
                    dan keamanan bisnis Anda.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div
                    class="bg-slate-900 p-8 rounded-2xl shadow-lg border border-slate-800 hover:border-amber-500/50 hover:shadow-amber-500/10 transition-all duration-300 transform hover:-translate-y-2 fade-in-up">
                    <div
                        class="w-16 h-16 bg-blue-900/50 rounded-2xl flex items-center justify-center mb-6 text-blue-400 border border-blue-800">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold text-white mb-4">Pemasangan Fiber Optik</h4>
                    <ul class="space-y-3 text-slate-300 font-medium text-sm">
                        <li class="flex items-start"><svg class="w-5 h-5 text-amber-500 mr-2 shrink-0" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg> Penarikan Kabel Fiber Optic</li>
                        <li class="flex items-start"><svg class="w-5 h-5 text-amber-500 mr-2 shrink-0" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg> Servis Jaringan Fiber Optic</li>
                        <li class="flex items-start"><svg class="w-5 h-5 text-amber-500 mr-2 shrink-0" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg> Interkoneksi Jaringan</li>
                    </ul>
                </div>

                <div
                    class="bg-slate-900 p-8 rounded-2xl shadow-lg border border-slate-800 hover:border-amber-500/50 hover:shadow-amber-500/10 transition-all duration-300 transform hover:-translate-y-2 fade-in-up delay-100">
                    <div
                        class="w-16 h-16 bg-amber-500/10 rounded-2xl flex items-center justify-center mb-6 text-amber-400 border border-amber-500/20">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9">
                            </path>
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold text-white mb-4">Instalasi Jaringan Internet</h4>
                    <ul class="space-y-3 text-slate-300 font-medium text-sm">
                        <li class="flex items-start"><svg class="w-5 h-5 text-amber-500 mr-2 shrink-0" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg> Layanan Internet Broadband</li>
                        <li class="flex items-start"><svg class="w-5 h-5 text-amber-500 mr-2 shrink-0" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg> Pemasangan Perangkat Wireless</li>
                        <li class="flex items-start"><svg class="w-5 h-5 text-amber-500 mr-2 shrink-0" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg> Layanan Corporate</li>
                    </ul>
                </div>

                <div
                    class="bg-slate-900 p-8 rounded-2xl shadow-lg border border-slate-800 hover:border-amber-500/50 hover:shadow-amber-500/10 transition-all duration-300 transform hover:-translate-y-2 fade-in-up delay-200">
                    <div
                        class="w-16 h-16 bg-blue-900/50 rounded-2xl flex items-center justify-center mb-6 text-blue-400 border border-blue-800">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold text-white mb-4">Instalasi Sistem CCTV</h4>
                    <ul class="space-y-3 text-slate-300 font-medium text-sm">
                        <li class="flex items-start"><svg class="w-5 h-5 text-amber-500 mr-2 shrink-0" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg> Pemasangan CCTV Indoor-Outdoor</li>
                        <li class="flex items-start"><svg class="w-5 h-5 text-amber-500 mr-2 shrink-0" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg> Konfigurasi Jaringan Keamanan</li>
                        <li class="flex items-start"><svg class="w-5 h-5 text-amber-500 mr-2 shrink-0" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg> Perawatan Jaringan CCTV</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section id="mengapa-kami" class="py-20 lg:py-32 bg-slate-900 relative overflow-hidden">
        <div class="absolute inset-0 opacity-5"
            style="background-image: radial-gradient(#FCD34D 1px, transparent 1px); background-size: 40px 40px;"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-16 fade-in-up">
                <h2 class="text-sm font-black text-amber-400 uppercase tracking-widest mb-2">Keunggulan MGD</h2>
                <h3 class="text-3xl lg:text-4xl font-black text-white mb-6">Mengapa Memilih Kami?</h3>
                <p class="text-slate-400 text-lg">Komitmen kami untuk memberikan standar kualitas tertinggi di setiap
                    pekerjaan yang kami tangani.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div
                    class="p-6 bg-slate-800/50 rounded-2xl border border-slate-700 hover:bg-slate-800 hover:border-slate-600 transition fade-in-up">
                    <span class="text-4xl font-black text-yellow-500 mb-4 block">01</span>
                    <h4 class="text-xl font-bold text-white mb-3">Legalitas Resmi</h4>
                    <p class="text-slate-400 text-sm leading-relaxed">PT Mandiri Global Data adalah perusahaan Legal,
                        menunjukkan keseriusan kami dalam melayani dan memberikan jaminan hukum.</p>
                </div>
                <div
                    class="p-6 bg-slate-800/50 rounded-2xl border border-slate-700 hover:bg-slate-800 hover:border-slate-600 transition fade-in-up delay-100">
                    <span class="text-4xl font-black text-yellow-500 mb-4 block">02</span>
                    <h4 class="text-xl font-bold text-white mb-3">Harga Disesuaikan</h4>
                    <p class="text-slate-400 text-sm leading-relaxed">Cocok bagi usaha kecil hingga instansi besar.
                        Harga layanan selalu disesuaikan dengan skala dan kualitas layanan.</p>
                </div>
                <div
                    class="p-6 bg-slate-800/50 rounded-2xl border border-slate-700 hover:bg-slate-800 hover:border-slate-600 transition fade-in-up delay-200">
                    <span class="text-4xl font-black text-yellow-500 mb-4 block">03</span>
                    <h4 class="text-xl font-bold text-white mb-3">Pelayanan Ramah</h4>
                    <p class="text-slate-400 text-sm leading-relaxed">Respon yang baik, aktif, dan cepat dari staf
                        maupun teknisi dengan penjelasan yang transparan.</p>
                </div>
                <div
                    class="p-6 bg-slate-800/50 rounded-2xl border border-slate-700 hover:bg-slate-800 hover:border-slate-600 transition fade-in-up">
                    <span class="text-4xl font-black text-yellow-500 mb-4 block">04</span>
                    <h4 class="text-xl font-bold text-white mb-3">Garansi Layanan</h4>
                    <p class="text-slate-400 text-sm leading-relaxed">Ketika terjadi kendala setelah penanganan, Anda
                        berhak melakukan klaim garansi pada pihak kami.</p>
                </div>
                <div
                    class="p-6 bg-slate-800/50 rounded-2xl border border-slate-700 hover:bg-slate-800 hover:border-slate-600 transition fade-in-up delay-100">
                    <span class="text-4xl font-black text-yellow-500 mb-4 block">05</span>
                    <h4 class="text-xl font-bold text-white mb-3">Berpengalaman</h4>
                    <p class="text-slate-400 text-sm leading-relaxed">Pekerjaan ditangani langsung oleh tim teknisi
                        yang handal, tersertifikasi, dan berpengalaman di lapangan.</p>
                </div>
                <div
                    class="p-6 bg-slate-800/50 rounded-2xl border border-slate-700 hover:bg-slate-800 hover:border-slate-600 transition fade-in-up delay-200">
                    <span class="text-4xl font-black text-yellow-500 mb-4 block">06</span>
                    <h4 class="text-xl font-bold text-white mb-3">Produk Premium</h4>
                    <p class="text-slate-400 text-sm leading-relaxed">Kami menggunakan produk dan hardware jaringan
                        dengan kualitas terbaik di kelasnya.</p>
                </div>
            </div>
        </div>
    </section>

    <footer id="kontak" class="bg-slate-950 text-slate-400 py-12 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 justify-between items-center gap-8">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <img src="/storage/img/LOGOMGD.png" alt="Logo MGD"
                            class="h-10 w-auto opacity-80 hover:opacity-100 transition">
                        <span class="font-black text-xl text-white">PT. Mandiri Global Data</span>
                    </div>
                    <p class="text-sm max-w-md">Perusahaan penyedia infrastruktur IT, Telekomunikasi, dan Jasa
                        Konstruksi terdepan di Kota Makassar.</p>
                </div>
                <div class="md:text-right">
                    <h4 class="text-white font-bold mb-4">Kontak Kami</h4>
                    <p class="text-sm mb-2">Makassar, Sulawesi Selatan, Indonesia</p>
                    <a href="mailto:info@mandiriglobaldata.co.id"
                        class="text-amber-500 hover:text-amber-400 font-bold block mb-1">info@mandiriglobaldata.co.id</a>
                </div>
            </div>
            <div
                class="border-t border-slate-800 mt-10 pt-8 flex flex-col md:flex-row justify-between items-center text-sm">
                <p>&copy; {{ date('Y') }} PT. Mandiri Global Data. Hak Cipta Dilindungi.</p>
                <p class="mt-4 md:mt-0">Sistem Manajemen Didukung oleh <span
                        class="text-amber-500 font-bold">NetManager</span></p>
            </div>
        </div>
    </footer>

    <script>
        tsParticles.load("tsparticles", {
            // MENCEGAH ANIMASI MUNCUL DI SELURUH HALAMAN (Hanya di dalam div #tsparticles)
            fullScreen: {
                enable: false
            },
            fpsLimit: 120,
            interactivity: {
                events: {
                    onClick: {
                        enable: true,
                        mode: "push",
                    },
                    onHover: {
                        enable: true,
                        mode: "grab",
                    },
                    resize: true,
                },
                modes: {
                    push: {
                        quantity: 3
                    },
                    grab: {
                        distance: 200,
                        link_opacity: 0.6,
                    },
                },
            },
            particles: {
                color: {
                    value: "#FCD34D", // EMAS
                },
                links: {
                    color: "#FCD34D", // EMAS
                    distance: 150,
                    enable: true,
                    opacity: 0.3,
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
                    value: 60,
                },
                opacity: {
                    value: 0.4
                },
                shape: {
                    type: "circle"
                },
                size: {
                    value: {
                        min: 1,
                        max: 2
                    }
                },
            },
            detectRetina: true,
        });
    </script>
</body>

</html>
