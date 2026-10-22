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

    /* Standar Ukuran Cetak Fisik PVC ISO/IEC 7810 ID-1 (85,60 x 53,98 mm) */
    @media print {
        @page {
            size: A4 portrait;
            margin: 15mm;
        }
        body {
            background: white !important;
            color: black !important;
        }
        body * {
            visibility: hidden !important;
        }
        #modalDownloadKta, #modalDownloadKta * {
            visibility: visible !important;
        }
        #modalDownloadKta {
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            background: transparent !important;
            padding: 0 !important;
            margin: 0 !important;
            display: block !important;
        }
        .print-hide {
            display: none !important;
        }
        .kta-pvc-card {
            width: 85.60mm !important;
            height: 53.98mm !important;
            min-width: 85.60mm !important;
            min-height: 53.98mm !important;
            max-width: 85.60mm !important;
            max-height: 53.98mm !important;
            border-radius: 3.18mm !important;
            box-sizing: border-box !important;
            page-break-inside: avoid !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            margin: 0 auto 8mm auto !important;
            box-shadow: none !important;
        }
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
                        KTA Digital Anda terverifikasi resmi oleh Pengurus {{ $user->chapter?->name ?? 'Chapter' }} SMeCK Hooligan. Tunjukkan QR Code atau tap kartu fisik NFC di gate masuk Stadion Teladan.
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
                    <span>Unduh KTA (PNG / PDF)</span>
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
                    {{-- TAMPAK BELAKANG KARTU DIGITAL ANGGOTA --}}
                    {{-- -------------------------------------------------- --}}
                    <div class="card-face card-back p-4 sm:p-5 flex flex-col justify-between overflow-hidden border border-white/15 shadow-2xl bg-gradient-to-br from-neutral-950 via-slate-950 to-neutral-900 text-white relative">
                        
                        {{-- Efek Kilau Halus --}}
                        <div class="absolute inset-0 shimmer-effect opacity-20 pointer-events-none"></div>

                        {{-- 1. PITA MAGNETIK ATAS --}}
                        <div class="-mx-4 -mt-4 sm:-mx-5 sm:-mt-5">
                            <div class="h-9 bg-neutral-900 border-b border-white/10 flex items-center justify-between px-4 sm:px-5">
                                <span class="text-[8px] sm:text-[9px] font-mono tracking-widest text-white/60 uppercase">
                                    KARTU TANDA ANGGOTA DIGITAL • {{ strtoupper($user->chapter?->name ?? 'CHAPTER SMECK HOOLIGAN') }}
                                </span>
                                <span class="text-[8px] sm:text-[9px] font-display font-bold text-smeck-gold tracking-wider">
                                    SADA ROHA
                                </span>
                            </div>

                            {{-- Sub-bar Identitas --}}
                            <div class="px-4 sm:px-5 py-1 bg-black/40 border-b border-white/5 flex items-center justify-between text-[8px] sm:text-[9px]">
                                <div class="flex items-center gap-1.5 text-emerald-400 font-semibold">
                                    <i class="fa-solid fa-circle-check text-[9px]"></i>
                                    <span>Suporter Resmi PSMS Medan</span>
                                </div>
                                <div class="text-white/50 font-mono flex items-center gap-1.5">
                                    <i class="fa-solid fa-wifi rotate-90 text-[8px] text-emerald-400"></i>
                                    <span>NFC Terhubung</span>
                                </div>
                            </div>
                        </div>

                        {{-- 2. KONTEN UTAMA: Tanda Tangan & QR Presensi --}}
                        <div class="my-auto py-1 sm:py-2 flex items-center justify-between gap-3 sm:gap-5 relative z-10">
                            
                            {{-- Sisi Kiri: Tanda Tangan, Identitas & Ketentuan --}}
                            <div class="flex-1 min-w-0 flex flex-col justify-between space-y-2">
                                
                                {{-- Area Tanda Tangan --}}
                                <div>
                                    <div class="text-[8px] sm:text-[9px] text-white/60 font-medium mb-1">
                                        Tanda Tangan Pemegang Kartu:
                                    </div>
                                    <div class="flex items-center rounded-lg overflow-hidden border border-white/15 bg-white shadow-sm">
                                        <div class="flex-1 h-8 sm:h-9 bg-slate-50 flex items-center px-3">
                                            <span class="font-signature text-base sm:text-lg text-slate-900 font-bold select-none -rotate-1 tracking-wide truncate">
                                                {{ $user->name }}
                                            </span>
                                        </div>
                                        <div class="px-2.5 h-8 sm:h-9 bg-emerald-600/15 border-l border-emerald-500/30 flex items-center justify-center">
                                            <span class="text-[8px] sm:text-[9px] font-bold text-emerald-600 uppercase tracking-wider flex items-center gap-1">
                                                <i class="fa-solid fa-shield-halved text-[9px]"></i> RESMI
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Ringkasan Identitas --}}
                                <div class="flex items-center gap-3 text-[8px] sm:text-[9px] font-mono text-white/70">
                                    <div>NIA: <strong class="text-smeck-gold">{{ $user->profile?->nia ?? 'SMCK-2024-1950' }}</strong></div>
                                    <span>•</span>
                                    <div>Basis: <strong class="text-white">{{ $user->chapter?->name ?? 'Medan Kota' }}</strong></div>
                                </div>

                                {{-- Ketentuan Penggunaan (Bahasa Normal & Santun) --}}
                                <div class="text-[7px] sm:text-[8px] text-white/50 leading-relaxed space-y-0.5">
                                    <div>• Kartu ini adalah bukti keanggotaan sah SMeCK Hooligan {{ $user->chapter?->name ? '('.$user->chapter->name.')' : '' }}.</div>
                                    <div>• Tunjukkan kartu saat presensi dan penukaran tiket di stadion.</div>
                                    <div>• Jika menemukan kartu ini, harap kembalikan ke Pengurus {{ $user->chapter?->name ?? 'Chapter' }}.</div>
                                </div>
                            </div>

                            {{-- Sisi Kanan: Kotak QR Presensi --}}
                            <div class="shrink-0 flex flex-col items-center">
                                <div class="p-2 rounded-xl bg-white/[0.04] border border-white/15 text-center flex flex-col items-center">
                                    <div class="text-[7px] sm:text-[8px] font-bold text-emerald-400 uppercase tracking-wider mb-1 flex items-center gap-1">
                                        <i class="fa-solid fa-qrcode text-[8px]"></i> QR Presensi
                                    </div>
                                    <div class="w-18 h-18 sm:w-20 sm:h-20 bg-white rounded-lg p-1.5 flex items-center justify-center shadow-md">
                                        <div id="dynamic-kta-qr" class="w-full h-full flex items-center justify-center">
                                            {{-- Matriks QR Bersih & Presisi --}}
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
                                                <rect x="36" y="10" width="5" height="5" fill="#052e16"/>
                                                <rect x="48" y="10" width="5" height="5" fill="#052e16"/>
                                                <rect x="58" y="10" width="5" height="5" fill="#052e16"/>
                                                <rect x="10" y="36" width="5" height="5" fill="#052e16"/>
                                                <rect x="10" y="48" width="5" height="5" fill="#052e16"/>
                                                <rect x="10" y="58" width="5" height="5" fill="#052e16"/>
                                                <rect x="36" y="24" width="6" height="6" fill="#052e16"/>
                                                <rect x="46" y="20" width="6" height="6" fill="#052e16"/>
                                                <rect x="56" y="26" width="6" height="6" fill="#052e16"/>
                                                <rect x="22" y="40" width="6" height="6" fill="#052e16"/>
                                                <rect x="34" y="36" width="6" height="6" fill="#052e16"/>
                                                <rect x="64" y="40" width="6" height="6" fill="#052e16"/>
                                                <rect x="20" y="50" width="6" height="6" fill="#052e16"/>
                                                <rect x="30" y="58" width="6" height="6" fill="#052e16"/>
                                                <rect x="40" y="52" width="6" height="6" fill="#052e16"/>
                                                <rect x="60" y="54" width="6" height="6" fill="#052e16"/>
                                                <rect x="70" y="50" width="6" height="6" fill="#052e16"/>
                                                <rect x="36" y="66" width="6" height="6" fill="#052e16"/>
                                                <rect x="48" y="62" width="6" height="6" fill="#052e16"/>
                                                <rect x="58" y="68" width="6" height="6" fill="#052e16"/>
                                                <rect x="36" y="78" width="6" height="6" fill="#052e16"/>
                                                <rect x="48" y="82" width="6" height="6" fill="#052e16"/>
                                                <rect x="58" y="78" width="6" height="6" fill="#052e16"/>
                                                <circle cx="50" cy="50" r="9" fill="#052e16" stroke="white" stroke-width="1.5"/>
                                                <path d="M47 46 L53 46 L54 52 L50 54 L46 52 Z" fill="#22c55e"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="text-[7px] text-white/50 font-mono mt-1">
                                        Pindai di Gate Stadion
                                    </div>
                                </div>
                            </div>

                        </div>

                        {{-- 3. FOOTER: Hotline Sekretariat & Slogan --}}
                        <div class="pt-1.5 sm:pt-2 border-t border-white/10 flex items-center justify-between text-[8px] sm:text-[9px] relative z-10">
                            <div class="flex items-center gap-1.5">
                                <i class="fa-solid fa-phone text-emerald-400 text-[10px]"></i>
                                <span class="text-white/60">
                                    Pengurus {{ $user->chapter?->name ?? 'Chapter' }}: <strong class="text-white font-mono">{{ $user->profile?->emergency_contact_number ?? '0812-6543-2199' }}</strong>
                                </span>
                            </div>
                            <div class="text-right">
                                <div class="font-display font-black text-[9px] sm:text-xs text-smeck-gold tracking-widest">
                                    "SALAM SADA ROHA"
                                </div>
                                <div class="text-[7px] text-white/40 uppercase tracking-wider">
                                    Ayam Kinantan Juara
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
<div id="modalDownloadKta" class="fixed inset-0 z-50 bg-black/90 backdrop-blur-md hidden flex items-center justify-center p-3 sm:p-4 overflow-y-auto print:p-0 print:m-0 print:bg-white print:block">
    <div class="bg-smeck-dark-900 border border-white/15 rounded-3xl max-w-xl w-full shadow-2xl relative my-auto flex flex-col max-h-[92vh] overflow-hidden print:p-0 print:m-0 print:shadow-none print:bg-white print:w-full print:max-w-none print:max-h-none print:border-none">
        
        {{-- Header Modal & Navigasi Tab --}}
        <div class="p-4 sm:p-5 border-b border-white/10 shrink-0 bg-neutral-950/70 print-hide">
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center text-lg shrink-0 border border-amber-500/30">
                        <i class="fa-solid fa-id-card"></i>
                    </div>
                    <div class="min-w-0">
                        <h3 class="font-display font-black text-base sm:text-lg text-white leading-tight truncate">
                            Unduh KTA Resmi (PNG / PDF)
                        </h3>
                        <p class="text-[11px] text-white/50 font-mono mt-0.5 truncate">
                            Standar Cetak PVC ATM: <strong>8,56 × 5,4 cm</strong> (1016 × 635 px • 300 DPI)
                        </p>
                    </div>
                </div>
                <button type="button" onclick="closeDownloadKtaModal()" class="w-8 h-8 rounded-xl bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-all text-sm shrink-0" title="Tutup">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            {{-- Tab Switcher Depan / Belakang / Kedua Sisi --}}
            <div class="flex items-center justify-center p-1 rounded-xl bg-white/5 border border-white/10 max-w-xs mx-auto mt-3.5">
                <button type="button" onclick="switchKtaTab('front')" id="tab-btn-front" class="flex-1 py-1.5 px-3 rounded-lg text-xs font-bold transition-all bg-emerald-600 text-white shadow">
                    <i class="fa-solid fa-id-card mr-1"></i> Depan
                </button>
                <button type="button" onclick="switchKtaTab('back')" id="tab-btn-back" class="flex-1 py-1.5 px-3 rounded-lg text-xs font-bold transition-all text-white/60 hover:text-white">
                    <i class="fa-solid fa-rotate mr-1"></i> Belakang
                </button>
                <button type="button" onclick="switchKtaTab('both')" id="tab-btn-both" class="flex-1 py-1.5 px-3 rounded-lg text-xs font-bold transition-all text-white/60 hover:text-white">
                    <i class="fa-solid fa-layer-group mr-1"></i> Kedua Sisi
                </button>
            </div>
        </div>

        {{-- Scrollable Card Preview Container (Proporsional & Rapi) --}}
        <div class="flex-1 overflow-y-auto p-4 sm:p-5 space-y-4">
            
            {{-- 1. SISI DEPAN (FRONT VIEW) --}}
            <div id="kta-preview-front-wrapper" class="w-full max-w-[460px] mx-auto">
                <div class="text-[10px] font-bold uppercase tracking-wider text-smeck-gold mb-1.5 flex items-center justify-between print-hide">
                    <span class="flex items-center gap-1.5">
                        <i class="fa-solid fa-credit-card"></i>
                        <span>Tampak Depan (Front View)</span>
                    </span>
                    <span class="font-mono text-white/40">1016 × 635 px • 300 DPI</span>
                </div>
                
                {{-- Container Depan Berukuran Rasio 85.6 / 53.98 --}}
                <div id="kta-pvc-front" class="kta-pvc-card w-full aspect-[85.6/53.98] rounded-2xl p-4 sm:p-5 bg-gradient-to-br from-emerald-950 via-smeck-dark-900 to-black border border-white/20 text-white shadow-2xl relative overflow-hidden flex flex-col justify-between">
                    {{-- Shimmer & Watermark --}}
                    <div class="absolute inset-0 shimmer-effect pointer-events-none opacity-20"></div>
                    <div class="absolute -right-6 -bottom-6 w-36 h-36 rounded-full overflow-hidden opacity-10 pointer-events-none">
                        <img src="{{ asset('images/logo-crest.jpg') }}" alt="Watermark" class="w-full h-full object-cover">
                    </div>

                    {{-- Header Kartu Depan --}}
                    <div class="relative z-10 flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl overflow-hidden border border-amber-400/60 p-0.5 bg-black shrink-0 shadow">
                                <img src="{{ asset('images/logo-crest.jpg') }}" alt="Logo" class="w-full h-full object-cover rounded-lg">
                            </div>
                            <div>
                                <div class="font-display font-black text-xs sm:text-sm tracking-wider uppercase text-white leading-tight">
                                    SMeCK HOOLIGAN
                                </div>
                                <div class="text-[8px] sm:text-[9px] text-amber-400 font-bold uppercase tracking-widest">
                                    SUPORTER RESMI PSMS MEDAN
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-wifi rotate-90 text-white/50 text-xs sm:text-sm"></i>
                            <span class="px-2 py-0.5 rounded text-[8px] sm:text-[9px] font-bold bg-smeck-green-500 text-white uppercase tracking-wider shadow-sm">
                                RESMI & AKTIF
                            </span>
                        </div>
                    </div>

                    {{-- Tengah: Gold Chip & Basis Chapter --}}
                    <div class="relative z-10 flex items-center justify-between my-auto py-1">
                        <div class="w-10 h-7 sm:w-11 sm:h-8 rounded bg-gradient-to-br from-amber-200 via-amber-400 to-amber-600 shadow-inner border border-amber-300/70 p-1 flex flex-col justify-between shrink-0">
                            <div class="h-[1px] bg-amber-900/40 w-full mt-0.5"></div>
                            <div class="h-[1px] bg-amber-900/40 w-2/3 mx-auto"></div>
                            <div class="h-[1px] bg-amber-900/40 w-full mb-0.5"></div>
                        </div>
                        <div class="text-right">
                            <div class="text-[8px] text-white/40 uppercase font-bold tracking-widest">BASIS CHAPTER</div>
                            <div class="text-xs sm:text-sm font-bold text-smeck-green-300 font-display">
                                {{ $user->chapter?->name ?? 'Chapter Medan Kota' }}
                            </div>
                        </div>
                    </div>

                    {{-- Footer Depan: Foto, Identitas, No Kartu --}}
                    <div class="relative z-10 flex items-end justify-between pt-2 border-t border-white/10 gap-2.5">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl border-2 border-smeck-green-400/80 bg-smeck-dark-800 overflow-hidden shrink-0 shadow flex items-center justify-center">
                                @if($user->profile?->profile_photo_path)
                                    <img src="{{ asset('storage/' . $user->profile->profile_photo_path) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-smeck-green-700 to-smeck-dark-900 flex items-center justify-center font-display font-black text-lg text-white">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <div class="font-display font-black text-xs sm:text-sm text-white tracking-wide truncate max-w-[160px] sm:max-w-[200px]">
                                    {{ strtoupper($user->name) }}
                                </div>
                                <div class="font-mono text-[11px] sm:text-xs text-smeck-gold font-bold tracking-wider">
                                    {{ $user->profile?->nia ?? 'SMCK-2024-1950' }}
                                </div>
                                <div class="text-[8px] sm:text-[9px] text-white/50 flex items-center gap-2 mt-0.5">
                                    <span>GOL: <strong class="text-white">{{ $user->profile?->blood_type ?? 'O' }}</strong></span>
                                    <span>•</span>
                                    <span>EST: <strong class="text-white">{{ $user->profile?->joined_date?->format('Y') ?? '2024' }}</strong></span>
                                </div>
                            </div>
                        </div>
                        <div class="text-right shrink-0">
                            <div class="font-mono text-[8px] text-white/40 tracking-widest uppercase">No. Kartu KTA:</div>
                            <div class="font-mono text-[10px] sm:text-[11px] text-smeck-gold font-bold tracking-wider">
                                {{ $user->nfcCard?->card_number ?? '6032-9800-2024-1950' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. SISI BELAKANG (BACK VIEW) --}}
            <div id="kta-preview-back-wrapper" class="w-full max-w-[460px] mx-auto hidden">
                <div class="text-[10px] font-bold uppercase tracking-wider text-smeck-green-400 mb-1.5 flex items-center justify-between print-hide">
                    <span class="flex items-center gap-1.5">
                        <i class="fa-solid fa-qrcode"></i>
                        <span>Tampak Belakang (Back View)</span>
                    </span>
                    <span class="font-mono text-white/40">1016 × 635 px • 300 DPI</span>
                </div>
                
                {{-- Container Belakang Berukuran Rasio 85.6 / 53.98 --}}
                <div id="kta-pvc-back" class="kta-pvc-card w-full aspect-[85.6/53.98] rounded-2xl p-4 sm:p-5 bg-gradient-to-br from-neutral-950 via-slate-950 to-neutral-900 border border-white/15 text-white shadow-2xl relative overflow-hidden flex flex-col justify-between">
                    {{-- Shimmer --}}
                    <div class="absolute inset-0 shimmer-effect opacity-15 pointer-events-none"></div>

                    {{-- Pita Magnetik Atas --}}
                    <div class="h-8 sm:h-9 -mx-4 sm:-mx-5 -mt-4 sm:-mt-5 mb-1 bg-neutral-900 border-b border-white/10 flex items-center justify-between px-4 sm:px-5">
                        <span class="text-[8px] sm:text-[9px] font-mono tracking-widest text-white/70 uppercase">
                            KARTU TANDA ANGGOTA DIGITAL • {{ strtoupper($user->chapter?->name ?? 'CHAPTER SMECK HOOLIGAN') }}
                        </span>
                        <span class="text-[8px] sm:text-[9px] font-display font-bold text-smeck-gold tracking-wider">
                            SADA ROHA
                        </span>
                    </div>

                    {{-- Sub-bar Identitas --}}
                    <div class="-mx-4 sm:-mx-5 px-4 sm:px-5 py-0.5 bg-black/40 border-b border-white/5 flex items-center justify-between text-[7px] sm:text-[8px]">
                        <div class="flex items-center gap-1.5 text-emerald-400 font-semibold">
                            <i class="fa-solid fa-circle-check text-[8px]"></i>
                            <span>Suporter Resmi PSMS Medan</span>
                        </div>
                        <div class="text-white/50 font-mono flex items-center gap-1">
                            <i class="fa-solid fa-wifi rotate-90 text-[7px] text-emerald-400"></i>
                            <span>NFC Terhubung</span>
                        </div>
                    </div>

                    {{-- Isi Kartu: Tanda Tangan, Ketentuan & QR Presensi --}}
                    <div class="my-auto py-1 flex items-center justify-between gap-3 sm:gap-4 relative z-10">
                        {{-- Kiri: Tanda Tangan & Ketentuan --}}
                        <div class="flex-1 min-w-0 space-y-1.5">
                            <div>
                                <div class="text-[8px] text-white/60 font-medium mb-0.5">Tanda Tangan Pemegang Kartu:</div>
                                <div class="flex items-center rounded-lg overflow-hidden border border-white/15 bg-white shadow-sm max-w-[190px] sm:max-w-[210px]">
                                    <div class="flex-1 h-7 sm:h-8 bg-slate-50 flex items-center px-2.5">
                                        <span class="font-signature text-sm sm:text-base text-slate-900 font-bold select-none truncate">
                                            {{ $user->name }}
                                        </span>
                                    </div>
                                    <div class="px-2 h-7 sm:h-8 bg-emerald-600/15 border-l border-emerald-500/30 flex items-center justify-center">
                                        <span class="text-[7px] sm:text-[8px] font-bold text-emerald-600 uppercase tracking-wider">
                                            RESMI
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 text-[8px] font-mono text-white/70">
                                <div>NIA: <strong class="text-smeck-gold">{{ $user->profile?->nia ?? 'SMCK-2024-1950' }}</strong></div>
                                <span>•</span>
                                <div>Basis: <strong class="text-white">{{ $user->chapter?->name ?? 'Medan Kota' }}</strong></div>
                            </div>

                            <div class="text-[7px] sm:text-[8px] text-white/50 leading-tight space-y-0.5">
                                <div>• Bukti keanggotaan sah SMeCK Hooligan {{ $user->chapter?->name ? '('.$user->chapter->name.')' : '' }}.</div>
                                <div>• Tunjukkan saat presensi dan penukaran tiket di stadion.</div>
                                <div>• Jika menemukan kartu ini, harap hubungi Pengurus {{ $user->chapter?->name ?? 'Chapter' }}.</div>
                            </div>
                        </div>

                        {{-- Kanan: QR Presensi --}}
                        <div class="shrink-0 flex flex-col items-center">
                            <div class="p-1.5 rounded-xl bg-white/[0.04] border border-white/15 text-center flex flex-col items-center">
                                <div class="text-[7px] font-bold text-emerald-400 uppercase tracking-wider mb-0.5 flex items-center gap-1">
                                    <i class="fa-solid fa-qrcode text-[7px]"></i> QR Presensi
                                </div>
                                <div class="w-16 h-16 sm:w-18 sm:h-18 bg-white rounded-lg p-1 flex items-center justify-center shadow">
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
                                            <circle cx="50" cy="50" r="9" fill="#052e16" stroke="white" stroke-width="1.5"/>
                                            <path d="M47 46 L53 46 L54 52 L50 54 L46 52 Z" fill="#22c55e"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="text-[6px] text-white/50 font-mono mt-0.5">Pintu Masuk</div>
                            </div>
                        </div>
                    </div>

                    {{-- Footer Belakang --}}
                    <div class="pt-1.5 border-t border-white/10 flex items-center justify-between text-[7px] sm:text-[8px] relative z-10">
                        <div class="flex items-center gap-1.5 text-white/60">
                            <i class="fa-solid fa-phone text-emerald-400 text-[8px]"></i>
                            <span>Pengurus {{ $user->chapter?->name ?? 'Chapter' }}: <strong class="text-white font-mono">{{ $user->profile?->emergency_contact_number ?? '0812-6543-2199' }}</strong></span>
                        </div>
                        <div class="text-right">
                            <span class="font-display font-bold text-smeck-gold tracking-wider">"SALAM SADA ROHA"</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sticky Bottom Action Toolbar (TIDAK PERNAH KETUTUP!) --}}
        <div class="p-3 sm:p-4 bg-neutral-950/95 border-t border-white/10 shrink-0 sticky bottom-0 z-20 backdrop-blur-md print-hide">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                <div class="text-[11px] text-white/60 hidden sm:flex items-center gap-1.5 font-mono">
                    <i class="fa-solid fa-circle-check text-emerald-400"></i>
                    <span>PNG HD Siap Cetak (1016 × 635 px • 300 DPI)</span>
                </div>
                <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto justify-end">
                    <button type="button" onclick="downloadKtaImage('front', this)" class="flex-1 sm:flex-initial px-3.5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow flex items-center justify-center gap-1.5 transition-all">
                        <i class="fa-solid fa-download"></i>
                        <span>Unduh Depan (PNG)</span>
                    </button>
                    <button type="button" onclick="downloadKtaImage('back', this)" class="flex-1 sm:flex-initial px-3.5 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-600 text-white font-bold text-xs shadow flex items-center justify-center gap-1.5 transition-all">
                        <i class="fa-solid fa-download"></i>
                        <span>Unduh Belakang (PNG)</span>
                    </button>
                    <button type="button" onclick="downloadBothKtaImages(this)" class="w-full sm:w-auto px-4 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-black text-xs shadow-lg flex items-center justify-center gap-1.5 transition-all">
                        <i class="fa-solid fa-layer-group"></i>
                        <span>Unduh Sepasang (PNG)</span>
                    </button>
                    <button type="button" onclick="window.print()" class="px-3 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs border border-white/15 flex items-center justify-center gap-1.5 transition-all" title="Cetak Kartu ke PDF / Printer">
                        <i class="fa-solid fa-print"></i>
                        <span>Cetak PDF</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
    function flipKtaCard() {
        const card = document.getElementById('kta-card');
        card.classList.toggle('card-flipped');
    }

    function switchKtaTab(tab) {
        const frontWrap = document.getElementById('kta-preview-front-wrapper');
        const backWrap = document.getElementById('kta-preview-back-wrapper');
        const btnFront = document.getElementById('tab-btn-front');
        const btnBack = document.getElementById('tab-btn-back');
        const btnBoth = document.getElementById('tab-btn-both');

        const activeClasses = ['bg-emerald-600', 'text-white', 'shadow'];
        const inactiveClasses = ['text-white/60', 'hover:text-white'];

        [btnFront, btnBack, btnBoth].forEach(b => {
            if (b) {
                b.classList.remove(...activeClasses);
                b.classList.add(...inactiveClasses);
            }
        });

        if (tab === 'front') {
            frontWrap.classList.remove('hidden');
            backWrap.classList.add('hidden');
            btnFront.classList.add(...activeClasses);
            btnFront.classList.remove(...inactiveClasses);
        } else if (tab === 'back') {
            frontWrap.classList.add('hidden');
            backWrap.classList.remove('hidden');
            btnBack.classList.add(...activeClasses);
            btnBack.classList.remove(...inactiveClasses);
        } else {
            frontWrap.classList.remove('hidden');
            backWrap.classList.remove('hidden');
            btnBoth.classList.add(...activeClasses);
            btnBoth.classList.remove(...inactiveClasses);
        }
    }

    function openDownloadKtaModal() {
        document.getElementById('modalDownloadKta').classList.remove('hidden');
        switchKtaTab('front');

        // Render QR Code di Modal
        const modalQr = document.getElementById('modal-dynamic-qr');
        if (modalQr && typeof QRCode !== 'undefined') {
            const qrVerificationUrl = "{{ route('api.check-kta', ['query' => $user->profile?->nia ?? ($user->nfcCard?->card_number ?? 'SMCK-1950')]) }}";
            modalQr.innerHTML = '';
            new QRCode(modalQr, {
                text: qrVerificationUrl,
                width: 68,
                height: 68,
                colorDark: "#052e16",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.M
            });
        }
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

    // Fungsi Download KTA sebagai Gambar PNG 300 DPI (1016 x 635 px)
    function downloadKtaImage(side, btn) {
        const elementId = side === 'front' ? 'kta-pvc-front' : 'kta-pvc-back';
        const element = document.getElementById(elementId);
        if (!element) return;

        const wrapper = side === 'front' ? document.getElementById('kta-preview-front-wrapper') : document.getElementById('kta-preview-back-wrapper');
        const wasHidden = wrapper && wrapper.classList.contains('hidden');
        if (wasHidden) {
            wrapper.classList.remove('hidden');
        }

        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i> Merender PNG...';
        btn.disabled = true;

        const targetWidth = 1016;
        const targetHeight = 635;

        setTimeout(() => {
            if (typeof html2canvas !== 'undefined') {
                html2canvas(element, {
                    scale: 3,
                    useCORS: true,
                    allowTaint: true,
                    backgroundColor: null,
                    logging: false
                }).then(canvas => {
                    const finalCanvas = document.createElement('canvas');
                    finalCanvas.width = targetWidth;
                    finalCanvas.height = targetHeight;
                    const ctx = finalCanvas.getContext('2d');
                    ctx.imageSmoothingEnabled = true;
                    ctx.imageSmoothingQuality = 'high';
                    ctx.drawImage(canvas, 0, 0, targetWidth, targetHeight);

                    const link = document.createElement('a');
                    const memberSlug = "{{ Str::slug($user->name) }}";
                    const sideLabel = side === 'front' ? 'DEPAN' : 'BELAKANG';
                    link.download = `KTA-SMECK-${sideLabel}-${memberSlug}-1016x635px-300DPI.png`;
                    link.href = finalCanvas.toDataURL('image/png', 1.0);
                    link.click();

                    if (wasHidden) {
                        wrapper.classList.add('hidden');
                    }
                    btn.innerHTML = originalHtml;
                    btn.disabled = false;
                }).catch(err => {
                    console.error('Gagal export KTA:', err);
                    if (wasHidden) {
                        wrapper.classList.add('hidden');
                    }
                    btn.innerHTML = originalHtml;
                    btn.disabled = false;
                    alert('Gagal mendownload gambar PNG. Silakan coba kembali.');
                });
            } else {
                if (wasHidden) {
                    wrapper.classList.add('hidden');
                }
                alert('Pustaka pengolah gambar sedang dimuat, silakan coba beberapa saat lagi.');
                btn.innerHTML = originalHtml;
                btn.disabled = false;
            }
        }, 80);
    }

    // Fungsi Unduh Sepasang Sekaligus (Depan + Belakang PNG)
    function downloadBothKtaImages(btn) {
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i> Mengunduh Depan...';
        btn.disabled = true;

        downloadKtaImage('front', btn);
        setTimeout(() => {
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i> Mengunduh Belakang...';
            downloadKtaImage('back', btn);
            setTimeout(() => {
                btn.innerHTML = originalHtml;
                btn.disabled = false;
            }, 1500);
        }, 1200);
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
                        width: 68,
                        height: 68,
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

