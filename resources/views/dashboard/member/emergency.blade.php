@extends('layouts.app')

@section('title', 'Lapor Kartu Darurat')
@section('header_title', 'Layanan Darurat KTA NFC')
@section('header_subtitle', 'Transparansi status keamanan kartu fisik NFC: lapor hilang atau pemantauan status sanksi disiplin')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- KASUS 1: AKUN / KARTU TERKENA SANKSI DISIPLIN PENGURUS --}}
    @if($user->status === 'suspended')
        <div class="p-6 sm:p-8 rounded-3xl bg-red-950/40 border-2 border-red-500/50 shadow-2xl relative overflow-hidden">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-2xl bg-red-500 text-white flex items-center justify-center text-xl shrink-0 shadow-lg shadow-red-700/40">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <div class="space-y-2">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase bg-red-500 text-white">
                            SANKSI DISIPLIN ORGANISASI
                        </span>
                        <span class="text-xs text-red-300 font-bold">AKUN & KARTU DINONAKTIFKAN SEMENTARA</span>
                    </div>
                    <h3 class="font-display font-black text-lg text-white">Pemberitahuan Penonaktifan oleh Pengurus</h3>
                    <div class="p-4 rounded-xl bg-black/40 border border-red-500/30 text-xs text-white/90 space-y-1">
                        <div class="text-[11px] uppercase tracking-wider text-red-400 font-bold">Alasan Penonaktifan:</div>
                        <div class="font-medium text-red-200 text-sm">
                            "{{ $user->suspension_reason ?? 'Pelanggaran Kode Etik Organisasi & Tata Tertib Tribun SMeCK Hooligan' }}"
                        </div>
                    </div>
                    <p class="text-xs text-white/70 leading-relaxed pt-1">
                        Sesuai Anggaran Dasar dan Rumah Tangga (AD/ART), status keanggotaan dan akses kartu fisik KTA NFC dibekukan selama masa sanksi. Anda <strong>tidak dapat membuka blokir secara mandiri</strong>. Silakan hubungi Pengurus Basis <strong>{{ $user->chapter?->name ?? 'Chapter' }}</strong> atau Dewan Pimpinan Pusat (DPP) untuk klarifikasi.
                    </p>
                </div>
            </div>
        </div>
    @endif

    {{-- CARD UTAMA: DETAIL KEAMANAN NFC --}}
    <div class="p-6 sm:p-10 rounded-3xl bg-smeck-dark-900 border border-white/10 relative overflow-hidden shadow-2xl">
        <div class="absolute -right-10 -top-10 w-48 h-48 bg-emerald-500/5 rounded-full blur-3xl"></div>

        <div class="flex items-start gap-5">
            <div class="w-14 h-14 rounded-2xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-2xl shrink-0">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <div>
                <h3 class="font-display font-black text-xl text-white">Status Keamanan Kartu NFC Fisik</h3>
                <p class="text-xs sm:text-sm text-white/60 mt-1 leading-relaxed">
                    Fitur darurat untuk melindungi kartu suporter Anda jika hilang, terjatuh di tribun, atau tercecer di perjalanan awaydays.
                </p>
            </div>
        </div>

        <div class="mt-8 p-5 rounded-2xl bg-white/[0.03] border border-white/10 space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs text-white/50">UID Chip NFC:</span>
                <span class="text-xs font-mono font-bold text-white">{{ $user->nfcCard?->uid_number ?? 'BELUM DITAUTKAN' }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-xs text-white/50">Nomor Kartu KTA:</span>
                <span class="text-xs font-mono font-bold text-smeck-gold">{{ $user->nfcCard?->card_number ?? 'BELUM TERBIT' }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-xs text-white/50">Tipe Vendor Fisik:</span>
                <span class="text-xs font-bold text-white uppercase">{{ strtoupper($user->nfcCard?->card_vendor ?? 'Mandiri E-Money') }}</span>
            </div>
            <div class="flex items-center justify-between pt-2 border-t border-white/10">
                <span class="text-xs text-white/50">Status Kartu Saat Ini:</span>
                @if($user->status === 'suspended')
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-600 text-white uppercase tracking-wider">
                        <i class="fa-solid fa-ban mr-1"></i> SANKSI DISIPLIN PENGURUS
                    </span>
                @elseif($user->nfcCard && $user->nfcCard->status === 'lost')
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-500 text-black uppercase tracking-wider">
                        <i class="fa-solid fa-triangle-exclamation mr-1"></i> KARTU HILANG (DILAPORKAN)
                    </span>
                @elseif($user->nfcCard && $user->nfcCard->status === 'blocked')
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-500 text-white uppercase tracking-wider">
                        <i class="fa-solid fa-lock mr-1"></i> DIBLOKIR PENGURUS
                    </span>
                @else
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-smeck-green-500 text-white uppercase tracking-wider">
                        <i class="fa-solid fa-circle-check mr-1"></i> AKTIF & AMAN
                    </span>
                @endif
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-white/10 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="text-xs text-white/40">
                Perubahan status kartu akan langsung tersinkronisasi ke scanner gate stadion Teladan secara realtime.
            </div>

            @if($user->status === 'suspended')
                <button type="button" disabled class="px-6 py-3 rounded-xl bg-white/10 text-white/40 font-bold text-xs cursor-not-allowed flex items-center gap-2">
                    <i class="fa-solid fa-lock"></i>
                    <span>Terkunci oleh Sanksi Organisasi</span>
                </button>
            @elseif($user->nfcCard && $user->nfcCard->status === 'lost')
                <form action="{{ route('member.emergency.toggle') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-6 py-3 rounded-xl bg-smeck-green-600 hover:bg-smeck-green-500 text-white font-bold text-xs shadow-lg transition-all flex items-center gap-2">
                        <i class="fa-solid fa-lock-open"></i>
                        <span>Batalkan Lapor Hilang (Kartu Ditemukan)</span>
                    </button>
                </form>
            @elseif($user->nfcCard && $user->nfcCard->status === 'blocked')
                <div class="text-xs text-red-400 font-bold">
                    <i class="fa-solid fa-circle-exclamation mr-1"></i> Kartu diblokir oleh admin chapter/pusat. Hubungi pengurus.
                </div>
            @elseif($user->nfcCard)
                <form action="{{ route('member.emergency.toggle') }}" method="POST">
                    @csrf
                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin MELAPORKAN HILANG kartu fisik KTA NFC Anda? Kartu fisik akan dinonaktifkan dari gerbang stadion Teladan.')" class="px-6 py-3 rounded-xl bg-red-600 hover:bg-red-500 text-white font-bold text-xs shadow-lg shadow-red-700/30 transition-all flex items-center gap-2">
                        <i class="fa-solid fa-ban"></i>
                        <span>Laporkan Kartu Hilang (Kunci Akses)</span>
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
