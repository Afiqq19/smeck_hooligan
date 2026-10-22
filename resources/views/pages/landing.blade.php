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

                <h3 class="font-display font-bold text-xl text-white mb-2">Jadwal Pertandingan</h3>
                <p class="text-white/50 text-sm leading-relaxed mb-5">Jadwal resmi laga PSMS Medan & kuota tiket tribun suporter SMeCK.</p>

                {{-- Next Match Card Dynamic --}}
                @if(isset($upcomingMatch))
                    <div class="p-4 rounded-xl bg-gradient-to-br from-smeck-green-900/30 to-smeck-dark-800 border border-smeck-green-500/20 mb-4">
                        <div class="flex items-center justify-between text-xs mb-3">
                            <span class="text-smeck-green-400 font-bold tracking-wider uppercase text-[10px] flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-smeck-green-400 animate-pulse"></span> LAGA BERIKUTNYA
                            </span>
                            <span class="px-2 py-0.5 rounded text-[9px] font-mono bg-white/10 text-white/70">
                                {{ $upcomingMatch->type === 'home' ? 'KANDANG' : 'AWAYDAYS' }}
                            </span>
                        </div>

                        <div class="font-display font-bold text-white text-base text-center mb-3 truncate">
                            {{ $upcomingMatch->match_title }}
                        </div>

                        <div class="flex items-center justify-between gap-2 py-2 px-3 rounded-lg bg-black/20 text-xs text-white/70 mb-3">
                            <div class="flex items-center gap-1.5 truncate">
                                <i class="fa-regular fa-calendar text-smeck-green-400 text-xs"></i>
                                <span>{{ $upcomingMatch->match_time->translatedFormat('d M Y') }}</span>
                            </div>
                            <div class="font-bold text-white font-mono">
                                {{ $upcomingMatch->match_time->format('H:i') }} WIB
                            </div>
                        </div>

                        <div class="text-[11px] text-white/50 flex items-center gap-1.5 truncate mb-2">
                            <i class="fa-solid fa-location-dot text-amber-400 text-[10px]"></i>
                            <span class="truncate">{{ $upcomingMatch->stadium }}</span>
                        </div>
                    </div>

                    <a href="{{ auth()->check() ? route('member.tickets') : route('login') }}" class="inline-flex items-center gap-2 text-smeck-green-400 text-sm font-semibold hover:text-smeck-green-300 transition-colors group/link">
                        <span>Pesan Tiket Tribun SMeCK</span>
                        <svg class="w-4 h-4 group-hover/link:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                @else
                    <div class="p-6 rounded-xl bg-white/5 border border-white/5 text-center mb-4">
                        <i class="fa-solid fa-calendar-xmark text-2xl text-white/30 mb-2"></i>
                        <p class="text-xs text-white/50">Jadwal laga resmi berikutnya sedang divalidasi operator kompetisi.</p>
                    </div>
                @endif
            </div>

            {{-- Card: Klasemen PT LIB (Opsi B Auto-Sync) --}}
            <div id="card-klasemen" class="group relative bg-smeck-dark-800/50 rounded-2xl border border-white/5 p-6 lg:p-8 hover:border-smeck-green-500/30 transition-all duration-500 card-shadow hover:-translate-y-1 md:col-span-2 lg:col-span-1">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-smeck-green-500 to-smeck-green-300 rounded-t-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                <div class="w-14 h-14 rounded-2xl bg-smeck-green-500/10 flex items-center justify-center mb-6 group-hover:bg-smeck-green-500/20 transition-colors">
                    <svg class="w-7 h-7 text-smeck-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                </div>

                <div class="flex items-center justify-between mb-2">
                    <h3 class="font-display font-bold text-xl text-white">Klasemen Liga 2</h3>
                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-emerald-500/20 text-emerald-300 font-mono">
                        PT LIB SYNC
                    </span>
                </div>
                <p class="text-white/50 text-sm leading-relaxed mb-5">Wilayah Barat (Grup 1) Pegadaian Liga 2 musim ini.</p>

                {{-- Mini Standings Dynamic --}}
                <div class="space-y-2">
                    @forelse($standings->take(3) as $st)
                        @php
                            $isPsms = $st->is_our_team;
                        @endphp
                        <div class="flex items-center justify-between p-3 rounded-xl transition-all {{ $isPsms ? 'bg-smeck-green-500/15 border border-smeck-green-500/30' : 'bg-white/5' }}">
                            <div class="flex items-center gap-3 truncate">
                                <span class="w-6 h-6 rounded-full flex-shrink-0 {{ $isPsms ? 'bg-smeck-green-500 text-white font-bold' : 'bg-white/10 text-white/60' }} flex items-center justify-center text-xs">
                                    {{ $st->position }}
                                </span>
                                <div class="truncate">
                                    <span class="{{ $isPsms ? 'text-white font-bold' : 'text-white/70' }} text-sm truncate">
                                        {{ $st->team_name }}
                                    </span>
                                    @if($isPsms)
                                        <span class="ml-1 px-1.5 py-0.2 rounded text-[8px] bg-smeck-gold text-smeck-dark-950 font-black">KINANTAN</span>
                                    @endif
                                </div>
                            </div>
                            <span class="{{ $isPsms ? 'text-smeck-green-400 font-black' : 'text-white/50' }} font-display text-sm ml-2 font-mono flex-shrink-0">
                                {{ $st->points }} pts
                            </span>
                        </div>
                    @empty
                        <div class="p-3 text-center text-xs text-white/40">Data klasemen sedang dimuat...</div>
                    @endforelse
                </div>

                <button type="button" onclick="openStandingsModal()" class="mt-6 inline-flex items-center gap-2 text-smeck-green-400 text-sm font-semibold hover:text-smeck-green-300 transition-colors group/link cursor-pointer">
                    <span>Lihat Klasemen Lengkap (10 Klub)</span>
                    <svg class="w-4 h-4 group-hover/link:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
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

{{-- ===== MODAL KLASEMEN LENGKAP PT LIB (OPSI B) ===== --}}
<div id="modal-full-standings" class="fixed inset-0 z-50 hidden bg-black/85 backdrop-blur-md flex items-center justify-center p-3 sm:p-4 overflow-y-auto">
    <div class="w-full max-w-4xl bg-smeck-dark-900 border border-smeck-green-500/30 rounded-3xl p-5 sm:p-8 shadow-2xl relative my-8">
        {{-- Close Button --}}
        <button type="button" onclick="closeStandingsModal()" class="absolute top-5 right-5 w-9 h-9 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center text-lg transition-colors cursor-pointer z-10">
            &times;
        </button>

        {{-- Header Modal --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-white/10">
            <div>
                <div class="flex items-center gap-2 mb-1.5">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 font-mono">
                        PT LIB AUTO-SYNC
                    </span>
                    <span class="text-xs text-white/40">Pegadaian Liga 2</span>
                </div>
                <h3 class="font-display font-black text-2xl text-white">Klasemen Wilayah Barat</h3>
                <p class="text-xs text-white/50 mt-0.5">Pantau posisi Ayam Kinantan di babak penyisihan musim kompetisi 2026/2027.</p>
            </div>

            {{-- PSMS Highlight Badge --}}
            <div class="px-4 py-2.5 rounded-2xl bg-smeck-green-500/10 border border-smeck-green-500/30 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-smeck-green-500 flex items-center justify-center text-white font-black text-sm">
                    PSM
                </div>
                <div>
                    <div class="text-[10px] uppercase font-bold text-smeck-green-400">PSMS Medan</div>
                    <div class="text-white font-bold text-sm">
                        Peringkat #{{ $psmsStanding?->position ?? 3 }} · <span class="text-smeck-green-300 font-mono">{{ $psmsStanding?->points ?? 11 }} PTS</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table Container --}}
        <div class="overflow-x-auto mt-4 max-h-[60vh]">
            <table class="w-full text-left text-xs text-white/80">
                <thead class="sticky top-0 bg-smeck-dark-900 z-10">
                    <tr class="border-b border-white/10 text-white/40 uppercase font-bold text-[10px] tracking-wider">
                        <th class="py-3 px-3 text-center w-12">#</th>
                        <th class="py-3 px-4">Klub / Tim</th>
                        <th class="py-3 px-2.5 text-center" title="Main">T</th>
                        <th class="py-3 px-2.5 text-center" title="Menang">M</th>
                        <th class="py-3 px-2.5 text-center" title="Seri">S</th>
                        <th class="py-3 px-2.5 text-center" title="Kalah">K</th>
                        <th class="py-3 px-3 text-center" title="Gol Masuk:Kemasukan">Gol</th>
                        <th class="py-3 px-2.5 text-center" title="Selisih Gol">SG</th>
                        <th class="py-3 px-3 text-center font-bold text-white" title="Poin">PTS</th>
                        <th class="py-3 px-4 text-center">Form</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($standings as $team)
                        @php
                            $isPsms = $team->is_our_team;
                            $formArr = array_filter(explode(',', $team->form ?? ''));
                        @endphp
                        <tr class="transition-colors {{ $isPsms ? 'bg-smeck-green-500/15 border-l-4 border-l-smeck-green-400 font-semibold text-white' : 'hover:bg-white/5 text-white/80' }}">
                            {{-- Rank --}}
                            <td class="py-3 px-3 text-center">
                                @if($team->position <= 3)
                                    <span class="w-6 h-6 mx-auto rounded-full bg-emerald-500/20 text-emerald-400 font-black text-xs flex items-center justify-center">
                                        {{ $team->position }}
                                    </span>
                                @elseif($team->position >= 7)
                                    <span class="w-6 h-6 mx-auto rounded-full bg-red-500/20 text-red-400 font-black text-xs flex items-center justify-center">
                                        {{ $team->position }}
                                    </span>
                                @else
                                    <span class="w-6 h-6 mx-auto rounded-full bg-white/5 text-white/50 text-xs flex items-center justify-center">
                                        {{ $team->position }}
                                    </span>
                                @endif
                            </td>

                            {{-- Club --}}
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-7 h-7 rounded-lg {{ $isPsms ? 'bg-smeck-green-500 text-white font-black' : 'bg-white/10 text-white/50' }} flex items-center justify-center text-[10px] font-mono font-bold">
                                        {{ $team->team_code ?? substr($team->team_name, 0, 3) }}
                                    </span>
                                    <div>
                                        <div class="font-bold {{ $isPsms ? 'text-smeck-green-300' : 'text-white' }} flex items-center gap-1.5">
                                            <span>{{ $team->team_name }}</span>
                                            @if($isPsms)
                                                <i class="fa-solid fa-star text-smeck-gold text-[10px]"></i>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Stats --}}
                            <td class="py-3 px-2.5 text-center font-mono">{{ $team->played }}</td>
                            <td class="py-3 px-2.5 text-center font-mono text-emerald-400">{{ $team->won }}</td>
                            <td class="py-3 px-2.5 text-center font-mono text-amber-300">{{ $team->drawn }}</td>
                            <td class="py-3 px-2.5 text-center font-mono text-red-400">{{ $team->lost }}</td>
                            <td class="py-3 px-3 text-center font-mono text-white/60">{{ $team->goals_for }}:{{ $team->goals_against }}</td>
                            <td class="py-3 px-2.5 text-center font-mono font-bold {{ $team->goal_difference > 0 ? 'text-emerald-400' : ($team->goal_difference < 0 ? 'text-red-400' : 'text-white/50') }}">
                                {{ $team->goal_difference > 0 ? '+' : '' }}{{ $team->goal_difference }}
                            </td>
                            <td class="py-3 px-3 text-center font-mono font-black text-sm {{ $isPsms ? 'text-smeck-green-400' : 'text-white' }}">
                                {{ $team->points }}
                            </td>

                            {{-- Form --}}
                            <td class="py-3 px-4 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    @forelse($formArr as $f)
                                        @if(trim(strtoupper($f)) === 'W')
                                            <span class="w-4 h-4 rounded bg-emerald-500/20 text-emerald-400 text-[9px] font-bold flex items-center justify-center" title="Menang">M</span>
                                        @elseif(trim(strtoupper($f)) === 'D')
                                            <span class="w-4 h-4 rounded bg-amber-500/20 text-amber-300 text-[9px] font-bold flex items-center justify-center" title="Seri">S</span>
                                        @else
                                            <span class="w-4 h-4 rounded bg-red-500/20 text-red-400 text-[9px] font-bold flex items-center justify-center" title="Kalah">K</span>
                                        @endif
                                    @empty
                                        <span class="text-white/30 text-[10px]">-</span>
                                    @endforelse
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="py-8 text-center text-white/40">Data belum tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer Legend & Attribution --}}
        <div class="mt-6 pt-4 border-t border-white/10 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-[11px] text-white/50">
            <div class="flex flex-wrap items-center gap-3">
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-sm bg-emerald-500"></span> 1-3: Playoff Promosi Liga 1</span>
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-sm bg-white/20"></span> 4-6: Zona Aman</span>
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-sm bg-red-500/80"></span> 7-10: Playoff Degradasi</span>
            </div>
            <div class="flex items-center gap-1.5 text-white/40">
                <i class="fa-solid fa-cloud-arrow-down text-emerald-400 text-xs"></i>
                <span>Disinkronkan otomatis dari PT Liga Indonesia Baru</span>
            </div>
        </div>
    </div>
</div>

<script>
    function openStandingsModal() {
        const modal = document.getElementById('modal-full-standings');
        if (modal) {
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
    }

    function closeStandingsModal() {
        const modal = document.getElementById('modal-full-standings');
        if (modal) {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    }
</script>

@endsection

