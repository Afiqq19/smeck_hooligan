@extends('layouts.app')

@section('title', 'Dashboard Utama Pusat')
@section('header_title', 'Dashboard DPP Pusat SMeCK Hooligan')
@section('header_subtitle', 'Pusat komando statistik keanggotaan nasional, monitoring chapter, dan peredaran KTA NFC')

@section('content')
<div class="space-y-8 max-w-7xl mx-auto">

    {{-- Top National Summary Banner --}}
    <div class="relative overflow-hidden rounded-3xl bg-smeck-dark-900 border border-red-500/20 p-6 sm:p-8">
        <div class="absolute inset-0 bg-cover bg-center opacity-15 pointer-events-none scale-105" style="background-image: url('{{ asset('images/auth-cover.jpg') }}');"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-smeck-dark-950 via-smeck-dark-950/90 to-red-950/80"></div>
        <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 rounded-2xl overflow-hidden border-2 border-red-500/40 shadow-xl p-0.5 bg-black shrink-0 hidden sm:flex items-center justify-center">
                    <img src="{{ asset('images/logo-crest.jpg') }}" alt="SMeCK Logo" class="w-full h-full object-cover rounded-xl">
                </div>
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-red-500/20 text-red-300 text-xs font-bold uppercase tracking-wider mb-2">
                        <i class="fa-solid fa-crown mr-1"></i> Komando Pusat SMeCK Hooligan Indonesia
                    </div>
                    <h1 class="font-display font-black text-2xl sm:text-3xl text-white">
                        Statistik Nasional Suporter PSMS Medan
                    </h1>
                    <p class="text-xs sm:text-sm text-white/60 mt-1 max-w-xl">
                        Pantau pertumbuhan basis anggota di seluruh Sumatera Utara & perantauan nasional, peredaran kartu NFC fisik, serta distribusi kuota tribun Teladan.
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('superadmin.nfc') }}" class="px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-500 text-white font-bold text-xs shadow-lg shadow-red-700/30 flex items-center gap-2 transition-all">
                    <i class="fa-solid fa-credit-card"></i>
                    <span>Aktivasi Kartu NFC</span>
                </a>
                <a href="{{ route('superadmin.broadcast') }}" class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/15 text-white font-bold text-xs border border-white/10 flex items-center gap-2 transition-all">
                    <i class="fa-solid fa-bullhorn"></i>
                    <span>Broadcast</span>
                </a>
            </div>
        </div>
    </div>

    {{-- National Statistics Metric Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <div class="p-6 rounded-3xl bg-smeck-dark-900 border border-white/10">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-white/40 uppercase">Total Anggota Nasional</span>
                <div class="w-8 h-8 rounded-lg bg-smeck-green-500/20 text-smeck-green-400 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
            <div class="font-display font-black text-3xl text-white mt-2">{{ $totalMembers }}</div>
            <div class="text-[11px] text-smeck-green-400 mt-1">Tersebar di {{ $totalChapters }} Basis Wilayah</div>
        </div>

        <div class="p-6 rounded-3xl bg-smeck-dark-900 border border-white/10">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-white/40 uppercase">Total Basis / Chapter</span>
                <div class="w-8 h-8 rounded-lg bg-blue-500/20 text-blue-400 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-sitemap"></i>
                </div>
            </div>
            <div class="font-display font-black text-3xl text-white mt-2">{{ $totalChapters }}</div>
            <div class="text-[11px] text-blue-400 mt-1">Sumut & Perantauan</div>
        </div>

        <div class="p-6 rounded-3xl bg-smeck-dark-900 border border-white/10">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-white/40 uppercase">KTA NFC Fisik Aktif</span>
                <div class="w-8 h-8 rounded-lg bg-amber-500/20 text-amber-400 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-wifi"></i>
                </div>
            </div>
            <div class="font-display font-black text-3xl text-amber-400 mt-2">{{ $totalActiveCards }}</div>
            <div class="text-[11px] text-white/50 mt-1">Chip RFID/NFC Beredar</div>
        </div>

        <div class="p-6 rounded-3xl bg-smeck-dark-900 border border-white/10">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-white/40 uppercase">Total Kas Terkumpul</span>
                <div class="w-8 h-8 rounded-lg bg-smeck-gold/20 text-smeck-gold flex items-center justify-center text-sm">
                    <i class="fa-solid fa-vault"></i>
                </div>
            </div>
            <div class="font-display font-black text-2xl text-smeck-gold font-mono mt-2">
                Rp {{ number_format($totalNationalFunds, 0, ',', '.') }}
            </div>
            <div class="text-[11px] text-white/50 mt-1">Akumulasi Kas Sukarela</div>
        </div>

    </div>

    {{-- Chapter Distribution & Recent Members --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        {{-- Chapter Distribution Progress Bars --}}
        <div class="lg:col-span-6 p-6 sm:p-8 rounded-3xl bg-smeck-dark-900 border border-white/10 space-y-4">
            <div class="flex items-center justify-between mb-2">
                <h3 class="font-display font-bold text-lg text-white">Distribusi Anggota per Chapter</h3>
                <a href="{{ route('superadmin.chapters') }}" class="text-xs text-red-400 font-bold hover:underline">Kelola Basis →</a>
            </div>

            <div class="space-y-4">
                @foreach($chapters as $ch)
                    <div>
                        <div class="flex items-center justify-between text-xs mb-1">
                            <span class="font-semibold text-white">{{ $ch->name }}</span>
                            <span class="text-white/60 font-mono">{{ $ch->users_count }} Anggota</span>
                        </div>
                        <div class="w-full h-2 rounded-full bg-white/10 overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-smeck-green-600 to-smeck-green-400 rounded-full" style="width: {{ min(100, max(15, ($ch->users_count / max(1, $totalMembers)) * 100)) }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Recent Member Registrations --}}
        <div class="lg:col-span-6 p-6 sm:p-8 rounded-3xl bg-smeck-dark-900 border border-white/10 space-y-4">
            <div class="flex items-center justify-between mb-2">
                <h3 class="font-display font-bold text-lg text-white">Anggota Baru Terverifikasi</h3>
                <span class="text-xs text-white/40">Database Nasional</span>
            </div>

            <div class="space-y-3">
                @forelse($recentMembers as $m)
                    <div class="p-3.5 rounded-2xl bg-white/[0.03] border border-white/5 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-smeck-green-600 flex items-center justify-center font-display font-black text-white text-sm">
                                {{ strtoupper(substr($m->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="text-xs font-bold text-white">{{ $m->name }}</div>
                                <div class="text-[11px] text-white/40">{{ $m->chapter?->name ?? 'Chapter Medan' }} • NIA: {{ $m->profile?->nia ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $m->nfcCard ? 'bg-smeck-green-500/20 text-smeck-green-400' : 'bg-white/10 text-white/50' }}">
                                {{ $m->nfcCard ? 'NFC READY' : 'BELUM NFC' }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6 text-white/40 text-xs">Belum ada anggota terdaftar.</div>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection
