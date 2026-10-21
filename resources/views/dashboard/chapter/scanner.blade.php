@extends('layouts.app')

@section('title', 'Scan Presensi NFC')
@section('header_title', 'Terminal Scanner Presensi NFC')
@section('header_subtitle', 'Gunakan sensor NFC bawaan HP panitia untuk membaca kartu fisik KTA suporter secara instan')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    
    {{-- Scanner Terminal Card --}}
    <div class="p-6 sm:p-10 rounded-3xl bg-smeck-dark-900 border border-amber-500/30 text-center relative overflow-hidden">
        <div class="w-20 h-20 mx-auto rounded-3xl bg-amber-500/20 text-amber-400 flex items-center justify-center text-4xl mb-4 animate-pulse">
            <i class="fa-solid fa-wifi rotate-90"></i>
        </div>
        <h2 class="font-display font-black text-2xl text-white">Terminal Tap KTA NFC Anggota</h2>
        <p class="text-xs sm:text-sm text-white/60 max-w-md mx-auto mt-1 mb-6">
            Tempelkan kartu fisik KTA suporter (e-money, Flazz, kartu NFC) ke bagian belakang HP panitia. Sistem otomatis membaca UID dan mencatat kehadiran.
        </p>

        {{-- Sensor NFC Status Bar & Button --}}
        <div class="max-w-md mx-auto mb-6 p-4 rounded-2xl bg-white/[0.03] border border-white/10 flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="flex items-center gap-2.5 text-xs text-left">
                <span id="nfc-status-dot" class="w-3 h-3 rounded-full bg-amber-400 animate-pulse shrink-0"></span>
                <span id="nfc-status-text" class="text-white/70 font-medium">Sensor NFC HP Siap Digunakan</span>
            </div>
            <button type="button" id="btn-start-nfc" onclick="startNfcScan()" class="px-4 py-2 rounded-xl bg-smeck-green-600 hover:bg-smeck-green-500 text-white font-bold text-xs shadow transition-all flex items-center gap-1.5 shrink-0">
                <i class="fa-solid fa-satellite-dish text-xs"></i>
                <span>Aktifkan Sensor HP</span>
            </button>
        </div>

        {{-- Form Scan --}}
        <form id="nfc-scan-form" action="{{ route('chapter.scanner.process') }}" method="POST" class="max-w-md mx-auto space-y-4">
            @csrf

            <div>
                <label class="block text-left text-xs font-bold uppercase text-white/50 mb-2">Nama Acara / Pertandingan <span class="text-red-400">*</span></label>
                <input type="text" name="event_name" required value="PSMS Medan vs Sriwijaya FC (Nobar Chapter)" class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-amber-400 focus:outline-none">
            </div>

            <div>
                <label class="block text-left text-xs font-bold uppercase text-white/50 mb-2">UID Nomor Kartu NFC <span class="text-red-400">*</span></label>
                <div class="relative">
                    <input type="text" id="uid_input" name="uid_number" required placeholder="Contoh: 04:A2:8B:1A:F3:60:80" class="w-full px-4 py-3.5 bg-white/5 border border-amber-500/50 rounded-xl text-white font-mono text-center tracking-widest text-base focus:border-amber-400 focus:outline-none">
                    <button type="button" onclick="document.getElementById('uid_input').value='04:A2:8B:1A:F3:60:80'" class="absolute right-2 top-2 px-2.5 py-1.5 rounded-lg bg-amber-500/20 text-amber-300 text-[10px] font-bold">
                        Simulasi Tap Demo
                    </button>
                </div>
                <span class="text-[11px] text-white/40 block text-left mt-1">Otomatis terisi saat kartu ditempelkan ke sensor NFC HP</span>
            </div>

            <button type="submit" id="btn-submit-scan" class="w-full py-4 rounded-xl bg-amber-600 hover:bg-amber-500 text-white font-bold text-sm shadow-xl shadow-amber-700/30 transition-all flex items-center justify-center gap-2">
                <i class="fa-solid fa-check-to-slot"></i>
                <span>Catat Kehadiran (Submit Presensi)</span>
            </button>
        </form>
    </div>

    {{-- Live Feed Tap Scanner --}}
    <div class="p-6 sm:p-8 rounded-3xl bg-smeck-dark-900 border border-white/10">
        <h3 class="font-display font-bold text-lg text-white mb-4">Feed Tap Terakhir di Chapter Ini</h3>
        <div class="space-y-3">
            @forelse($recentScans as $sc)
                <div class="p-3.5 rounded-2xl bg-white/[0.03] border border-white/5 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-smeck-green-500/20 text-smeck-green-400 flex items-center justify-center text-sm font-bold">
                            <i class="fa-solid fa-id-card"></i>
                        </div>
                        <div>
                            <div class="text-xs font-bold text-white">{{ $sc->user->name }} ({{ $sc->user->profile?->nia ?? 'NIA' }})</div>
                            <div class="text-[11px] text-white/40">{{ $sc->event_name }}</div>
                        </div>
                    </div>
                    <span class="text-xs font-mono text-amber-400">{{ $sc->scanned_at->format('H:i:s d/m/Y') }}</span>
                </div>
            @empty
                <div class="text-center py-6 text-white/40 text-xs">Belum ada scan kartu tercatat hari ini.</div>
            @endforelse
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    let nfcReader = null;

    async function startNfcScan() {
        const statusText = document.getElementById('nfc-status-text');
        const statusDot = document.getElementById('nfc-status-dot');
        const startBtn = document.getElementById('btn-start-nfc');

        // Check if Web NFC API is supported
        if (!('NDEFReader' in window)) {
            alert("Perhatian: Web NFC API membutuhkan browser Google Chrome di HP Android dan koneksi HTTPS aman.\n\nJika di jaringan Wi-Fi lokal, Anda bisa memasukkan nomor UID kartu atau klik tombol 'Simulasi Tap Demo'.");
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
            startBtn.innerHTML = '<i class="fa-solid fa-check"></i> Siap Menempel';
            startBtn.classList.replace('bg-smeck-green-600', 'bg-white/10');
            startBtn.disabled = true;

            nfcReader.onreading = (event) => {
                // Get UID / serial number from the physical NFC card
                const serialNumber = event.serialNumber;
                
                if (navigator.vibrate) {
                    navigator.vibrate(200); // Haptic feedback getar
                }

                if (serialNumber) {
                    document.getElementById('uid_input').value = serialNumber.toUpperCase();
                    statusText.innerText = `Terbaca UID: ${serialNumber.toUpperCase()}! Memproses...`;
                    
                    // Auto submit form
                    setTimeout(() => {
                        document.getElementById('nfc-scan-form').submit();
                    }, 500);
                }
            };

            nfcReader.onreadingerror = () => {
                alert("Gagal membaca sinyal kartu. Pastikan kartu NFC menempel pas di posisi antena belakang HP Anda.");
            };

        } catch (error) {
            console.error('NFC error:', error);
            alert("Izin NFC: " + error.message);
        }
    }

    // Auto-detect if browser supports NFC on load
    window.addEventListener('DOMContentLoaded', () => {
        if ('NDEFReader' in window) {
            document.getElementById('nfc-status-text').innerText = "HP Mendukung Web NFC! Klik 'Aktifkan Sensor HP'";
        }
    });
</script>
@endpush
