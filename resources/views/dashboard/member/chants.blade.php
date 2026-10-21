@extends('layouts.app')

@section('title', 'Pustaka Chants')
@section('header_title', 'Pustaka Chants Tribun SMeCK')
@section('header_subtitle', 'Koleksi lirik nyanyian dan audio pembakar semangat Ayam Kinantan di Stadion Teladan')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    
    {{-- Banner --}}
    <div class="p-6 sm:p-8 rounded-3xl bg-gradient-to-r from-smeck-dark-900 via-smeck-green-950 to-smeck-dark-900 border border-smeck-green-500/20 flex flex-col md:flex-row items-center justify-between gap-6">
        <div>
            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-smeck-gold/20 text-smeck-gold border border-smeck-gold/30">
                <i class="fa-solid fa-volume-high mr-1"></i> Chant Class Mandiri
            </span>
            <h2 class="font-display font-black text-2xl text-white mt-2">Hafalkan Sebelum Menginjak Tribun!</h2>
            <p class="text-xs sm:text-sm text-white/60 mt-1 max-w-xl">
                90 menit berdiri tanpa henti, satu suara di bawah komando Capo SMeCK Hooligan. Dengarkan beat drum dan lafalkan liriknya bersama-sama.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-xs text-white/50 font-mono">{{ $chants->count() }} Chants Terdaftar</span>
        </div>
    </div>

    {{-- Chants Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($chants as $chant)
            <div class="p-6 rounded-3xl bg-smeck-dark-900 border border-white/10 hover:border-smeck-green-500/40 transition-all flex flex-col justify-between space-y-4">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-smeck-green-500/20 text-smeck-green-400">
                            {{ $chant->category }}
                        </span>
                        <span class="text-[11px] font-mono text-white/40">
                            <i class="fa-regular fa-clock mr-1"></i>{{ $chant->duration ?? '02:30' }}
                        </span>
                    </div>

                    <h3 class="font-display font-bold text-lg text-white mb-3">
                        {{ $chant->title }}
                    </h3>

                    {{-- Lyrics Container --}}
                    <div class="p-4 rounded-2xl bg-white/[0.03] border border-white/5 max-h-48 overflow-y-auto text-xs text-white/80 leading-relaxed font-sans whitespace-pre-line">
                        {{ $chant->lyrics }}
                    </div>
                </div>

                {{-- Mock Interactive Audio Player Controls --}}
                <div class="pt-3 border-t border-white/10 flex items-center justify-between">
                    <button type="button" onclick="alert('Memutar chant: {{ addslashes($chant->title) }}')" class="px-3.5 py-2 rounded-xl bg-smeck-green-600 hover:bg-smeck-green-500 text-white text-xs font-bold flex items-center gap-2 transition-colors">
                        <i class="fa-solid fa-play text-[10px]"></i>
                        <span>Putar Chant</span>
                    </button>
                    <span class="text-[10px] text-white/40">Vokal & Snare Drum</span>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-12 text-white/40">Belum ada lirik chant yang diunggah.</div>
        @endforelse
    </div>

</div>
@endsection
