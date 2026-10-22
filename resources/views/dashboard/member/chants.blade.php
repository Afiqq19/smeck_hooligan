@extends('layouts.app')

@section('title', 'Pustaka Chants Tribun')
@section('header_title', 'Pustaka Chants Tribun SMeCK Hooligan')
@section('header_subtitle', 'Koleksi lirik nyanyian sakral, tabuhan snare drum, & audio pembakar semangat Ayam Kinantan')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    
    {{-- Banner with Drum Beat Simulator Bar --}}
    <div class="p-6 sm:p-8 rounded-3xl bg-gradient-to-r from-smeck-dark-900 via-smeck-green-950 to-smeck-dark-900 border border-smeck-green-500/20 shadow-2xl flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div>
            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-smeck-gold/20 text-smeck-gold border border-smeck-gold/30 inline-flex items-center gap-1.5">
                <i class="fa-solid fa-drum"></i> Chant Class Komando Capo
            </span>
            <h2 class="font-display font-black text-2xl text-white mt-2">Hafalkan Sebelum Menginjakkan Kaki di Tribun!</h2>
            <p class="text-xs sm:text-sm text-white/60 mt-1 max-w-xl">
                90 menit berdiri tanpa henti, satu suara satu komando. Dengarkan tabuhan drum tribun dan nyanyikan bersama seluruh keluarga besar Sada Roha.
            </p>
        </div>
        
        {{-- Interactive Capo Drum Beat Synthesizer --}}
        <div class="p-4 rounded-2xl bg-white/[0.04] border border-white/10 shrink-0 text-center space-y-2">
            <div class="text-[11px] font-bold uppercase text-white/60 flex items-center justify-center gap-1.5">
                <span id="drum-pulse-dot" class="w-2 h-2 rounded-full bg-smeck-green-400"></span>
                <span>Simulator Beat Snare Drum</span>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" id="btn-drum-toggle" onclick="toggleTribunDrumBeat()" class="px-4 py-2 rounded-xl bg-smeck-green-600 hover:bg-smeck-green-500 text-white font-bold text-xs shadow-lg shadow-smeck-green-700/30 transition-all flex items-center gap-1.5">
                    <i id="drum-icon" class="fa-solid fa-play"></i>
                    <span id="drum-label">Nyalakan Beat Drum</span>
                </button>
            </div>
            <div class="text-[9px] text-white/40">Synthesizer Ritmik Web Audio</div>
        </div>
    </div>

    {{-- Chants Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($chants as $chant)
            @php
                $chantJson = json_encode([
                    'id' => $chant->id,
                    'title' => $chant->title,
                    'category' => $chant->category,
                    'duration' => $chant->duration ?? '02:30',
                    'lyrics' => $chant->lyrics,
                ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
            @endphp
            <div class="p-6 rounded-3xl bg-smeck-dark-900 border border-white/10 hover:border-smeck-green-500/40 transition-all flex flex-col justify-between space-y-4 shadow-xl">
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

                {{-- Interactive Controls --}}
                <div class="pt-3 border-t border-white/10 flex items-center justify-between gap-2">
                    <button type="button" onclick='openSingAlongModal({!! $chantJson !!})' class="px-3.5 py-2 rounded-xl bg-smeck-green-600 hover:bg-smeck-green-500 text-white text-xs font-bold flex items-center gap-2 transition-all shadow">
                        <i class="fa-solid fa-microphone-lines text-xs"></i>
                        <span>Sing-Along Mode</span>
                    </button>
                    <button type="button" onclick='copyChantLyrics({{ json_encode($chant->lyrics) }})' class="p-2 rounded-xl bg-white/5 hover:bg-white/10 text-white/70 hover:text-white text-xs transition-colors" title="Salin Lirik">
                        <i class="fa-regular fa-copy"></i>
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-12 text-white/40 rounded-3xl bg-smeck-dark-900 border border-white/10">
                <i class="fa-solid fa-music text-3xl mb-2 block text-white/20"></i>
                Belum ada lirik chant yang terdaftar di basis data.
            </div>
        @endforelse
    </div>

</div>

{{-- ========================================================================= --}}
{{-- MODAL SING-ALONG KARAOKE TRIBUN DENGAN BEAT DRUM --}}
{{-- ========================================================================= --}}
<div id="modalSingAlong" class="fixed inset-0 z-50 bg-black/95 backdrop-blur-md hidden flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-smeck-dark-900 border border-smeck-green-500/30 rounded-3xl max-w-xl w-full p-6 sm:p-8 shadow-2xl relative my-8 text-center">
        <button onclick="closeSingAlongModal()" class="absolute top-6 right-6 text-white/40 hover:text-white text-lg">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <span id="singAlongCategory" class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest bg-smeck-green-500/20 text-smeck-green-400 border border-smeck-green-500/30">
            CHANT TRIBUN
        </span>

        <h3 id="singAlongTitle" class="font-display font-black text-2xl text-white mt-3 mb-1">
            Judul Chant
        </h3>
        <p class="text-xs text-white/50 mb-6">Mode Sing-Along Suporter • Lantunkan lirik dengan penuh kebanggaan!</p>

        {{-- Lirik Display Box --}}
        <div class="p-6 rounded-2xl bg-black/60 border border-white/10 text-center max-h-80 overflow-y-auto space-y-3 shadow-inner">
            <p id="singAlongLyrics" class="font-sans font-bold text-sm sm:text-base text-amber-300 leading-relaxed whitespace-pre-line">
                Lirik chant tribun...
            </p>
        </div>

        {{-- Audio Drum Controls inside Modal --}}
        <div class="mt-6 flex flex-wrap items-center justify-center gap-3">
            <button type="button" onclick="toggleTribunDrumBeat()" class="px-5 py-2.5 rounded-xl bg-smeck-green-600 hover:bg-smeck-green-500 text-white font-bold text-xs shadow-lg transition-all flex items-center gap-2">
                <i class="fa-solid fa-drum"></i>
                <span id="modal-drum-text">Putar Irama Snare</span>
            </button>
            <button type="button" onclick="closeSingAlongModal()" class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-semibold">
                Tutup
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let drumInterval = null;
    let isDrumPlaying = false;
    let drumCtx = null;

    // Web Audio Synthesizer: Realistic stadium snare & bass drum kick simulation
    function playDrumSound(type = 'snare') {
        try {
            const AudioContext = window.AudioContext || window.webkitAudioContext;
            if (!AudioContext) return;
            if (!drumCtx) drumCtx = new AudioContext();
            if (drumCtx.state === 'suspended') drumCtx.resume();

            const now = drumCtx.currentTime;

            if (type === 'kick') {
                const osc = drumCtx.createOscillator();
                const gain = drumCtx.createGain();
                osc.frequency.setValueAtTime(150, now);
                osc.frequency.exponentialRampToValueAtTime(0.01, now + 0.3);
                gain.gain.setValueAtTime(0.6, now);
                gain.gain.exponentialRampToValueAtTime(0.001, now + 0.3);
                osc.connect(gain);
                gain.connect(drumCtx.destination);
                osc.start(now);
                osc.stop(now + 0.3);
            } else {
                // Snare Drum simulation (Noise + Tone)
                const osc = drumCtx.createOscillator();
                const gain = drumCtx.createGain();
                osc.type = 'triangle';
                osc.frequency.setValueAtTime(220, now);
                gain.gain.setValueAtTime(0.3, now);
                gain.gain.exponentialRampToValueAtTime(0.001, now + 0.15);
                osc.connect(gain);
                gain.connect(drumCtx.destination);
                osc.start(now);
                osc.stop(now + 0.15);
            }
        } catch (e) {
            console.warn('Drum sound error:', e);
        }
    }

    function toggleTribunDrumBeat() {
        const btn = document.getElementById('btn-drum-toggle');
        const icon = document.getElementById('drum-icon');
        const label = document.getElementById('drum-label');
        const pulseDot = document.getElementById('drum-pulse-dot');
        const modalText = document.getElementById('modal-drum-text');

        if (isDrumPlaying) {
            clearInterval(drumInterval);
            isDrumPlaying = false;
            if (label) label.innerText = "Nyalakan Beat Drum";
            if (icon) icon.className = "fa-solid fa-play";
            if (btn) btn.classList.replace('bg-red-600', 'bg-smeck-green-600');
            if (pulseDot) pulseDot.classList.remove('animate-ping');
            if (modalText) modalText.innerText = "Putar Irama Snare";
        } else {
            isDrumPlaying = true;
            let step = 0;
            // Tribun Rhythm: KICK - SNARE - KICK KICK - SNARE (classic football rhythm)
            drumInterval = setInterval(() => {
                if (step % 4 === 0) playDrumSound('kick');
                else if (step % 2 === 1) playDrumSound('snare');
                step++;
            }, 300);

            if (label) label.innerText = "Hentikan Beat";
            if (icon) icon.className = "fa-solid fa-stop";
            if (btn) btn.classList.replace('bg-smeck-green-600', 'bg-red-600');
            if (pulseDot) pulseDot.classList.add('animate-ping');
            if (modalText) modalText.innerText = "Hentikan Irama";
        }
    }

    function openSingAlongModal(data) {
        document.getElementById('singAlongTitle').innerText = data.title;
        document.getElementById('singAlongCategory').innerText = data.category;
        document.getElementById('singAlongLyrics').innerText = data.lyrics;
        document.getElementById('modalSingAlong').classList.remove('hidden');
    }

    function closeSingAlongModal() {
        document.getElementById('modalSingAlong').classList.add('hidden');
    }

    function copyChantLyrics(lyrics) {
        navigator.clipboard.writeText(lyrics).then(() => {
            alert("Lirik chant berhasil disalin ke clipboard!");
        });
    }
</script>
@endpush
@endsection

