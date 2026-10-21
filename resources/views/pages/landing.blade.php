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
            <a href="#cek-kta" id="hero-cta-status" class="group px-8 py-4 rounded-2xl border-2 border-white/20 text-white font-bold text-lg hover:bg-white/10 hover:border-smeck-green-400 hover:text-smeck-green-400 transition-all duration-300 flex items-center gap-3 w-full sm:w-auto justify-center">
                <i class="fa-solid fa-wifi text-smeck-green-400 text-base"></i>
                <span>Tap / Cek KTA NFC</span>
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

{{-- ===== CEK KEASLIAN KTA & TAP NFC SECTION ===== --}}
<section id="cek-kta" class="py-20 lg:py-28 relative scroll-mt-20 overflow-hidden bg-gradient-to-b from-smeck-dark-950 via-smeck-dark-900/90 to-smeck-dark-950">
    {{-- Glow background lights --}}
    <div class="absolute top-1/2 left-1/4 -translate-y-1/2 w-96 h-96 bg-smeck-green-600/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-10 right-1/4 w-80 h-80 bg-smeck-gold/5 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        {{-- Section Header --}}
        <div class="text-center max-w-3xl mx-auto mb-16">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-smeck-green-500/10 border border-smeck-green-500/20 text-smeck-green-400 text-xs font-bold uppercase tracking-wider mb-4 animate-fade-in">
                <i class="fa-solid fa-wifi animate-pulse"></i>
                <span>Sistem KTA NFC Pintar Tribun SMeCK</span>
            </div>
            <h2 class="font-display font-black text-3xl sm:text-4xl lg:text-5xl text-white tracking-tight leading-tight mb-4">
                Tap KTA NFC & <span class="text-gradient-smeck">Verifikasi Suporter</span>
            </h2>
            <p class="text-white/60 text-sm sm:text-base leading-relaxed">
                Cek keaslian kartu, asal basis chapter, dan identitas resmi suporter SMeCK Hooligan secara instan.
                Tempelkan kartu fisik ke sensor HP atau masukkan nomor UID / NIA di bawah ini.
            </p>
        </div>

        {{-- Grid: Scanner / Input (Left) & Holographic KTA Reveal (Right) --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            {{-- KOLOM KIRI: Scanner & Form Pencarian (5 Cols) --}}
            <div class="lg:col-span-5 space-y-6">

                {{-- Interactive NFC Radar Card --}}
                <div class="p-6 sm:p-8 rounded-3xl bg-gradient-to-br from-smeck-dark-900 to-smeck-dark-950 border border-white/10 shadow-2xl relative overflow-hidden group">
                    <div class="absolute -right-8 -top-8 w-32 h-32 bg-smeck-green-500/10 rounded-full blur-2xl group-hover:bg-smeck-green-500/20 transition-all"></div>

                    {{-- Visual Radar Sensor --}}
                    <div class="text-center py-6">
                        <div class="relative w-28 h-28 mx-auto flex items-center justify-center mb-5">
                            {{-- Ripple Wave 1 --}}
                            <div class="absolute inset-0 rounded-full border-2 border-smeck-green-500/20 animate-ping" style="animation-duration: 2.5s;"></div>
                            {{-- Ripple Wave 2 --}}
                            <div class="absolute inset-2 rounded-full border border-smeck-green-400/30 animate-pulse"></div>
                            {{-- Central Icon --}}
                            <div class="w-16 h-16 rounded-2xl bg-smeck-green-600/30 border border-smeck-green-500/50 flex items-center justify-center text-smeck-green-400 text-2xl shadow-lg shadow-smeck-green-700/30">
                                <i class="fa-solid fa-wifi rotate-90"></i>
                            </div>
                        </div>

                        <h3 class="font-display font-bold text-lg text-white mb-1">Sensor NFC Smartphone</h3>
                        <p class="text-xs text-white/50 max-w-xs mx-auto mb-5">
                            Jika membuka lewat Android Chrome (HTTPS), aktifkan tombol di bawah lalu tempelkan kartu fisik ke bodi belakang HP Anda.
                        </p>

                        <button id="btn-start-nfc" onclick="startWebNfcScan()" class="w-full py-3.5 px-6 rounded-2xl bg-smeck-green-600 hover:bg-smeck-green-500 text-white font-bold text-xs shadow-lg shadow-smeck-green-700/40 transition-all flex items-center justify-center gap-2 group/btn">
                            <i class="fa-solid fa-mobile-screen text-sm group-hover/btn:scale-110 transition-transform"></i>
                            <span id="btn-nfc-text">🔴 Aktifkan Sensor NFC (Tap HP)</span>
                        </button>
                    </div>

                    {{-- Status Reader Text --}}
                    <div id="nfc-status-box" class="p-3 rounded-xl bg-white/5 border border-white/10 text-center text-xs text-white/60">
                        <span id="nfc-status-msg"><i class="fa-solid fa-circle-info text-smeck-green-400 mr-1.5"></i>Sensor dalam mode siaga.</span>
                    </div>
                </div>

                {{-- Input Manual UID / NIA Card --}}
                <div class="p-6 sm:p-8 rounded-3xl bg-smeck-dark-900 border border-white/10 shadow-2xl">
                    <h4 class="font-display font-bold text-sm text-white uppercase tracking-wider mb-2 flex items-center gap-2">
                        <i class="fa-solid fa-keyboard text-amber-400"></i>
                        <span>Atau Masukkan UID / NIA Suporter</span>
                    </h4>
                    <p class="text-xs text-white/50 mb-4">Gunakan kolom ini jika mengakses lewat Laptop/PC atau smartphone tanpa sensor NFC.</p>

                    <form id="form-check-kta" onsubmit="event.preventDefault(); submitManualCheck();" class="space-y-3">
                        <div class="relative">
                            <input type="text" id="input-query-kta" required placeholder="UID: 04:A2:8B:1A... atau NIA: SMCK-..." class="w-full px-4 py-3 bg-white/5 border border-white/15 rounded-xl text-white font-mono text-sm placeholder:text-white/30 focus:border-smeck-green-400 focus:outline-none transition-colors">
                            <button type="submit" id="btn-submit-manual" class="absolute right-2 top-2 px-4 py-1.5 rounded-lg bg-smeck-green-600 hover:bg-smeck-green-500 text-white font-bold text-xs shadow transition-colors flex items-center gap-1.5">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                <span>Cek</span>
                            </button>
                        </div>
                    </form>

                    {{-- Quick Demo Chips --}}
                    <div class="mt-4 pt-4 border-t border-white/5">
                        <div class="text-[11px] text-white/40 mb-2 font-semibold">Coba Sampel Data Suporter:</div>
                        <div class="flex flex-wrap gap-2">
                            <button onclick="checkSample('6032-9800-2024-1950')" class="px-2.5 py-1 rounded-lg bg-white/5 hover:bg-white/10 text-white/70 hover:text-white border border-white/10 text-[11px] font-mono transition-colors">
                                🟢 Muhammad Syafiq (No. KTA)
                            </button>
                            <button onclick="checkSample('SMCK-2015-0245')" class="px-2.5 py-1 rounded-lg bg-white/5 hover:bg-white/10 text-white/70 hover:text-white border border-white/10 text-[11px] font-mono transition-colors">
                                🟡 Korwil Tembung (NIA)
                            </button>
                            <button onclick="checkSample('SMCK-2003-0001')" class="px-2.5 py-1 rounded-lg bg-white/5 hover:bg-white/10 text-white/70 hover:text-white border border-white/10 text-[11px] font-mono transition-colors">
                                🔴 Ketua Umum DPP
                            </button>
                        </div>
                    </div>
                </div>

            </div>

            {{-- KOLOM KANAN: Hasil Verifikasi KTA (7 Cols) --}}
            <div class="lg:col-span-7">

                {{-- State 1: Placeholder Belum Ada Kartu Di-Scan --}}
                <div id="kta-placeholder" class="p-8 sm:p-12 rounded-3xl bg-smeck-dark-900/60 border-2 border-dashed border-white/10 text-center flex flex-col items-center justify-center min-h-[460px]">
                    <div class="w-20 h-20 rounded-3xl bg-white/5 border border-white/10 flex items-center justify-center text-white/30 text-3xl mb-4">
                        <i class="fa-solid fa-id-card"></i>
                    </div>
                    <h4 class="font-display font-bold text-lg text-white/70 mb-2">Menunggu Kartu di-Tap / Diperiksa</h4>
                    <p class="text-xs text-white/40 max-w-sm mx-auto leading-relaxed">
                        Tempelkan kartu fisik KTA NFC ke bodi belakang ponsel Anda atau masukkan nomor UID / NIA di sebelah kiri untuk menampilkan identitas resmi suporter.
                    </p>
                </div>

                {{-- State 2: Loading State --}}
                <div id="kta-loading" class="hidden p-8 sm:p-12 rounded-3xl bg-smeck-dark-900 border border-white/10 text-center flex flex-col items-center justify-center min-h-[460px]">
                    <div class="w-16 h-16 rounded-full border-4 border-smeck-green-500/20 border-t-smeck-green-400 animate-spin mb-4"></div>
                    <h4 class="font-display font-bold text-base text-white">Memverifikasi Data ke Server SMeCK...</h4>
                    <p class="text-xs text-white/40 mt-1">Mencocokkan nomor chip dengan basis chapter suporter</p>
                </div>

                {{-- State 3: Tampilan Kartu KTA Digital Interaktif (Reveal Card) --}}
                <div id="kta-result-card" class="hidden space-y-4">
                    {{-- Status Banner --}}
                    <div id="kta-banner-status" class="p-4 rounded-2xl bg-smeck-green-500/20 border border-smeck-green-500/30 text-smeck-green-300 text-xs font-semibold flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i id="kta-banner-icon" class="fa-solid fa-circle-check text-base text-smeck-green-400"></i>
                            <span id="kta-banner-text">ANGGOTA RESMI TERVERIFIKASI DPP SMeCK HOOLIGAN</span>
                        </div>
                        <span class="text-[10px] font-mono opacity-60">LIVE VERIFIED</span>
                    </div>

                    {{-- 3D-Look Physical KTA Digital Card --}}
                    <div class="p-6 sm:p-8 rounded-3xl bg-gradient-to-br from-smeck-green-950 via-smeck-dark-900 to-black border-2 border-smeck-green-500/50 shadow-2xl relative overflow-hidden group">
                        {{-- Holographic overlay glares --}}
                        <div class="absolute -right-20 -top-20 w-80 h-80 bg-smeck-green-500/15 rounded-full blur-3xl pointer-events-none"></div>
                        <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-smeck-gold/10 rounded-full blur-3xl pointer-events-none"></div>

                        {{-- Card Header: Logos & Org Name --}}
                        <div class="flex items-start justify-between gap-4 mb-6 relative z-10">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl overflow-hidden border border-smeck-green-400/40 bg-black p-0.5 shadow-lg shrink-0">
                                    <img src="{{ asset('images/logo-crest.jpg') }}" alt="SMeCK" class="w-full h-full object-cover rounded-lg">
                                </div>
                                <div>
                                    <div class="text-[10px] font-extrabold uppercase tracking-widest text-smeck-gold">Kartu Tanda Anggota Resmi</div>
                                    <div class="font-display font-black text-lg text-white leading-tight">SMeCK HOOLIGAN</div>
                                    <div class="text-[10px] text-white/50 tracking-wider">SUPORTER PSMS MEDAN • SEJAK 2003</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-smeck-green-500/20 text-smeck-green-400 border border-smeck-green-500/30">
                                    NFC VERIFIED
                                </span>
                            </div>
                        </div>

                        {{-- Card Body: Chip Graphic + Supporter Details --}}
                        <div class="space-y-5 relative z-10">
                            {{-- Chip EMV & Card Number --}}
                            <div class="flex items-center justify-between">
                                {{-- Realistic EMV Gold Smart Chip --}}
                                <div class="w-12 h-9 rounded-lg bg-gradient-to-br from-amber-200 via-amber-400 to-yellow-600 border border-amber-300/80 shadow-md flex items-center justify-center p-1 relative">
                                    <div class="w-full h-full border border-amber-800/40 rounded flex flex-col justify-around py-0.5">
                                        <div class="w-full h-px bg-amber-800/40"></div>
                                        <div class="w-full h-px bg-amber-800/40"></div>
                                    </div>
                                </div>
                                <div class="text-right font-mono text-xs text-white/70">
                                    <span class="text-white/40 text-[10px] uppercase block">Nomor Kartu KTA:</span>
                                    <span id="res-card-number" class="text-smeck-gold font-bold tracking-widest text-sm"></span>
                                </div>
                            </div>

                            {{-- Nama Suporter & NIA --}}
                            <div class="pt-2">
                                <div class="text-[10px] font-bold uppercase tracking-wider text-white/40 mb-0.5">Nama Suporter</div>
                                <div id="res-name" class="font-display font-black text-2xl sm:text-3xl text-white tracking-tight"></div>
                                <div class="font-mono text-xs sm:text-sm text-smeck-gold font-bold mt-1 flex items-center gap-1.5">
                                    <i class="fa-solid fa-id-badge text-xs"></i>
                                    <span id="res-nia"></span>
                                </div>
                            </div>

                            {{-- Chapter / Basis Wilayah (Highlights the user request!) --}}
                            <div class="p-4 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-sm">
                                <div class="text-[10px] font-bold uppercase tracking-wider text-amber-400 mb-1 flex items-center gap-1.5">
                                    <i class="fa-solid fa-location-dot"></i>
                                    <span>Basis Wilayah / Chapter Asal</span>
                                </div>
                                <div id="res-chapter" class="font-display font-black text-lg text-white"></div>
                                <div id="res-chapter-address" class="text-xs text-white/50 mt-0.5"></div>
                                <div class="flex items-center gap-4 text-[11px] text-white/40 mt-2 pt-2 border-t border-white/5">
                                    <div>Kode Wilayah: <span id="res-chapter-code" class="text-white font-mono font-bold"></span></div>
                                    <div>Korwil: <span id="res-chapter-leader" class="text-white font-semibold"></span></div>
                                </div>
                            </div>

                            {{-- Grid Data Tribun: Gol Darah & Bergabung --}}
                            <div class="grid grid-cols-3 gap-3 pt-1">
                                <div class="p-3 rounded-xl bg-white/5 border border-white/5 text-center">
                                    <div class="text-[10px] text-white/40 uppercase">Gol. Darah</div>
                                    <div id="res-blood" class="text-sm font-bold text-red-400 mt-0.5"></div>
                                </div>
                                <div class="p-3 rounded-xl bg-white/5 border border-white/5 text-center">
                                    <div class="text-[10px] text-white/40 uppercase">Ukuran Kaos</div>
                                    <div id="res-shirt" class="text-sm font-bold text-white mt-0.5"></div>
                                </div>
                                <div class="p-3 rounded-xl bg-white/5 border border-white/5 text-center">
                                    <div class="text-[10px] text-white/40 uppercase">Bergabung</div>
                                    <div id="res-joined" class="text-xs font-semibold text-white/80 mt-1 truncate"></div>
                                </div>
                            </div>
                        </div>

                        {{-- Card Footer --}}
                        <div class="mt-6 pt-4 border-t border-white/10 flex items-center justify-between text-[11px] text-white/40 relative z-10">
                            <div>Status KTA: <span id="res-status" class="font-bold text-smeck-green-400">AKTIF</span></div>
                            <div class="font-mono text-smeck-gold italic">"Salam Sada Roha!"</div>
                        </div>
                    </div>
                </div>

                {{-- State 4: Error State (Not Found) --}}
                <div id="kta-error" class="hidden p-8 sm:p-12 rounded-3xl bg-red-950/30 border border-red-500/30 text-center flex flex-col items-center justify-center min-h-[460px]">
                    <div class="w-16 h-16 rounded-2xl bg-red-500/20 text-red-400 flex items-center justify-center text-3xl mb-4">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <h4 class="font-display font-bold text-lg text-white mb-2">Data Suporter Tidak Ditemukan</h4>
                    <p id="kta-error-msg" class="text-xs text-white/50 max-w-sm mx-auto leading-relaxed mb-6">
                        Nomor kartu UID atau NIA tidak terdaftar dalam database resmi SMeCK Hooligan.
                    </p>
                    <button onclick="resetCheckForm()" class="px-5 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-semibold text-xs transition-colors">
                        Coba Periksa Kembali
                    </button>
                </div>

            </div>

        </div>

    </div>
</section>

{{-- Script Logika Cek KTA & Web NFC --}}
<script>
    let nfcScanActive = false;

    // Fungsi Fetch ke Backend API
    async function fetchKtaData(query) {
        showKtaState('loading');

        try {
            const response = await fetch(`/api/check-kta?query=${encodeURIComponent(query)}`);
            const result = await response.json();

            if (result.success && result.data) {
                renderKtaResult(result.data);
                playSuccessSound();
            } else {
                showKtaError(result.message || 'Data suporter tidak ditemukan.');
            }
        } catch (error) {
            console.error('Error fetching KTA data:', error);
            showKtaError('Gagal menghubungi server database SMeCK. Periksa koneksi internet Anda.');
        }
    }

    // Tampilkan Hasil Kartu Suporter
    function renderKtaResult(data) {
        document.getElementById('res-name').innerText = data.name;
        document.getElementById('res-nia').innerText = data.nia;
        document.getElementById('res-chapter').innerText = data.chapter;
        document.getElementById('res-chapter-address').innerText = data.chapter_address;
        document.getElementById('res-chapter-code').innerText = data.chapter_code;
        document.getElementById('res-chapter-leader').innerText = data.chapter_leader;
        document.getElementById('res-card-number').innerText = data.card_number_masked;
        document.getElementById('res-blood').innerText = data.blood_type;
        document.getElementById('res-shirt').innerText = data.shirt_size;
        document.getElementById('res-joined').innerText = data.joined_date;

        const bannerBox = document.getElementById('kta-banner-status');
        const bannerIcon = document.getElementById('kta-banner-icon');
        const bannerText = document.getElementById('kta-banner-text');
        const statusLabel = document.getElementById('res-status');

        if (data.card_status === 'blocked') {
            bannerBox.className = 'p-4 rounded-2xl bg-red-500/20 border border-red-500/40 text-red-300 text-xs font-semibold flex items-center justify-between animate-pulse';
            bannerIcon.className = 'fa-solid fa-ban text-base text-red-400';
            bannerText.innerText = 'PERINGATAN: KARTU INI DILAPORKAN HILANG / DIBLOKIR!';
            statusLabel.className = 'font-bold text-red-400';
            statusLabel.innerText = 'DIBLOKIR / HILANG';
        } else {
            bannerBox.className = 'p-4 rounded-2xl bg-smeck-green-500/20 border border-smeck-green-500/30 text-smeck-green-300 text-xs font-semibold flex items-center justify-between';
            bannerIcon.className = 'fa-solid fa-circle-check text-base text-smeck-green-400';
            bannerText.innerText = 'ANGGOTA RESMI TERVERIFIKASI DPP SMeCK HOOLIGAN';
            statusLabel.className = 'font-bold text-smeck-green-400';
            statusLabel.innerText = 'AKTIF & BERLAKU RESMI';
        }

        showKtaState('result');

        // Otomatis langsung scroll ke tampilan identitas KTA suporter tanpa perlu scroll manual
        setTimeout(() => {
            const cardEl = document.getElementById('kta-result-card');
            if (cardEl) {
                cardEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
                cardEl.classList.add('ring-4', 'ring-smeck-green-400/60', 'transition-all');
                setTimeout(() => cardEl.classList.remove('ring-4', 'ring-smeck-green-400/60'), 1500);
            }
        }, 100);
    }

    // Switch Tampilan State
    function showKtaState(state) {
        document.getElementById('kta-placeholder').classList.add('hidden');
        document.getElementById('kta-loading').classList.add('hidden');
        document.getElementById('kta-result-card').classList.add('hidden');
        document.getElementById('kta-error').classList.add('hidden');

        if (state === 'placeholder') document.getElementById('kta-placeholder').classList.remove('hidden');
        if (state === 'loading') {
            document.getElementById('kta-loading').classList.remove('hidden');
            // Jika di mobile, scroll juga ke loading box
            if (window.innerWidth < 1024) {
                document.getElementById('kta-loading').scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
        if (state === 'result') document.getElementById('kta-result-card').classList.remove('hidden');
        if (state === 'error') {
            document.getElementById('kta-error').classList.remove('hidden');
            if (window.innerWidth < 1024) {
                document.getElementById('kta-error').scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    }

    function showKtaError(msg) {
        document.getElementById('kta-error-msg').innerText = msg;
        showKtaState('error');
    }

    function resetCheckForm() {
        showKtaState('placeholder');
        document.getElementById('input-query-kta').value = '';
    }

    function submitManualCheck() {
        const query = document.getElementById('input-query-kta').value.trim();
        if (query) {
            fetchKtaData(query);
        }
    }

    function checkSample(query) {
        document.getElementById('input-query-kta').value = query;
        fetchKtaData(query);
    }

    // Web NFC Reader API untuk Smartphone
    async function startWebNfcScan() {
        const statusMsg = document.getElementById('nfc-status-msg');
        const btnText = document.getElementById('btn-nfc-text');

        if (!('NDEFReader' in window)) {
            statusMsg.innerHTML = '<span class="text-amber-400"><i class="fa-solid fa-circle-exclamation mr-1"></i>Web NFC hanya didukung di Android Chrome via HTTPS. Gunakan form input manual di bawah!</span>';
            return;
        }

        try {
            const ndef = new NDEFReader();
            await ndef.scan();
            nfcScanActive = true;
            btnText.innerHTML = '<i class="fa-solid fa-spinner animate-spin"></i> Mendengarkan Kartu NFC...';
            statusMsg.innerHTML = '<span class="text-smeck-green-400 font-bold"><i class="fa-solid fa-wifi animate-pulse mr-1"></i>Sensor Siaga: Silakan tempelkan kartu KTA ke belakang ponsel!</span>';

            ndef.onreading = (event) => {
                const serial = event.serialNumber;
                if (serial) {
                    statusMsg.innerHTML = `<span class="text-smeck-green-300 font-bold">Kartu Terbaca: ${serial}! Memuat data...</span>`;
                    document.getElementById('input-query-kta').value = serial;
                    fetchKtaData(serial);
                }
            };

            ndef.onreadingerror = () => {
                statusMsg.innerHTML = '<span class="text-red-400">Gagal membaca chip kartu. Coba tempelkan kembali dengan posisi tepat.</span>';
            };

        } catch (err) {
            console.error('NFC Scan Error:', err);
            statusMsg.innerHTML = `<span class="text-red-400"><i class="fa-solid fa-triangle-exclamation mr-1"></i>Izin NFC ditolak atau tidak aktif: ${err.message}</span>`;
        }
    }

    // Audio Chime Synthesizer saat Kartu Berhasil Terverifikasi
    function playSuccessSound() {
        try {
            const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.type = 'triangle';
            osc.frequency.setValueAtTime(587.33, audioCtx.currentTime); // D5
            osc.frequency.exponentialRampToValueAtTime(880, audioCtx.currentTime + 0.15); // A5
            gain.gain.setValueAtTime(0.15, audioCtx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.35);
            osc.connect(gain);
            gain.connect(audioCtx.destination);
            osc.start();
            osc.stop(audioCtx.currentTime + 0.35);
        } catch(e) {}
    }
</script>

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
