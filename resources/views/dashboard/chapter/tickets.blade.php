@extends('layouts.app')

@section('title', 'Kuota Tiket Chapter')
@section('header_title', 'Alokasi & Kuota Tiket Pertandingan')
@section('header_subtitle', 'Kelola jatah tiket resmi Stadion Teladan yang dialokasikan Pengurus Pusat ke basis wilayah')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($allocations as $al)
            <div class="p-6 rounded-3xl bg-smeck-dark-900 border border-white/10 space-y-4">
                <div class="flex items-center justify-between">
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase bg-smeck-green-500/20 text-smeck-green-300">
                        Alokasi Pusat
                    </span>
                    <span class="font-mono text-xs text-white/50">{{ $al->matchTicket->match_time->format('d M Y') }}</span>
                </div>

                <h3 class="font-display font-black text-xl text-white">
                    {{ $al->matchTicket->match_title }}
                </h3>

                <div class="grid grid-cols-2 gap-3 text-xs">
                    <div class="p-3 rounded-2xl bg-white/5">
                        <div class="text-white/40">Jatah Kuota Diberikan:</div>
                        <div class="text-lg font-bold text-white mt-0.5">{{ $al->allocated_quota }} Lembar</div>
                    </div>
                    <div class="p-3 rounded-2xl bg-white/5">
                        <div class="text-white/40">Telah Terdistribusi:</div>
                        <div class="text-lg font-bold text-smeck-green-400 mt-0.5">{{ $al->distributed_quota }} Lembar</div>
                    </div>
                </div>

                <div class="pt-2 flex items-center justify-between border-t border-white/10 text-xs">
                    <span class="text-white/50">Sisa Kuota Chapter: <strong class="text-smeck-gold">{{ $al->allocated_quota - $al->distributed_quota }} Lembar</strong></span>
                    <button type="button" onclick="alert('Form distribusi tiket kolektif dibuka!')" class="px-4 py-2 rounded-xl bg-amber-600 hover:bg-amber-500 text-white font-bold text-xs transition-colors">
                        Distribusi ke Anggota
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-2 text-center py-12 text-white/40">Belum ada alokasi tiket dari Pusat untuk chapter ini.</div>
        @endforelse
    </div>
</div>
@endsection
