<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO --}}
    <title>@yield('title', 'SMeCK Hooligan — Website Resmi Suporter PSMS Medan')</title>
    <meta name="description" content="@yield('meta_description', 'SMeCK Hooligan — Komunitas Resmi Suporter PSMS Medan. Salam Sada Roha! Bergabung bersama kami mendukung kesebelasan kebanggaan Kota Medan.')">
    <meta name="keywords" content="SMeCK Hooligan, PSMS Medan, Suporter, Ultras, Medan, Salam Sada Roha">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    {{-- Favicon --}}
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo-crest.jpg') }}">

    {{-- Heroicons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="bg-smeck-dark-950 text-white font-sans antialiased min-h-screen flex flex-col">

    {{-- ===== NAVBAR ===== --}}
    <nav id="navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-18 lg:h-20">

                {{-- Logo --}}
                <a href="{{ url('/') }}" class="flex items-center gap-3 group" id="nav-logo">
                    <div class="w-10 h-10 lg:w-11 lg:h-11 rounded-xl overflow-hidden border border-smeck-green-500/40 shadow-lg group-hover:scale-105 transition-transform duration-300 bg-black flex items-center justify-center p-0.5">
                        <img src="{{ asset('images/logo-crest.jpg') }}" alt="SMeCK Hooligan" class="w-full h-full object-cover rounded-lg">
                    </div>
                    <div>
                        <span class="font-display font-bold text-lg lg:text-xl text-white tracking-tight">SMeCK</span>
                        <span class="font-display font-bold text-lg lg:text-xl text-smeck-green-400 tracking-tight"> Hooligan</span>
                    </div>
                </a>

                {{-- Desktop Nav Links --}}
                <div class="hidden lg:flex items-center gap-1">
                    <a href="{{ url('/') }}" class="nav-link px-4 py-2 rounded-lg text-sm font-medium text-white/80 hover:text-white hover:bg-white/10 transition-all duration-300" id="nav-beranda">Beranda</a>
                    <a href="#berita" class="nav-link px-4 py-2 rounded-lg text-sm font-medium text-white/80 hover:text-white hover:bg-white/10 transition-all duration-300" id="nav-berita">Berita</a>
                    <a href="#jadwal" class="nav-link px-4 py-2 rounded-lg text-sm font-medium text-white/80 hover:text-white hover:bg-white/10 transition-all duration-300" id="nav-jadwal">Jadwal</a>
                    <a href="#" class="nav-link px-4 py-2 rounded-lg text-sm font-medium text-white/80 hover:text-white hover:bg-white/10 transition-all duration-300" id="nav-store">SMeCK Store</a>
                </div>

                {{-- Auth Buttons --}}
                <div class="hidden lg:flex items-center gap-3">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 rounded-xl bg-smeck-green-500 text-white text-sm font-semibold hover:bg-smeck-green-400 transition-all duration-300 btn-glow" id="nav-dashboard">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ url('/login') }}" class="px-5 py-2.5 rounded-xl border border-white/20 text-white text-sm font-medium hover:bg-white/10 transition-all duration-300" id="nav-login">
                            Masuk
                        </a>
                        <a href="{{ url('/register') }}" class="px-5 py-2.5 rounded-xl bg-smeck-green-500 text-white text-sm font-semibold hover:bg-smeck-green-400 transition-all duration-300 btn-glow" id="nav-register">
                            Daftar
                        </a>
                    @endauth
                </div>

                {{-- Mobile Menu Toggle --}}
                <button id="mobile-menu-btn" class="lg:hidden p-2 rounded-lg text-white/80 hover:bg-white/10 transition-all" onclick="toggleMobileMenu()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path id="menu-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile Menu Panel --}}
        <div id="mobile-menu" class="lg:hidden hidden glass-dark border-t border-white/10">
            <div class="px-4 py-4 space-y-2">
                <a href="{{ url('/') }}" class="block px-4 py-3 rounded-lg text-white/80 hover:text-white hover:bg-white/10 transition-all font-medium">Beranda</a>
                <a href="#berita" class="block px-4 py-3 rounded-lg text-white/80 hover:text-white hover:bg-white/10 transition-all font-medium">Berita</a>
                <a href="#jadwal" class="block px-4 py-3 rounded-lg text-white/80 hover:text-white hover:bg-white/10 transition-all font-medium">Jadwal</a>
                <a href="#" class="block px-4 py-3 rounded-lg text-white/80 hover:text-white hover:bg-white/10 transition-all font-medium">SMeCK Store</a>
                <div class="pt-3 border-t border-white/10 space-y-2">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="block px-4 py-3 rounded-lg bg-smeck-green-500 text-white text-center font-semibold">Dashboard</a>
                    @else
                        <a href="{{ url('/login') }}" class="block px-4 py-3 rounded-lg border border-white/20 text-white text-center font-medium">Masuk</a>
                        <a href="{{ url('/register') }}" class="block px-4 py-3 rounded-lg bg-smeck-green-500 text-white text-center font-semibold">Daftar Sekarang</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- ===== MAIN CONTENT ===== --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- ===== FOOTER ===== --}}
    <footer class="bg-smeck-dark-950 border-t border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Footer Main --}}
            <div class="py-12 lg:py-16 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">
                {{-- Brand --}}
                <div class="lg:col-span-1">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl overflow-hidden border border-smeck-green-500/30 bg-black flex items-center justify-center p-0.5">
                            <img src="{{ asset('images/logo-crest.jpg') }}" alt="SMeCK Hooligan" class="w-full h-full object-cover rounded-lg">
                        </div>
                        <div>
                            <span class="font-display font-bold text-lg text-white">SMeCK</span>
                            <span class="font-display font-bold text-lg text-smeck-green-400"> Hooligan</span>
                        </div>
                    </div>
                    <p class="text-white/50 text-sm leading-relaxed mb-4">
                        Komunitas resmi suporter PSMS Medan. Bersatu dalam satu tekad, satu semangat untuk kesebelasan kebanggaan Kota Medan.
                    </p>
                    <p class="text-smeck-green-400 font-display font-bold text-sm italic">"Salam Sada Roha!"</p>
                </div>

                {{-- Quick Links --}}
                <div>
                    <h4 class="font-display font-semibold text-white mb-4">Menu</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ url('/') }}" class="text-white/50 hover:text-smeck-green-400 transition-colors text-sm">Beranda</a></li>
                        <li><a href="#berita" class="text-white/50 hover:text-smeck-green-400 transition-colors text-sm">Berita Terkini</a></li>
                        <li><a href="#jadwal" class="text-white/50 hover:text-smeck-green-400 transition-colors text-sm">Jadwal Pertandingan</a></li>
                        <li><a href="#" class="text-white/50 hover:text-smeck-green-400 transition-colors text-sm">SMeCK Store</a></li>
                    </ul>
                </div>

                {{-- Info --}}
                <div>
                    <h4 class="font-display font-semibold text-white mb-4">Organisasi</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ url('/register') }}" class="text-white/50 hover:text-smeck-green-400 transition-colors text-sm">Gabung Anggota</a></li>
                        <li><a href="#" class="text-white/50 hover:text-smeck-green-400 transition-colors text-sm">Tentang Kami</a></li>
                        <li><a href="#" class="text-white/50 hover:text-smeck-green-400 transition-colors text-sm">Struktur Organisasi</a></li>
                        <li><a href="#" class="text-white/50 hover:text-smeck-green-400 transition-colors text-sm">Hubungi Kami</a></li>
                    </ul>
                </div>

                {{-- Social --}}
                <div>
                    <h4 class="font-display font-semibold text-white mb-4">Ikuti Kami</h4>
                    <div class="flex gap-3">
                        <a href="#" class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center text-white/50 hover:bg-smeck-green-500 hover:text-white transition-all duration-300">
                            <i class="fab fa-instagram text-lg"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center text-white/50 hover:bg-smeck-green-500 hover:text-white transition-all duration-300">
                            <i class="fab fa-twitter text-lg"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center text-white/50 hover:bg-smeck-green-500 hover:text-white transition-all duration-300">
                            <i class="fab fa-youtube text-lg"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center text-white/50 hover:bg-smeck-green-500 hover:text-white transition-all duration-300">
                            <i class="fab fa-tiktok text-lg"></i>
                        </a>
                    </div>
                    <div class="mt-6 p-4 rounded-xl bg-white/5 border border-white/10">
                        <p class="text-white/40 text-xs mb-1">Status Keanggotaan</p>
                        <a href="{{ url('/login') }}" class="text-smeck-green-400 text-sm font-medium hover:underline">Cek di sini →</a>
                    </div>
                </div>
            </div>

            {{-- Footer Bottom --}}
            <div class="py-6 border-t border-white/5 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-white/30 text-xs">&copy; {{ date('Y') }} SMeCK Hooligan. All rights reserved.</p>
                <p class="text-white/30 text-xs">Dibuat dengan <span class="text-red-500">❤</span> untuk PSMS Medan</p>
            </div>
        </div>
    </footer>

    {{-- ===== SCRIPTS ===== --}}
    <script>
        // Navbar scroll effect
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('glass-dark', 'shadow-2xl');
            } else {
                navbar.classList.remove('glass-dark', 'shadow-2xl');
            }
        });

        // Mobile menu toggle
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }

        // Close mobile menu on link click
        document.querySelectorAll('#mobile-menu a').forEach(link => {
            link.addEventListener('click', () => {
                document.getElementById('mobile-menu').classList.add('hidden');
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
