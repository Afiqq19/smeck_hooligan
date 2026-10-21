@extends('layouts.guest')

@section('title', 'SMeCK Hooligan — Salam Sada Roha! | Website Resmi Suporter PSMS Medan')
@section('meta_description', 'Website resmi SMeCK Hooligan, komunitas suporter PSMS Medan. Bergabung bersama ribuan anggota dan dukung PSMS Medan!')

@section('content')

{{-- ===== HERO SECTION ===== --}}
<section id="hero" class="relative min-h-screen flex items-center justify-center overflow-hidden">
    {{-- Background with Real Stadium Choreo Image & Dark Green Overlay --}}
    <div class="absolute inset-0">
        {{-- Real Stadium Photo Background --}}
        <div class="absolute inset-0 z-0 bg-cover bg-center scale-105 transition-transform duration-1000" style="background-image: url('{{ asset('images/hero-stadium.jpg') }}');"></div>

        {{-- Gradient overlays to ensure high contrast and readability --}}
        <div class="absolute inset-0 bg-gradient-to-b from-smeck-dark-950/85 via-smeck-dark-950/70 to-smeck-dark-950 z-10"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-smeck-green-950/60 via-transparent to-smeck-dark-950/60 z-10"></div>
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-transparent via-smeck-dark-950/40 to-smeck-dark-950 z-10"></div>
    </div>

    {{-- Decorative Elements --}}
    <div class="absolute top-1/4 right-10 w-72 h-72 bg-smeck-green-500/10 rounded-full blur-3xl animate-pulse-slow z-10"></div>
    <div class="absolute bottom-1/4 left-10 w-96 h-96 bg-smeck-green-700/5 rounded-full blur-3xl animate-pulse-slow z-10" style="animation-delay: 2s;"></div>

    {{-- Hero Content --}}
    <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center pt-24 pb-16">
        {{-- Badge --}}
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full glass-green mb-8 animate-fade-in">
            <span class="w-2 h-2 bg-smeck-green-400 rounded-full animate-pulse"></span>
            <span class="text-smeck-green-300 text-sm font-medium">Website Resmi SMeCK Hooligan</span>
        </div>

        {{-- Main Heading --}}
        <h1 class="font-display font-black text-5xl sm:text-6xl md:text-7xl lg:text-8xl xl:text-9xl text-white leading-none mb-6 animate-slide-up tracking-tight">
            SALAM<br>
            <span class="text-gradient-smeck">SADA ROHA</span>
        </h1>

        {{-- Subtitle --}}
        <p class="text-white/60 text-lg sm:text-xl lg:text-2xl max-w-2xl mx-auto mb-10 animate-slide-up font-light" style="animation-delay: 0.2s;">
            Bersatu dalam satu hati, satu suara mendukung
            <span class="text-smeck-green-400 font-semibold">PSMS Medan</span>
        </p>

        {{-- CTA Buttons --}}
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 animate-slide-up" style="animation-delay: 0.4s;">
            <a href="{{ url('/register') }}" id="hero-cta-join" class="group px-8 py-4 rounded-2xl bg-smeck-green-500 text-white font-bold text-lg hover:bg-smeck-green-400 transition-all duration-300 btn-glow flex items-center gap-3 w-full sm:w-auto justify-center">
                <span>Gabung Sekarang</span>
                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
            <a href="{{ url('/login') }}" id="hero-cta-status" class="group px-8 py-4 rounded-2xl border-2 border-white/20 text-white font-bold text-lg hover:bg-white/10 hover:border-white/30 transition-all duration-300 flex items-center gap-3 w-full sm:w-auto justify-center">
                <svg class="w-5 h-5 text-smeck-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                </svg>
                <span>Cek Status Anggota</span>
            </a>
        </div>

        {{-- Stats --}}
        <div class="mt-16 grid grid-cols-3 gap-6 max-w-lg mx-auto animate-slide-up" style="animation-delay: 0.6s;">
            <div class="text-center">
                <div class="font-display font-black text-2xl sm:text-3xl text-white">2K+</div>
                <div class="text-white/40 text-xs sm:text-sm mt-1">Anggota</div>
            </div>
            <div class="text-center border-x border-white/10">
                <div class="font-display font-black text-2xl sm:text-3xl text-smeck-green-400">15+</div>
                <div class="text-white/40 text-xs sm:text-sm mt-1">Chapter</div>
            </div>
            <div class="text-center">
                <div class="font-display font-black text-2xl sm:text-3xl text-white">∞</div>
                <div class="text-white/40 text-xs sm:text-sm mt-1">Semangat</div>
            </div>
        </div>
    </div>

    {{-- Scroll Indicator --}}
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-20 animate-float">
        <div class="w-6 h-10 rounded-full border-2 border-white/20 flex items-start justify-center p-1.5">
            <div class="w-1.5 h-3 bg-smeck-green-400 rounded-full animate-bounce"></div>
        </div>
    </div>
</section>

{{-- ===== INFO SECTION ===== --}}
<section id="info" class="py-20 lg:py-28 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Section Header --}}
        <div class="text-center mb-14">
            <span class="inline-block px-4 py-1.5 rounded-full bg-smeck-green-500/10 text-smeck-green-400 text-sm font-medium mb-4">Informasi Terbaru</span>
            <h2 class="font-display font-bold text-3xl sm:text-4xl lg:text-5xl text-white">
                Ikuti Terus <span class="text-gradient-smeck">Perkembangan</span>
            </h2>
        </div>

        {{-- Info Cards Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">

            {{-- Card: Berita Terkini --}}
            <div id="card-berita" class="group relative bg-smeck-dark-800/50 rounded-2xl border border-white/5 p-6 lg:p-8 hover:border-smeck-green-500/30 transition-all duration-500 card-shadow hover:-translate-y-1">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-smeck-green-500 to-smeck-green-300 rounded-t-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                <div class="w-14 h-14 rounded-2xl bg-smeck-green-500/10 flex items-center justify-center mb-6 group-hover:bg-smeck-green-500/20 transition-colors">
                    <svg class="w-7 h-7 text-smeck-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                </div>

                <h3 class="font-display font-bold text-xl text-white mb-3">Berita Terkini</h3>
                <p class="text-white/50 text-sm leading-relaxed mb-6">Update terbaru seputar PSMS Medan, kegiatan SMeCK Hooligan, dan informasi pertandingan.</p>

                <div class="space-y-3">
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-white/5 hover:bg-white/8 transition-colors cursor-pointer">
                        <div class="w-2 h-2 bg-smeck-green-400 rounded-full flex-shrink-0"></div>
                        <span class="text-white/70 text-sm truncate">PSMS Medan siap hadapi laga derby...</span>
                    </div>
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-white/5 hover:bg-white/8 transition-colors cursor-pointer">
                        <div class="w-2 h-2 bg-smeck-green-400 rounded-full flex-shrink-0"></div>
                        <span class="text-white/70 text-sm truncate">Registrasi anggota baru gelombang 3...</span>
                    </div>
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-white/5 hover:bg-white/8 transition-colors cursor-pointer">
                        <div class="w-2 h-2 bg-smeck-gold rounded-full flex-shrink-0"></div>
                        <span class="text-white/70 text-sm truncate">Launching kartu anggota NFC resmi</span>
                    </div>
                </div>

                <a href="#" class="mt-6 inline-flex items-center gap-2 text-smeck-green-400 text-sm font-medium hover:text-smeck-green-300 transition-colors group/link">
                    Lihat Semua Berita
                    <svg class="w-4 h-4 group-hover/link:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            {{-- Card: Jadwal Pertandingan --}}
            <div id="card-jadwal" class="group relative bg-smeck-dark-800/50 rounded-2xl border border-white/5 p-6 lg:p-8 hover:border-smeck-green-500/30 transition-all duration-500 card-shadow hover:-translate-y-1">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-smeck-green-500 to-smeck-green-300 rounded-t-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                <div class="w-14 h-14 rounded-2xl bg-smeck-green-500/10 flex items-center justify-center mb-6 group-hover:bg-smeck-green-500/20 transition-colors">
                    <svg class="w-7 h-7 text-smeck-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>

                <h3 class="font-display font-bold text-xl text-white mb-3">Jadwal Pertandingan</h3>
                <p class="text-white/50 text-sm leading-relaxed mb-6">Jadwal lengkap pertandingan PSMS Medan di berbagai kompetisi musim ini.</p>

                {{-- Next Match Card --}}
                <div class="p-4 rounded-xl bg-gradient-to-br from-smeck-green-900/30 to-smeck-dark-800 border border-smeck-green-500/20 mb-4">
                    <div class="text-smeck-green-400 text-xs font-medium mb-3">PERTANDINGAN SELANJUTNYA</div>
                    <div class="flex items-center justify-between gap-4">
                        <div class="text-center flex-1">
                            <div class="w-12 h-12 mx-auto rounded-xl bg-smeck-green-500/20 flex items-center justify-center mb-2">
                                <span class="font-display font-bold text-sm text-smeck-green-400">PSM</span>
                            </div>
                            <span class="text-white text-xs font-medium">PSMS Medan</span>
                        </div>
                        <div class="text-center">
                            <div class="text-white/30 text-xs mb-1">VS</div>
                            <div class="text-white font-display font-bold text-lg">19:00</div>
                            <div class="text-white/40 text-xs">WIB</div>
                        </div>
                        <div class="text-center flex-1">
                            <div class="w-12 h-12 mx-auto rounded-xl bg-white/10 flex items-center justify-center mb-2">
                                <span class="font-display font-bold text-sm text-white/50">AWY</span>
                            </div>
                            <span class="text-white text-xs font-medium">Away Team</span>
                        </div>
                    </div>
                </div>

                <a href="#" class="inline-flex items-center gap-2 text-smeck-green-400 text-sm font-medium hover:text-smeck-green-300 transition-colors group/link">
                    Lihat Jadwal Lengkap
                    <svg class="w-4 h-4 group-hover/link:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            {{-- Card: Klasemen --}}
            <div id="card-klasemen" class="group relative bg-smeck-dark-800/50 rounded-2xl border border-white/5 p-6 lg:p-8 hover:border-smeck-green-500/30 transition-all duration-500 card-shadow hover:-translate-y-1 md:col-span-2 lg:col-span-1">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-smeck-green-500 to-smeck-green-300 rounded-t-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                <div class="w-14 h-14 rounded-2xl bg-smeck-green-500/10 flex items-center justify-center mb-6 group-hover:bg-smeck-green-500/20 transition-colors">
                    <svg class="w-7 h-7 text-smeck-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                </div>

                <h3 class="font-display font-bold text-xl text-white mb-3">Klasemen</h3>
                <p class="text-white/50 text-sm leading-relaxed mb-6">Pantau posisi PSMS Medan di klasemen kompetisi berjalan.</p>

                {{-- Mini Standings --}}
                <div class="space-y-2">
                    <div class="flex items-center justify-between p-3 rounded-xl bg-smeck-green-500/10 border border-smeck-green-500/20">
                        <div class="flex items-center gap-3">
                            <span class="w-6 h-6 rounded-full bg-smeck-green-500 flex items-center justify-center text-xs font-bold text-white">1</span>
                            <span class="text-white font-medium text-sm">PSMS Medan</span>
                        </div>
                        <span class="text-smeck-green-400 font-display font-bold text-sm">45 pts</span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-xl bg-white/5">
                        <div class="flex items-center gap-3">
                            <span class="w-6 h-6 rounded-full bg-white/10 flex items-center justify-center text-xs font-bold text-white/60">2</span>
                            <span class="text-white/60 text-sm">Tim B</span>
                        </div>
                        <span class="text-white/40 font-display font-bold text-sm">42 pts</span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-xl bg-white/5">
                        <div class="flex items-center gap-3">
                            <span class="w-6 h-6 rounded-full bg-white/10 flex items-center justify-center text-xs font-bold text-white/60">3</span>
                            <span class="text-white/60 text-sm">Tim C</span>
                        </div>
                        <span class="text-white/40 font-display font-bold text-sm">40 pts</span>
                    </div>
                </div>

                <a href="#" class="mt-6 inline-flex items-center gap-2 text-smeck-green-400 text-sm font-medium hover:text-smeck-green-300 transition-colors group/link">
                    Lihat Klasemen Lengkap
                    <svg class="w-4 h-4 group-hover/link:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ===== CTA SECTION ===== --}}
<section class="py-20 relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-r from-smeck-green-900/20 to-transparent"></div>
    <div class="absolute top-0 right-0 w-96 h-96 bg-smeck-green-500/5 rounded-full blur-3xl"></div>

    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="font-display font-bold text-3xl sm:text-4xl lg:text-5xl text-white mb-6">
            Siap Jadi Bagian dari <span class="text-gradient-smeck">SMeCK?</span>
        </h2>
        <p class="text-white/50 text-lg mb-10 max-w-2xl mx-auto">
            Daftar sekarang dan dapatkan kartu anggota NFC resmi. Satu kartu, satu identitas, satu keluarga besar.
        </p>
        <a href="{{ url('/register') }}" class="inline-flex items-center gap-3 px-10 py-5 rounded-2xl bg-smeck-green-500 text-white font-bold text-lg hover:bg-smeck-green-400 transition-all duration-300 btn-glow">
            <span>Daftar Sekarang</span>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
        </a>
    </div>
</section>

@endsection
