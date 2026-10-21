@extends('layouts.app')

@section('title', 'Dashboard Chapter')
@section('header_title', 'Dashboard Pengurus Wilayah')
@section('header_subtitle', 'Panel operasional dan statistik anggota basis ' . ($chapter->name ?? 'Chapter SMeCK'))

@section('content')
<div class="space-y-8 max-w-7xl mx-auto">

    {{-- Chapter Header Info --}}
    <div class="relative overflow-hidden rounded-3xl bg-smeck-dark-900 border border-amber-500/20 p-6 sm:p-8">
        <div class="absolute inset-0 bg-cover bg-center opacity-15 pointer-events-none scale-105" style="background-image: url('{{ asset('images/auth-cover.jpg') }}');"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-smeck-dark-950 via-smeck-dark-950/90 to-amber-950/80"></div>
        <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 rounded-2xl overflow-hidden border-2 border-amber-500/40 shadow-xl p-0.5 bg-black shrink-0 hidden sm:flex items-center justify-center">
                    <img src="{{ asset('images/logo-crest.jpg') }}" alt="SMeCK Logo" class="w-full h-full object-cover rounded-xl">
                </div>
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-500/20 text-amber-300 text-xs font-bold uppercase tracking-wider mb-2">
                        <i class="fa-solid fa-building-flag mr-1"></i> {{ $chapter->region_code ?? 'MDK-01' }}
                    </div>
                    <h1 class="font-display font-black text-2xl sm:text-3xl text-white">
                        {{ $chapter->name ?? 'Chapter Medan Kota' }}
                    </h1>
                    <p class="text-xs sm:text-sm text-white/60 mt-1">
                        Basecamp: {{ $chapter->basecamp_address ?? 'Jl. Stadion Teladan No. 12' }} • Korwil: <strong class="text-white">{{ $chapter->leader_name ?? 'Ketua Wilayah' }}</strong>
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('chapter.scanner') }}" class="px-5 py-3 rounded-xl bg-amber-600 hover:bg-amber-500 text-white font-bold text-xs shadow-lg shadow-amber-700/30 flex items-center gap-2 transition-all">
                    <i class="fa-solid fa-wifi"></i>
                    <span>Buka Scanner Presensi NFC</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="p-6 rounded-3xl bg-smeck-dark-900 border border-white/10">
            <div class="text-xs font-semibold text-white/40 uppercase">Total Anggota Terdaftar</div>
            <div class="font-display font-black text-3xl text-white mt-1">{{ $totalMembers }}</div>
            <div class="text-[11px] text-smeck-green-400 mt-1">Anggota Aktif Basis</div>
        </div>

        <div class="p-6 rounded-3xl bg-smeck-dark-900 border border-white/10">
            <div class="text-xs font-semibold text-white/40 uppercase">Menunggu Verifikasi</div>
            <div class="font-display font-black text-3xl text-amber-400 mt-1">{{ $pendingMembers }}</div>
            <a href="{{ route('chapter.verifications') }}" class="text-[11px] text-amber-300 hover:underline mt-1 block">Periksa Berkas e-KTP →</a>
        </div>

        <div class="p-6 rounded-3xl bg-smeck-dark-900 border border-white/10">
            <div class="text-xs font-semibold text-white/40 uppercase">Total Kas Chapter</div>
            <div class="font-display font-black text-2xl text-smeck-gold font-mono mt-1">Rp {{ number_format($totalKas, 0, ',', '.') }}</div>
            <div class="text-[11px] text-white/40 mt-1">Dana Terverifikasi</div>
        </div>

        <div class="p-6 rounded-3xl bg-smeck-dark-900 border border-white/10">
            <div class="text-xs font-semibold text-white/40 uppercase">Presensi Laga Terakhir</div>
            <div class="font-display font-black text-3xl text-white mt-1">{{ $recentAttendances->count() }}</div>
            <div class="text-[11px] text-white/40 mt-1">Anggota Tap Hadir</div>
        </div>
    </div>

    {{-- Recent Presensi Tap Activity --}}
    <div class="p-6 sm:p-8 rounded-3xl bg-smeck-dark-900 border border-white/10">
        <div class="flex items-center justify-between mb-5">
            <h3 class="font-display font-bold text-lg text-white">Presensi Anggota Terbaru (Realtime Tap)</h3>
            <a href="{{ route('chapter.scanner') }}" class="text-xs text-amber-400 font-bold hover:underline">Scan Lagi →</a>
        </div>

        <div class="space-y-3">
            @forelse($recentAttendances as $rec)
                <div class="p-4 rounded-2xl bg-white/[0.03] border border-white/5 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center font-bold text-sm">
                            <i class="fa-solid fa-id-badge"></i>
                        </div>
                        <div>
                            <div class="text-sm font-bold text-white">{{ $rec->user->name }}</div>
                            <div class="text-xs text-white/40">NIA: {{ $rec->user->profile?->nia ?? 'SMCK-MEMBER' }} • Event: {{ $rec->event_name }}</div>
                        </div>
                    </div>
                    <span class="text-xs font-mono text-smeck-green-400 font-bold">
                        {{ $rec->scanned_at->format('H:i:s - d/m/Y') }}
                    </span>
                </div>
            @empty
                <div class="text-center py-8 text-white/40 text-xs">Belum ada aktivitas presensi tap di chapter ini.</div>
            @endforelse
        </div>
    </div>

</div>
@endsection
