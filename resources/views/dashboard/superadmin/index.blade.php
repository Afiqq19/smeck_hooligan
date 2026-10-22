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
                        <i class="fa-solid fa-crown mr-1"></i> Komando Pusat DPP SMeCK Hooligan Indonesia
                    </div>
                    <h1 class="font-display font-black text-2xl sm:text-3xl text-white">
                        Statistik Nasional Suporter PSMS Medan
                    </h1>
                    <p class="text-xs sm:text-sm text-white/60 mt-1 max-w-xl">
                        Pantau pertumbuhan basis anggota terverifikasi di seluruh Sumatera Utara & perantauan nasional, peredaran kartu NFC fisik, serta distribusi kuota tribun Teladan.
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
                    <span>Broadcast DPP</span>
                </a>
            </div>
        </div>
    </div>

    {{-- National Statistics Metric Cards (Synchronized with Membership Data) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        {{-- Card 1: Total Anggota Nasional --}}
        <div class="p-6 rounded-3xl bg-smeck-dark-900 border border-white/10 shadow-xl relative overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-white/40 uppercase">Total Anggota Nasional</span>
                <div class="w-8 h-8 rounded-lg bg-smeck-green-500/20 text-smeck-green-400 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
            <div class="font-display font-black text-3xl text-white mt-2">{{ $totalMembers }}</div>
            <div class="text-[11px] mt-2 flex flex-wrap items-center gap-1.5 font-medium">
                <span class="px-1.5 py-0.5 rounded bg-smeck-green-500/20 text-smeck-green-300 font-mono text-[10px]">{{ $activeMembers }} Aktif</span>
                @if($pendingMembers > 0)
                    <span class="px-1.5 py-0.5 rounded bg-amber-500/20 text-amber-300 font-mono text-[10px]">{{ $pendingMembers }} Pending</span>
                @endif
                @if($suspendedMembers > 0)
                    <span class="px-1.5 py-0.5 rounded bg-red-500/20 text-red-300 font-mono text-[10px]">{{ $suspendedMembers }} Sanksi</span>
                @endif
            </div>
        </div>

        {{-- Card 2: Total Basis / Chapter --}}
        <div class="p-6 rounded-3xl bg-smeck-dark-900 border border-white/10 shadow-xl">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-white/40 uppercase">Total Basis / Chapter</span>
                <div class="w-8 h-8 rounded-lg bg-blue-500/20 text-blue-400 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-sitemap"></i>
                </div>
            </div>
            <div class="font-display font-black text-3xl text-white mt-2">{{ $totalChapters }}</div>
            <div class="text-[11px] text-blue-400 mt-2 font-medium">
                <i class="fa-solid fa-location-dot mr-1"></i> Tersebar di Sumut & Perantauan
            </div>
        </div>

        {{-- Card 3: KTA NFC Fisik Aktif --}}
        <div class="p-6 rounded-3xl bg-smeck-dark-900 border border-white/10 shadow-xl">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-white/40 uppercase">KTA NFC Fisik Beredar</span>
                <div class="w-8 h-8 rounded-lg bg-amber-500/20 text-amber-400 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-wifi"></i>
                </div>
            </div>
            <div class="font-display font-black text-3xl text-amber-400 mt-2">{{ $totalActiveCards }}</div>
            <div class="text-[11px] text-white/50 mt-2 flex items-center gap-1.5 font-medium">
                <span class="text-smeck-green-400 font-mono text-[10px]"><i class="fa-solid fa-check mr-0.5"></i>{{ $totalActiveCards }} Aktif</span>
                <span>•</span>
                <span class="text-red-400 font-mono text-[10px]"><i class="fa-solid fa-ban mr-0.5"></i>{{ $totalBlockedCards }} Diblokir</span>
            </div>
        </div>

        {{-- Card 4: Total Kas Terkumpul --}}
        <div class="p-6 rounded-3xl bg-smeck-dark-900 border border-white/10 shadow-xl">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-white/40 uppercase">Total Kas Terverifikasi</span>
                <div class="w-8 h-8 rounded-lg bg-smeck-gold/20 text-smeck-gold flex items-center justify-center text-sm">
                    <i class="fa-solid fa-vault"></i>
                </div>
            </div>
            <div class="font-display font-black text-2xl text-smeck-gold font-mono mt-2">
                Rp {{ number_format($totalNationalFunds, 0, ',', '.') }}
            </div>
            <div class="text-[11px] text-white/50 mt-2 font-medium">
                <i class="fa-solid fa-hand-holding-dollar mr-1 text-smeck-gold"></i> Akumulasi Iuran Sukarela Anggota
            </div>
        </div>

    </div>

    {{-- Chapter Distribution & Recent Members --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        {{-- Chapter Distribution Progress Bars --}}
        <div class="lg:col-span-6 p-6 sm:p-8 rounded-3xl bg-smeck-dark-900 border border-white/10 space-y-4 shadow-xl">
            <div class="flex items-center justify-between mb-2">
                <div>
                    <h3 class="font-display font-bold text-lg text-white">Distribusi Anggota per Basis</h3>
                    <p class="text-xs text-white/40">Peringkat chapter berdasarkan anggota terverifikasi</p>
                </div>
                <a href="{{ route('superadmin.chapters') }}" class="text-xs text-red-400 font-bold hover:underline">Kelola Basis →</a>
            </div>

            <div class="space-y-4">
                @php
                    $maxCount = max(1, $chapters->max('users_count') ?? 1);
                @endphp
                @forelse($chapters as $ch)
                    @php
                        $pct = round(($ch->users_count / $maxCount) * 100);
                        $nationalPct = $totalMembers > 0 ? round(($ch->users_count / $totalMembers) * 100) : 0;
                    @endphp
                    <div>
                        <div class="flex items-center justify-between text-xs mb-1.5">
                            <span class="font-semibold text-white flex items-center gap-1.5">
                                <i class="fa-solid fa-location-dot text-red-400 text-[10px]"></i>
                                {{ $ch->name }}
                            </span>
                            <div class="flex items-center gap-2">
                                <span class="text-smeck-gold font-mono font-bold">{{ $ch->users_count }} Anggota</span>
                                <span class="px-1.5 py-0.2 rounded text-[10px] bg-white/10 text-white/60 font-mono">{{ $nationalPct }}%</span>
                            </div>
                        </div>
                        <div class="w-full h-2 rounded-full bg-white/10 overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-smeck-green-600 via-emerald-400 to-smeck-gold rounded-full transition-all duration-500" style="width: {{ max(8, $pct) }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6 text-white/40 text-xs">Belum ada basis wilayah terdaftar.</div>
                @endforelse
            </div>
        </div>

        {{-- Kesiapsiagaan Medis Tribun (Golongan Darah Nasional) --}}
        <div class="lg:col-span-6 p-6 sm:p-8 rounded-3xl bg-smeck-dark-900 border border-white/10 space-y-4 shadow-xl">
            <div class="flex items-center justify-between mb-2">
                <div>
                    <h3 class="font-display font-bold text-lg text-white flex items-center gap-2">
                        <i class="fa-solid fa-droplet text-red-500"></i>
                        <span>Kesiapsiagaan Medis Tribun</span>
                    </h3>
                    <p class="text-xs text-white/40">Komposisi golongan darah anggota untuk tanggap darurat laga</p>
                </div>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-red-500/20 text-red-300 border border-red-500/30">
                    <i class="fa-solid fa-kit-medical mr-1"></i> Tanggap Darurat
                </span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <div class="p-4 rounded-2xl bg-white/[0.03] border border-white/5 text-center">
                    <div class="text-xs text-white/40 font-bold uppercase">Gol. A</div>
                    <div class="font-display font-black text-2xl text-red-400 mt-1">{{ $bloodTypeStats['A'] ?? 0 }}</div>
                    <div class="text-[10px] text-white/40">Suporter</div>
                </div>
                <div class="p-4 rounded-2xl bg-white/[0.03] border border-white/5 text-center">
                    <div class="text-xs text-white/40 font-bold uppercase">Gol. B</div>
                    <div class="font-display font-black text-2xl text-amber-400 mt-1">{{ $bloodTypeStats['B'] ?? 0 }}</div>
                    <div class="text-[10px] text-white/40">Suporter</div>
                </div>
                <div class="p-4 rounded-2xl bg-white/[0.03] border border-white/5 text-center">
                    <div class="text-xs text-white/40 font-bold uppercase">Gol. AB</div>
                    <div class="font-display font-black text-2xl text-emerald-400 mt-1">{{ $bloodTypeStats['AB'] ?? 0 }}</div>
                    <div class="text-[10px] text-white/40">Suporter</div>
                </div>
                <div class="p-4 rounded-2xl bg-white/[0.03] border border-white/5 text-center">
                    <div class="text-xs text-white/40 font-bold uppercase">Gol. O</div>
                    <div class="font-display font-black text-2xl text-blue-400 mt-1">{{ $bloodTypeStats['O'] ?? 0 }}</div>
                    <div class="text-[10px] text-white/40">Suporter</div>
                </div>
            </div>

            {{-- Recent Member Registrations --}}
            <div class="pt-2">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold uppercase text-white/60">Pendaftaran & Suporter Terbaru</span>
                    <a href="{{ route('superadmin.members') }}" class="text-xs text-red-400 hover:underline">Semua Anggota ({{ $totalMembers }}) →</a>
                </div>
                <div class="space-y-2.5">
                    @forelse($recentMembers as $m)
                        <div class="p-2.5 rounded-xl bg-white/[0.02] border border-white/5 flex items-center justify-between">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-8 h-8 rounded-lg bg-smeck-green-600 flex items-center justify-center font-display font-black text-white text-xs shrink-0">
                                    {{ strtoupper(substr($m->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <div class="text-xs font-bold text-white truncate">{{ $m->name }}</div>
                                    <div class="text-[10px] text-white/40 truncate">
                                        {{ $m->chapter?->name ?? 'DPP Pusat' }} • <span class="text-smeck-gold font-mono">{{ $m->profile?->nia ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-1.5 shrink-0">
                                @if($m->status === 'suspended')
                                    <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase bg-red-500/20 text-red-400 border border-red-500/30">
                                        SUSPENDED
                                    </span>
                                @elseif($m->status === 'pending_approval')
                                    <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase bg-amber-500/20 text-amber-300 border border-amber-500/30">
                                        PENDING
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase {{ $m->nfcCard ? 'bg-smeck-green-500/20 text-smeck-green-400' : 'bg-white/10 text-white/40' }}">
                                        {{ $m->nfcCard ? 'KTA AKTIF' : 'BELUM KTA' }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-white/40 text-xs">Belum ada anggota terdaftar.</div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    {{-- Live NFC Tap Attendance Feed --}}
    <div class="p-6 sm:p-8 rounded-3xl bg-smeck-dark-900 border border-white/10 shadow-xl">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="font-display font-bold text-lg text-white flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-smeck-green-400 animate-pulse"></span>
                    <span>Live Tap NFC Gate & Kehadiran Laga</span>
                </h3>
                <p class="text-xs text-white/40">Log kehadiran fisik suporter yang melakukan tap KTA di gerbang stadion & acara resmi</p>
            </div>
            <a href="{{ route('superadmin.nfc') }}" class="text-xs font-bold text-red-400 hover:underline">Kelola KTA NFC →</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($recentAttendances as $att)
                <div class="p-3.5 rounded-2xl bg-white/[0.02] border border-white/5 flex items-center justify-between">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-9 h-9 rounded-xl bg-smeck-gold/20 text-smeck-gold flex items-center justify-center font-bold text-xs shrink-0">
                            <i class="fa-solid fa-tower-broadcast"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="text-xs font-bold text-white truncate">{{ $att->user?->name ?? 'Suporter' }}</div>
                            <div class="text-[10px] text-white/40 truncate">{{ $att->event_name }}</div>
                            <div class="text-[9px] text-smeck-green-400 font-mono">{{ $att->scanned_at ? $att->scanned_at->diffForHumans() : 'Baru saja' }}</div>
                        </div>
                    </div>
                    <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-smeck-green-500/20 text-smeck-green-400 shrink-0">
                        VERIFIED
                    </span>
                </div>
            @empty
                <div class="col-span-full py-8 text-center text-white/40 text-xs">
                    <i class="fa-solid fa-wifi text-2xl mb-2 text-white/20 block"></i>
                    Belum ada riwayat tap NFC hari ini. Data akan muncul otomatis saat gate stadion beroperasi.
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
