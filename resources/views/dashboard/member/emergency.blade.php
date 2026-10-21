@extends('layouts.app')

@section('title', 'Lapor Kartu Darurat')
@section('header_title', 'Layanan Darurat KTA NFC')
@section('header_subtitle', 'Blokir instan kartu fisik NFC jika hilang atau rusak untuk mencegah penyalahgunaan')

@section('content')
<div class="max-w-3xl mx-auto space-y-8">
    <div class="p-6 sm:p-10 rounded-3xl bg-smeck-dark-900 border border-red-500/30 relative overflow-hidden">
        <div class="absolute -right-10 -top-10 w-48 h-48 bg-red-500/10 rounded-full blur-3xl"></div>

        <div class="flex items-start gap-5">
            <div class="w-14 h-14 rounded-2xl bg-red-500/20 text-red-400 flex items-center justify-center text-2xl shrink-0">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <div>
                <h3 class="font-display font-black text-xl text-white">Status Keamanan Kartu NFC</h3>
                <p class="text-xs sm:text-sm text-white/60 mt-1 leading-relaxed">
                    Jika kartu fisik KTA NFC Anda hilang di stadion atau terjatuh di jalan, Anda dapat langsung mengunci kartu ini secara instan dari panel ini.
                </p>
            </div>
        </div>

        <div class="mt-8 p-5 rounded-2xl bg-white/[0.03] border border-white/10 space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs text-white/50">UID Kartu Fisik:</span>
                <span class="text-xs font-mono font-bold text-white">{{ $user->nfcCard?->uid_number ?? 'BELUM DITAUTKAN' }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-xs text-white/50">Jenis Kartu:</span>
                <span class="text-xs font-bold text-smeck-gold uppercase">{{ $user->nfcCard?->card_vendor ?? 'E-Money' }}</span>
            </div>
            <div class="flex items-center justify-between pt-2 border-t border-white/10">
                <span class="text-xs text-white/50">Status Kartu Saat Ini:</span>
                @if($user->nfcCard && $user->nfcCard->status === 'blocked')
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-500 text-white uppercase tracking-wider">
                        <i class="fa-solid fa-lock mr-1"></i> DIBLOKIR / NONAKTIF
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
                Tindakan ini langsung memperbarui sistem scanner gate stadion secara realtime.
            </div>

            <form action="{{ route('member.emergency.toggle') }}" method="POST">
                @csrf
                @if($user->nfcCard && $user->nfcCard->status === 'blocked')
                    <button type="submit" class="px-6 py-3 rounded-xl bg-smeck-green-600 hover:bg-smeck-green-500 text-white font-bold text-xs shadow-lg transition-all flex items-center gap-2">
                        <i class="fa-solid fa-lock-open"></i>
                        <span>Buka Blokir Kartu (Aktifkan Kembali)</span>
                    </button>
                @else
                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin memblokir kartu NFC fisik Anda? Kartu tidak akan dapat di-tap di stadion.')" class="px-6 py-3 rounded-xl bg-red-600 hover:bg-red-500 text-white font-bold text-xs shadow-lg shadow-red-700/30 transition-all flex items-center gap-2">
                        <i class="fa-solid fa-ban"></i>
                        <span>Blokir Kartu Sekarang (Kartu Hilang)</span>
                    </button>
                @endif
            </form>
        </div>
    </div>
</div>
@endsection
