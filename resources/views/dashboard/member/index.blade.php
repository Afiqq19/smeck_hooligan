@extends('layouts.app')

@section('title', 'KTA Digital Anggota')
@section('header_title', 'Kartu Tanda Anggota (KTA) Digital')
@section('header_subtitle', 'Identitas resmi suporter SMeCK Hooligan dengan chip NFC & QR Presensi')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Caveat:wght@600;700&family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
<style>
    /* 3D Card Flip Styling */
    .perspective-1000 {
        perspective: 1000px;
    }
    .card-inner {
        position: relative;
        width: 100%;
        height: 100%;
        transition: transform 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        transform-style: preserve-3d;
    }
    .card-flipped {
        transform: rotateY(180deg);
    }
    .card-face {
        position: absolute;
        width: 100%;
        height: 100%;
        -webkit-backface-visibility: hidden;
        backface-visibility: hidden;
        border-radius: 1.5rem;
    }
    .card-back {
        transform: rotateY(180deg);
    }
    /* Holographic sheen shimmer */
    .shimmer-effect {
        background: linear-gradient(
            105deg,
            transparent 20%,
            rgba(255, 255, 255, 0.12) 25%,
            rgba(255, 215, 0, 0.2) 30%,
            transparent 35%
        );
        background-size: 200% 100%;
        animation: sheen 6s infinite linear;
    }
    @keyframes sheen {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }
    .font-signature {
        font-family: 'Caveat', 'Dancing Script', cursive;
    }
    .guilloche-bg {
        background-color: #f8fafc;
        background-image: 
            repeating-linear-gradient(45deg, rgba(16, 185, 129, 0.12) 0, rgba(16, 185, 129, 0.12) 2px, transparent 2px, transparent 6px),
            repeating-linear-gradient(-45deg, rgba(15, 23, 42, 0.08) 0, rgba(15, 23, 42, 0.08) 2px, transparent 2px, transparent 6px);
    }
    .holo-stripe {
        background: linear-gradient(90deg, #18181b 0%, #27272a 25%, #3f3f46 50%, #27272a 75%, #18181b 100%);
        position: relative;
        overflow: hidden;
    }
    .holo-stripe::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, transparent, rgba(245, 158, 11, 0.18), rgba(16, 185, 129, 0.18), transparent);
        animation: holo-pass 4s infinite linear;
    }
    @keyframes holo-pass {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }
</style>
@endpush

@section('content')
<div class="space-y-8 max-w-7xl mx-auto">

    {{-- TOP BANNER / GREETING WITH SUPORTER CHOREO ATMOSPHERE --}}
    <div class="relative overflow-hidden rounded-3xl bg-smeck-dark-900 border border-smeck-green-500/20 p-6 sm:p-8">
        {{-- Background Choreo Image --}}
        <div class="absolute inset-0 bg-cover bg-center opacity-20 pointer-events-none scale-105" style="background-image: url('{{ asset('images/auth-cover.jpg') }}');"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-smeck-dark-950 via-smeck-dark-950/85 to-smeck-green-950/80"></div>
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-smeck-green-500/10 rounded-full blur-3xl"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 rounded-2xl overflow-hidden border-2 border-smeck-green-500/40 shadow-xl p-0.5 bg-black shrink-0 hidden sm:flex items-center justify-center">
                    <img src="{{ asset('images/logo-crest.jpg') }}" alt="SMeCK Logo" class="w-full h-full object-cover rounded-xl">
                </div>
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-smeck-green-500/20 text-smeck-green-300 text-xs font-bold uppercase tracking-wider mb-2">
                        <span class="w-2 h-2 rounded-full bg-smeck-green-400 animate-ping"></span>
                        Salam Sada Roha!
                    </div>
                    <h1 class="font-display font-black text-2xl sm:text-3xl text-white tracking-tight">
                        Horas, <span class="text-gradient-smeck">{{ $user->name }}</span>!
                    </h1>
                    <p class="text-sm text-white/60 mt-1 max-w-xl">
                        KTA Digital Anda terverifikasi resmi oleh Pengurus Pusat SMeCK Hooligan. Tunjukkan QR Code atau tap kartu fisik NFC di gate masuk Stadion Teladan.
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                <button type="button" onclick="flipKtaCard()" class="px-3.5 py-2.5 rounded-xl bg-white/10 hover:bg-white/15 text-white text-xs font-bold border border-white/10 flex items-center gap-1.5 transition-all">
                    <i class="fa-solid fa-rotate text-smeck-green-400"></i>
                    <span>Putar Kartu</span>
                </button>
                <button type="button" onclick="openDownloadKtaModal()" class="px-4 py-2.5 rounded-xl bg-amber-500/20 hover:bg-amber-500/30 text-amber-300 border border-amber-500/30 text-xs font-bold flex items-center gap-1.5 transition-all shadow">
                    <i class="fa-solid fa-download"></i>
                    <span>Simpan KTA (HD)</span>
                </button>
                @php
                    $niaText = $user->profile?->nia ?? 'SMCK-1950';
                    $waShareText = urlencode("🟢 *KTA DIGITAL RESMI SMECK HOOLIGAN*\n\nNama: {$user->name}\nNIA: {$niaText}\nBasis: " . ($user->chapter->name ?? 'Pusat') . "\nGol. Darah: " . ($user->profile?->blood_type ?? '-') . "\nStatus: AKTIF TERVERIFIKASI ✅\n\nVerifikasi Kartu Asli:\n" . url('/api/check-kta?query=' . $niaText) . "\n\nSalam Sada Roha! 🐓⚽");
                @endphp
                <a href="https://wa.me/?text={{ $waShareText }}" target="_blank" class="px-4 py-2.5 rounded-xl bg-emerald-600/30 hover:bg-emerald-600/50 text-emerald-300 border border-emerald-500/40 text-xs font-bold flex items-center gap-1.5 transition-all">
                    <i class="fa-brands fa-whatsapp text-sm"></i>
                    <span>Share KTA ke WA</span>
                </a>
                <a href="{{ route('member.tickets') }}" class="px-5 py-2.5 rounded-xl bg-smeck-green-600 hover:bg-smeck-green-500 text-white text-xs font-bold shadow-lg shadow-smeck-green-700/30 flex items-center gap-2 transition-all">
                    <i class="fa-solid fa-ticket"></i>
                    <span>Pesan Tiket Match</span>
                </a>
            </div>
        </div>
    </div>

    {{-- ============================================================== --}}
    {{-- CENTERPIECE: VISUAL KTA DIGITAL (NFC HYBRID CARD) --}}
    {{-- ============================================================== --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        {{-- Left 7 Cols: 3D Interactive KTA Card --}}
        <div class="lg:col-span-7 flex flex-col items-center">
            
            <div class="w-full max-w-md sm:max-w-lg aspect-[1.586/1] perspective-1000">
                <div id="kta-card" class="card-inner cursor-pointer" onclick="flipKtaCard()">
                    
                    {{-- -------------------------------------------------- --}}
                    {{-- FRONT OF THE CARD --}}
                    {{-- -------------------------------------------------- --}}
                    <div class="card-face p-6 sm:p-7 flex flex-col justify-between overflow-hidden border border-white/20 shadow-2xl bg-gradient-to-br from-smeck-green-950 via-smeck-dark-900 to-black text-white relative">
                        
                        {{-- Holographic Shimmer Layer --}}
                        <div class="absolute inset-0 shimmer-effect pointer-events-none"></div>

                        {{-- Watermark Official Crest --}}
                        <div class="absolute -right-6 -bottom-6 w-48 h-48 rounded-full overflow-hidden opacity-10 pointer-events-none">
                            <img src="{{ asset('images/logo-crest.jpg') }}" alt="Watermark" class="w-full h-full object-cover">
                        </div>

                        {{-- Card Header: Logos & Organization --}}
                        <div class="relative z-10 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl overflow-hidden border border-smeck-gold/50 shadow-md flex items-center justify-center p-0.5 bg-black">
                                    <img src="{{ asset('images/logo-crest.jpg') }}" alt="Logo SMeCK" class="w-full h-full object-cover rounded-lg">
                                </div>
                                <div>
                                    <div class="font-display font-black text-white text-sm sm:text-base tracking-wider uppercase">
                                        SMeCK HOOLIGAN
                                    </div>
                                    <div class="text-[9px] sm:text-[10px] text-smeck-gold font-bold tracking-widest uppercase">
                                        SUPORTER RESMI PSMS MEDAN
                                    </div>
                                </div>
                            </div>

                            {{-- NFC Contactless Waves + Card Status Badge --}}
                            <div class="flex items-center gap-2">
                                <div class="text-white/60 text-lg sm:text-xl">
                                    <i class="fa-solid fa-wifi rotate-90"></i>
                                </div>
                                @if($user->nfcCard && $user->nfcCard->status === 'blocked')
                                    <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-red-500 text-white uppercase">DIBLOKIR</span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-smeck-green-500 text-white uppercase tracking-wider">AKTIF</span>
                                @endif
                            </div>
                        </div>

                        {{-- Card Middle: Gold EMV Chip & Chapter --}}
                        <div class="relative z-10 my-auto py-2 flex items-center justify-between">
                            {{-- EMV Gold Chip Graphic --}}
                            <div class="w-11 h-8 sm:w-12 sm:h-9 rounded-md bg-gradient-to-br from-amber-200 via-amber-400 to-amber-600 shadow-inner border border-amber-300/60 p-1 flex flex-col justify-between">
                                <div class="h-[1px] bg-amber-800/40 w-full mt-1"></div>
                                <div class="h-[1px] bg-amber-800/40 w-2/3 mx-auto"></div>
                                <div class="h-[1px] bg-amber-800/40 w-full mb-1"></div>
                            </div>

                            {{-- Chapter Badge --}}
                            <div class="text-right">
                                <div class="text-[9px] text-white/40 uppercase font-bold tracking-widest">BASIS WILAYAH</div>
                                <div class="text-xs sm:text-sm font-bold text-smeck-green-300 font-display">
                                    {{ $user->chapter?->name ?? 'Chapter Medan Kota' }}
                                </div>
                            </div>
                        </div>

                        {{-- Card Bottom: Profile Photo, Name, NIA & Details --}}
                        <div class="relative z-10 flex items-end justify-between pt-2 border-t border-white/10">
                            <div class="flex items-center gap-3">
                                {{-- Member Photo --}}
                                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl border-2 border-smeck-green-400/80 bg-smeck-dark-800 overflow-hidden shrink-0 shadow-lg flex items-center justify-center">
                                    @if($user->profile?->profile_photo_path)
                                        <img src="{{ asset('storage/' . $user->profile->profile_photo_path) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-smeck-green-700 to-smeck-dark-900 flex items-center justify-center font-display font-black text-xl text-white">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>

                                {{-- Name & NIA --}}
                                <div>
                                    <div class="font-display font-black text-sm sm:text-base text-white tracking-wide truncate max-w-[180px] sm:max-w-[220px]">
                                        {{ strtoupper($user->name) }}
                                    </div>
                                    <div class="font-mono text-xs sm:text-sm text-smeck-gold font-bold tracking-wider">
                                        {{ $user->profile?->nia ?? 'SMCK-2024-1950' }}
                                    </div>
                                    <div class="text-[9px] text-white/50 flex items-center gap-2 mt-0.5">
                                        <span>GOL: <strong class="text-white">{{ $user->profile?->blood_type ?? 'O' }}</strong></span>
                                        <span>•</span>
                                        <span>EST: <strong class="text-white">{{ $user->profile?->joined_date?->format('Y') ?? '2024' }}</strong></span>
                                    </div>
                                </div>
                            </div>

                            {{-- Nomor Kartu Fisik KTA --}}
                            <div class="text-right">
                                <div class="font-mono text-[9px] text-white/40 tracking-widest uppercase">No. Kartu KTA:</div>
                                <div class="font-mono text-[10px] sm:text-xs text-smeck-gold font-bold tracking-wider">
                                    {{ $user->nfcCard?->card_number ?? '6032-9800-2024-1950' }}
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- -------------------------------------------------- --}}
                    {{-- BACK OF THE CARD: ULTRA-MODERN VIP SUPPORTER NFC HYBRID --}}
                    {{-- -------------------------------------------------- --}}
                    <div class="card-face card-back p-3.5 sm:p-5 flex flex-col justify-between overflow-hidden border border-emerald-500/30 ring-1 ring-amber-400/20 shadow-2xl bg-gradient-to-br from-neutral-950 via-slate-950 to-neutral-950 text-white relative">
                        
                        {{-- Holographic Ambient Light Orbs --}}
                        <div class="absolute -top-12 -right-12 w-40 h-40 bg-emerald-500/15 rounded-full blur-2xl pointer-events-none"></div>
                        <div class="absolute -bottom-12 -left-12 w-40 h-40 bg-amber-500/10 rounded-full blur-2xl pointer-events-none"></div>

                        {{-- Holographic Sheen Layer --}}
                        <div class="absolute inset-0 shimmer-effect opacity-30 pointer-events-none"></div>

                        {{-- Watermark Rooster Crest --}}
                        <div class="absolute right-4 bottom-4 w-36 h-36 rounded-full overflow-hidden opacity-[0.04] pointer-events-none">
                            <img src="{{ asset('images/logo-crest.jpg') }}" alt="Watermark" class="w-full h-full object-cover">
                        </div>

                        {{-- TOP: Hi-Co Magnetic Stripe with Holographic Laser Micro-Text --}}
                        <div class="-mx-3.5 -mt-3.5 sm:-mx-5 sm:-mt-5 relative">
                            <div class="h-8 sm:h-9 holo-stripe border-b border-amber-400/20 shadow-inner flex items-center justify-between px-3 sm:px-5">
                                <div class="flex items-center gap-2 overflow-hidden select-none whitespace-nowrap">
                                    <span class="text-[7px] sm:text-[8px] font-mono tracking-widest text-white/50 uppercase font-semibold">
                                        ★ SMeCK HOOLIGAN ★ PSMS MEDAN 1950 ★ OFFICIAL SUPPORTER IDENTITY ★ NFC DUAL-INTERFACE PASS ★ SADA ROHA ★
                                    </span>
                                </div>
                                <div class="text-[7px] sm:text-[8px] font-mono text-amber-300/80 font-bold shrink-0 pl-2">
                                    HI-CO 4000 Oe
                                </div>
                            </div>

                            {{-- Technical Standard Sub-bar --}}
                            <div class="px-3 sm:px-5 py-1 flex items-center justify-between bg-black/40 border-b border-white/5 text-[7px] sm:text-[8px] text-white/50 font-mono">
                                <div class="flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                    <span class="text-white/70 font-semibold">DPP SMECK OFFICIAL NFC PASS</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span>ISO/IEC 7810 ID-1</span>
                                    <span>•</span>
                                    <span class="text-emerald-400 flex items-center gap-1">
                                        <i class="fa-solid fa-wifi rotate-90 text-[8px]"></i> 13.56 MHz
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- MIDDLE SECTION: Signature Panel + Barcode + Gate QR Scanner --}}
                        <div class="my-auto py-1 sm:py-2 flex items-center justify-between gap-3 sm:gap-4 relative z-10">
                            
                            {{-- Left Column: Security Signature Strip & Barcode & Terms --}}
                            <div class="flex-1 min-w-0 flex flex-col justify-between space-y-1.5 sm:space-y-2">
                                
                                {{-- Signature Strip --}}
                                <div>
                                    <div class="flex items-center justify-between text-[7px] sm:text-[8px] uppercase tracking-wider mb-0.5">
                                        <span class="text-white/70 font-bold flex items-center gap-1">
                                            <i class="fa-solid fa-signature text-emerald-400 text-[8px]"></i> Tanda Tangan Resmi Pemegang
                                        </span>
                                        <span class="text-[6px] sm:text-[7px] text-amber-300 font-mono">AUTHORIZED SIGNATURE</span>
                                    </div>
                                    
                                    <div class="flex items-center rounded-lg overflow-hidden border border-emerald-500/40 shadow-inner">
                                        {{-- Guilloche Pattern Signature Area --}}
                                        <div class="flex-1 h-7 sm:h-8 guilloche-bg flex items-center px-2.5 relative overflow-hidden">
                                            {{-- Security micro-watermark --}}
                                            <div class="absolute inset-0 flex items-center justify-around opacity-15 select-none pointer-events-none text-[6px] font-mono tracking-widest text-slate-800">
                                                <span>SMECK</span><span>HOOLIGAN</span><span>VALID</span><span>MEMBER</span>
                                            </div>
                                            {{-- Cursive handwritten signature --}}
                                            <span class="relative z-10 font-signature text-base sm:text-lg text-slate-900 font-bold select-none -rotate-1 tracking-wide truncate">
                                                {{ $user->name }}
                                            </span>
                                        </div>
                                        
                                        {{-- Embedded Security Code Pill (CVV Style) --}}
                                        <div class="h-7 sm:h-8 px-2 sm:px-2.5 bg-gradient-to-br from-amber-300 via-amber-400 to-amber-500 text-slate-950 flex flex-col items-center justify-center border-l border-amber-600/40 shrink-0">
                                            <span class="text-[6px] font-bold uppercase tracking-tighter opacity-70">SEC CODE</span>
                                            <span class="font-mono font-black text-[9px] sm:text-[10px] tracking-wider leading-none">
                                                {{ substr(crc32($user->id . 'smeck1950'), 0, 3) ?: '195' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Laser Etched Barcode --}}
                                <div class="pt-0.5">
                                    <div class="h-4 sm:h-5 flex items-center gap-[1.5px] sm:gap-[2px] opacity-75 overflow-hidden">
                                        {{-- Stylized Laser Barcode Lines --}}
                                        @for($i = 0; $i < 38; $i++)
                                            @php
                                                $barWidth = ($i % 5 == 0 || $i % 7 == 0) ? 'w-1 sm:w-1.5' : (($i % 3 == 0) ? 'w-0.5 sm:w-1' : 'w-[1px]');
                                                $barOpacity = ($i % 4 == 0) ? 'bg-white' : 'bg-emerald-300';
                                            @endphp
                                            <div class="{{ $barWidth }} h-full {{ $barOpacity }} rounded-full"></div>
                                        @endfor
                                    </div>
                                    <div class="flex items-center justify-between text-[7px] sm:text-[8px] font-mono text-white/50 tracking-wider mt-0.5">
                                        <span>* {{ $user->profile?->nia ?? 'SMCK-2024-1950' }} *</span>
                                        <span class="text-amber-400/90 font-bold uppercase">SUPORTER TRIBUN RESMI</span>
                                    </div>
                                </div>

                                {{-- Official Terms --}}
                                <div class="text-[7px] sm:text-[8px] text-white/45 leading-tight line-clamp-2">
                                    Kartu ini adalah hak milik resmi DPP SMeCK Hooligan. Wajib ditunjukkan saat verifikasi gate Stadion Teladan & penukaran gelang matchday.
                                </div>
                            </div>

                            {{-- Right Column: Gate Access QR Scanner Box --}}
                            <div class="shrink-0 flex flex-col items-center">
                                <div class="relative p-1.5 sm:p-2 rounded-xl bg-gradient-to-b from-slate-900 to-black border border-emerald-500/40 shadow-xl flex flex-col items-center">
                                    
                                    {{-- Viewfinder Corner Crosshairs --}}
                                    <div class="absolute top-0.5 left-0.5 w-2 h-2 border-t-2 border-l-2 border-emerald-400 rounded-tl"></div>
                                    <div class="absolute top-0.5 right-0.5 w-2 h-2 border-t-2 border-r-2 border-emerald-400 rounded-tr"></div>
                                    <div class="absolute bottom-0.5 left-0.5 w-2 h-2 border-b-2 border-l-2 border-emerald-400 rounded-bl"></div>
                                    <div class="absolute bottom-0.5 right-0.5 w-2 h-2 border-b-2 border-r-2 border-emerald-400 rounded-br"></div>

                                    {{-- Status Pill --}}
                                    <div class="flex items-center gap-1 px-1.5 py-0.5 mb-1 rounded-full bg-emerald-500/20 border border-emerald-500/30 text-[6px] sm:text-[7px] font-mono text-emerald-300 font-bold uppercase">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                        <span>GATE TELADAN</span>
                                    </div>

                                    {{-- QR Matrix Container --}}
                                    <div class="w-16 h-16 sm:w-20 sm:h-20 bg-white rounded-lg p-1 shadow-md flex items-center justify-center relative overflow-hidden">
                                        <div id="dynamic-kta-qr" class="w-full h-full flex items-center justify-center">
                                            {{-- High-Density SVG QR Matrix --}}
                                            <svg class="w-full h-full" viewBox="0 0 100 100" fill="none">
                                                <rect width="100" height="100" fill="white"/>
                                                
                                                {{-- Top-Left Finder Pattern --}}
                                                <rect x="6" y="6" width="26" height="26" rx="2" fill="#052e16"/>
                                                <rect x="10" y="10" width="18" height="18" fill="white"/>
                                                <rect x="13" y="13" width="12" height="12" rx="1.5" fill="#052e16"/>

                                                {{-- Top-Right Finder Pattern --}}
                                                <rect x="68" y="6" width="26" height="26" rx="2" fill="#052e16"/>
                                                <rect x="72" y="10" width="18" height="18" fill="white"/>
                                                <rect x="75" y="13" width="12" height="12" rx="1.5" fill="#052e16"/>

                                                {{-- Bottom-Left Finder Pattern --}}
                                                <rect x="6" y="68" width="26" height="26" rx="2" fill="#052e16"/>
                                                <rect x="10" y="72" width="18" height="18" fill="white"/>
                                                <rect x="13" y="75" width="12" height="12" rx="1.5" fill="#052e16"/>

                                                {{-- Timing & Alignment Patterns --}}
                                                <rect x="36" y="10" width="4" height="4" fill="#052e16"/>
                                                <rect x="44" y="10" width="4" height="4" fill="#052e16"/>
                                                <rect x="52" y="10" width="4" height="4" fill="#052e16"/>
                                                <rect x="60" y="10" width="4" height="4" fill="#052e16"/>

                                                <rect x="10" y="36" width="4" height="4" fill="#052e16"/>
                                                <rect x="10" y="44" width="4" height="4" fill="#052e16"/>
                                                <rect x="10" y="52" width="4" height="4" fill="#052e16"/>
                                                <rect x="10" y="60" width="4" height="4" fill="#052e16"/>

                                                <rect x="70" y="70" width="16" height="16" rx="1" fill="#052e16"/>
                                                <rect x="73" y="73" width="10" height="10" fill="white"/>
                                                <rect x="76" y="76" width="4" height="4" fill="#052e16"/>

                                                {{-- Data Modules --}}
                                                <rect x="36" y="22" width="6" height="6" fill="#052e16"/>
                                                <rect x="46" y="18" width="6" height="6" fill="#052e16"/>
                                                <rect x="56" y="24" width="6" height="6" fill="#052e16"/>
                                                
                                                <rect x="22" y="38" width="6" height="6" fill="#052e16"/>
                                                <rect x="32" y="34" width="6" height="6" fill="#052e16"/>
                                                <rect x="62" y="38" width="6" height="6" fill="#052e16"/>
                                                
                                                <rect x="18" y="48" width="6" height="6" fill="#052e16"/>
                                                <rect x="28" y="56" width="6" height="6" fill="#052e16"/>
                                                <rect x="38" y="50" width="6" height="6" fill="#052e16"/>
                                                <rect x="58" y="52" width="6" height="6" fill="#052e16"/>
                                                <rect x="68" y="48" width="6" height="6" fill="#052e16"/>

                                                <rect x="36" y="64" width="6" height="6" fill="#052e16"/>
                                                <rect x="46" y="60" width="6" height="6" fill="#052e16"/>
                                                <rect x="56" y="66" width="6" height="6" fill="#052e16"/>

                                                <rect x="36" y="76" width="6" height="6" fill="#052e16"/>
                                                <rect x="46" y="80" width="6" height="6" fill="#052e16"/>
                                                <rect x="56" y="76" width="6" height="6" fill="#052e16"/>

                                                {{-- Center Emblem Badge --}}
                                                <circle cx="50" cy="50" r="10" fill="#052e16" stroke="white" stroke-width="2"/>
                                                <path d="M47 45 L53 45 L55 52 L50 55 L45 52 Z" fill="#22c55e"/>
                                                <circle cx="50" cy="48" r="2" fill="#fbbf24"/>
                                            </svg>
                                        </div>
                                    </div>

                                    {{-- Micro Scan Prompt --}}
                                    <div class="text-[6px] sm:text-[7px] text-white/70 font-mono font-bold mt-1 tracking-wider uppercase">
                                        SCAN PRESENSI
                                    </div>
                                </div>
                            </div>

                        </div>

                        {{-- BOTTOM FOOTER: DPP Emergency Hotline & Gold Foil Slogan --}}
                        <div class="pt-1.5 sm:pt-2 border-t border-white/10 flex items-center justify-between text-[7px] sm:text-[9px] relative z-10">
                            {{-- Hotline DPP --}}
                            <div class="flex items-center gap-1.5 sm:gap-2">
                                <div class="w-5 h-5 sm:w-6 sm:h-6 rounded-md bg-red-500/20 text-red-400 flex items-center justify-center text-[9px] sm:text-xs shrink-0 border border-red-500/30">
                                    <i class="fa-solid fa-headset"></i>
                                </div>
                                <div>
                                    <div class="text-[6px] sm:text-[7px] text-white/40 uppercase font-mono">Bantuan & Darurat DPP:</div>
                                    <div class="font-mono text-[8px] sm:text-[10px] font-bold text-white tracking-wide">
                                        {{ $user->profile?->emergency_contact_number ?? '0812-6543-2199' }}
                                    </div>
                                </div>
                            </div>

                            {{-- Holographic Security Emblem --}}
                            <div class="hidden sm:flex items-center gap-1 px-2 py-0.5 rounded-full bg-white/[0.04] border border-white/10 text-[7px] font-mono text-white/60">
                                <i class="fa-solid fa-shield-halved text-amber-400 text-[8px]"></i>
                                <span>AUTHENTIC PASS</span>
                            </div>

                            {{-- Slogan --}}
                            <div class="text-right">
                                <div class="font-display font-black text-[9px] sm:text-xs text-smeck-gold tracking-widest drop-shadow">
                                    "SALAM SADA ROHA"
                                </div>
                                <div class="text-[6px] sm:text-[7px] font-mono text-emerald-400 font-bold tracking-widest uppercase">
                                    RIBAT SUDE • PSMS MEDAN
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

            <div class="mt-4 text-xs text-white/40 flex items-center gap-2">
                <i class="fa-solid fa-hand-pointer text-smeck-green-400"></i>
                <span>Klik kartu di atas untuk memutar tampilan (flip effect)</span>
            </div>
        </div>

        {{-- Right 5 Cols: Quick Member Status & Shortcuts --}}
        <div class="lg:col-span-5 space-y-4">
            
            {{-- Quick Card Info Detail Card --}}
            <div class="p-6 rounded-3xl bg-smeck-dark-900 border border-white/10 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-white/10">
                    <h3 class="font-display font-bold text-base text-white">Status Fisik & NFC</h3>
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-smeck-green-500/20 text-smeck-green-300 border border-smeck-green-500/30">
                        {{ strtoupper($user->nfcCard?->card_vendor ?? 'E-Money Mandiri') }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-3 text-xs">
                    <div class="p-3 rounded-2xl bg-white/5 border border-white/5">
                        <div class="text-white/40">Status Kartu:</div>
                        <div class="text-sm font-bold mt-0.5 {{ $user->nfcCard?->status === 'blocked' ? 'text-red-400' : 'text-smeck-green-400' }}">
                            {{ $user->nfcCard?->status === 'blocked' ? 'DIBLOKIR SEMENTARA' : 'AKTIF & DAPAT DITAP' }}
                        </div>
                    </div>
                    <div class="p-3 rounded-2xl bg-white/5 border border-white/5">
                        <div class="text-white/40">Golongan Darah:</div>
                        <div class="text-sm font-bold text-white mt-0.5">
                            Tipe {{ $user->profile?->blood_type ?? 'O' }} (Rhesus +)
                        </div>
                    </div>
                    <div class="p-3 rounded-2xl bg-white/5 border border-white/5">
                        <div class="text-white/40">NIK KTP Terdaftar:</div>
                        <div class="text-xs font-mono font-bold text-white/80 mt-0.5">
                            {{ substr($user->profile?->nik_ktp ?? '1271081206980005', 0, 6) }}******
                        </div>
                    </div>
                    <div class="p-3 rounded-2xl bg-white/5 border border-white/5">
                        <div class="text-white/40">Ukuran Kaos Jersey:</div>
                        <div class="text-sm font-bold text-smeck-gold mt-0.5">
                            Size {{ $user->profile?->shirt_size ?? 'L' }}
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="pt-2 flex flex-col sm:flex-row gap-2.5">
                    <a href="{{ route('member.profile') }}" class="flex-1 py-2.5 px-4 rounded-xl bg-white/5 hover:bg-white/10 text-white text-xs font-bold text-center border border-white/10 transition-colors">
                        <i class="fa-solid fa-user-pen mr-1.5"></i> Edit Biodata
                    </a>
                    <a href="{{ route('member.emergency') }}" class="py-2.5 px-4 rounded-xl bg-red-500/10 hover:bg-red-500/20 text-red-400 text-xs font-bold text-center border border-red-500/20 transition-colors">
                        <i class="fa-solid fa-lock mr-1.5"></i> Lapor Hilang
                    </a>
                </div>

                {{-- Quick Self-Checkin Button (Solusi 3) --}}
                <div class="pt-2">
                    <button type="button" onclick="openMemberCheckinModal()" class="w-full py-2.5 px-4 rounded-xl bg-gradient-to-r from-emerald-600 via-smeck-green-500 to-emerald-600 hover:from-emerald-500 hover:to-smeck-green-400 text-white font-bold text-xs shadow-lg shadow-emerald-900/30 transition-all flex items-center justify-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-qrcode text-sm"></i>
                        <span>Presensi Mandiri (Scan QR Event / Matchday)</span>
                    </button>
                </div>
            </div>

            {{-- Upcoming Match Ticket Card --}}
            @if(isset($upcomingMatch))
                <div class="p-5 rounded-3xl bg-gradient-to-br from-smeck-green-950/60 to-smeck-dark-900 border border-smeck-green-500/30">
                    <div class="flex items-center justify-between text-xs text-smeck-green-400 font-bold uppercase tracking-wider mb-2">
                        <span><i class="fa-regular fa-futbol mr-1"></i> Pertandingan Berikutnya</span>
                        <span class="text-smeck-gold">Tribun SMeCK</span>
                    </div>
                    <div class="font-display font-black text-lg text-white">
                        {{ $upcomingMatch->match_title }}
                    </div>
                    <div class="text-xs text-white/60 mt-1 flex items-center gap-2">
                        <i class="fa-regular fa-calendar"></i>
                        <span>{{ $upcomingMatch->match_time->translatedFormat('l, d F Y - H:i') }} WIB</span>
                    </div>
                    <div class="text-xs text-white/60 mt-0.5 flex items-center gap-2">
                        <i class="fa-solid fa-location-dot text-smeck-green-400"></i>
                        <span>{{ $upcomingMatch->stadium }}</span>
                    </div>
                    <div class="mt-4 flex items-center justify-between pt-3 border-t border-white/10">
                        <div>
                            <span class="text-[10px] text-white/40 block">Harga Khusus Member</span>
                            <span class="text-base font-bold text-smeck-gold font-mono">Rp {{ number_format($upcomingMatch->price, 0, ',', '.') }}</span>
                        </div>
                        <a href="{{ route('member.tickets') }}" class="px-4 py-2 rounded-xl bg-smeck-green-500 hover:bg-smeck-green-400 text-white text-xs font-bold transition-colors">
                            Ambil Tiket
                        </a>
                    </div>
                </div>
            @endif

            {{-- PSMS Liga 2 Standing Status Widget --}}
            @if(isset($psmsStanding))
                <div class="p-4 rounded-3xl bg-smeck-dark-900 border border-emerald-500/30 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-sm font-black shrink-0">
                            #{{ $psmsStanding->position }}
                        </div>
                        <div>
                            <div class="text-[10px] text-emerald-400 font-bold uppercase tracking-wider flex items-center gap-1">
                                <i class="fa-solid fa-trophy text-[9px]"></i> Pegadaian Liga 2 (Wilayah Barat)
                            </div>
                            <div class="text-white font-bold text-xs mt-0.5">
                                PSMS Medan · <span class="text-emerald-300 font-mono">{{ $psmsStanding->points }} PTS</span> ({{ $psmsStanding->won }}M-{{ $psmsStanding->drawn }}S-{{ $psmsStanding->lost }}K)
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        @if($psmsStanding->position === 1)
                            <span class="px-2.5 py-1 rounded-full text-[9px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                🟢 Lolos Langsung Liga 1
                            </span>
                        @elseif($psmsStanding->position === 2)
                            <span class="px-2.5 py-1 rounded-full text-[9px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30">
                                🟡 Play-off vs Runner-up Grup B
                            </span>
                        @else
                            <span class="px-2.5 py-1 rounded-full text-[9px] font-bold bg-white/10 text-white/70 border border-white/15">
                                ⚪ Peringkat 3 (Kejar Play-off)
                            </span>
                        @endif
                    </div>
                </div>
            @endif

        </div>

    </div>

    {{-- ============================================================== --}}
    {{-- STATS & QUICK LOGS --}}
    {{-- ============================================================== --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- Stat 1: Total Presensi --}}
        <div class="p-6 rounded-3xl bg-smeck-dark-900 border border-white/10 flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-smeck-green-500/20 text-smeck-green-400 flex items-center justify-center text-2xl shrink-0">
                <i class="fa-solid fa-fingerprint"></i>
            </div>
            <div>
                <div class="text-xs font-semibold uppercase tracking-wider text-white/40">Total Presensi Tap</div>
                <div class="text-2xl font-black font-display text-white mt-1">
                    {{ $user->attendances->count() }} <span class="text-xs font-normal text-white/50">Pertandingan</span>
                </div>
                <div class="text-[11px] text-smeck-green-400 mt-0.5">Tercatat di Server Presensi SMeCK</div>
            </div>
        </div>

        {{-- Stat 2: Iuran Kas Sukarela --}}
        <div class="p-6 rounded-3xl bg-smeck-dark-900 border border-white/10 flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-smeck-gold/20 text-smeck-gold flex items-center justify-center text-2xl shrink-0">
                <i class="fa-solid fa-hand-holding-dollar"></i>
            </div>
            <div>
                <div class="text-xs font-semibold uppercase tracking-wider text-white/40">Kontribusi Kas & Donasi</div>
                <div class="text-2xl font-black font-display text-white mt-1">
                    Rp {{ number_format($user->voluntaryDues->where('status', 'verified')->sum('amount'), 0, ',', '.') }}
                </div>
                <div class="text-[11px] text-white/50 mt-0.5">Mendukung kegiatan & koreografi</div>
            </div>
        </div>

        {{-- Stat 3: Status Basis Chapter --}}
        <div class="p-6 rounded-3xl bg-smeck-dark-900 border border-white/10 flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-blue-500/20 text-blue-400 flex items-center justify-center text-2xl shrink-0">
                <i class="fa-solid fa-building-flag"></i>
            </div>
            <div>
                <div class="text-xs font-semibold uppercase tracking-wider text-white/40">Kode Basis Wilayah</div>
                <div class="text-2xl font-black font-display text-white mt-1">
                    {{ $user->chapter?->region_code ?? 'MDK-01' }}
                </div>
                <div class="text-[11px] text-blue-400 mt-0.5">{{ $user->chapter?->leader_name ? 'Korwil: ' . $user->chapter->leader_name : 'Pusat Teladan' }}</div>
            </div>
        </div>

    </div>

    {{-- ============================================================== --}}
    {{-- RECENT LOGS SECTION --}}
    {{-- ============================================================== --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        {{-- Log 1: Riwayat Presensi NFC --}}
        <div class="p-6 sm:p-7 rounded-3xl bg-smeck-dark-900 border border-white/10">
            <div class="flex items-center justify-between mb-5">
                <h3 class="font-display font-bold text-lg text-white flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-smeck-green-400"></i>
                    Riwayat Tap NFC di Stadion
                </h3>
            </div>

            @if($recentAttendances->count() > 0)
                <div class="space-y-3">
                    @foreach($recentAttendances as $att)
                        <div class="p-3.5 rounded-2xl bg-white/[0.03] border border-white/5 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-smeck-green-500/20 text-smeck-green-400 flex items-center justify-center text-sm">
                                    <i class="fa-solid fa-check"></i>
                                </div>
                                <div>
                                    <div class="text-xs font-bold text-white">{{ $att->event_name }}</div>
                                    <div class="text-[11px] text-white/40">{{ $att->scanned_at->format('d M Y, H:i') }} WIB</div>
                                </div>
                            </div>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-smeck-green-500/20 text-smeck-green-300">
                                HADIR
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-white/40 text-xs">
                    Belum ada riwayat tap presensi pertandingan.
                </div>
            @endif
        </div>

        {{-- Log 2: Riwayat Iuran & Kas Sukarela --}}
        <div class="p-6 sm:p-7 rounded-3xl bg-smeck-dark-900 border border-white/10">
            <div class="flex items-center justify-between mb-5">
                <h3 class="font-display font-bold text-lg text-white flex items-center gap-2">
                    <i class="fa-solid fa-receipt text-smeck-gold"></i>
                    Riwayat Kas & Iuran Sukarela
                </h3>
                <a href="{{ route('member.dues') }}" class="text-xs font-bold text-smeck-green-400 hover:underline">
                    Kirim Bukti +
                </a>
            </div>

            @if($recentDues->count() > 0)
                <div class="space-y-3">
                    @foreach($recentDues as $due)
                        <div class="p-3.5 rounded-2xl bg-white/[0.03] border border-white/5 flex items-center justify-between">
                            <div>
                                <div class="text-xs font-bold text-white">{{ $due->notes ?? 'Iuran Kas Sukarela' }}</div>
                                <div class="text-[11px] text-white/40">{{ $due->created_at->format('d M Y') }}</div>
                            </div>
                            <div class="text-right">
                                <div class="text-xs font-bold font-mono text-smeck-gold">
                                    Rp {{ number_format($due->amount, 0, ',', '.') }}
                                </div>
                                <span class="text-[10px] uppercase font-bold {{ $due->status === 'verified' ? 'text-smeck-green-400' : 'text-amber-400' }}">
                                    {{ $due->status === 'verified' ? 'TERVERIFIKASI' : 'MENUNGGU VERIFIKASI' }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-white/40 text-xs">
                    Belum ada riwayat iuran sukarela.
                </div>
            @endif
        </div>

    </div>

</div>

{{-- ========================================================================= --}}
{{-- MODAL SIMPAN KTA HD KE GALERI HP / CETAK FISIK --}}
{{-- ========================================================================= --}}
<div id="modalDownloadKta" class="fixed inset-0 z-50 bg-black/90 backdrop-blur-md hidden flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-smeck-dark-900 border border-white/10 rounded-3xl max-w-2xl w-full p-6 sm:p-8 shadow-2xl relative my-8 print:p-0 print:m-0 print:shadow-none print:bg-white print:w-full">
        <div class="flex items-center justify-between pb-4 border-b border-white/10 mb-6 print:hidden">
            <div>
                <h3 class="font-display font-black text-lg text-white">Simpan / Cetak KTA Digital HD</h3>
                <p class="text-xs text-white/50">Tampak depan dan belakang KTA resmi suporter SMeCK Hooligan beresolusi tinggi.</p>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="window.print()" class="px-4 py-2 rounded-xl bg-smeck-green-600 hover:bg-smeck-green-500 text-white font-bold text-xs shadow flex items-center gap-1.5">
                    <i class="fa-solid fa-print"></i>
                    <span>Cetak / Simpan PDF</span>
                </button>
                <button onclick="closeDownloadKtaModal()" class="px-3 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs">
                    Tutup
                </button>
            </div>
        </div>

        {{-- Preview Kartu 2 Sisi HD --}}
        <div class="space-y-6">
            {{-- TAMPAK DEPAN KARTU --}}
            <div>
                <div class="text-[11px] font-bold uppercase tracking-wider text-smeck-gold mb-2 flex items-center gap-1.5 print:text-black">
                    <i class="fa-solid fa-credit-card"></i>
                    <span>Tampak Depan (Front View)</span>
                </div>
                <div class="rounded-2xl p-6 bg-gradient-to-br from-emerald-950 via-slate-900 to-black border border-white/20 text-white shadow-xl relative overflow-hidden print:border-slate-800">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl overflow-hidden border border-amber-400/50 bg-black p-0.5">
                                <img src="{{ asset('images/logo-crest.jpg') }}" alt="Logo" class="w-full h-full object-cover rounded-lg">
                            </div>
                            <div>
                                <div class="font-black text-sm tracking-wider uppercase">SMeCK HOOLIGAN</div>
                                <div class="text-[9px] text-amber-400 font-bold uppercase tracking-widest">KTA SUPORTER RESMI PSMS</div>
                            </div>
                        </div>
                        <span class="px-2 py-0.5 rounded bg-emerald-500 text-white font-bold text-[9px] uppercase tracking-wider">RESMI</span>
                    </div>

                    <div class="flex items-center justify-between gap-4 py-2">
                        <div>
                            <div class="text-[9px] text-white/50 uppercase">Nama Lengkap Anggota:</div>
                            <div class="text-base font-black tracking-wide">{{ $user->name }}</div>
                            <div class="text-[11px] font-mono text-amber-400 font-bold mt-0.5">
                                NIA: {{ $user->profile?->nia ?? 'SMCK-1950-2024' }}
                            </div>
                            <div class="text-[10px] text-white/60 mt-1">
                                Basis: <strong>{{ $user->chapter->name ?? 'Medan Kota' }}</strong> • Gol. Darah: <strong>{{ $user->profile?->blood_type ?? 'O' }}</strong>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="w-12 h-12 rounded-xl bg-white/10 border border-white/10 flex items-center justify-center font-black text-xl text-emerald-400 font-display">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-t border-white/10 flex items-center justify-between text-xs">
                        <div class="font-mono font-bold text-amber-400 tracking-wider">
                            {{ $user->nfcCard?->card_number ?? '6032-9800-2024-1950' }}
                        </div>
                        <div class="text-[9px] text-white/40 uppercase font-mono">
                            CHIP UID: {{ $user->nfcCard?->uid_number ? substr($user->nfcCard->uid_number, 0, 8).'••' : 'ONLINE NFC' }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- TAMPAK BELAKANG KARTU --}}
            <div>
                <div class="text-[11px] font-bold uppercase tracking-wider text-smeck-green-400 mb-2 flex items-center gap-1.5 print:text-black">
                    <i class="fa-solid fa-qrcode"></i>
                    <span>Tampak Belakang (Back View & QR Presensi Gate)</span>
                </div>
                <div class="rounded-2xl p-4 sm:p-5 bg-gradient-to-br from-neutral-950 via-slate-950 to-neutral-950 border border-emerald-500/30 text-white shadow-xl relative overflow-hidden print:border-slate-800">
                    
                    {{-- Magnetic Stripe --}}
                    <div class="h-8 -mx-4 sm:-mx-5 -mt-4 sm:-mt-5 mb-3 holo-stripe border-b border-amber-400/20 shadow-inner flex items-center justify-between px-4">
                        <span class="text-[7px] sm:text-[8px] font-mono tracking-widest text-white/50 uppercase font-semibold">
                            ★ SMeCK HOOLIGAN ★ PSMS MEDAN 1950 ★ OFFICIAL SUPPORTER IDENTITY ★ NFC DUAL-INTERFACE PASS ★
                        </span>
                        <span class="text-[7px] font-mono text-amber-300 font-bold shrink-0">HI-CO 4000 Oe</span>
                    </div>

                    {{-- Middle Body --}}
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex-1 space-y-2">
                            {{-- Signature Strip --}}
                            <div>
                                <div class="text-[8px] text-white/60 uppercase font-semibold mb-1 flex items-center justify-between">
                                    <span>Tanda Tangan Suporter:</span>
                                    <span class="text-[7px] text-amber-400 font-mono">AUTHORIZED SIGNATURE</span>
                                </div>
                                <div class="flex items-center rounded-lg overflow-hidden border border-emerald-500/40">
                                    <div class="flex-1 h-7 guilloche-bg flex items-center px-2.5">
                                        <span class="font-signature text-base text-slate-900 font-bold select-none -rotate-1">
                                            {{ $user->name }}
                                        </span>
                                    </div>
                                    <div class="h-7 px-2 bg-gradient-to-r from-amber-300 to-amber-500 text-slate-950 flex flex-col items-center justify-center font-mono font-black text-[9px] border-l border-amber-600/40">
                                        <span>{{ substr(crc32($user->id . 'smeck1950'), 0, 3) ?: '195' }}</span>
                                    </div>
                                </div>
                            </div>

                            <p class="text-[8px] text-white/50 leading-relaxed">
                                Tunjukkan QR Code ini kepada panitia pintu Stadion Teladan untuk verifikasi hak akses tribun & presensi suporter resmi.
                            </p>
                            <div class="text-[8px] text-amber-400 font-bold tracking-wider">
                                SALAM SADA ROHA • RIBAT SUDE • KINANTAN JUARA
                            </div>
                        </div>

                        {{-- QR Viewfinder --}}
                        <div class="p-1.5 rounded-xl bg-slate-900 border border-emerald-500/40 shrink-0 text-center">
                            <div class="w-16 h-16 bg-white rounded-lg p-1 flex items-center justify-center">
                                <div id="modal-dynamic-qr" class="w-full h-full flex items-center justify-center">
                                    <svg class="w-full h-full" viewBox="0 0 100 100" fill="none">
                                        <rect width="100" height="100" fill="white"/>
                                        <rect x="6" y="6" width="26" height="26" rx="2" fill="#052e16"/>
                                        <rect x="10" y="10" width="18" height="18" fill="white"/>
                                        <rect x="13" y="13" width="12" height="12" rx="1.5" fill="#052e16"/>
                                        <rect x="68" y="6" width="26" height="26" rx="2" fill="#052e16"/>
                                        <rect x="72" y="10" width="18" height="18" fill="white"/>
                                        <rect x="75" y="13" width="12" height="12" rx="1.5" fill="#052e16"/>
                                        <rect x="6" y="68" width="26" height="26" rx="2" fill="#052e16"/>
                                        <rect x="10" y="72" width="18" height="18" fill="white"/>
                                        <rect x="13" y="75" width="12" height="12" rx="1.5" fill="#052e16"/>
                                        <circle cx="50" cy="50" r="10" fill="#052e16" stroke="white" stroke-width="2"/>
                                        <path d="M47 45 L53 45 L55 52 L50 55 L45 52 Z" fill="#22c55e"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="text-[6px] text-white/70 font-mono font-bold mt-1 uppercase">PRESENSI GATE</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 text-center text-xs text-white/50 print:hidden">
            <i class="fa-solid fa-circle-info mr-1 text-amber-400"></i> Klik <strong>Cetak / Simpan PDF</strong> untuk menyimpan kartu ke perangkat atau mencetaknya ke kertas PVC.
        </div>
    </div>
</div>


@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    function flipKtaCard() {
        const card = document.getElementById('kta-card');
        card.classList.toggle('card-flipped');
    }

    function openDownloadKtaModal() {
        document.getElementById('modalDownloadKta').classList.remove('hidden');
    }

    function closeDownloadKtaModal() {
        document.getElementById('modalDownloadKta').classList.add('hidden');
    }

    function openMemberCheckinModal() {
        document.getElementById('modalMemberCheckin').classList.remove('hidden');
    }

    function closeMemberCheckinModal() {
        document.getElementById('modalMemberCheckin').classList.add('hidden');
    }

    // Generate dynamic verifiable QR Code
    document.addEventListener('DOMContentLoaded', function () {
        const qrVerificationUrl = "{{ route('api.check-kta', ['query' => $user->profile?->nia ?? ($user->nfcCard?->card_number ?? 'SMCK-1950')]) }}";
        
        if (typeof QRCode !== 'undefined') {
            try {
                const cardQr = document.getElementById('dynamic-kta-qr');
                if (cardQr) {
                    cardQr.innerHTML = '';
                    new QRCode(cardQr, {
                        text: qrVerificationUrl,
                        width: 76,
                        height: 76,
                        colorDark: "#052e16",
                        colorLight: "#ffffff",
                        correctLevel: QRCode.CorrectLevel.M
                    });
                }

                const modalQr = document.getElementById('modal-dynamic-qr');
                if (modalQr) {
                    modalQr.innerHTML = '';
                    new QRCode(modalQr, {
                        text: qrVerificationUrl,
                        width: 60,
                        height: 60,
                        colorDark: "#052e16",
                        colorLight: "#ffffff",
                        correctLevel: QRCode.CorrectLevel.M
                    });
                }
            } catch (e) {
                console.warn('QR Code generation fallback:', e);
            }
        }
    });
</script>
@endpush

