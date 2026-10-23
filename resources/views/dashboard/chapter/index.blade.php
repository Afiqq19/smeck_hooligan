@extends('layouts.app')

@section('title', 'Dashboard Chapter')
@section('header_title', 'Dashboard Pengurus Wilayah')
@section('header_subtitle', 'Panel operasional dan statistik anggota basis ' . ($chapter->name ?? 'Chapter SMeCK'))

@section('content')
<div class="space-y-8 max-w-7xl mx-auto">

    {{-- Chapter Header Info Banner --}}
    <div class="relative overflow-hidden rounded-3xl bg-smeck-dark-900 border border-amber-500/30 p-6 sm:p-8 shadow-2xl">
        <div class="absolute inset-0 bg-cover bg-center opacity-15 pointer-events-none scale-105" style="background-image: url('{{ asset('images/auth-cover.jpg') }}');"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-smeck-dark-950 via-smeck-dark-950/90 to-amber-950/80"></div>
        
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 rounded-2xl overflow-hidden border-2 border-amber-500/40 shadow-xl p-0.5 bg-black shrink-0 hidden sm:flex items-center justify-center">
                    <img src="{{ asset('images/logo-crest.jpg') }}" alt="SMeCK Logo" class="w-full h-full object-cover rounded-xl">
                </div>
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-500/20 text-amber-300 text-xs font-bold uppercase tracking-wider mb-2 border border-amber-500/30">
                        <i class="fa-solid fa-building-flag mr-1"></i> BASIS WILAYAH: {{ $chapter->region_code ?? 'MDK-01' }}
                    </div>
                    <h1 class="font-display font-black text-2xl sm:text-3xl text-white">
                        {{ $chapter->name ?? 'Chapter Medan Kota' }}
                    </h1>
                    <p class="text-xs sm:text-sm text-white/60 mt-1">
                        Basecamp: <strong class="text-white">{{ $chapter->basecamp_address ?? 'Jl. Stadion Teladan No. 12' }}</strong> • Korwil: <strong class="text-amber-400">{{ $chapter->leader_name ?? 'Ketua Wilayah' }}</strong>
                    </p>
                </div>
            </div>

            {{-- Tombol Tindakan Cepat Lapangan --}}
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('chapter.scanner') }}" class="px-5 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-500 text-white font-bold text-xs shadow-lg shadow-amber-700/30 flex items-center gap-2 transition-all">
                    <i class="fa-solid fa-wifi animate-pulse"></i>
                    <span>Buka Scanner Presensi NFC</span>
                </a>
                <a href="{{ route('chapter.members') }}" class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/15 text-white font-bold text-xs border border-white/10 flex items-center gap-2 transition-all">
                    <i class="fa-solid fa-user-plus"></i>
                    <span>+ Anggota Baru</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Stats Grid 4 Metrik --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="p-6 rounded-3xl bg-smeck-dark-900 border border-white/10 shadow-xl">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-white/40 uppercase">Anggota Terdaftar</span>
                <div class="w-8 h-8 rounded-lg bg-smeck-green-500/20 text-smeck-green-400 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
            <div class="font-display font-black text-3xl text-white mt-2">{{ $totalMembers }}</div>
            <div class="text-[11px] text-smeck-green-400 mt-1">Suporter Aktif Basis Ini</div>
        </div>

        <div class="p-6 rounded-3xl bg-smeck-dark-900 border border-white/10 shadow-xl">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-white/40 uppercase">Verifikasi e-KTP</span>
                <div class="w-8 h-8 rounded-lg bg-amber-500/20 text-amber-400 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-id-card"></i>
                </div>
            </div>
            <div class="font-display font-black text-3xl text-amber-400 mt-2">{{ $pendingMembers }}</div>
            <a href="{{ route('chapter.verifications') }}" class="text-[11px] text-amber-300 hover:underline mt-1 block">Periksa Berkas Pendaftar →</a>
        </div>

        <div class="p-6 rounded-3xl bg-smeck-dark-900 border border-white/10 shadow-xl">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-white/40 uppercase">Kas Kas Sukarela</span>
                <div class="w-8 h-8 rounded-lg bg-smeck-gold/20 text-smeck-gold flex items-center justify-center text-sm">
                    <i class="fa-solid fa-vault"></i>
                </div>
            </div>
            <div class="font-display font-black text-2xl text-smeck-gold font-mono mt-2">Rp {{ number_format($totalKas, 0, ',', '.') }}</div>
            <div class="text-[11px] text-white/40 mt-1">Saldo Kas Terverifikasi</div>
        </div>

        <div class="p-6 rounded-3xl bg-smeck-dark-900 border border-white/10 shadow-xl">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-white/40 uppercase">Presensi Terkini</span>
                <div class="w-8 h-8 rounded-lg bg-blue-500/20 text-blue-400 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-tower-broadcast"></i>
                </div>
            </div>
            <div class="font-display font-black text-3xl text-white mt-2">{{ $recentAttendances->count() }}</div>
            <div class="text-[11px] text-blue-400 mt-1">Anggota Tap Hadir</div>
        </div>
    </div>

    {{-- Kesiapsiagaan Medis (Gol. Darah) & Distribusi Ukuran Jersey --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        {{-- Golongan Darah Basis (Tanggap Darurat Medis Tribun) --}}
        <div class="lg:col-span-6 p-6 sm:p-8 rounded-3xl bg-smeck-dark-900 border border-white/10 shadow-xl space-y-4">
            <div class="flex items-center justify-between mb-2">
                <div>
                    <h3 class="font-display font-bold text-lg text-white flex items-center gap-2">
                        <i class="fa-solid fa-droplet text-red-500"></i>
                        <span>Kesiapsiagaan Medis Rombongan</span>
                    </h3>
                    <p class="text-xs text-white/40">Data golongan darah anggota chapter untuk kesiapan darurat saat awaydays/laga</p>
                </div>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-500/20 text-red-300">Medis</span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <div class="p-3.5 rounded-2xl bg-white/[0.03] border border-white/5 text-center">
                    <div class="text-xs text-white/40 font-bold">GOL. A</div>
                    <div class="font-display font-black text-2xl text-red-400 mt-1">{{ $bloodTypeStats['A'] ?? 0 }}</div>
                    <div class="text-[10px] text-white/40">Orang</div>
                </div>
                <div class="p-3.5 rounded-2xl bg-white/[0.03] border border-white/5 text-center">
                    <div class="text-xs text-white/40 font-bold">GOL. B</div>
                    <div class="font-display font-black text-2xl text-amber-400 mt-1">{{ $bloodTypeStats['B'] ?? 0 }}</div>
                    <div class="text-[10px] text-white/40">Orang</div>
                </div>
                <div class="p-3.5 rounded-2xl bg-white/[0.03] border border-white/5 text-center">
                    <div class="text-xs text-white/40 font-bold">GOL. AB</div>
                    <div class="font-display font-black text-2xl text-emerald-400 mt-1">{{ $bloodTypeStats['AB'] ?? 0 }}</div>
                    <div class="text-[10px] text-white/40">Orang</div>
                </div>
                <div class="p-3.5 rounded-2xl bg-white/[0.03] border border-white/5 text-center">
                    <div class="text-xs text-white/40 font-bold">GOL. O</div>
                    <div class="font-display font-black text-2xl text-blue-400 mt-1">{{ $bloodTypeStats['O'] ?? 0 }}</div>
                    <div class="text-[10px] text-white/40">Orang</div>
                </div>
            </div>
        </div>

        {{-- Ukuran Jersey / Baju Kebesaran Anggota --}}
        <div class="lg:col-span-6 p-6 sm:p-8 rounded-3xl bg-smeck-dark-900 border border-white/10 shadow-xl space-y-4">
            <div class="flex items-center justify-between mb-2">
                <div>
                    <h3 class="font-display font-bold text-lg text-white flex items-center gap-2">
                        <i class="fa-solid fa-shirt text-smeck-green-400"></i>
                        <span>Ukuran Baju Jersey Kebesaran</span>
                    </h3>
                    <p class="text-xs text-white/40">Acuan ukuran pakaian suporter saat pemesanan baju resmi chapter</p>
                </div>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-smeck-green-500/20 text-smeck-green-400">Merchandise</span>
            </div>

            <div class="grid grid-cols-3 sm:grid-cols-6 gap-2">
                @foreach(['S', 'M', 'L', 'XL', 'XXL', '3XL'] as $size)
                    <div class="p-3 rounded-2xl bg-white/[0.03] border border-white/5 text-center">
                        <div class="text-[11px] font-bold text-white/50">{{ $size }}</div>
                        <div class="font-display font-black text-xl text-smeck-green-300 mt-0.5">{{ $shirtSizeStats[$size] ?? 0 }}</div>
                        <div class="text-[9px] text-white/40">Pcs</div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    {{-- Alokasi Tiket Stadion dari Pengurus Pusat --}}
    @if(isset($allocations) && $allocations->isNotEmpty())
        <div class="p-6 sm:p-8 rounded-3xl bg-smeck-dark-900 border border-white/10 shadow-xl space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-display font-bold text-lg text-white flex items-center gap-2">
                        <i class="fa-solid fa-ticket text-smeck-gold"></i>
                        <span>Alokasi Jatah Tiket Stadion dari Pengurus Pusat</span>
                    </h3>
                    <p class="text-xs text-white/40">Kuota resmi gelang/tiket tribun Stadion Teladan yang dialokasikan untuk basis ini</p>
                </div>
                <a href="{{ route('chapter.tickets') }}" class="text-xs text-amber-400 font-bold hover:underline">Detail Tiket →</a>
            </div>

            <div class="space-y-4 pt-2">
                @foreach($allocations as $alc)
                    @php
                        $remaining = max(0, $alc->allocated_quota - $alc->distributed_quota);
                        $distPercent = $alc->allocated_quota > 0 ? min(100, ($alc->distributed_quota / $alc->allocated_quota) * 100) : 0;
                    @endphp
                    <div class="p-4 rounded-2xl bg-white/[0.02] border border-white/5 space-y-2">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between text-xs gap-1">
                            <span class="font-bold text-white text-sm">{{ $alc->matchTicket?->match_title ?? 'Laga PSMS' }}</span>
                            <div class="flex items-center gap-3">
                                <span class="text-white/40">Alokasi: <strong class="text-white font-mono">{{ $alc->allocated_quota }}</strong></span>
                                <span class="text-smeck-green-400">Terbagi: <strong class="font-mono">{{ $alc->distributed_quota }}</strong></span>
                                <span class="text-smeck-gold font-bold">Sisa: <strong class="font-mono">{{ $remaining }}</strong> Lembar</span>
                            </div>
                        </div>
                        <div class="w-full h-2 rounded-full bg-white/10 overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-amber-500 to-smeck-green-400 rounded-full" style="width: {{ $distPercent }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Recent Presensi Tap Activity --}}
    <div class="p-6 sm:p-8 rounded-3xl bg-smeck-dark-900 border border-white/10 shadow-xl">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="font-display font-bold text-lg text-white flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-400 animate-pulse"></span>
                    <span>Presensi Anggota Terbaru (Realtime Tap)</span>
                </h3>
                <p class="text-xs text-white/40">Log kehadiran anggota basis yang men-tap kartu KTA di event/kopdar chapter</p>
            </div>
            <a href="{{ route('chapter.scanner') }}" class="text-xs text-amber-400 font-bold hover:underline">Buka Scanner →</a>
        </div>

        <div class="space-y-3">
            @forelse($recentAttendances as $rec)
                <div class="p-4 rounded-2xl bg-white/[0.03] border border-white/5 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-smeck-dark-800 border border-amber-400/30 overflow-hidden flex items-center justify-center font-bold text-sm text-white shrink-0 shadow">
                            @if($rec->user->profile?->profile_photo_path)
                                <img src="{{ asset('storage/' . $rec->user->profile->profile_photo_path) }}" alt="{{ $rec->user->name }}" class="w-full h-full object-cover" style="object-position: center top;">
                            @else
                                <span class="text-amber-400">{{ strtoupper(substr($rec->user->name, 0, 1)) }}</span>
                            @endif
                        </div>
                        <div>
                            <div class="text-sm font-bold text-white">{{ $rec->user->name }}</div>
                            <div class="text-xs text-white/40">NIA: <span class="text-smeck-gold font-mono">{{ $rec->user->profile?->nia ?? 'SMCK-MEMBER' }}</span> • Event: {{ $rec->event_name }}</div>
                        </div>
                    </div>
                    <span class="text-xs font-mono text-smeck-green-400 font-bold">
                        {{ $rec->scanned_at ? $rec->scanned_at->format('H:i - d/m/Y') : 'Baru saja' }}
                    </span>
                </div>
            @empty
                <div class="text-center py-8 text-white/40 text-xs">Belum ada aktivitas presensi tap di chapter ini.</div>
            @endforelse
        </div>
    </div>

</div>
@endsection
