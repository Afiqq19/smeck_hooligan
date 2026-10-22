@extends('layouts.app')

@section('title', 'Scan Presensi NFC')
@section('header_title', 'Terminal Scanner Presensi NFC Basis')
@section('header_subtitle', 'Gunakan sensor NFC bawaan HP panitia untuk membaca kartu fisik KTA suporter secara instan')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    
    {{-- Scanner Terminal Card --}}
    <div class="p-6 sm:p-10 rounded-3xl bg-smeck-dark-900 border border-amber-500/30 text-center relative overflow-hidden shadow-2xl">
        {{-- Background Glow --}}
        <div class="absolute -top-24 -left-24 w-72 h-72 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-72 h-72 bg-smeck-green-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative">
            <div class="w-20 h-20 mx-auto rounded-3xl bg-amber-500/20 text-amber-400 flex items-center justify-center text-4xl mb-4 shadow-lg shadow-amber-500/10 animate-pulse">
                <i class="fa-solid fa-wifi rotate-90"></i>
            </div>
            <h2 class="font-display font-black text-2xl text-white">Terminal Tap KTA NFC Anggota</h2>
            <p class="text-xs sm:text-sm text-white/60 max-w-md mx-auto mt-1 mb-6">
                Tempelkan kartu fisik KTA suporter (e-money, BCA Flazz, atau RFID PVC) ke bagian belakang HP panitia. Sistem otomatis membaca UID chip & mencatat kehadiran.
            </p>

            {{-- Quick Button for Solusi 3: Poster QR Presensi Suporter Mandiri --}}
            <div class="max-w-md mx-auto mb-6">
                <button type="button" onclick="openQrBannerModal()" class="w-full py-2.5 px-4 rounded-2xl bg-gradient-to-r from-amber-500/20 via-smeck-green-500/20 to-amber-500/20 hover:from-amber-500/30 hover:to-smeck-green-500/30 text-amber-300 font-bold text-xs border border-amber-500/40 shadow-lg transition-all flex items-center justify-center gap-2 group cursor-pointer">
                    <i class="fa-solid fa-qrcode text-base group-hover:scale-110 transition-transform"></i>
                    <span>Tampilkan QR Banner Presensi (Suporter Scan Mandiri)</span>
                </button>
            </div>

            {{-- Sensor NFC Status Bar & Controls --}}
            <div class="max-w-md mx-auto mb-6 p-4 rounded-2xl bg-white/[0.03] border border-white/10 flex flex-col sm:flex-row items-center justify-between gap-3">
                <div class="flex items-center gap-2.5 text-xs text-left">
                    <span id="nfc-status-dot" class="w-3 h-3 rounded-full bg-amber-400 animate-pulse shrink-0"></span>
                    <span id="nfc-status-text" class="text-white/70 font-medium">Sensor NFC HP Siap Digunakan</span>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" id="btn-sound-toggle" onclick="toggleChimeSound()" class="px-3 py-1.5 rounded-xl bg-white/5 hover:bg-white/10 text-white/80 text-[11px] font-semibold border border-white/10 transition-colors flex items-center gap-1.5" title="Toggle Bunyi Chime Gate">
                        <i id="sound-icon" class="fa-solid fa-volume-high text-amber-400"></i>
                        <span id="sound-label">Audio ON</span>
                    </button>
                    <button type="button" id="btn-start-nfc" onclick="startNfcScan()" class="px-4 py-1.5 rounded-xl bg-smeck-green-600 hover:bg-smeck-green-500 text-white font-bold text-xs shadow-lg shadow-smeck-green-700/30 transition-all flex items-center gap-1.5 shrink-0">
                        <i class="fa-solid fa-satellite-dish text-xs"></i>
                        <span>Aktifkan Sensor</span>
                    </button>
                </div>
            </div>

            {{-- Form Scan --}}
            <form id="nfc-scan-form" action="{{ route('chapter.scanner.process') }}" method="POST" class="max-w-md mx-auto space-y-4 text-left">
                @csrf

                {{-- Preset Event Selector --}}
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Kategori / Acara Presensi <span class="text-red-400">*</span></label>
                    <div class="space-y-2">
                        <select id="event_preset_select" onchange="handleEventSelectChange(this.value)" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-xs focus:border-amber-400 focus:outline-none">
                            <option value="Laga Kandang PSMS Medan (Stadion Teladan)" class="bg-smeck-dark-900">🏟️ Laga Kandang PSMS Medan (Stadion Teladan)</option>
                            <option value="Awaydays Tur Gabungan PSMS Medan" class="bg-smeck-dark-900">🚌 Awaydays Tur Gabungan PSMS Medan</option>
                            <option value="Nobar Akbar PSMS Medan" class="bg-smeck-dark-900" selected>📺 Nobar Akbar PSMS Medan</option>
                            <option value="Kopdar Rutin & Arisan Basis" class="bg-smeck-dark-900">☕ Kopdar Rutin & Arisan Basis</option>
                            <option value="Bakti Sosial & Aksi Solidaritas" class="bg-smeck-dark-900">🤝 Bakti Sosial & Aksi Solidaritas</option>
                            <option value="CUSTOM" class="bg-smeck-dark-900">✏️ Acara Lainnya (Ketik Manual)...</option>
                        </select>
                        <input type="text" id="event_name_input" name="event_name" required value="Nobar Akbar PSMS Medan" placeholder="Tuliskan nama acara / laga..." class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-xs focus:border-amber-400 focus:outline-none">
                    </div>
                </div>

                {{-- Input UID / Card Number / NIA --}}
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="text-xs font-bold uppercase text-white/60">UID Chip NFC / Nomor Kartu KTA <span class="text-red-400">*</span></label>
                        <button type="button" onclick="playSuccessChime(); document.getElementById('uid_input').value='04:A2:8B:1A:F3:60:80'" class="text-[10px] text-amber-400 hover:text-amber-300 font-bold underline">
                            Simulasi Tap Demo
                        </button>
                    </div>
                    <div class="relative">
                        <input type="text" id="uid_input" name="uid_number" required placeholder="04:XX:XX:XX:XX atau 6032-XXXX-XXXX-XXXX" class="w-full px-4 py-3 bg-white/5 border border-amber-500/50 rounded-xl text-white font-mono text-center tracking-wider text-sm focus:border-amber-400 focus:outline-none">
                    </div>
                    <span class="text-[11px] text-white/40 block mt-1">Dapat membaca UID chip fisik, Nomor KTA 16-digit, atau Nomor Induk Anggota (NIA).</span>
                </div>

                <button type="submit" id="btn-submit-scan" class="w-full py-3.5 rounded-xl bg-amber-600 hover:bg-amber-500 text-white font-bold text-xs sm:text-sm shadow-xl shadow-amber-700/30 transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-check-to-slot"></i>
                    <span>Catat Kehadiran (Submit Presensi)</span>
                </button>
            </form>
        </div>
    </div>

    {{-- Live Feed Tap Scanner --}}
    <div class="p-6 sm:p-8 rounded-3xl bg-smeck-dark-900 border border-white/10 shadow-xl">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-display font-bold text-lg text-white flex items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left text-amber-400"></i>
                <span>Feed Tap Presensi Terakhir di Chapter Ini</span>
            </h3>
            <span class="text-[11px] text-white/40">{{ $recentScans->count() }} Data Tercatat</span>
        </div>
        <div class="space-y-3">
            @forelse($recentScans as $sc)
                <div class="p-3.5 rounded-2xl bg-white/[0.03] border border-white/5 flex items-center justify-between hover:bg-white/[0.05] transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-smeck-green-500/20 text-smeck-green-400 flex items-center justify-center text-sm font-bold shrink-0">
                            <i class="fa-solid fa-id-card"></i>
                        </div>
                        <div>
                            <div class="text-xs font-bold text-white flex items-center gap-2">
                                <span>{{ $sc->user->name }}</span>
                                <span class="font-mono text-[10px] text-amber-400 px-1.5 py-0.5 rounded bg-amber-400/10">{{ $sc->user->profile?->nia ?? 'NIA' }}</span>
                            </div>
                            <div class="text-[11px] text-white/50">{{ $sc->event_name }}</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-mono text-white/80 block">{{ $sc->scanned_at->format('H:i:s') }} WIB</span>
                        <span class="text-[10px] text-white/40">{{ $sc->scanned_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-white/40 text-xs">
                    <i class="fa-regular fa-clipboard text-3xl mb-2 block text-white/20"></i>
                    Belum ada scan kartu tercatat hari ini di basis ini.
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    let nfcReader = null;
    let isSoundEnabled = true;
    let audioCtx = null;

    // Web Audio Synthesizer: Iconic stadium gate chime beep!
    function playSuccessChime() {
        if (!isSoundEnabled) return;
        try {
            const AudioContext = window.AudioContext || window.webkitAudioContext;
            if (!AudioContext) return;
            if (!audioCtx) audioCtx = new AudioContext();
            if (audioCtx.state === 'suspended') {
                audioCtx.resume();
            }

            const now = audioCtx.currentTime;
            
            // First note (High Ding)
            const osc1 = audioCtx.createOscillator();
            const gain1 = audioCtx.createGain();
            osc1.type = 'sine';
            osc1.frequency.setValueAtTime(880, now); // A5
            gain1.gain.setValueAtTime(0.3, now);
            gain1.gain.exponentialRampToValueAtTime(0.001, now + 0.2);
            osc1.connect(gain1);
            gain1.connect(audioCtx.destination);
            osc1.start(now);
            osc1.stop(now + 0.2);

            // Second higher harmonic note (Double Ding)
            const osc2 = audioCtx.createOscillator();
            const gain2 = audioCtx.createGain();
            osc2.type = 'sine';
            osc2.frequency.setValueAtTime(1760, now + 0.08); // A6
            gain2.gain.setValueAtTime(0.2, now + 0.08);
            gain2.gain.exponentialRampToValueAtTime(0.001, now + 0.35);
            osc2.connect(gain2);
            gain2.connect(audioCtx.destination);
            osc2.start(now + 0.08);
            osc2.stop(now + 0.35);
        } catch (e) {
            console.warn('Audio play error:', e);
        }
    }

    function toggleChimeSound() {
        isSoundEnabled = !isSoundEnabled;
        const soundIcon = document.getElementById('sound-icon');
        const soundLabel = document.getElementById('sound-label');
        if (isSoundEnabled) {
            soundIcon.className = "fa-solid fa-volume-high text-amber-400";
            soundLabel.innerText = "Audio ON";
            playSuccessChime();
        } else {
            soundIcon.className = "fa-solid fa-volume-xmark text-white/40";
            soundLabel.innerText = "Audio Mute";
        }
    }

    function handleEventSelectChange(value) {
        const input = document.getElementById('event_name_input');
        if (value === 'CUSTOM') {
            input.value = '';
            input.focus();
        } else {
            input.value = value;
        }
    }

    async function startNfcScan() {
        const statusText = document.getElementById('nfc-status-text');
        const statusDot = document.getElementById('nfc-status-dot');
        const startBtn = document.getElementById('btn-start-nfc');

        // Check if Web NFC API is supported
        if (!('NDEFReader' in window)) {
            alert("Informasi Web NFC:\nSensor Web NFC bawaan browser saat ini aktif pada Google Chrome di Android (koneksi HTTPS).\n\nAnda tetap dapat memasukkan UID / Nomor KTA secara langsung atau memanfaatkan tombol 'Simulasi Tap Demo'.");
            return;
        }

        try {
            nfcReader = new NDEFReader();
            await nfcReader.scan();

            statusText.innerText = "Sensor NFC Aktif! Tempelkan kartu di belakang HP...";
            statusText.classList.remove('text-white/70');
            statusText.classList.add('text-smeck-green-400', 'font-bold');
            statusDot.classList.remove('bg-amber-400');
            statusDot.classList.add('bg-smeck-green-400');
            startBtn.innerHTML = '<i class="fa-solid fa-check"></i> Sensor Siap';
            startBtn.classList.replace('bg-smeck-green-600', 'bg-white/10');
            startBtn.disabled = true;

            nfcReader.onreading = (event) => {
                const serialNumber = event.serialNumber;
                
                // Play audio chime and haptic feedback
                playSuccessChime();
                if (navigator.vibrate) {
                    navigator.vibrate([100, 50, 100]);
                }

                if (serialNumber) {
                    document.getElementById('uid_input').value = serialNumber.toUpperCase();
                    statusText.innerText = `Terbaca UID: ${serialNumber.toUpperCase()}! Memproses...`;
                    
                    // Auto submit form
                    setTimeout(() => {
                        document.getElementById('nfc-scan-form').submit();
                    }, 400);
                }
            };

            nfcReader.onreadingerror = () => {
                alert("Gagal membaca sinyal kartu. Pastikan posisi kartu menempel pas di antena NFC belakang smartphone Anda.");
            };

        } catch (error) {
            console.error('NFC error:', error);
            alert("Izin NFC: " + error.message);
        }
    }

    // Auto-detect if browser supports NFC on load
    window.addEventListener('DOMContentLoaded', () => {
        if ('NDEFReader' in window) {
            document.getElementById('nfc-status-text').innerText = "HP Mendukung Web NFC! Klik 'Aktifkan Sensor'";
        }
    });

    // Handle Solusi 3: QR Banner Presensi Suporter Mandiri
    function openQrBannerModal() {
        updateQrBannerCode();
        document.getElementById('modalQrBanner').classList.remove('hidden');
    }

    function closeQrBannerModal() {
        document.getElementById('modalQrBanner').classList.add('hidden');
    }

    function updateQrBannerCode() {
        const select = document.getElementById('qr_event_select');
        const customInput = document.getElementById('qr_event_custom');
        let eventName = select.value;
        if (eventName === 'CUSTOM') {
            customInput.classList.remove('hidden');
            eventName = customInput.value || 'Matchday PSMS Medan';
        } else {
            customInput.classList.add('hidden');
        }

        document.getElementById('qr_banner_event_title').innerText = eventName;
        
        const baseUrl = "{{ route('member.checkin.self') }}";
        const fullUrl = baseUrl + '?event_name=' + encodeURIComponent(eventName);
        
        const qrImg = document.getElementById('qr_banner_image');
        qrImg.src = `https://api.qrserver.com/v1/create-qr-code/?size=300x300&margin=10&data=${encodeURIComponent(fullUrl)}`;
        document.getElementById('qr_banner_link_input').value = fullUrl;
    }

    function copyQrBannerLink() {
        const input = document.getElementById('qr_banner_link_input');
        input.select();
        navigator.clipboard.writeText(input.value);
        alert('Link presensi mandiri berhasil disalin! Anda bisa membagikannya ke WhatsApp Group Basis.');
    }
</script>

{{-- MODAL POSTER QR BANNER PRESENSI MANDIRI (SOLUSI 3) --}}
<div id="modalQrBanner" class="fixed inset-0 z-50 bg-black/85 backdrop-blur-md hidden flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-smeck-dark-900 border border-amber-500/30 text-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl relative my-8 print:bg-white print:text-black print:max-w-full print:m-0 print:p-0 print:border-none">
        
        {{-- Close Button --}}
        <button onclick="closeQrBannerModal()" class="absolute top-5 right-5 text-white/40 hover:text-white text-2xl print:hidden">&times;</button>

        {{-- Header Modal --}}
        <div class="text-center pb-4 border-b border-white/10 mb-5 print:border-slate-300">
            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-500/20 text-amber-300 border border-amber-500/30 font-mono print:hidden">
                SOLUSI ANTI-PEGAL
            </span>
            <h3 class="font-display font-black text-xl text-white mt-2 print:text-black print:text-2xl">Poster QR Presensi Suporter Mandiri</h3>
            <p class="text-xs text-white/50 print:text-slate-600 mt-1">
                Tampilkan di layar proyektor / banner gate tribun. Suporter cukup scan dengan kamera ponsel untuk absensi serentak!
            </p>
        </div>

        {{-- Event Selector --}}
        <div class="space-y-2 mb-4 print:hidden">
            <label class="block text-xs font-bold uppercase text-white/60">Pilih Pertandingan / Acara:</label>
            <select id="qr_event_select" onchange="updateQrBannerCode()" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-xs focus:border-amber-400 focus:outline-none">
                @if(isset($upcomingMatches) && $upcomingMatches->count() > 0)
                    @foreach($upcomingMatches as $um)
                        <option value="Laga: {{ $um->match_title }}" class="bg-smeck-dark-900">🏟️ Laga: {{ $um->match_title }} ({{ $um->match_time->format('d/m/Y') }})</option>
                    @endforeach
                @endif
                <option value="Nobar Akbar PSMS Medan" class="bg-smeck-dark-900">📺 Nobar Akbar PSMS Medan</option>
                <option value="Awaydays Tur Gabungan PSMS Medan" class="bg-smeck-dark-900">🚌 Awaydays Tur Gabungan PSMS Medan</option>
                <option value="Kopdar Rutin & Arisan Basis" class="bg-smeck-dark-900">☕ Kopdar Rutin & Arisan Basis</option>
                <option value="CUSTOM" class="bg-smeck-dark-900">✏️ Acara Lainnya (Ketik Sendiri)...</option>
            </select>
            <input type="text" id="qr_event_custom" oninput="updateQrBannerCode()" placeholder="Tuliskan nama acara..." class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-xs focus:border-amber-400 focus:outline-none hidden">
        </div>

        {{-- Printable Poster Sheet Container --}}
        <div class="bg-white text-slate-900 p-6 rounded-2xl shadow-xl text-center border-2 border-emerald-700 mx-auto max-w-sm">
            <div class="flex items-center justify-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-lg bg-emerald-800 text-amber-400 flex items-center justify-center font-black text-xs">SM</div>
                <div class="text-left">
                    <div class="text-xs font-black uppercase text-emerald-900 tracking-wider">SMeCK HOOLIGAN</div>
                    <div class="text-[9px] text-slate-500 font-bold">PRESENSI RESMI SUPORTER</div>
                </div>
            </div>

            <div id="qr_banner_event_title" class="text-sm font-black text-emerald-800 mt-2 mb-3 px-2 py-1 bg-emerald-50 rounded-lg">
                Memuat Acara...
            </div>

            {{-- QR Code Image --}}
            <div class="w-48 h-48 mx-auto p-2 bg-white border border-slate-300 rounded-xl shadow-inner flex items-center justify-center">
                <img id="qr_banner_image" src="" alt="QR Presensi Suporter" class="w-full h-full object-contain">
            </div>

            <p class="text-[10px] text-slate-600 font-bold mt-3">
                📷 Buka Kamera HP Anda & Arahkan ke QR Code di atas
            </p>
            <p class="text-[9px] text-slate-400 mt-0.5">
                Otomatis tercatat hadir di database resmi suporter SMeCK
            </p>
        </div>

        {{-- Link Sharing & Actions --}}
        <div class="mt-5 space-y-3 print:hidden">
            <div class="flex items-center gap-2">
                <input type="text" id="qr_banner_link_input" readonly class="flex-1 px-3 py-2 bg-white/5 border border-white/10 rounded-xl text-white text-[11px] font-mono">
                <button type="button" onclick="copyQrBannerLink()" class="px-3.5 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs transition-colors shrink-0">
                    <i class="fa-solid fa-copy mr-1"></i> Salin
                </button>
            </div>

            <div class="flex items-center justify-between gap-2 pt-2 border-t border-white/10">
                <button type="button" onclick="window.print()" class="flex-1 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-lg transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-print"></i>
                    <span>Cetak Poster QR (A4)</span>
                </button>
                <button type="button" onclick="closeQrBannerModal()" class="px-4 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 text-white/70 text-xs">
                    Tutup
                </button>
            </div>
        </div>

    </div>
</div>
@endpush

