@extends('layouts.app')

@section('title', 'Tiket & Awaydays')
@section('header_title', 'Tiket Laga & Tur Awaydays')
@section('header_subtitle', 'Pemesanan kolektif tiket kandang Stadion Teladan dan pendaftaran kursi bus tour tandang')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">

    {{-- Match Cards --}}
    <div class="space-y-6">
        @forelse($matches as $m)
            <div class="p-6 sm:p-8 rounded-3xl bg-smeck-dark-900 border border-white/10 hover:border-smeck-green-500/30 transition-all">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                    
                    {{-- Match Details --}}
                    <div class="space-y-2">
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $m->type === 'away' ? 'bg-amber-500/20 text-amber-300 border border-amber-500/30' : 'bg-smeck-green-500/20 text-smeck-green-300 border border-smeck-green-500/30' }}">
                                {{ $m->type === 'away' ? 'TUR TANDANG (AWAYDAYS)' : 'PERTANDINGAN KANDANG' }}
                            </span>
                            <span class="text-xs text-white/50">• Kuota Tersisa: <strong class="text-white">{{ $m->remaining_quota }} Kursi</strong></span>
                        </div>

                        <h3 class="font-display font-black text-xl sm:text-2xl text-white">
                            {{ $m->match_title }}
                        </h3>

                        <div class="text-xs text-white/60 space-y-1">
                            <div><i class="fa-regular fa-calendar text-smeck-green-400 mr-1.5"></i> {{ $m->match_time->format('l, d F Y - H:i') }} WIB</div>
                            <div><i class="fa-solid fa-location-dot text-smeck-green-400 mr-1.5"></i> {{ $m->stadium }}</div>
                            @if($m->bus_transport_info)
                                <div class="text-smeck-gold"><i class="fa-solid fa-bus text-smeck-gold mr-1.5"></i> Fasilitas: {{ $m->bus_transport_info }}</div>
                            @endif
                        </div>
                    </div>

                    {{-- Price & Booking Button --}}
                    <div class="lg:text-right shrink-0 pt-4 lg:pt-0 border-t lg:border-t-0 border-white/10 flex flex-row lg:flex-col items-center lg:items-end justify-between gap-4">
                        <div>
                            <span class="text-[10px] text-white/40 block">Biaya Kolektif Member</span>
                            <span class="font-display font-black text-2xl text-smeck-gold font-mono">
                                Rp {{ number_format($m->price, 0, ',', '.') }}
                            </span>
                        </div>
                        <button type="button" onclick="alert('Tiket berhasil dipesan secara kolektif melalui Chapter Anda!')" class="px-6 py-3 rounded-xl bg-smeck-green-600 hover:bg-smeck-green-500 text-white font-bold text-xs shadow-lg transition-all flex items-center gap-2">
                            <i class="fa-solid fa-ticket"></i>
                            <span>{{ $m->type === 'away' ? 'Daftar & Pilih Kursi Bus' : 'Pesan Tiket Masuk' }}</span>
                        </button>
                    </div>

                </div>
            </div>
        @empty
            <div class="text-center py-12 text-white/40">Belum ada jadwal pertandingan yang dibuka.</div>
        @endforelse
    </div>

</div>
@endsection
