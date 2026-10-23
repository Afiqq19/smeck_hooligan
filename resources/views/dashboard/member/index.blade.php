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
@php
    $logoBase64 = '';
    $logoPath = public_path('images/logo-crest.jpg');
    if (file_exists($logoPath)) {
        $logoBase64 = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($logoPath));
    }

    $photoBase64 = '';
    if ($user->profile?->profile_photo_path) {
        $photoDiskPath = storage_path('app/public/' . $user->profile->profile_photo_path);
        if (file_exists($photoDiskPath)) {
            $mime = @mime_content_type($photoDiskPath) ?: 'image/jpeg';
            $photoBase64 = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($photoDiskPath));
        }
    }

    $psmsBase64 = '';
    $psmsPath = public_path('logoKTA/LogoPSMS.png');
    if (file_exists($psmsPath)) {
        $psmsBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($psmsPath));
    }

    $mandiriBase64 = '';
    $mandiriPath = public_path('logoKTA/logo_mandiri_resmi.png');
    if (!file_exists($mandiriPath)) {
        $mandiriPath = public_path('logoKTA/Bank_Mandiri_logo_2016.svg.webp');
    }
    if (file_exists($mandiriPath)) {
        $mime = str_ends_with($mandiriPath, '.webp') ? 'image/webp' : 'image/png';
        $mandiriBase64 = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($mandiriPath));
    }

    $emoneyBase64 = '';
    $emoneyPath = public_path('logoKTA/logo_emoney_resmi.png');
    if (!file_exists($emoneyPath)) {
        $emoneyPath = public_path('logoKTA/logo-mandiri-e-money-png-svg.webp');
    }
    if (file_exists($emoneyPath)) {
        $mime = str_ends_with($emoneyPath, '.webp') ? 'image/webp' : 'image/png';
        $emoneyBase64 = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($emoneyPath));
    }
@endphp
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

            <div class="grid grid-cols-2 sm:flex sm:flex-wrap items-center gap-2.5 w-full md:w-auto">
                <button type="button" onclick="flipKtaCard()" class="px-3 py-2.5 rounded-xl bg-white/10 hover:bg-white/15 text-white text-xs font-bold border border-white/10 flex items-center justify-center gap-1.5 transition-all">
                    <i class="fa-solid fa-rotate text-smeck-green-400"></i>
                    <span>Putar Kartu</span>
                </button>
                <a href="{{ route('member.profile') }}" class="px-3 py-2.5 rounded-xl bg-white/10 hover:bg-white/15 text-white text-xs font-bold border border-white/10 flex items-center justify-center gap-1.5 transition-all" title="Unggah / Perbarui Pas Foto KTA Anda">
                    <i class="fa-solid fa-camera text-amber-300"></i>
                    <span>Pasang Foto</span>
                </a>
                <button type="button" onclick="openDownloadKtaModal()" class="col-span-2 sm:col-auto px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold flex items-center justify-center gap-2 transition-all shadow-lg shadow-emerald-700/30">
                    <i class="fa-solid fa-download"></i>
                    <span>Unduh KTA (PNG / PDF)</span>
                </button>
                @php
                    $niaText = $user->profile?->nia ?? 'SMCK-1950';
                    $waShareText = urlencode("🟢 *KTA DIGITAL RESMI SMECK HOOLIGAN*\n\nNama: {$user->name}\nNIA: {$niaText}\nBasis: " . ($user->chapter->name ?? 'Pusat') . "\nGol. Darah: " . ($user->profile?->blood_type ?? '-') . "\nStatus: AKTIF TERVERIFIKASI ✅\n\nVerifikasi Kartu Asli:\n" . url('/api/check-kta?query=' . $niaText) . "\n\nSalam Sada Roha! 🐓⚽");
                @endphp
                <a href="https://wa.me/?text={{ $waShareText }}" target="_blank" class="px-3 py-2.5 rounded-xl bg-white/10 hover:bg-white/15 text-emerald-300 border border-emerald-500/30 text-xs font-bold flex items-center justify-center gap-1.5 transition-all">
                    <i class="fa-brands fa-whatsapp text-sm text-emerald-400"></i>
                    <span>Share WA</span>
                </a>
                <a href="{{ route('member.tickets') }}" class="px-3 py-2.5 rounded-xl bg-smeck-green-600 hover:bg-smeck-green-500 text-white text-xs font-bold shadow-lg shadow-smeck-green-700/30 flex items-center justify-center gap-1.5 transition-all">
                    <i class="fa-solid fa-ticket"></i>
                    <span>Pesan Tiket</span>
                </a>
            </div>
        </div>
    </div>

    {{-- STATUS NOTIFICATION BANNERS --}}
    @if($user->status === 'suspended')
        <div class="p-5 rounded-3xl bg-red-950/40 border-2 border-red-500/50 shadow-2xl flex items-start gap-4">
            <div class="w-12 h-12 rounded-2xl bg-red-600 text-white flex items-center justify-center text-xl shrink-0 shadow-lg shadow-red-700/50">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div class="space-y-1.5 flex-1">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase bg-red-500 text-white">
                        SANKSI DISIPLIN PENGURUS
                    </span>
                    <span class="text-xs text-red-300 font-bold">AKUN & KARTU DINONAKTIFKAN</span>
                </div>
                <div class="text-xs text-white/90">
                    <strong>Alasan Sanksi:</strong> <span class="text-red-200 font-medium">"{{ $user->suspension_reason ?? 'Pelanggaran Kode Etik Organisasi & Tata Tertib Tribun SMeCK Hooligan' }}"</span>
                </div>
                <p class="text-[11px] text-white/60 leading-relaxed">
                    Akses tiket, event, dan tap kartu fisik NFC di stadion Teladan dibekukan sementara. Silakan hubungi Pengurus Basis atau Pengurus Pusat untuk penyelesaian kode disiplin.
                </p>
            </div>
        </div>
    @elseif($user->nfcCard && $user->nfcCard->status === 'lost')
        <div class="p-5 rounded-3xl bg-amber-500/15 border-2 border-amber-500/40 shadow-2xl flex items-start gap-4">
            <div class="w-12 h-12 rounded-2xl bg-amber-500 text-black flex items-center justify-center text-xl shrink-0 shadow-lg shadow-amber-500/30">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div class="space-y-1 flex-1">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase bg-amber-500 text-black">
                        KARTU FISIK DILAPORKAN HILANG
                    </span>
                    <span class="text-xs text-amber-300 font-bold">MENUNGGU CETAK ULANG</span>
                </div>
                <p class="text-xs text-white/80 leading-relaxed">
                    Kartu fisik NFC Anda telah dilaporkan hilang dan dinonaktifkan dari scanner stadion demi keamanan. Silakan temui Pengurus Basis untuk cetak kartu pengganti, atau batalkan laporan hilang jika kartu telah ditemukan di <a href="{{ route('member.emergency') }}" class="underline text-amber-300 font-bold">Menu Darurat</a>.
                </p>
            </div>
        </div>
    @elseif($user->status === 'pending_approval')
        <div class="p-5 rounded-3xl bg-amber-500/10 border border-amber-500/30 shadow-xl flex items-start gap-4">
            <div class="w-12 h-12 rounded-2xl bg-amber-500/20 text-amber-400 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-hourglass-half animate-pulse"></i>
            </div>
            <div class="space-y-1 flex-1">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-amber-500/20 text-amber-300 border border-amber-500/30">
                        MENUNGGU VALIDASI PUSAT
                    </span>
                    <span class="text-xs text-white/60">Verifikasi Pengurus Pusat DPP</span>
                </div>
                <p class="text-xs text-white/80 leading-relaxed">
                    Pendaftaran identitas suporter Anda sedang diverifikasi oleh Pengurus Pusat. Setelah disetujui, E-KTA akan sah sepenuhnya dan Anda dapat mengambil kartu fisik NFC di Basis <strong>{{ $user->chapter?->name ?? 'Chapter' }}</strong>.
                </p>
            </div>
        </div>
    @endif

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

                        {{-- Card Header: Logos & Mandiri Branding --}}
                        <div class="relative z-10 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl overflow-hidden border border-emerald-400/50 shadow-md flex items-center justify-center p-0.5 bg-black shrink-0">
                                    <img src="{{ asset('images/logo-crest.jpg') }}" alt="Logo SMeCK" class="w-full h-full object-cover rounded-lg">
                                </div>
                                <div>
                                    <div class="font-display font-black text-white text-sm sm:text-base tracking-wider uppercase leading-tight">
                                        SMeCK HOOLIGAN
                                    </div>
                                    <div class="text-[8px] sm:text-[9px] text-emerald-300 font-bold tracking-widest uppercase mt-0.5">
                                        SUPORTER RESMI PSMS MEDAN
                                    </div>
                                </div>
                            </div>

                            {{-- Brand Mandiri (Kanan Atas) --}}
                            <div class="flex items-center px-3 py-1.5 rounded-xl bg-white/95 backdrop-blur-md border border-white/60 shadow-md shrink-0">
                                <img src="{{ $mandiriBase64 ?: asset('logoKTA/logo_mandiri_resmi.png') }}" alt="Bank Mandiri" class="h-4 sm:h-5 w-auto object-contain">
                            </div>
                        </div>

                        {{-- Card Middle: PSMS Medan Crest & Basis Chapter (Chip Emas Dihapus) --}}
                        <div class="relative z-10 my-auto py-2 flex items-center justify-between gap-3">
                            {{-- Logo PSMS Medan Club Crest --}}
                            <div class="flex items-center gap-2.5 bg-black/40 backdrop-blur-sm px-3 py-1.5 rounded-xl border border-white/10 shadow-sm">
                                <div class="w-8 h-10 sm:w-9 sm:h-11 shrink-0 flex items-center justify-center">
                                    <img src="{{ $psmsBase64 ?: asset('logoKTA/LogoPSMS.png') }}" alt="PSMS Medan" class="w-full h-full object-contain">
                                </div>
                                <div>
                                    <div class="text-[8px] text-amber-300 font-bold uppercase tracking-widest leading-none">PSMS MEDAN</div>
                                    <div class="text-xs sm:text-sm font-black text-white font-display tracking-wide mt-0.5">AYAM KINANTAN</div>
                                </div>
                            </div>

                            {{-- Chapter Badge --}}
                            <div class="text-right">
                                <div class="text-[8px] sm:text-[9px] text-white/50 uppercase font-bold tracking-widest">BASIS CHAPTER</div>
                                <div class="text-xs sm:text-sm font-bold text-smeck-green-300 font-display">
                                    {{ $user->chapter?->name ?? 'Chapter Medan Kota' }}
                                </div>
                            </div>
                        </div>

                        {{-- Card Bottom: Profile Photo, Name, NIA & Logo e-money (Nomor KTA Dipindah ke Belakang) --}}
                        <div class="relative z-10 flex items-end justify-between pt-2 border-t border-white/10 gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                {{-- Member Photo / Pas Foto Anggota --}}
                                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl border-2 border-amber-400/80 bg-smeck-dark-800 overflow-hidden shrink-0 shadow-lg flex items-center justify-center relative">
                                    @if($user->profile?->profile_photo_path)
                                        <img src="{{ asset('storage/' . $user->profile->profile_photo_path) }}" alt="{{ $user->name }}" class="w-full h-full object-cover" style="object-position: center top;">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-emerald-800 via-smeck-dark-900 to-black flex flex-col items-center justify-center text-white">
                                            <i class="fa-solid fa-user text-lg text-emerald-300"></i>
                                            <span class="text-[7px] text-white/50 mt-0.5 uppercase font-bold">FOTO</span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Name & NIA --}}
                                <div class="min-w-0">
                                    <div class="font-display font-black text-xs sm:text-base text-white tracking-wide truncate max-w-[190px] sm:max-w-none" title="{{ strtoupper($user->name) }}">
                                        {{ strtoupper($user->name) }}
                                    </div>
                                    <div class="font-mono text-xs sm:text-sm text-amber-300 font-bold tracking-wider">
                                        {{ $user->profile?->nia ?? 'SMCK-2024-1950' }}
                                    </div>
                                    <div class="text-[7.5px] sm:text-[9px] text-white/50 flex items-center gap-1.5 sm:gap-2 mt-0.5">
                                        <span>GOL: <strong class="text-white">{{ $user->profile?->blood_type ?? 'O' }}</strong></span>
                                        <span>•</span>
                                        <span>EST: <strong class="text-white">{{ $user->profile?->joined_date?->format('Y') ?? '2024' }}</strong></span>
                                    </div>
                                </div>
                            </div>

                            {{-- Brand e-money (Bawah Sebelah Kanan) --}}
                            <div class="bg-white px-2.5 py-1 sm:px-3.5 sm:py-2 rounded-xl shadow-lg border border-white/80 flex items-center justify-center shrink-0">
                                <img src="{{ $emoneyBase64 ?: asset('logoKTA/logo_emoney_resmi.png') }}" alt="e-money" class="h-5 sm:h-6 w-auto object-contain" style="height: 20px; max-height: 24px; width: auto;">
                            </div>
                        </div>

                    </div>

                    {{-- -------------------------------------------------- --}}
                    {{-- TAMPAK BELAKANG KARTU DIGITAL ANGGOTA (E-KTA RESMI) --}}
                    {{-- -------------------------------------------------- --}}
                    <div class="card-face card-back p-3 sm:p-5 flex flex-col justify-between overflow-hidden border border-emerald-500/30 shadow-2xl bg-gradient-to-br from-neutral-950 via-slate-950 to-neutral-900 text-white relative">
                        
                        {{-- Efek Kilau Halus --}}
                        <div class="absolute inset-0 shimmer-effect opacity-15 pointer-events-none"></div>

                        {{-- 1. PITA IDENTITAS ATAS (E-KTA RESMI) --}}
                        <div class="-mx-3 -mt-3 sm:-mx-5 sm:-mt-5">
                            <div class="h-8 sm:h-10 bg-neutral-900/95 border-b border-white/10 flex items-center justify-between px-3 sm:px-5">
                                <div class="flex items-center gap-1.5 sm:gap-2">
                                    <span class="px-1.5 py-0.5 rounded text-[7.5px] sm:text-[9px] bg-emerald-500/20 text-emerald-300 font-mono font-bold border border-emerald-500/40">
                                        E-KTA
                                    </span>
                                    <span class="text-[8px] sm:text-[10px] font-bold tracking-wider text-white uppercase font-display">
                                        KARTU ANGGOTA ELEKTRONIK
                                    </span>
                                </div>
                                <span class="text-[8px] sm:text-[10px] font-display font-black text-amber-400 tracking-wider">
                                    SALAM SADA ROHA
                                </span>
                            </div>

                            {{-- Sub-bar Basis & Status --}}
                            <div class="px-3 sm:px-5 py-0.5 sm:py-1 bg-black/80 border-b border-white/5 flex items-center justify-between text-[7px] sm:text-[8.5px]">
                                <div class="flex items-center gap-1 text-emerald-400 font-bold uppercase tracking-wider truncate max-w-[65%]">
                                    <i class="fa-solid fa-shield-halved text-[7px] shrink-0"></i>
                                    <span class="truncate">BASIS: {{ strtoupper($user->chapter?->name ?? 'CHAPTER MEDAN KOTA') }}</span>
                                </div>
                                <div class="flex items-center gap-1 text-emerald-400 font-bold shrink-0">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                    <span>TERVERIFIKASI DIGITAL</span>
                                </div>
                            </div>
                        </div>

                        {{-- 2. KONTEN UTAMA: Panel Tanda Tangan, Ketentuan & QR Verifikasi Identitas --}}
                        <div class="my-auto py-1 sm:py-2 flex items-center justify-between gap-2.5 sm:gap-4 relative z-10">
                            
                            {{-- Sisi Kiri: Tanda Tangan & Maklumat Ketentuan E-KTA --}}
                            <div class="flex-1 min-w-0 flex flex-col justify-between space-y-1 sm:space-y-1.5">
                                
                                {{-- Area Tanda Tangan --}}
                                <div>
                                    <div class="text-[7px] sm:text-[8.5px] text-white/70 font-medium mb-0.5 sm:mb-1 flex items-center justify-between">
                                        <span>Tanda Tangan Pemegang Sah:</span>
                                        <span class="font-mono text-[7.5px] sm:text-[9px] text-amber-300 font-bold">NIA: {{ $user->profile?->nia ?? 'SMCK-1950' }}</span>
                                    </div>
                                    <div class="flex items-center rounded-lg sm:rounded-xl overflow-hidden border border-white/20 bg-white shadow-md max-w-[190px] sm:max-w-[240px]">
                                        <div class="flex-1 h-6.5 sm:h-8.5 bg-slate-50 flex items-center px-2.5 sm:px-3">
                                            <span class="font-signature text-sm sm:text-base text-slate-900 font-bold select-none truncate">
                                                {{ $user->name }}
                                            </span>
                                        </div>
                                        <div class="px-2 sm:px-2.5 h-6.5 sm:h-8.5 bg-emerald-700 text-white flex items-center justify-center">
                                            <span class="text-[7px] sm:text-[8px] font-black uppercase tracking-wider">
                                                SAH
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Maklumat & Ketentuan Suporter E-KTA --}}
                                <div class="text-[6.5px] sm:text-[8px] text-white/70 leading-snug space-y-0.5">
                                    <div class="font-bold text-amber-300 uppercase tracking-wider flex items-center gap-1 text-[7px] sm:text-[8px]">
                                        <i class="fa-solid fa-circle-info text-[7px]"></i>
                                        <span>KETENTUAN PEMEGANG E-KTA:</span>
                                    </div>
                                    <div>• Identitas resmi suporter SMeCK Hooligan (Ayam Kinantan).</div>
                                    <div>• Akses presensi matchday Teladan & agenda suporter.</div>
                                    <div>• Junjung tinggi persaudaraan <em>Salam Sada Roha</em>.</div>
                                </div>
                            </div>

                            {{-- Sisi Kanan: Box QR Verifikasi Identitas Digital --}}
                            <div class="shrink-0 flex flex-col items-center">
                                <div class="p-1 sm:p-2 rounded-xl bg-black/60 border border-emerald-500/30 text-center flex flex-col items-center shadow-lg">
                                    <div class="text-[6.5px] sm:text-[7.5px] font-bold text-emerald-400 uppercase tracking-wider mb-0.5 sm:mb-1 flex items-center gap-1">
                                        <i class="fa-solid fa-id-card-clip text-[7px]"></i>
                                        <span>VERIFIKASI DIGITAL</span>
                                    </div>
                                    <div class="w-14 h-14 sm:w-16 sm:h-16 bg-white rounded-lg p-1 flex items-center justify-center shadow-md">
                                        <div id="dynamic-kta-qr" class="w-full h-full flex items-center justify-center">
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
                                    <div class="text-[6px] sm:text-[6.5px] text-emerald-400 font-bold uppercase tracking-wider mt-0.5 sm:mt-1">
                                        SCAN KEASLIAN
                                    </div>
                                </div>
                            </div>

                        </div>

                        {{-- 3. FOOTER: Hotline Pengurus Chapter & Slogan --}}
                        <div class="pt-1 sm:pt-2 border-t border-white/10 flex items-center justify-between text-[7px] sm:text-[8px] relative z-10">
                            <div class="flex items-center gap-1.5 text-white/70">
                                <i class="fa-solid fa-phone text-emerald-400 text-[7px]"></i>
                                <span>Basis: <strong class="text-white font-mono">{{ $user->profile?->emergency_contact_number ?? '0812-6543-2199' }}</strong></span>
                            </div>
                            <div class="text-right">
                                <span class="font-display font-black text-amber-400 tracking-widest text-[7.5px] sm:text-[9px]">
                                    AYAM KINANTAN JUARA
                                </span>
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
                        <div class="text-sm font-bold mt-0.5">
                            @if($user->status === 'suspended')
                                <span class="text-red-400">SANKSI DISIPLIN</span>
                            @elseif($user->nfcCard && $user->nfcCard->status === 'lost')
                                <span class="text-amber-400">KARTU HILANG</span>
                            @elseif($user->nfcCard && $user->nfcCard->status === 'blocked')
                                <span class="text-red-400">DIBLOKIR PENGURUS</span>
                            @elseif($user->status === 'pending_approval')
                                <span class="text-amber-300">VALIDASI PUSAT</span>
                            @else
                                <span class="text-smeck-green-400">AKTIF & DAPAT DITAP</span>
                            @endif
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
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-lg shrink-0 border border-emerald-500/30">
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
                <div id="kta-pvc-front" class="kta-pvc-card w-full aspect-[85.6/53.98] rounded-2xl p-3.5 sm:p-5 bg-gradient-to-br from-emerald-950 via-smeck-dark-900 to-black border border-white/20 text-white shadow-2xl relative overflow-hidden flex flex-col justify-between">
                    {{-- Shimmer & Watermark --}}
                    <div class="absolute inset-0 shimmer-effect pointer-events-none opacity-20"></div>
                    <div class="absolute -right-6 -bottom-6 w-36 h-36 rounded-full overflow-hidden opacity-10 pointer-events-none">
                        <img src="{{ $logoBase64 ?: asset('images/logo-crest.jpg') }}" alt="Watermark" class="w-full h-full object-cover">
                    </div>

                    {{-- Header Kartu Depan: SMeCK & Mandiri --}}
                    <div class="relative z-10 flex items-center justify-between">
                        <div class="flex items-center gap-2 sm:gap-2.5">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl overflow-hidden border border-emerald-400/60 p-0.5 bg-black shrink-0 shadow">
                                <img src="{{ $logoBase64 ?: asset('images/logo-crest.jpg') }}" alt="Logo" class="w-full h-full object-cover rounded-lg">
                            </div>
                            <div>
                                <div class="font-display font-black text-xs sm:text-sm tracking-wider uppercase text-white leading-tight">
                                    SMeCK HOOLIGAN
                                </div>
                                <div class="text-[7.5px] sm:text-[9px] text-emerald-300 font-bold uppercase tracking-widest">
                                    SUPORTER RESMI PSMS MEDAN
                                </div>
                            </div>
                        </div>

                        {{-- Brand Mandiri (Kanan Atas) --}}
                        <div class="flex items-center px-2 py-1 sm:px-2.5 sm:py-1 rounded-xl bg-white/95 backdrop-blur-md border border-white/60 shadow-sm shrink-0">
                            <img src="{{ $mandiriBase64 ?: asset('logoKTA/logo_mandiri_resmi.png') }}" alt="Bank Mandiri" class="h-3 sm:h-4 w-auto object-contain" style="height: 15px; max-height: 18px; width: auto;">
                        </div>
                    </div>

                    {{-- Tengah: PSMS Medan Crest & Basis Chapter (Chip Emas Dihapus) --}}
                    <div class="relative z-10 flex items-center justify-between my-auto py-1 gap-2">
                        {{-- Logo PSMS Medan Club Crest --}}
                        <div class="flex items-center gap-1.5 sm:gap-2 bg-black/40 backdrop-blur-sm px-2 py-1 sm:px-2.5 sm:py-1 rounded-xl border border-white/10 shadow-sm">
                            <div class="w-6 h-8 sm:w-8 sm:h-10 shrink-0 flex items-center justify-center">
                                <img src="{{ $psmsBase64 ?: asset('logoKTA/LogoPSMS.png') }}" alt="PSMS Medan" class="w-full h-full object-contain">
                            </div>
                            <div>
                                <div class="text-[6.5px] sm:text-[7.5px] text-amber-300 font-bold uppercase tracking-widest leading-none">PSMS MEDAN</div>
                                <div class="text-[10px] sm:text-xs font-black text-white font-display tracking-wide mt-0.5">AYAM KINANTAN</div>
                            </div>
                        </div>

                        <div class="text-right">
                            <div class="text-[6.5px] sm:text-[8px] text-white/50 uppercase font-bold tracking-widest">BASIS CHAPTER</div>
                            <div class="text-[11px] sm:text-sm font-bold text-smeck-green-300 font-display">
                                {{ $user->chapter?->name ?? 'Chapter Medan Kota' }}
                            </div>
                        </div>
                    </div>

                    {{-- Footer Depan: Foto, Identitas & Logo e-money (Nomor Kartu Dipindah ke Belakang) --}}
                    <div class="relative z-10 flex items-end justify-between pt-1.5 sm:pt-2 border-t border-white/10 gap-2">
                        <div class="flex items-center gap-2 sm:gap-2.5 min-w-0">
                            {{-- Pas Foto Anggota --}}
                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl border-2 border-amber-400/80 bg-smeck-dark-800 overflow-hidden shrink-0 shadow flex items-center justify-center relative">
                                @if($photoBase64)
                                    <img src="{{ $photoBase64 }}" alt="{{ $user->name }}" class="w-full h-full object-cover" style="object-position: center top;">
                                @elseif($user->profile?->profile_photo_path)
                                    <img src="{{ asset('storage/' . $user->profile->profile_photo_path) }}" alt="{{ $user->name }}" class="w-full h-full object-cover" style="object-position: center top;">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-emerald-800 via-smeck-dark-900 to-black flex flex-col items-center justify-center text-white">
                                        <i class="fa-solid fa-user text-sm text-emerald-300"></i>
                                        <span class="text-[6px] text-white/50 mt-0.5 uppercase font-bold">FOTO</span>
                                    </div>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <div class="font-display font-black text-xs sm:text-sm text-white tracking-wide truncate max-w-[150px] sm:max-w-[200px]" title="{{ strtoupper($user->name) }}">
                                    {{ strtoupper($user->name) }}
                                </div>
                                <div class="font-mono text-[10px] sm:text-xs text-amber-300 font-bold tracking-wider">
                                    {{ $user->profile?->nia ?? 'SMCK-2024-1950' }}
                                </div>
                                <div class="text-[7.5px] sm:text-[9px] text-white/50 flex items-center gap-1.5 sm:gap-2 mt-0.5">
                                    <span>GOL: <strong class="text-white">{{ $user->profile?->blood_type ?? 'O' }}</strong></span>
                                    <span>•</span>
                                    <span>EST: <strong class="text-white">{{ $user->profile?->joined_date?->format('Y') ?? '2024' }}</strong></span>
                                </div>
                            </div>
                        </div>

                        {{-- Brand e-money (Bawah Sebelah Kanan) --}}
                        <div class="bg-white px-2 py-1 rounded-lg shadow-md border border-white/80 flex items-center justify-center shrink-0">
                            <img src="{{ $emoneyBase64 ?: asset('logoKTA/logo_emoney_resmi.png') }}" alt="e-money" class="h-4 sm:h-5 w-auto object-contain" style="height: 18px; max-height: 20px; width: auto;">
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
                <div id="kta-pvc-back" class="kta-pvc-card w-full aspect-[85.6/53.98] rounded-2xl p-3 sm:p-5 bg-gradient-to-br from-neutral-950 via-slate-950 to-neutral-900 border border-emerald-500/30 text-white shadow-2xl relative overflow-hidden flex flex-col justify-between">
                    {{-- Shimmer --}}
                    <div class="absolute inset-0 shimmer-effect opacity-15 pointer-events-none"></div>

                    {{-- Pita Identitas Atas (E-KTA Resmi) --}}
                    <div class="h-7.5 sm:h-9 -mx-3 sm:-mx-5 -mt-3 sm:-mt-5 bg-neutral-900/95 border-b border-white/10 flex items-center justify-between px-3 sm:px-5">
                        <div class="flex items-center gap-1.5 sm:gap-2">
                            <span class="px-1.5 py-0.5 rounded text-[7px] sm:text-[8.5px] bg-emerald-500/20 text-emerald-300 font-mono font-bold border border-emerald-500/40">
                                E-KTA
                            </span>
                            <span class="text-[7.5px] sm:text-[9.5px] font-bold tracking-wider text-white uppercase font-display">
                                KARTU ANGGOTA ELEKTRONIK
                            </span>
                        </div>
                        <span class="text-[7.5px] sm:text-[9.5px] font-display font-black text-amber-400 tracking-wider">
                            SALAM SADA ROHA
                        </span>
                    </div>

                    {{-- Sub-bar Basis & Status --}}
                    <div class="-mx-3 sm:-mx-5 px-3 sm:px-5 py-0.5 sm:py-1 bg-black/80 border-b border-white/5 flex items-center justify-between text-[6.5px] sm:text-[8px]">
                        <div class="flex items-center gap-1 text-emerald-400 font-bold uppercase tracking-wider truncate max-w-[65%]">
                            <i class="fa-solid fa-shield-halved text-[7px] shrink-0"></i>
                            <span class="truncate">BASIS: {{ strtoupper($user->chapter?->name ?? 'CHAPTER MEDAN KOTA') }}</span>
                        </div>
                        <div class="flex items-center gap-1 text-emerald-400 font-bold shrink-0">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span>TERVERIFIKASI DIGITAL</span>
                        </div>
                    </div>

                    {{-- Konten Utama: Tanda Tangan, Ketentuan & QR Verifikasi E-KTA --}}
                    <div class="my-auto py-1 sm:py-1.5 flex items-center justify-between gap-2.5 sm:gap-4 relative z-10">
                        {{-- Kiri: Tanda Tangan & Ketentuan --}}
                        <div class="flex-1 min-w-0 space-y-1 sm:space-y-1.5">
                            <div>
                                <div class="text-[6.5px] sm:text-[8px] text-white/70 font-medium mb-0.5 flex items-center justify-between">
                                    <span>Tanda Tangan Pemegang Sah:</span>
                                    <span class="font-mono text-[7px] sm:text-[8.5px] text-amber-300 font-bold">NIA: {{ $user->profile?->nia ?? 'SMCK-1950' }}</span>
                                </div>
                                <div class="flex items-center rounded-lg sm:rounded-xl overflow-hidden border border-white/20 bg-white shadow-sm max-w-[170px] sm:max-w-[220px]">
                                    <div class="flex-1 h-6 sm:h-7.5 bg-slate-50 flex items-center px-2 sm:px-2.5">
                                        <span class="font-signature text-xs sm:text-base text-slate-900 font-bold select-none truncate">
                                            {{ $user->name }}
                                        </span>
                                    </div>
                                    <div class="px-2 h-6 sm:h-7.5 bg-emerald-700 text-white flex items-center justify-center">
                                        <span class="text-[6.5px] sm:text-[7.5px] font-black uppercase tracking-wider">
                                            SAH
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="text-[6px] sm:text-[7.5px] text-white/70 leading-snug space-y-0.5">
                                <div class="font-bold text-amber-300 uppercase tracking-wider flex items-center gap-1 text-[6.5px] sm:text-[7.5px]">
                                    <i class="fa-solid fa-circle-info text-[6.5px]"></i>
                                    <span>KETENTUAN PEMEGANG E-KTA:</span>
                                </div>
                                <div>• Identitas resmi suporter SMeCK Hooligan (Ayam Kinantan).</div>
                                <div>• Akses presensi matchday Teladan & agenda suporter.</div>
                                <div>• Junjung tinggi persaudaraan <em>Salam Sada Roha</em>.</div>
                            </div>
                        </div>

                        {{-- Kanan: Box QR Verifikasi Digital --}}
                        <div class="shrink-0 flex flex-col items-center">
                            <div class="p-1 sm:p-2 rounded-xl bg-black/60 border border-emerald-500/40 text-center flex flex-col items-center shadow-xl">
                                <div class="text-[6px] sm:text-[7.5px] font-bold text-emerald-400 uppercase tracking-wider mb-0.5 flex items-center gap-1">
                                    <i class="fa-solid fa-id-card-clip text-[6.5px]"></i>
                                    <span>VERIFIKASI DIGITAL</span>
                                </div>
                                <div class="w-13 h-13 sm:w-16 sm:h-16 bg-white rounded-lg p-1 flex items-center justify-center shadow" style="width: 50px; height: 50px;">
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
                                <div class="text-[5.5px] sm:text-[6.5px] text-emerald-400 font-bold uppercase tracking-wider mt-0.5">
                                    SCAN KEASLIAN
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Footer Belakang --}}
                    <div class="pt-1 sm:pt-1.5 border-t border-white/10 flex items-center justify-between text-[6.5px] sm:text-[8px] relative z-10">
                        <div class="flex items-center gap-1.5 text-white/70">
                            <i class="fa-solid fa-phone text-emerald-400 text-[7px]"></i>
                            <span>Basis: <strong class="text-white font-mono">{{ $user->profile?->emergency_contact_number ?? '0812-6543-2199' }}</strong></span>
                        </div>
                        <div class="text-right">
                            <span class="font-display font-black text-amber-400 tracking-widest text-[7px] sm:text-[8.5px]">
                                AYAM KINANTAN JUARA
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sticky Bottom Action Toolbar (TIDAK PERNAH KETUTUP!) --}}
        <div class="p-3 sm:p-4 bg-neutral-950/95 border-t border-white/10 shrink-0 sticky bottom-0 z-20 backdrop-blur-md print-hide">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-2.5 sm:gap-3">
                <div class="text-[11px] text-white/60 hidden sm:flex items-center gap-1.5 font-mono">
                    <i class="fa-solid fa-circle-check text-emerald-400"></i>
                    <span>PNG HD Siap Cetak (1016 × 635 px • 300 DPI)</span>
                </div>
                <div class="grid grid-cols-2 sm:flex sm:flex-wrap items-center gap-2 w-full sm:w-auto">
                    <button type="button" onclick="downloadKtaImage('front', this)" class="px-3 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow flex items-center justify-center gap-1.5 transition-all">
                        <i class="fa-solid fa-download text-[11px]"></i>
                        <span>Unduh Depan</span>
                    </button>
                    <button type="button" onclick="downloadKtaImage('back', this)" class="px-3 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-600 text-white font-bold text-xs shadow flex items-center justify-center gap-1.5 transition-all">
                        <i class="fa-solid fa-download text-[11px]"></i>
                        <span>Unduh Belakang</span>
                    </button>
                    <button type="button" onclick="downloadBothKtaImages(this)" class="col-span-2 sm:col-auto px-4 py-2.5 rounded-xl bg-gradient-to-r from-emerald-600 via-teal-600 to-emerald-700 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-xs shadow-lg border border-emerald-400/30 flex items-center justify-center gap-1.5 transition-all">
                        <i class="fa-solid fa-layer-group text-[11px]"></i>
                        <span>Unduh Sepasang (PNG)</span>
                    </button>
                    <button type="button" onclick="window.print()" class="col-span-2 sm:col-auto px-3 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs border border-white/15 flex items-center justify-center gap-1.5 transition-all" title="Cetak Kartu ke PDF / Printer">
                        <i class="fa-solid fa-print text-[11px]"></i>
                        <span>Cetak PDF</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ========================================================================= --}}
{{-- DEDICATED OFF-SCREEN EXPORT CONTAINER (1016 x 635 px • 300 DPI)           --}}
{{-- Selalu standby di memori tanpa display:none sehingga bebas reflow & CORS   --}}
{{-- ========================================================================= --}}
<div id="kta-export-staging" style="position: fixed; left: 0; top: 0; width: 1016px; height: 635px; overflow: hidden; pointer-events: none; z-index: -9999; opacity: 0.001;" class="print-hide">
    
    {{-- EXPORT DEPAN: 1016 x 635 px (Standar Cetak PVC 300 DPI) --}}
    <div id="kta-export-front" style="width: 1016px; height: 635px; background: linear-gradient(135deg, #052e16 0%, #064e3b 35%, #0f172a 70%, #020617 100%); color: #ffffff; padding: 44px 48px; box-sizing: border-box; position: relative; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; display: flex; flex-direction: column; justify-content: space-between; border-radius: 28px; overflow: hidden; border: 3px solid rgba(255,255,255,0.2);">
        
        {{-- Watermark Logo --}}
        <div style="position: absolute; right: -50px; bottom: -50px; width: 380px; height: 380px; opacity: 0.08; pointer-events: none; border-radius: 50%; overflow: hidden;">
            <img src="{{ $logoBase64 ?: asset('images/logo-crest.jpg') }}" alt="Watermark" style="width: 100%; height: 100%; object-fit: cover;">
        </div>

        {{-- Header Depan: SMeCK & Mandiri --}}
        <div style="position: relative; z-index: 10; display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 20px;">
                <div style="width: 80px; height: 80px; border-radius: 18px; border: 2.5px solid #10b981; background: #000; padding: 4px; box-sizing: border-box; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                    <img src="{{ $logoBase64 ?: asset('images/logo-crest.jpg') }}" alt="Logo" style="width: 100%; height: 100%; object-fit: cover; border-radius: 12px;">
                </div>
                <div>
                    <div style="font-size: 32px; font-weight: 900; letter-spacing: 2px; text-transform: uppercase; color: #ffffff; line-height: 1.1;">
                        SMeCK HOOLIGAN
                    </div>
                    <div style="font-size: 15px; font-weight: 800; letter-spacing: 3px; color: #6ee7b7; text-transform: uppercase; margin-top: 4px;">
                        SUPORTER RESMI PSMS MEDAN
                    </div>
                </div>
            </div>

            {{-- Brand Mandiri (Kanan Atas) --}}
            <div style="display: flex; align-items: center; background: rgba(255,255,255,0.95); padding: 8px 24px; border-radius: 16px; border: 1.5px solid rgba(255,255,255,0.8); box-shadow: 0 4px 16px rgba(0,0,0,0.3);">
                <img src="{{ $mandiriBase64 ?: asset('logoKTA/logo_mandiri_resmi.png') }}" alt="Bank Mandiri" style="height: 38px; width: auto; object-fit: contain;">
            </div>
        </div>

        {{-- Middle Depan: PSMS Medan Crest & Basis Chapter (Chip Emas Dihapus) --}}
        <div style="position: relative; z-index: 10; display: flex; align-items: center; justify-content: space-between; margin: auto 0; padding: 10px 0;">
            {{-- PSMS Medan Club Crest --}}
            <div style="display: flex; align-items: center; gap: 16px; background: rgba(0,0,0,0.45); padding: 10px 22px; border-radius: 18px; border: 1.5px solid rgba(255,255,255,0.12);">
                <div style="width: 52px; height: 65px; display: flex; align-items: center; justify-content: center;">
                    <img src="{{ $psmsBase64 ?: asset('logoKTA/LogoPSMS.png') }}" alt="PSMS Medan" style="width: 100%; height: 100%; object-fit: contain;">
                </div>
                <div>
                    <div style="font-size: 13px; font-weight: 800; letter-spacing: 2.5px; color: #fbbf24; text-transform: uppercase;">PSMS MEDAN</div>
                    <div style="font-size: 22px; font-weight: 900; color: #ffffff; letter-spacing: 1px; margin-top: 2px;">AYAM KINANTAN</div>
                </div>
            </div>

            <div style="text-align: right;">
                <div style="font-size: 15px; font-weight: 800; letter-spacing: 3px; color: rgba(255,255,255,0.5); text-transform: uppercase;">
                    BASIS CHAPTER
                </div>
                <div style="font-size: 26px; font-weight: 900; color: #86efac; margin-top: 4px;">
                    {{ $user->chapter?->name ?? 'Chapter Medan Kota' }}
                </div>
            </div>
        </div>

        {{-- Footer Depan: Foto, Identitas, Logo e-money (Nomor KTA Dipindah ke Belakang) --}}
        <div style="position: relative; z-index: 10; display: flex; align-items: flex-end; justify-content: space-between; border-top: 2px solid rgba(255,255,255,0.15); padding-top: 20px;">
            <div style="display: flex; align-items: center; gap: 24px;">
                <div style="width: 120px; height: 120px; border-radius: 20px; border: 3px solid #fbbf24; background: #0f172a; overflow: hidden; display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 24px rgba(0,0,0,0.5);">
                    @if($photoBase64)
                        <img src="{{ $photoBase64 }}" alt="{{ $user->name }}" style="width: 100%; height: 100%; object-fit: cover; object-position: center top;">
                    @else
                        <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #064e3b, #022c22); display: flex; flex-direction: column; align-items: center; justify-content: center; color: #ffffff;">
                            <div style="font-size: 42px;">👤</div>
                            <div style="font-size: 11px; font-weight: 800; color: rgba(255,255,255,0.6); letter-spacing: 2px; margin-top: 2px;">PAS FOTO</div>
                        </div>
                    @endif
                </div>
                <div>
                    <div style="font-size: 32px; font-weight: 900; color: #ffffff; letter-spacing: 1px; max-width: 480px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        {{ strtoupper($user->name) }}
                    </div>
                    <div style="font-size: 24px; font-family: monospace; font-weight: 900; color: #fbbf24; letter-spacing: 2px; margin-top: 4px;">
                        {{ $user->profile?->nia ?? 'SMCK-2024-1950' }}
                    </div>
                    <div style="font-size: 17px; color: rgba(255,255,255,0.7); margin-top: 6px; font-weight: 600;">
                        <span>GOL: <strong style="color:#fff;">{{ $user->profile?->blood_type ?? 'O' }}</strong></span>
                        <span style="margin: 0 10px;">•</span>
                        <span>EST: <strong style="color:#fff;">{{ $user->profile?->joined_date?->format('Y') ?? '2024' }}</strong></span>
                    </div>
                </div>
            </div>

            {{-- Brand e-money (Bawah Sebelah Kanan) --}}
            <div style="background: #ffffff; padding: 10px 24px; border-radius: 16px; box-shadow: 0 4px 16px rgba(0,0,0,0.3); border: 1.5px solid rgba(255,255,255,0.8); display: flex; align-items: center; justify-content: center;">
                <img src="{{ $emoneyBase64 ?: asset('logoKTA/logo_emoney_resmi.png') }}" alt="e-money" style="height: 48px; width: auto; object-fit: contain;">
            </div>
        </div>
    </div>

    {{-- EXPORT BELAKANG: 1016 x 635 px (Standar Cetak PVC 300 DPI) --}}
    <div id="kta-export-back" style="width: 1016px; height: 635px; background: linear-gradient(135deg, #09090b 0%, #020617 50%, #0a0a0a 100%); color: #ffffff; padding: 0; box-sizing: border-box; position: relative; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; display: flex; flex-direction: column; justify-content: space-between; border-radius: 28px; overflow: hidden; border: 3px solid rgba(255,255,255,0.2);">
        
        {{-- Pita Identitas Atas (E-KTA Resmi) --}}
        <div>
            <div style="height: 68px; background: #18181b; border-bottom: 2px solid rgba(255,255,255,0.15); display: flex; align-items: center; justify-content: space-between; padding: 0 44px;">
                <div style="display: flex; align-items: center; gap: 14px;">
                    <span style="font-size: 15px; font-family: monospace; font-weight: 800; background: rgba(16, 185, 129, 0.2); color: #6ee7b7; border: 1.5px solid rgba(16, 185, 129, 0.4); padding: 4px 12px; border-radius: 8px;">
                        E-KTA
                    </span>
                    <span style="font-size: 19px; font-weight: 900; letter-spacing: 2px; color: #ffffff; text-transform: uppercase;">
                        KARTU TANDA ANGGOTA ELEKTRONIK
                    </span>
                </div>
                <span style="font-size: 20px; font-weight: 900; color: #fbbf24; letter-spacing: 2px;">
                    SALAM SADA ROHA
                </span>
            </div>
            <div style="height: 38px; background: rgba(0,0,0,0.65); border-bottom: 1.5px solid rgba(255,255,255,0.08); display: flex; align-items: center; justify-content: space-between; padding: 0 44px; font-size: 15px;">
                <div style="color: #4ade80; font-weight: 800; display: flex; align-items: center; gap: 8px; text-transform: uppercase; letter-spacing: 1.5px;">
                    <span>🛡️</span> <span>BASIS: {{ strtoupper($user->chapter?->name ?? 'CHAPTER MEDAN KOTA') }}</span>
                </div>
                <div style="color: #4ade80; font-weight: 800; letter-spacing: 1px; display: flex; align-items: center; gap: 6px;">
                    <span>🟢</span> <span>TERVERIFIKASI DIGITAL</span>
                </div>
            </div>
        </div>

        {{-- Isi Kartu: Tanda Tangan, Ketentuan & QR Verifikasi E-KTA --}}
        <div style="padding: 24px 44px; display: flex; align-items: center; justify-content: space-between; gap: 40px; margin: auto 0;">
            {{-- Kiri: Tanda Tangan & Ketentuan --}}
            <div style="flex: 1; min-width: 0;">
                <div style="display: flex; align-items: center; justify-content: space-between; max-width: 480px; margin-bottom: 8px;">
                    <div style="font-size: 16px; color: rgba(255,255,255,0.7); font-weight: 600;">
                        Tanda Tangan Pemegang Sah:
                    </div>
                    <div style="font-size: 16px; font-family: monospace; color: #fbbf24; font-weight: 800;">
                        NIA: {{ $user->profile?->nia ?? 'SMCK-1950' }}
                    </div>
                </div>
                <div style="display: flex; align-items: center; width: 480px; height: 74px; background: #ffffff; border-radius: 14px; overflow: hidden; border: 2px solid rgba(255,255,255,0.2);">
                    <div style="flex: 1; height: 100%; background: #f8fafc; display: flex; align-items: center; padding: 0 24px;">
                        <span style="font-family: 'Caveat', cursive, sans-serif; font-size: 38px; font-weight: 700; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            {{ $user->name }}
                        </span>
                    </div>
                    <div style="padding: 0 24px; height: 100%; background: #15803d; color: #ffffff; display: flex; align-items: center; justify-content: center;">
                        <span style="font-size: 16px; font-weight: 900; letter-spacing: 2px;">
                            SAH
                        </span>
                    </div>
                </div>

                <div style="font-size: 15px; color: rgba(255,255,255,0.75); line-height: 1.8; margin-top: 18px;">
                    <div style="font-weight: 800; color: #fbbf24; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 4px;">
                        KETENTUAN PEMEGANG E-KTA:
                    </div>
                    <div>• Identitas resmi anggota terdaftar suporter SMeCK Hooligan (Ayam Kinantan).</div>
                    <div>• Hak akses presensi matchday Stadion Teladan, laga awaydays, & agenda suporter.</div>
                    <div>• E-KTA bersifat pribadi & wajib menjunjung ikrar persaudaraan <em>Salam Sada Roha</em>.</div>
                </div>
            </div>

            {{-- Kanan: Box QR Verifikasi Digital --}}
            <div style="width: 230px; background: rgba(0,0,0,0.6); border: 2px solid rgba(74,222,128,0.4); border-radius: 24px; padding: 18px; text-align: center; box-sizing: border-box; display: flex; flex-direction: column; align-items: center; box-shadow: 0 10px 30px rgba(0,0,0,0.6);">
                <div style="font-size: 14px; font-weight: 900; color: #4ade80; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 10px;">
                    VERIFIKASI DIGITAL
                </div>
                <div style="width: 160px; height: 160px; background: #ffffff; border-radius: 14px; padding: 8px; box-sizing: border-box; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 16px rgba(0,0,0,0.4);">
                    <div id="export-dynamic-qr" style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;"></div>
                </div>
                <div style="font-size: 12px; color: #4ade80; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; margin-top: 10px;">
                    SCAN CEK KEASLIAN
                </div>
            </div>
        </div>

        {{-- Footer Belakang --}}
        <div style="height: 56px; border-top: 2px solid rgba(255,255,255,0.15); padding: 0 44px; display: flex; align-items: center; justify-content: space-between; font-size: 16px;">
            <div style="color: rgba(255,255,255,0.7); display: flex; align-items: center; gap: 10px;">
                <span style="color: #4ade80;">📞</span>
                <span>Pengurus Chapter: <strong style="color: #ffffff; font-family: monospace;">{{ $user->profile?->emergency_contact_number ?? '0812-6543-2199' }}</strong></span>
            </div>
            <div style="font-weight: 900; color: #fbbf24; letter-spacing: 2px;">
                "AYAM KINANTAN JUARA"
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
        const modal = document.getElementById('modalDownloadKta');
        if (modal) {
            modal.classList.remove('hidden');
            modal.style.display = 'flex';
        }
        switchKtaTab('front');
        ensureExportQrCodeReady();

        // Render QR Code di Modal
        const modalQr = document.getElementById('modal-dynamic-qr');
        if (modalQr && typeof QRCode !== 'undefined') {
            const qrVerificationUrl = "{{ route('api.check-kta', ['query' => $user->profile?->nia ?? ($user->nfcCard?->card_number ?? 'SMCK-1950')]) }}";
            modalQr.innerHTML = '';
            new QRCode(modalQr, {
                text: qrVerificationUrl,
                width: 76,
                height: 76,
                colorDark: "#052e16",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.M
            });
        }
    }

    function closeDownloadKtaModal() {
        const modal = document.getElementById('modalDownloadKta');
        if (modal) {
            modal.classList.add('hidden');
            modal.style.display = 'none';
        }
    }

    function openMemberCheckinModal() {
        const modal = document.getElementById('modalMemberCheckin');
        if (modal) {
            modal.classList.remove('hidden');
            modal.style.display = 'flex';
        }
    }

    function closeMemberCheckinModal() {
        const modal = document.getElementById('modalMemberCheckin');
        if (modal) {
            modal.classList.add('hidden');
            modal.style.display = 'none';
        }
    }

    // Pastikan QR Code di export staging selalu siap
    function ensureExportQrCodeReady() {
        const qrVerificationUrl = "{{ route('api.check-kta', ['query' => $user->profile?->nia ?? ($user->nfcCard?->card_number ?? 'SMCK-1950')]) }}";
        const exportQr = document.getElementById('export-dynamic-qr');
        if (exportQr && typeof QRCode !== 'undefined' && exportQr.children.length === 0) {
            try {
                new QRCode(exportQr, {
                    text: qrVerificationUrl,
                    width: 150,
                    height: 150,
                    colorDark: "#052e16",
                    colorLight: "#ffffff",
                    correctLevel: QRCode.CorrectLevel.M
                });
            } catch (e) {
                console.warn('QRCode export staging error:', e);
            }
        }
    }

    // Fungsi Utama Download KTA sebagai Gambar PNG 300 DPI (1016 x 635 px)
    function downloadKtaImage(side, btn, onComplete) {
        ensureExportQrCodeReady();

        const elementId = side === 'front' ? 'kta-export-front' : 'kta-export-back';
        let element = document.getElementById(elementId);
        if (!element) {
            element = document.getElementById(side === 'front' ? 'kta-pvc-front' : 'kta-pvc-back');
        }
        if (!element) {
            if (onComplete) onComplete(false);
            return;
        }

        const originalHtml = btn ? btn.innerHTML : '';
        if (btn && !onComplete) {
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i> Merender PNG...';
            btn.disabled = true;
        }

        const memberSlug = "{{ Str::slug($user->name) }}";
        const sideLabel = side === 'front' ? 'DEPAN' : 'BELAKANG';
        const fileName = `KTA-SMECK-${sideLabel}-${memberSlug}-1016x635px-300DPI.png`;

        const finishDownload = (dataUrl) => {
            const link = document.createElement('a');
            link.download = fileName;
            link.href = dataUrl;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            if (btn && !onComplete) {
                btn.innerHTML = originalHtml;
                btn.disabled = false;
            }
            if (onComplete) onComplete(true);
        };

        if (typeof html2canvas !== 'undefined') {
            html2canvas(element, {
                width: 1016,
                height: 635,
                scale: 1,
                useCORS: true,
                allowTaint: false,
                backgroundColor: side === 'front' ? '#052e16' : '#09090b',
                logging: false
            }).then(canvas => {
                const dataUrl = canvas.toDataURL('image/png', 1.0);
                finishDownload(dataUrl);
            }).catch(err => {
                console.warn('html2canvas error, using native canvas fallback:', err);
                try {
                    drawFallbackKta(side, finishDownload);
                } catch (fallbackErr) {
                    console.error('KTA export failed completely:', fallbackErr);
                    if (btn && !onComplete) {
                        btn.innerHTML = originalHtml;
                        btn.disabled = false;
                    }
                    if (onComplete) {
                        onComplete(false);
                    } else {
                        alert('Gagal mendownload gambar PNG. Silakan gunakan opsi Cetak PDF.');
                    }
                }
            });
        } else {
            try {
                drawFallbackKta(side, finishDownload);
            } catch (e) {
                if (btn && !onComplete) {
                    btn.innerHTML = originalHtml;
                    btn.disabled = false;
                    alert('Pustaka pengolah gambar sedang dimuat, silakan coba beberapa saat lagi.');
                }
                if (onComplete) onComplete(false);
            }
        }
    }

    // Fungsi Unduh Sepasang Sekaligus (Depan + Belakang PNG)
    function downloadBothKtaImages(btn) {
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i> Mengunduh Depan (1/2)...';
        btn.disabled = true;

        downloadKtaImage('front', btn, function(successFront) {
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i> Mengunduh Belakang (2/2)...';
            setTimeout(() => {
                downloadKtaImage('back', btn, function(successBack) {
                    btn.innerHTML = '<i class="fa-solid fa-check mr-1"></i> Berhasil Diunduh!';
                    setTimeout(() => {
                        btn.innerHTML = originalHtml;
                        btn.disabled = false;
                    }, 2000);
                });
            }, 600);
        });
    }

    // Native HTML5 2D Canvas Fallback Drawer (100% Guaranteed Success without any external dependencies)
    function drawFallbackKta(side, callback) {
        const canvas = document.createElement('canvas');
        canvas.width = 1016;
        canvas.height = 635;
        const ctx = canvas.getContext('2d');

        if (side === 'front') {
            const grad = ctx.createLinearGradient(0, 0, 1016, 635);
            grad.addColorStop(0, '#052e16');
            grad.addColorStop(0.35, '#064e3b');
            grad.addColorStop(0.7, '#0f172a');
            grad.addColorStop(1, '#020617');
            ctx.fillStyle = grad;
            ctx.fillRect(0, 0, 1016, 635);

            ctx.strokeStyle = 'rgba(255, 255, 255, 0.2)';
            ctx.lineWidth = 4;
            ctx.strokeRect(8, 8, 1000, 619);

            ctx.fillStyle = '#ffffff';
            ctx.font = '900 32px sans-serif';
            ctx.fillText('SMeCK HOOLIGAN', 145, 75);

            ctx.fillStyle = '#fbbf24';
            ctx.font = '700 15px sans-serif';
            ctx.fillText('SUPORTER RESMI PSMS MEDAN', 145, 105);

            // Brand Mandiri (Kanan Atas)
            ctx.fillStyle = 'rgba(255, 255, 255, 0.12)';
            ctx.beginPath();
            ctx.roundRect(800, 42, 168, 50, 12);
            ctx.fill();
            ctx.strokeStyle = 'rgba(255, 255, 255, 0.25)';
            ctx.lineWidth = 1.5;
            ctx.stroke();

            // Mandiri golden swoosh
            ctx.strokeStyle = '#f59e0b';
            ctx.lineWidth = 4;
            ctx.beginPath();
            ctx.arc(825, 68, 12, 1 * Math.PI, 1.8 * Math.PI);
            ctx.stroke();
            ctx.strokeStyle = '#fbbf24';
            ctx.lineWidth = 2.5;
            ctx.beginPath();
            ctx.arc(827, 65, 8, 1 * Math.PI, 1.8 * Math.PI);
            ctx.stroke();

            ctx.fillStyle = '#ffffff';
            ctx.font = '900 22px sans-serif';
            ctx.fillText('mandiri', 848, 74);

            // Middle: PSMS Medan Emblem (Chip Dihapus)
            ctx.fillStyle = 'rgba(0, 0, 0, 0.45)';
            ctx.beginPath();
            ctx.roundRect(48, 185, 280, 80, 16);
            ctx.fill();
            ctx.strokeStyle = 'rgba(255, 255, 255, 0.12)';
            ctx.lineWidth = 1.5;
            ctx.stroke();

            ctx.fillStyle = '#d4af37';
            ctx.font = '800 13px sans-serif';
            ctx.fillText('PSMS MEDAN 1950', 70, 218);
            ctx.fillStyle = '#ffffff';
            ctx.font = '900 22px sans-serif';
            ctx.fillText('AYAM KINANTAN', 70, 246);

            ctx.textAlign = 'right';
            ctx.fillStyle = 'rgba(255, 255, 255, 0.5)';
            ctx.font = '700 15px sans-serif';
            ctx.fillText('BASIS CHAPTER', 968, 215);

            ctx.fillStyle = '#86efac';
            ctx.font = '900 26px sans-serif';
            ctx.fillText("{{ $user->chapter?->name ?? 'Chapter Medan Kota' }}", 968, 250);
            ctx.textAlign = 'left';

            ctx.strokeStyle = 'rgba(255, 255, 255, 0.15)';
            ctx.lineWidth = 2;
            ctx.beginPath();
            ctx.moveTo(48, 470);
            ctx.lineTo(968, 470);
            ctx.stroke();

            ctx.fillStyle = '#ffffff';
            ctx.font = '900 32px sans-serif';
            ctx.fillText("{{ strtoupper($user->name) }}", 200, 520);

            ctx.fillStyle = '#fbbf24';
            ctx.font = 'bold 24px monospace';
            ctx.fillText("{{ $user->profile?->nia ?? 'SMCK-2024-1950' }}", 200, 555);

            ctx.fillStyle = 'rgba(255, 255, 255, 0.7)';
            ctx.font = '16px sans-serif';
            ctx.fillText("GOL: {{ $user->profile?->blood_type ?? 'O' }}  •  EST: {{ $user->profile?->joined_date?->format('Y') ?? '2024' }}", 200, 585);

            // Brand e-money (Kanan Bawah)
            ctx.fillStyle = '#ffffff';
            ctx.beginPath();
            ctx.roundRect(810, 530, 158, 52, 14);
            ctx.fill();
            ctx.strokeStyle = 'rgba(255, 255, 255, 0.8)';
            ctx.lineWidth = 2;
            ctx.stroke();

            ctx.fillStyle = '#002d62';
            ctx.font = '900 26px sans-serif';
            ctx.fillText('e-money', 835, 567);

            // e-money orange swoosh
            ctx.strokeStyle = '#f59e0b';
            ctx.lineWidth = 3.5;
            ctx.beginPath();
            ctx.arc(885, 545, 18, 1.1 * Math.PI, 1.8 * Math.PI);
            ctx.stroke();

            const logoSrc = "{{ $logoBase64 }}";
            const photoSrc = "{{ $photoBase64 }}";

            const renderPhotoAndDone = () => {
                if (photoSrc) {
                    const pImg = new Image();
                    pImg.onload = () => {
                        ctx.save();
                        ctx.beginPath();
                        ctx.roundRect(48, 485, 120, 120, 18);
                        ctx.clip();
                        // Anchor to top so portrait face is always visible
                        const aspect = pImg.width / pImg.height;
                        let sw = pImg.width, sh = pImg.height, sx = 0, sy = 0;
                        if (aspect > 1) {
                            sw = pImg.height;
                            sx = (pImg.width - sw) / 2;
                        } else {
                            sh = pImg.width;
                            sy = 0;
                        }
                        ctx.drawImage(pImg, sx, sy, sw, sh, 48, 485, 120, 120);
                        ctx.restore();
                        ctx.strokeStyle = '#fbbf24';
                        ctx.lineWidth = 3;
                        ctx.strokeRect(48, 485, 120, 120);
                        callback(canvas.toDataURL('image/png', 1.0));
                    };
                    pImg.onerror = () => callback(canvas.toDataURL('image/png', 1.0));
                    pImg.src = photoSrc;
                } else {
                    ctx.fillStyle = '#064e3b';
                    ctx.beginPath();
                    ctx.roundRect(48, 485, 120, 120, 18);
                    ctx.fill();
                    ctx.fillStyle = '#ffffff';
                    ctx.font = '900 48px sans-serif';
                    ctx.fillText("{{ strtoupper(substr($user->name, 0, 1)) }}", 88, 565);
                    callback(canvas.toDataURL('image/png', 1.0));
                }
            };

            if (logoSrc) {
                const lImg = new Image();
                lImg.onload = () => {
                    ctx.drawImage(lImg, 48, 45, 75, 75);
                    renderPhotoAndDone();
                };
                lImg.onerror = () => renderPhotoAndDone();
                lImg.src = logoSrc;
            } else {
                renderPhotoAndDone();
            }

        } else {
            ctx.fillStyle = '#0a0a0c';
            ctx.fillRect(0, 0, 1016, 635);

            ctx.fillStyle = '#18181b';
            ctx.fillRect(0, 0, 1016, 75);
            ctx.strokeStyle = 'rgba(255, 255, 255, 0.15)';
            ctx.lineWidth = 2;
            ctx.beginPath();
            ctx.moveTo(0, 75);
            ctx.lineTo(1016, 75);
            ctx.stroke();

            ctx.fillStyle = '#0a0a0c';
            ctx.fillRect(0, 0, 1016, 635);

            // Pita Atas
            ctx.fillStyle = '#18181b';
            ctx.fillRect(0, 0, 1016, 75);
            ctx.strokeStyle = 'rgba(255, 255, 255, 0.15)';
            ctx.lineWidth = 2;
            ctx.beginPath();
            ctx.moveTo(0, 75);
            ctx.lineTo(1016, 75);
            ctx.stroke();

            // Badge E-KTA
            ctx.fillStyle = 'rgba(16, 185, 129, 0.2)';
            ctx.beginPath();
            ctx.roundRect(44, 22, 64, 32, 8);
            ctx.fill();
            ctx.strokeStyle = 'rgba(16, 185, 129, 0.4)';
            ctx.lineWidth = 1.5;
            ctx.stroke();

            ctx.fillStyle = '#6ee7b7';
            ctx.font = 'bold 15px monospace';
            ctx.fillText("E-KTA", 54, 43);

            ctx.fillStyle = '#ffffff';
            ctx.font = '900 18px sans-serif';
            ctx.fillText("KARTU TANDA ANGGOTA ELEKTRONIK RESMI", 122, 44);

            ctx.textAlign = 'right';
            ctx.fillStyle = '#fbbf24';
            ctx.font = '900 19px sans-serif';
            ctx.fillText('SALAM SADA ROHA', 972, 45);
            ctx.textAlign = 'left';

            // Sub-bar Basis & Status
            ctx.fillStyle = 'rgba(0, 0, 0, 0.65)';
            ctx.fillRect(0, 75, 1016, 38);
            ctx.fillStyle = '#4ade80';
            ctx.font = 'bold 15px sans-serif';
            ctx.fillText("🛡️ BASIS: {{ strtoupper($user->chapter?->name ?? 'CHAPTER MEDAN KOTA') }}", 44, 100);

            ctx.textAlign = 'right';
            ctx.fillStyle = '#4ade80';
            ctx.font = 'bold 14px sans-serif';
            ctx.fillText('🟢 TERVERIFIKASI DIGITAL', 972, 100);
            ctx.textAlign = 'left';

            // Tanda Tangan
            ctx.fillStyle = 'rgba(255, 255, 255, 0.7)';
            ctx.font = '15px sans-serif';
            ctx.fillText('Tanda Tangan Pemegang Sah:', 48, 155);

            ctx.fillStyle = '#ffffff';
            ctx.beginPath();
            ctx.roundRect(48, 170, 480, 74, 14);
            ctx.fill();

            ctx.fillStyle = '#0f172a';
            ctx.font = 'bold 38px Caveat, cursive, sans-serif';
            ctx.fillText("{{ $user->name }}", 72, 220);

            // Badge SAH
            ctx.fillStyle = '#15803d';
            ctx.beginPath();
            ctx.roundRect(440, 170, 88, 74, [0, 14, 14, 0]);
            ctx.fill();
            ctx.fillStyle = '#ffffff';
            ctx.font = '900 16px sans-serif';
            ctx.fillText('SAH', 466, 215);

            ctx.fillStyle = '#fbbf24';
            ctx.font = 'bold 17px monospace';
            ctx.fillText("NIA: {{ $user->profile?->nia ?? 'SMCK-1950' }}", 48, 280);

            // Maklumat & Ketentuan Suporter E-KTA
            ctx.fillStyle = '#fbbf24';
            ctx.font = 'bold 16px sans-serif';
            ctx.fillText("KETENTUAN PEMEGANG E-KTA:", 48, 330);

            ctx.fillStyle = 'rgba(255, 255, 255, 0.75)';
            ctx.font = '14.5px sans-serif';
            ctx.fillText("• Identitas resmi anggota terdaftar suporter SMeCK Hooligan (Ayam Kinantan).", 48, 370);
            ctx.fillText("• Hak akses presensi matchday Stadion Teladan, laga awaydays, & agenda suporter.", 48, 410);
            ctx.fillText("• E-KTA bersifat pribadi & wajib menjunjung ikrar persaudaraan Salam Sada Roha.", 48, 450);

            // Garis Pembatas Bawah
            ctx.strokeStyle = 'rgba(255, 255, 255, 0.15)';
            ctx.lineWidth = 2;
            ctx.beginPath();
            ctx.moveTo(48, 565);
            ctx.lineTo(968, 565);
            ctx.stroke();

            // Footer Belakang
            ctx.fillStyle = 'rgba(255, 255, 255, 0.7)';
            ctx.font = '15px sans-serif';
            ctx.fillText("📞 Pengurus Chapter: {{ $user->profile?->emergency_contact_number ?? '0812-6543-2199' }}", 48, 602);

            ctx.textAlign = 'right';
            ctx.fillStyle = '#fbbf24';
            ctx.font = '900 17px sans-serif';
            ctx.fillText('"AYAM KINANTAN JUARA"', 968, 602);
            ctx.textAlign = 'left';

            // Box QR Verifikasi Digital
            ctx.fillStyle = 'rgba(0, 0, 0, 0.6)';
            ctx.beginPath();
            ctx.roundRect(720, 140, 248, 395, 24);
            ctx.fill();
            ctx.strokeStyle = 'rgba(74, 222, 128, 0.4)';
            ctx.lineWidth = 2;
            ctx.stroke();

            ctx.textAlign = 'center';
            ctx.fillStyle = '#4ade80';
            ctx.font = '800 14px sans-serif';
            ctx.fillText('VERIFIKASI DIGITAL', 844, 178);

            // White box for QR code
            ctx.fillStyle = '#ffffff';
            ctx.beginPath();
            ctx.roundRect(764, 200, 160, 160, 14);
            ctx.fill();

            const qrImg = document.querySelector('#export-dynamic-qr img, #dynamic-kta-qr img, #modal-dynamic-qr img');
            if (qrImg && qrImg.src) {
                const img = new Image();
                img.onload = () => {
                    ctx.drawImage(img, 764, 200, 160, 160);
                    ctx.textAlign = 'center';
                    ctx.fillStyle = '#4ade80';
                    ctx.font = 'bold 12px sans-serif';
                    ctx.fillText('SCAN CEK KEASLIAN', 844, 400);
                    ctx.fillStyle = 'rgba(255, 255, 255, 0.5)';
                    ctx.font = '11px sans-serif';
                    ctx.fillText('PORTAL RESMI SUPORTER', 844, 430);
                    ctx.textAlign = 'left';
                    callback(canvas.toDataURL('image/png', 1.0));
                };
                img.onerror = () => {
                    ctx.textAlign = 'center';
                    ctx.fillStyle = '#4ade80';
                    ctx.font = 'bold 12px sans-serif';
                    ctx.fillText('SCAN CEK KEASLIAN', 844, 400);
                    ctx.textAlign = 'left';
                    callback(canvas.toDataURL('image/png', 1.0));
                };
                img.src = qrImg.src;
            } else {
                ctx.textAlign = 'center';
                ctx.fillStyle = '#4ade80';
                ctx.font = 'bold 12px sans-serif';
                ctx.fillText('SCAN CEK KEASLIAN', 844, 400);
                ctx.textAlign = 'left';
                callback(canvas.toDataURL('image/png', 1.0));
            }
        }
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

                const exportQr = document.getElementById('export-dynamic-qr');
                if (exportQr) {
                    exportQr.innerHTML = '';
                    new QRCode(exportQr, {
                        text: qrVerificationUrl,
                        width: 150,
                        height: 150,
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

    // Expose all interactive functions explicitly on window
    window.flipKtaCard = flipKtaCard;
    window.openDownloadKtaModal = openDownloadKtaModal;
    window.closeDownloadKtaModal = closeDownloadKtaModal;
    window.switchKtaTab = switchKtaTab;
    window.downloadKtaImage = downloadKtaImage;
    window.downloadBothKtaImages = downloadBothKtaImages;
    window.openMemberCheckinModal = openMemberCheckinModal;
    window.closeMemberCheckinModal = closeMemberCheckinModal;
</script>
@endpush

