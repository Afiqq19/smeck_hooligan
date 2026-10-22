<!DOCTYPE html>
<html lang="id" class="h-full bg-smeck-dark-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') — SMeCK Hooligan Official Portal</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Outfit:wght@400;500;600;700;800;900&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">

    {{-- Favicon --}}
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo-crest.jpg') }}">

    {{-- FontAwesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="h-full bg-smeck-dark-950 text-white font-sans antialiased overflow-x-hidden">

<div class="min-h-full flex">

    {{-- ============================================================== --}}
    {{-- SIDEBAR (FIXED DESKTOP + COLLAPSIBLE MOBILE) --}}
    {{-- ============================================================== --}}
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 w-64 lg:w-72 bg-smeck-dark-900 border-r border-white/10 flex flex-col transition-transform duration-300 -translate-x-full lg:translate-x-0">
        
        {{-- Sidebar Brand Header --}}
        <div class="h-20 flex items-center justify-between px-6 border-b border-white/10 bg-smeck-dark-950/60">
            <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-xl overflow-hidden border border-smeck-green-500/40 shadow-lg shadow-smeck-green-950/50 group-hover:scale-105 transition-transform bg-black flex items-center justify-center p-0.5">
                    <img src="{{ asset('images/logo-crest.jpg') }}" alt="SMeCK Logo" class="w-full h-full object-cover rounded-lg">
                </div>
                <div>
                    <div class="font-display font-black text-white text-base tracking-tight leading-none">
                        SMeCK <span class="text-smeck-green-400">Hooligan</span>
                    </div>
                    <div class="text-[10px] text-white/40 tracking-widest uppercase font-semibold mt-1">
                        Portal Terpadu PSMS
                    </div>
                </div>
            </a>
            <button class="lg:hidden text-white/60 hover:text-white p-1" onclick="toggleSidebar()">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        {{-- Active Role Badge Container --}}
        <div class="px-6 py-4 border-b border-white/5 bg-white/[0.02]">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-white/40">Area Hak Akses</span>
                @if(auth()->user()->role === 'super_admin')
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-red-500/20 text-red-300 border border-red-500/30">
                        <i class="fa-solid fa-crown text-[9px] mr-1"></i> Super Admin
                    </span>
                @elseif(auth()->user()->role === 'admin_chapter')
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-500/20 text-amber-300 border border-amber-500/30">
                        <i class="fa-solid fa-shield text-[9px] mr-1"></i> Admin Chapter
                    </span>
                @else
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-smeck-green-500/20 text-smeck-green-300 border border-smeck-green-500/30">
                        <i class="fa-solid fa-id-card text-[9px] mr-1"></i> Anggota Resmi
                    </span>
                @endif
            </div>
            <div class="mt-2 text-xs text-white/70 truncate font-medium">
                <i class="fa-solid fa-location-dot text-smeck-green-400 mr-1 text-[11px]"></i>
                {{ auth()->user()->chapter?->name ?? 'Pusat Stadion Teladan' }}
            </div>
        </div>

        {{-- Sidebar Navigation Links (Dynamic per Role) --}}
        <div id="sidebar-nav-container" class="flex-1 overflow-y-auto px-4 py-4 space-y-6">

            {{-- ====================================================== --}}
            {{-- 1. MENU UNTUK ROLE 'member' (ANGGOTA) --}}
            {{-- ====================================================== --}}
            @if(auth()->user()->role === 'member')
                <div>
                    <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-wider text-white/40">
                        Menu Utama Anggota
                    </div>
                    <nav class="space-y-1">
                        {{-- 1. Dashboard (KTA Digital & QR Code/Status NFC) --}}
                        <a href="{{ route('member.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('member.dashboard') ? 'bg-smeck-green-600 text-white shadow-lg shadow-smeck-green-700/30' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                            <i class="fa-solid fa-id-card-clip w-5 text-center text-smeck-green-400 {{ request()->routeIs('member.dashboard') ? 'text-white' : '' }}"></i>
                            <span>Dashboard KTA Digital</span>
                        </a>

                        {{-- 2. Profil Saya --}}
                        <a href="{{ route('member.profile') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('member.profile') ? 'bg-smeck-green-600 text-white shadow-lg shadow-smeck-green-700/30' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                            <i class="fa-solid fa-user-pen w-5 text-center text-smeck-green-400 {{ request()->routeIs('member.profile') ? 'text-white' : '' }}"></i>
                            <span>Profil Saya</span>
                        </a>

                        {{-- 3. Iuran & Kas Suka Rela --}}
                        <a href="{{ route('member.dues') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('member.dues*') ? 'bg-smeck-green-600 text-white shadow-lg shadow-smeck-green-700/30' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                            <i class="fa-solid fa-hand-holding-dollar w-5 text-center text-smeck-green-400 {{ request()->routeIs('member.dues*') ? 'text-white' : '' }}"></i>
                            <span>Iuran & Kas Sukarela</span>
                        </a>

                        {{-- 4. Lapor Kartu Darurat --}}
                        <a href="{{ route('member.emergency') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('member.emergency*') ? 'bg-red-600/80 text-white shadow-lg shadow-red-700/30' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                            <i class="fa-solid fa-triangle-exclamation w-5 text-center text-red-400 {{ request()->routeIs('member.emergency*') ? 'text-white' : '' }}"></i>
                            <span>Lapor Kartu Darurat</span>
                        </a>
                    </nav>
                </div>

                {{-- [Prompt 6] Budaya Tribun Ekstra --}}
                <div>
                    <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-wider text-smeck-green-400">
                        Budaya Tribun & Fans
                    </div>
                    <nav class="space-y-1">
                        {{-- 1. Pustaka Chants --}}
                        <a href="{{ route('member.chants') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('member.chants') ? 'bg-smeck-green-600 text-white' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                            <i class="fa-solid fa-music w-5 text-center text-smeck-gold"></i>
                            <span>Pustaka Chants</span>
                        </a>

                        {{-- 2. Tiket & Awaydays --}}
                        <a href="{{ route('member.tickets') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('member.tickets') ? 'bg-smeck-green-600 text-white' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                            <i class="fa-solid fa-bus-simple w-5 text-center text-smeck-gold"></i>
                            <span>Tiket & Awaydays</span>
                        </a>

                        {{-- 3. SMeCK Store --}}
                        <a href="{{ route('member.store') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('member.store') ? 'bg-smeck-green-600 text-white' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                            <i class="fa-solid fa-bag-shopping w-5 text-center text-smeck-gold"></i>
                            <span>SMeCK Store</span>
                        </a>
                    </nav>
                </div>
            @endif

            {{-- ====================================================== --}}
            {{-- 2. MENU UNTUK ROLE 'admin_chapter' (PENGURUS WILAYAH) --}}
            {{-- ====================================================== --}}
            @if(auth()->user()->role === 'admin_chapter' || auth()->user()->role === 'super_admin')
                <div>
                    <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-wider text-amber-400">
                        Pengurus Basis / Chapter
                    </div>
                    <nav class="space-y-1">
                        {{-- 1. Dashboard Chapter --}}
                        <a href="{{ route('chapter.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('chapter.dashboard') ? 'bg-amber-600 text-white shadow-lg shadow-amber-700/30' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                            <i class="fa-solid fa-chart-pie w-5 text-center text-amber-400 {{ request()->routeIs('chapter.dashboard') ? 'text-white' : '' }}"></i>
                            <span>Dashboard Chapter</span>
                        </a>

                        {{-- 2. Kelola Anggota & KTA NFC --}}
                        <a href="{{ route('chapter.members') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('chapter.members*') ? 'bg-amber-600 text-white shadow-lg shadow-amber-700/30' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                            <i class="fa-solid fa-users-gear w-5 text-center text-amber-400 {{ request()->routeIs('chapter.members*') ? 'text-white' : '' }}"></i>
                            <span>Daftar & Tambah Anggota</span>
                        </a>

                        {{-- 3. Verifikasi Anggota Baru --}}
                        <a href="{{ route('chapter.verifications') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('chapter.verifications*') ? 'bg-amber-600 text-white shadow-lg shadow-amber-700/30' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                            <i class="fa-solid fa-user-check w-5 text-center text-amber-400 {{ request()->routeIs('chapter.verifications*') ? 'text-white' : '' }}"></i>
                            <span>Verifikasi Anggota Baru</span>
                        </a>

                        {{-- 3. Scan Presensi NFC --}}
                        <a href="{{ route('chapter.scanner') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('chapter.scanner*') ? 'bg-amber-600 text-white shadow-lg shadow-amber-700/30' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                            <i class="fa-solid fa-wifi w-5 text-center text-amber-400 {{ request()->routeIs('chapter.scanner*') ? 'text-white' : '' }}"></i>
                            <span>Scan Presensi NFC</span>
                        </a>

                        {{-- 4. Kas Chapter --}}
                        <a href="{{ route('chapter.dues') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('chapter.dues*') ? 'bg-amber-600 text-white shadow-lg shadow-amber-700/30' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                            <i class="fa-solid fa-vault w-5 text-center text-amber-400 {{ request()->routeIs('chapter.dues*') ? 'text-white' : '' }}"></i>
                            <span>Kas Chapter</span>
                        </a>

                        {{-- [Prompt 6] 1. Kuota Tiket Chapter --}}
                        <a href="{{ route('chapter.tickets') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('chapter.tickets*') ? 'bg-amber-600 text-white shadow-lg shadow-amber-700/30' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                            <i class="fa-solid fa-ticket-simple w-5 text-center text-amber-400 {{ request()->routeIs('chapter.tickets*') ? 'text-white' : '' }}"></i>
                            <span>Kuota Tiket Chapter</span>
                        </a>

                        {{-- [Prompt 6] 2. Dokumentasi Kegiatan --}}
                        <a href="{{ route('chapter.documentations') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('chapter.documentations*') ? 'bg-amber-600 text-white shadow-lg shadow-amber-700/30' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                            <i class="fa-solid fa-camera-retro w-5 text-center text-amber-400 {{ request()->routeIs('chapter.documentations*') ? 'text-white' : '' }}"></i>
                            <span>Dokumentasi Kegiatan</span>
                        </a>
                    </nav>
                </div>
            @endif

            {{-- ====================================================== --}}
            {{-- 3. MENU UNTUK ROLE 'super_admin' (PENGURUS PUSAT) --}}
            {{-- ====================================================== --}}
            @if(auth()->user()->role === 'super_admin')
                @php
                    $pendingApprovalsCount = \App\Models\User::whereIn('role', ['member', 'admin_chapter'])->where('status', 'pending_approval')->count();
                @endphp
                {{-- Group 1: Utama & Data Basis --}}
                <div class="space-y-1">
                    <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-wider text-red-400 flex items-center justify-between">
                        <span>Pusat DPP · Keanggotaan</span>
                        <span class="px-1.5 py-0.5 rounded text-[9px] bg-red-500/20 text-red-300 font-mono">PUSAT</span>
                    </div>
                    <nav class="space-y-1">
                        <a href="{{ route('superadmin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('superadmin.dashboard') ? 'bg-red-600 text-white shadow-lg shadow-red-700/30' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                            <i class="fa-solid fa-chart-line w-5 text-center text-red-400 {{ request()->routeIs('superadmin.dashboard') ? 'text-white' : '' }}"></i>
                            <span>Dashboard Utama</span>
                        </a>

                        <a href="{{ route('superadmin.members') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('superadmin.members*') ? 'bg-red-600 text-white shadow-lg shadow-red-700/30' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-users w-5 text-center text-red-400 {{ request()->routeIs('superadmin.members*') ? 'text-white' : '' }}"></i>
                                <span>Anggota Nasional</span>
                            </div>
                            @if($pendingApprovalsCount > 0)
                                <span class="px-1.5 py-0.5 rounded-full text-[9px] font-black bg-amber-500 text-black animate-pulse">
                                    {{ $pendingApprovalsCount }}
                                </span>
                            @endif
                        </a>

                        <a href="{{ route('superadmin.chapters') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('superadmin.chapters*') ? 'bg-red-600 text-white shadow-lg shadow-red-700/30' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                            <i class="fa-solid fa-sitemap w-5 text-center text-red-400 {{ request()->routeIs('superadmin.chapters*') ? 'text-white' : '' }}"></i>
                            <span>Master Basis & Chapter</span>
                        </a>

                        <a href="{{ route('superadmin.nfc') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('superadmin.nfc*') ? 'bg-red-600 text-white shadow-lg shadow-red-700/30' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                            <i class="fa-solid fa-credit-card w-5 text-center text-red-400 {{ request()->routeIs('superadmin.nfc*') ? 'text-white' : '' }}"></i>
                            <span>KTA NFC & Cetak Fisik</span>
                        </a>
                    </nav>
                </div>

                {{-- Group 2: Operasional Tribun & Finansial --}}
                <div class="space-y-1 pt-3">
                    <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-wider text-white/40">
                        Operasional & Budaya Tribun
                    </div>
                    <nav class="space-y-1">
                        <a href="{{ route('superadmin.tickets') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('superadmin.tickets*') ? 'bg-red-600 text-white shadow-lg shadow-red-700/30' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                            <i class="fa-solid fa-ticket-simple w-5 text-center text-red-400 {{ request()->routeIs('superadmin.tickets*') ? 'text-white' : '' }}"></i>
                            <span>Tiket & Kuota Laga</span>
                        </a>

                        <a href="{{ route('superadmin.documentations') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('superadmin.documentations*') ? 'bg-red-600 text-white shadow-lg shadow-red-700/30' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                            <i class="fa-solid fa-camera-retro w-5 text-center text-red-400 {{ request()->routeIs('superadmin.documentations*') ? 'text-white' : '' }}"></i>
                            <span>Galeri & Koreo Tribun</span>
                        </a>

                        <a href="{{ route('superadmin.chants') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('superadmin.chants*') ? 'bg-red-600 text-white shadow-lg shadow-red-700/30' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                            <i class="fa-solid fa-music w-5 text-center text-red-400 {{ request()->routeIs('superadmin.chants*') ? 'text-white' : '' }}"></i>
                            <span>Pustaka Chant & Anthem</span>
                        </a>

                        <a href="{{ route('superadmin.finance') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('superadmin.finance*') ? 'bg-red-600 text-white shadow-lg shadow-red-700/30' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                            <i class="fa-solid fa-wallet w-5 text-center text-red-400 {{ request()->routeIs('superadmin.finance*') ? 'text-white' : '' }}"></i>
                            <span>Kas & Keuangan Sukarela</span>
                        </a>

                        <a href="{{ route('superadmin.partners') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('superadmin.partners*') ? 'bg-red-600 text-white shadow-lg shadow-red-700/30' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                            <i class="fa-solid fa-handshake w-5 text-center text-red-400 {{ request()->routeIs('superadmin.partners*') ? 'text-white' : '' }}"></i>
                            <span>SMeCK Partner Merchants</span>
                        </a>
                    </nav>
                </div>

                {{-- Group 3: Komunikasi & Gateway --}}
                <div class="space-y-1 pt-3">
                    <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-wider text-white/40">
                        Publikasi & Gateway
                    </div>
                    <nav class="space-y-1">
                        <a href="{{ route('superadmin.broadcast') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('superadmin.broadcast*') ? 'bg-red-600 text-white shadow-lg shadow-red-700/30' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                            <i class="fa-solid fa-bullhorn w-5 text-center text-red-400 {{ request()->routeIs('superadmin.broadcast*') ? 'text-white' : '' }}"></i>
                            <span>Broadcast Maklumat DPP</span>
                        </a>

                        <a href="{{ route('superadmin.standings') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('superadmin.standings*') ? 'bg-red-600 text-white shadow-lg shadow-red-700/30' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                            <i class="fa-solid fa-ranking-star w-5 text-center text-red-400 {{ request()->routeIs('superadmin.standings*') ? 'text-white' : '' }}"></i>
                            <span>Klasemen PT LIB (Liga 2)</span>
                        </a>

                        <a href="{{ route('superadmin.news') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('superadmin.news*') ? 'bg-red-600 text-white shadow-lg shadow-red-700/30' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                            <i class="fa-solid fa-newspaper w-5 text-center text-red-400 {{ request()->routeIs('superadmin.news*') ? 'text-white' : '' }}"></i>
                            <span>Manajemen Berita Pers</span>
                        </a>

                        <a href="{{ route('superadmin.whatsapp') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('superadmin.whatsapp*') ? 'bg-red-600 text-white shadow-lg shadow-red-700/30' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
                            <div class="flex items-center gap-3">
                                <i class="fa-brands fa-whatsapp w-5 text-center text-emerald-400 {{ request()->routeIs('superadmin.whatsapp*') ? 'text-white' : '' }}"></i>
                                <span>WhatsApp Bot Gateway</span>
                            </div>
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse" title="Gateway Siap"></span>
                        </a>
                    </nav>
                </div>
            @endif
        </div>

        {{-- Instant Sidebar Scroll Restoration (Mencegah Sidebar Reset ke Atas Saat Pindah Menu) --}}
        <script>
            (function() {
                try {
                    const sc = document.getElementById('sidebar-nav-container');
                    const saved = sessionStorage.getItem('smeck_sidebar_scroll');
                    if (sc && saved !== null) {
                        sc.scrollTop = parseInt(saved, 10);
                    }
                } catch(e) {}
            })();
        </script>

        {{-- Sidebar Footer: User Card Mini & Switch Portal if Admin --}}
        <div class="p-4 border-t border-white/10 bg-smeck-dark-950/80">
            @if(auth()->user()->role === 'super_admin')
                <div class="mb-3 flex items-center justify-between text-[11px] text-white/50 px-1">
                    <span>Lihat Mode:</span>
                    <div class="flex gap-1.5">
                        <a href="{{ route('member.dashboard') }}" class="px-2 py-0.5 rounded bg-white/10 hover:bg-smeck-green-600 text-white transition-colors">Member</a>
                        <a href="{{ route('chapter.dashboard') }}" class="px-2 py-0.5 rounded bg-white/10 hover:bg-amber-600 text-white transition-colors">Chapter</a>
                        <a href="{{ route('superadmin.dashboard') }}" class="px-2 py-0.5 rounded bg-white/10 hover:bg-red-600 text-white transition-colors">Pusat</a>
                    </div>
                </div>
            @endif

            <div class="flex items-center justify-between gap-3 p-2.5 rounded-xl bg-white/5 border border-white/5">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-9 h-9 rounded-xl bg-smeck-green-600 flex items-center justify-center font-bold text-sm text-white shrink-0">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <div class="text-xs font-bold text-white truncate">{{ auth()->user()->name }}</div>
                        <div class="text-[11px] text-white/40 truncate">{{ auth()->user()->email }}</div>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" title="Keluar" class="p-2 text-white/40 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition-colors">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Backdrop Overlay for Mobile --}}
    <div id="sidebar-backdrop" class="fixed inset-0 z-30 bg-black/60 backdrop-blur-sm lg:hidden hidden" onclick="toggleSidebar()"></div>

    {{-- ============================================================== --}}
    {{-- MAIN WRAPPER (TOPBAR + CONTENT) --}}
    {{-- ============================================================== --}}
    <div class="flex-1 flex flex-col min-w-0 lg:pl-72">

        {{-- Topbar --}}
        <header class="sticky top-0 z-20 h-20 bg-smeck-dark-900/90 backdrop-blur-xl border-b border-white/10 px-4 sm:px-6 lg:px-8 flex items-center justify-between">
            {{-- Left: Toggle Mobile Sidebar + Breadcrumbs / Title --}}
            <div class="flex items-center gap-4">
                <button class="lg:hidden p-2 rounded-xl bg-white/5 text-white hover:bg-white/10 transition-colors" onclick="toggleSidebar()">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>
                <div>
                    <h2 class="font-display font-bold text-lg sm:text-xl text-white tracking-tight">
                        @yield('header_title', 'Dashboard')
                    </h2>
                    <p class="text-xs text-white/50 hidden sm:block">
                        @yield('header_subtitle', 'SMeCK Hooligan — Bersatu Mendukung PSMS Medan')
                    </p>
                </div>
            </div>

            {{-- Right: Quick Stats / Profile Dropdown --}}
            <div class="flex items-center gap-3 sm:gap-4">
                {{-- Quick Link to Website --}}
                <a href="{{ url('/') }}" target="_blank" class="hidden sm:inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-white/5 hover:bg-white/10 text-white/70 hover:text-white text-xs font-semibold border border-white/10 transition-colors">
                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                    <span>Lihat Web</span>
                </a>

                {{-- Status Online Indicator --}}
                <div class="hidden md:flex items-center gap-2 px-3 py-1.5 rounded-xl bg-smeck-green-500/10 border border-smeck-green-500/20 text-smeck-green-400 text-xs">
                    <span class="w-2 h-2 rounded-full bg-smeck-green-400 animate-pulse"></span>
                    <span class="font-medium">NFC Server Online</span>
                </div>

                {{-- User Avatar & Logout --}}
                <div class="flex items-center gap-3 pl-3 border-l border-white/10">
                    <div class="text-right hidden sm:block">
                        <div class="text-xs font-bold text-white">{{ auth()->user()->name }}</div>
                        <div class="text-[10px] text-smeck-green-400 font-mono">{{ auth()->user()->profile?->nia ?? 'ANGGOTA' }}</div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="px-3 py-2 rounded-xl bg-red-500/10 hover:bg-red-500 text-red-400 hover:text-white border border-red-500/20 text-xs font-semibold transition-all duration-200 flex items-center gap-1.5">
                            <i class="fa-solid fa-power-off"></i>
                            <span class="hidden sm:inline">Keluar</span>
                        </button>
                    </form>
                </div>
            </div>
        </header>

        {{-- Flash Notification Alerts --}}
        <div class="px-4 sm:px-6 lg:px-8 pt-6">
            @if(session('success'))
                <div class="p-4 rounded-2xl bg-smeck-green-600/20 border border-smeck-green-500/30 text-smeck-green-300 text-sm flex items-center justify-between mb-4 animate-slide-up">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-circle-check text-smeck-green-400 text-base"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-white/40 hover:text-white">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 rounded-2xl bg-red-500/20 border border-red-500/30 text-red-300 text-sm flex items-center justify-between mb-4 animate-slide-up">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-triangle-exclamation text-red-400 text-base"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-white/40 hover:text-white">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif
        </div>

        {{-- Main Content Area --}}
        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            @yield('content')
        </main>

        {{-- Dashboard Footer --}}
        <footer class="py-6 px-6 lg:px-8 border-t border-white/5 text-center sm:text-left text-xs text-white/30 flex flex-col sm:flex-row items-center justify-between gap-2">
            <div>
                © {{ date('Y') }} SMeCK Hooligan Indonesia • PSMS Medan Forever.
            </div>
            <div class="font-mono text-smeck-green-400">
                "Salam Sada Roha!"
            </div>
        </footer>
    </div>
</div>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const backdrop = document.getElementById('sidebar-backdrop');
        const isHidden = sidebar.classList.contains('-translate-x-full');

        if (isHidden) {
            sidebar.classList.remove('-translate-x-full');
            backdrop.classList.remove('hidden');
        } else {
            sidebar.classList.add('-translate-x-full');
            backdrop.classList.add('hidden');
        }
    }

    // Persistensi Scroll Sidebar (Mencegah Sidebar Reset Naik ke Atas saat Pindah Menu)
    document.addEventListener('DOMContentLoaded', function() {
        const sc = document.getElementById('sidebar-nav-container');
        if (!sc) return;

        const saved = sessionStorage.getItem('smeck_sidebar_scroll');
        if (saved !== null) {
            sc.scrollTop = parseInt(saved, 10);
        } else {
            // Jika baru dibuka, scroll item menu aktif ke posisi terlihat
            const activeLink = sc.querySelector('.bg-red-600, .bg-amber-600, .bg-smeck-green-600');
            if (activeLink) {
                activeLink.scrollIntoView({ block: 'nearest', behavior: 'instant' });
            }
        }

        // Catat posisi saat pengguna scrolling di sidebar
        sc.addEventListener('scroll', function() {
            sessionStorage.setItem('smeck_sidebar_scroll', sc.scrollTop);
        }, { passive: true });

        // Catat posisi saat link menu diklik
        sc.querySelectorAll('a').forEach(function(link) {
            link.addEventListener('click', function() {
                sessionStorage.setItem('smeck_sidebar_scroll', sc.scrollTop);
            });
        });
    });
</script>

@stack('scripts')
</body>
</html>
