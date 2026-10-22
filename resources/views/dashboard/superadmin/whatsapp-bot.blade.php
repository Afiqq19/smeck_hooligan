@extends('layouts.app')

@section('title', 'WhatsApp Bot Gateway SMeCK Hooligan')
@section('header_title', 'WhatsApp Bot Gateway Resmi SMeCK')
@section('header_subtitle', 'Layanan asisten otomatis 24 jam untuk cek keaslian KTA suporter, jadwal matchday, klasemen PT LIB, dan broadcast pengumuman (100% Gratis)')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">

    {{-- Top Metrics & Gateway Status --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Card 1: Status Bot Real --}}
        <div class="p-5 rounded-2xl bg-smeck-dark-900 border border-emerald-500/30 relative overflow-hidden">
            <div class="absolute -right-4 -bottom-4 w-20 h-20 bg-emerald-500/10 rounded-full blur-xl pointer-events-none"></div>
            <div class="flex items-center justify-between text-xs text-white/50 mb-1">
                <span>Status Gateway</span>
                <span id="gatewayPingDot" class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping"></span>
            </div>
            <div id="gatewayStatusTitle" class="text-xl sm:text-2xl font-display font-black text-emerald-400 flex items-center gap-2">
                <i class="fa-brands fa-whatsapp text-2xl sm:text-3xl"></i>
                <span id="gatewayStatusText">MEMUAT STATUS...</span>
            </div>
            <div id="gatewayStatusSub" class="text-[11px] text-white/60 mt-1 flex items-center justify-between">
                <span id="gatewayPhoneText">Mengecek koneksi bot...</span>
                <button type="button" onclick="openQrModal()" id="btnActionPair" class="text-xs text-smeck-gold hover:underline font-bold">
                    Tautkan HP ➔
                </button>
            </div>
        </div>

        {{-- Card 2: Biaya Layanan --}}
        <div class="p-5 rounded-2xl bg-smeck-dark-900 border border-white/10">
            <div class="flex items-center justify-between text-xs text-white/50 mb-1">
                <span>Biaya Langganan</span>
                <i class="fa-solid fa-hand-holding-dollar text-smeck-gold"></i>
            </div>
            <div class="text-3xl font-display font-black text-white flex items-baseline gap-2">
                <span>Rp 0</span>
                <span class="text-xs font-normal text-smeck-green-400">100% GRATIS</span>
            </div>
            <div class="text-[11px] text-white/50 mt-1">Tanpa token pesan Meta</div>
        </div>

        {{-- Card 3: Database Suporter Terhubung --}}
        <div class="p-5 rounded-2xl bg-smeck-dark-900 border border-white/10">
            <div class="flex items-center justify-between text-xs text-white/50 mb-1">
                <span>Database Terhubung</span>
                <i class="fa-solid fa-database text-cyan-400"></i>
            </div>
            <div class="text-3xl font-display font-black text-cyan-400 flex items-baseline gap-2">
                <span>{{ $totalMembers }}</span>
                <span class="text-xs font-normal text-white/40">Anggota</span>
            </div>
            <div class="text-[11px] text-white/50 mt-1">{{ $totalActiveCards }} KTA NFC Aktif Terpantau</div>
        </div>

        {{-- Card 4: Webhook Handler --}}
        <div class="p-5 rounded-2xl bg-smeck-dark-900 border border-white/10">
            <div class="flex items-center justify-between text-xs text-white/50 mb-1">
                <span>Integrasi Webhook</span>
                <i class="fa-solid fa-code text-purple-400"></i>
            </div>
            <div class="text-2xl font-display font-black text-purple-400">
                REST API
            </div>
            <div class="text-[11px] text-white/50 mt-1 font-mono truncate" title="{{ $webhookUrl }}">
                /api/whatsapp/webhook
            </div>
        </div>
    </div>

    {{-- Main Container: Interactive Simulator & Command Directory --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        {{-- LEFT 5 COLS: Interactive WhatsApp Smartphone Mockup Simulator --}}
        <div class="lg:col-span-5 space-y-4">
            <div class="rounded-3xl bg-smeck-dark-900 border border-emerald-500/30 overflow-hidden shadow-2xl flex flex-col h-[650px]">
                {{-- Mockup WhatsApp Header --}}
                <div class="p-4 bg-emerald-800 text-white flex items-center justify-between shadow-md">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-white/20 border border-white/30 flex items-center justify-center font-bold text-sm overflow-hidden">
                            <img src="{{ asset('images/logo-crest.jpg') }}" alt="SMeCK" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <div class="font-bold text-sm leading-tight">Bot SMeCK Hooligan 🟢</div>
                            <div class="text-[10px] text-emerald-200">Online • Auto-Responder Aktif</div>
                        </div>
                    </div>
                    <button onclick="clearChatSimulator()" title="Bersihkan Percakapan" class="w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20 flex items-center justify-center text-xs transition-colors">
                        <i class="fa-solid fa-rotate-right"></i>
                    </button>
                </div>

                {{-- Chat Feed Body --}}
                <div id="chatFeed" class="flex-1 p-4 overflow-y-auto space-y-3 bg-[#0b141a] custom-scrollbar text-xs">
                    {{-- System Notice --}}
                    <div class="text-center my-2">
                        <span class="px-3 py-1 rounded-full bg-white/10 text-white/60 text-[10px]">
                            🔒 Pesan terenkripsi & tersambung ke database SMeCK
                        </span>
                    </div>

                    {{-- Welcome Message from Bot --}}
                    <div class="flex items-start gap-2">
                        <div class="max-w-[85%] rounded-2xl rounded-tl-none p-3.5 bg-[#202c33] text-white/90 border border-white/5 space-y-2 shadow">
                            <div class="font-bold text-emerald-400 text-xs flex items-center gap-1.5">
                                <i class="fa-brands fa-whatsapp"></i>
                                <span>Bot SMeCK Hooligan</span>
                            </div>
                            <div class="leading-relaxed whitespace-pre-line">
Halo Saudaraku! 💚
Selamat datang di Layanan WhatsApp Otomatis Resmi SMeCK Hooligan (PSMS Medan).

Ketik kata kunci untuk mencoba:
• *CEK KTA SMCK-2024-1950*
• *JADWAL*
• *KLASEMEN*
• *DAFTAR*
• *CHANT*
                            </div>
                            <div class="text-[9px] text-white/40 text-right font-mono">10:00</div>
                        </div>
                    </div>
                </div>

                {{-- Quick Prompts Chips --}}
                <div class="p-2.5 bg-smeck-dark-950 border-t border-white/10 flex items-center gap-1.5 overflow-x-auto text-[11px] shrink-0 custom-scrollbar">
                    <button onclick="sendQuickPrompt('CEK KTA SMCK-2024-1950')" class="px-2.5 py-1 rounded-lg bg-white/10 hover:bg-emerald-600/30 text-emerald-300 font-semibold whitespace-nowrap transition-colors">
                        🔍 Cek KTA Aktif
                    </button>
                    <button onclick="sendQuickPrompt('CEK KTA SMCK-9999-0000')" class="px-2.5 py-1 rounded-lg bg-white/10 hover:bg-red-600/30 text-red-300 font-semibold whitespace-nowrap transition-colors">
                        ⚠️ Cek KTA Palsu
                    </button>
                    <button onclick="sendQuickPrompt('JADWAL')" class="px-2.5 py-1 rounded-lg bg-white/10 hover:bg-white/20 text-white/80 font-semibold whitespace-nowrap transition-colors">
                        📅 Jadwal
                    </button>
                    <button onclick="sendQuickPrompt('KLASEMEN')" class="px-2.5 py-1 rounded-lg bg-white/10 hover:bg-white/20 text-white/80 font-semibold whitespace-nowrap transition-colors">
                        📊 Klasemen
                    </button>
                    <button onclick="sendQuickPrompt('DAFTAR')" class="px-2.5 py-1 rounded-lg bg-white/10 hover:bg-white/20 text-white/80 font-semibold whitespace-nowrap transition-colors">
                        📝 Daftar
                    </button>
                </div>

                {{-- Chat Input Form --}}
                <form id="chatSimulatorForm" onsubmit="event.preventDefault(); submitChatMessage();" class="p-3 bg-smeck-dark-950 flex items-center gap-2 border-t border-white/10">
                    <input type="text" id="simMessageInput" placeholder="Ketik perintah (contoh: JADWAL)..."
                        class="flex-1 px-3.5 py-2.5 bg-[#2a3942] border border-white/10 rounded-xl text-xs text-white placeholder-white/40 focus:outline-none focus:border-emerald-500">
                    <button type="submit" id="btnSendSim" class="w-10 h-10 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white flex items-center justify-center transition-colors shrink-0 shadow">
                        <i class="fa-solid fa-paper-plane text-xs"></i>
                    </button>
                </form>
            </div>
            <p class="text-[11px] text-white/40 text-center">
                💡 Simulator di atas langsung menjalankan logika backend riil seperti chat WhatsApp sesungguhnya.
            </p>
        </div>

        {{-- RIGHT 7 COLS: Command Reference & Connection Guide --}}
        <div class="lg:col-span-7 space-y-6">

            {{-- Card: Tautkan WhatsApp Menggunakan Nomor Sendiri (100% Gratis) --}}
            <div class="p-6 sm:p-7 rounded-3xl bg-gradient-to-br from-emerald-950/40 via-smeck-dark-900 to-smeck-dark-900 border border-emerald-500/30 shadow-2xl relative overflow-hidden">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-white/10 mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-2xl shrink-0">
                            <i class="fa-brands fa-whatsapp"></i>
                        </div>
                        <div>
                            <h3 class="font-display font-bold text-base sm:text-lg text-white">Tautkan Nomor WhatsApp Organisasi</h3>
                            <p class="text-xs text-white/50">Gunakan nomor HP Anda sendiri via fitur Perangkat Tertaut (Linked Devices)</p>
                        </div>
                    </div>
                    <button onclick="openQrModal()" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs flex items-center justify-center gap-2 shadow-lg transition-all cursor-pointer">
                        <i class="fa-solid fa-qrcode"></i>
                        <span>Scan QR Code Perangkat</span>
                    </button>
                </div>

                {{-- 3 Easy Steps --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs text-white/80">
                    <div class="p-3.5 rounded-2xl bg-white/[0.02] border border-white/5 space-y-1">
                        <div class="w-6 h-6 rounded-lg bg-emerald-500/20 text-emerald-300 font-bold flex items-center justify-center text-xs">1</div>
                        <div class="font-bold text-white">Buka WhatsApp HP</div>
                        <div class="text-[11px] text-white/50 leading-relaxed">Buka aplikasi WhatsApp di HP pengurus, klik titik tiga di kanan atas ➔ pilih <strong>Perangkat Tertaut</strong>.</div>
                    </div>
                    <div class="p-3.5 rounded-2xl bg-white/[0.02] border border-white/5 space-y-1">
                        <div class="w-6 h-6 rounded-lg bg-emerald-500/20 text-emerald-300 font-bold flex items-center justify-center text-xs">2</div>
                        <div class="font-bold text-white">Scan QR Code</div>
                        <div class="text-[11px] text-white/50 leading-relaxed">Klik tombol <strong>Scan QR Code</strong> di atas, lalu arahkan kamera WhatsApp HP ke barcode di layar.</div>
                    </div>
                    <div class="p-3.5 rounded-2xl bg-white/[0.02] border border-white/5 space-y-1">
                        <div class="w-6 h-6 rounded-lg bg-emerald-500/20 text-emerald-300 font-bold flex items-center justify-center text-xs">3</div>
                        <div class="font-bold text-white">Bot Aktif 24 Jam!</div>
                        <div class="text-[11px] text-white/50 leading-relaxed">Nomor Anda langsung menjadi bot otomatis tanpa biaya bulanan sepeserpun!</div>
                    </div>
                </div>
            </div>

            {{-- Card: Kamus Perintah (Cheat Sheet Commands) --}}
            <div class="p-6 sm:p-7 rounded-3xl bg-smeck-dark-900 border border-white/10 shadow-2xl space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-white/10">
                    <div>
                        <h4 class="font-display font-bold text-base text-white">Daftar Perintah Resmi Bot</h4>
                        <p class="text-xs text-white/50">Perintah yang dapat diketik suporter kapan saja ke nomor WhatsApp</p>
                    </div>
                    <span class="px-2.5 py-1 rounded-full bg-emerald-500/10 text-emerald-400 font-mono text-[10px] font-bold">
                        5 PERINTAH UTAMA
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-white/10 text-white/40 uppercase tracking-wider text-[11px]">
                                <th class="py-2.5 px-3">Kata Kunci</th>
                                <th class="py-2.5 px-3">Kegunaan & Fungsi</th>
                                <th class="py-2.5 px-3">Contoh Chat</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            <tr class="hover:bg-white/[0.02]">
                                <td class="py-3 px-3 font-mono font-bold text-emerald-400">CEK KTA &lt;nomor&gt;</td>
                                <td class="py-3 px-3 text-white/80">Validasi keaslian KTA suporter, nama, basis, gol. darah & masa berlaku (Cegah KTA palsu).</td>
                                <td class="py-3 px-3 font-mono text-white/50">`CEK KTA SMCK-2024-1950`</td>
                            </tr>
                            <tr class="hover:bg-white/[0.02]">
                                <td class="py-3 px-3 font-mono font-bold text-emerald-400">JADWAL</td>
                                <td class="py-3 px-3 text-white/80">Informasi pertandingan terdekat PSMS Medan, stadion, harga & sisa kuota tiket suporter.</td>
                                <td class="py-3 px-3 font-mono text-white/50">`JADWAL`</td>
                            </tr>
                            <tr class="hover:bg-white/[0.02]">
                                <td class="py-3 px-3 font-mono font-bold text-emerald-400">KLASEMEN</td>
                                <td class="py-3 px-3 text-white/80">Posisi & poin klasemen PSMS Medan terkini disinkronkan langsung dari PT LIB.</td>
                                <td class="py-3 px-3 font-mono text-white/50">`KLASEMEN`</td>
                            </tr>
                            <tr class="hover:bg-white/[0.02]">
                                <td class="py-3 px-3 font-mono font-bold text-emerald-400">DAFTAR</td>
                                <td class="py-3 px-3 text-white/80">Tautan instan dan syarat pendaftaran anggota baru bagi suporter yang ingin bergabung.</td>
                                <td class="py-3 px-3 font-mono text-white/50">`DAFTAR`</td>
                            </tr>
                            <tr class="hover:bg-white/[0.02]">
                                <td class="py-3 px-3 font-mono font-bold text-emerald-400">CHANT</td>
                                <td class="py-3 px-3 text-white/80">Lirik anthem kebanggaan tribun *Salam Sada Roha* dan lagu semangat Ayam Kinantan.</td>
                                <td class="py-3 px-3 font-mono text-white/50">`CHANT`</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Card: Broadcast Cepat ke WhatsApp (100% Gratis) --}}
            <div class="p-6 sm:p-7 rounded-3xl bg-smeck-dark-900 border border-white/10 shadow-2xl space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-bullhorn"></i>
                    </div>
                    <div>
                        <h4 class="font-display font-bold text-base text-white">Kirim Pesan WhatsApp Cepat (1-Klik Bebas Biaya)</h4>
                        <p class="text-xs text-white/50">Kirim pesan resmi langsung dari HP admin ke nomor tujuan tanpa biaya sepeserpun</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-white/70 mb-1">Nomor WhatsApp Tujuan</label>
                        <input type="text" id="directWaPhone" placeholder="081234567890" class="w-full px-3.5 py-2.5 bg-black/40 border border-white/10 rounded-xl text-xs text-white placeholder-white/40 focus:outline-none focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-white/70 mb-1">Template Pesan</label>
                        <select onchange="fillWaTemplate(this.value)" class="w-full px-3.5 py-2.5 bg-black/40 border border-white/10 rounded-xl text-xs text-white focus:outline-none focus:border-emerald-500">
                            <option value="">Pilih Template Pesan Cepat...</option>
                            <option value="verif">Konfirmasi Verifikasi KTA Diterima</option>
                            <option value="match">Himbauan Komando Laga Kandang</option>
                            <option value="away">Info Kuota Bus Awaydays</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-white/70 mb-1">Isi Pesan WhatsApp</label>
                    <textarea id="directWaMessage" rows="3" placeholder="Tuliskan pesan broadcast..." class="w-full px-3.5 py-2.5 bg-black/40 border border-white/10 rounded-xl text-xs text-white placeholder-white/40 focus:outline-none focus:border-emerald-500"></textarea>
                </div>

                <button type="button" onclick="launchDirectWa()" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs flex items-center justify-center gap-2 shadow-lg transition-all cursor-pointer">
                    <i class="fa-brands fa-whatsapp text-sm"></i>
                    <span>Kirim Pesan via WhatsApp Sekarang</span>
                </button>
            </div>

        </div>
    </div>
</div>

{{-- MODAL SCAN QR CODE & PAIRING CODE REAL BAILEYS --}}
<div id="modalQrConnect" class="fixed inset-0 z-50 bg-black/85 backdrop-blur-md hidden items-center justify-center p-4 overflow-y-auto">
    <div class="relative w-full max-w-lg bg-smeck-dark-900 border border-emerald-500/40 rounded-3xl p-6 sm:p-8 shadow-2xl text-center">
        <button onclick="closeQrModal()" class="absolute top-5 right-5 text-white/40 hover:text-white text-xl">
            <i class="fa-solid fa-xmark"></i>
        </button>

        {{-- Connected State View (Hidden by default) --}}
        <div id="viewConnected" class="hidden space-y-4">
            <div class="w-16 h-16 mx-auto rounded-2xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-3xl shadow-lg shadow-emerald-950/40">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <h3 class="font-display font-black text-2xl text-white">WhatsApp Bot Berhasil Terhubung!</h3>
            <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-left space-y-2">
                <div class="flex items-center justify-between text-xs">
                    <span class="text-white/60">Nomor WhatsApp:</span>
                    <span id="connUserPhone" class="font-mono font-bold text-emerald-400 text-sm">-</span>
                </div>
                <div class="flex items-center justify-between text-xs">
                    <span class="text-white/60">Nama Perangkat:</span>
                    <span id="connUserName" class="font-bold text-white">-</span>
                </div>
                <div class="flex items-center justify-between text-xs">
                    <span class="text-white/60">Status Auto-Reply:</span>
                    <span class="px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 font-bold text-[10px]">AKTIF 24 JAM</span>
                </div>
            </div>
            <p class="text-xs text-white/60">
                Bot resmi sekarang aktif merespon pesan suporter (`CEK KTA`, `JADWAL`, `KLASEMEN`, dll) secara otomatis tanpa perlu membuka aplikasi WhatsApp.
            </p>
            <div class="flex items-center gap-3 pt-2">
                <button type="button" onclick="closeQrModal()" class="flex-1 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-lg transition-all">
                    Selesai & Tutup
                </button>
                <button type="button" onclick="disconnectBotSession()" class="py-2.5 px-4 rounded-xl bg-red-600/20 hover:bg-red-600 text-red-300 hover:text-white font-bold text-xs border border-red-500/30 transition-all">
                    Putuskan Sesi
                </button>
            </div>
        </div>

        {{-- Disconnected / Pairing View --}}
        <div id="viewPairing" class="space-y-4">
            <div class="w-12 h-12 mx-auto rounded-2xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-2xl shadow-lg shadow-emerald-950/40">
                <i class="fa-brands fa-whatsapp"></i>
            </div>

            <div>
                <h3 class="font-display font-black text-xl text-white">Hubungkan WhatsApp Pengurus</h3>
                <p class="text-xs text-white/50 mt-1">
                    Pilih metode tautkan perangkat ke WhatsApp Resmi SMeCK Hooligan
                </p>
            </div>

            {{-- Tab Switcher --}}
            <div class="flex rounded-xl bg-black/40 p-1 border border-white/10 text-xs font-semibold">
                <button type="button" onclick="switchPairingTab('qr')" id="tabBtnQr" class="flex-1 py-2 rounded-lg bg-emerald-600 text-white transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-qrcode"></i>
                    <span>Cara 1: Scan Barcode QR</span>
                </button>
                <button type="button" onclick="switchPairingTab('code')" id="tabBtnCode" class="flex-1 py-2 rounded-lg text-white/60 hover:text-white transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-key"></i>
                    <span>Cara 2: Kode 8-Digit (Tanpa Kamera)</span>
                </button>
            </div>

            {{-- TAB 1: SCAN QR CODE REAL --}}
            <div id="tabQrContent" class="space-y-4">
                {{-- QR Box --}}
                <div class="w-60 h-60 mx-auto p-3 bg-white rounded-2xl shadow-2xl flex items-center justify-center border-4 border-emerald-500 relative">
                    <img id="realQrImage" src="" alt="Memuat Kode QR..." class="w-full h-full object-contain hidden">
                    
                    {{-- Placeholder / Loading Spinner --}}
                    <div id="qrLoadingSpinner" class="flex flex-col items-center justify-center text-gray-700 space-y-2 p-4 text-center">
                        <i class="fa-solid fa-circle-notch fa-spin text-3xl text-emerald-600"></i>
                        <span class="text-xs font-bold text-gray-800">Menghasilkan Kode QR WhatsApp...</span>
                        <span class="text-[10px] text-gray-500">Pastikan layanan bot berjalan</span>
                    </div>
                </div>

                {{-- Alert jika bot belum aktif --}}
                <div id="botOfflineAlert" class="hidden p-3 rounded-xl bg-amber-500/10 border border-amber-500/30 text-left text-xs text-amber-200 space-y-1">
                    <div class="font-bold flex items-center gap-1.5 text-amber-400">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <span>Layanan Bot Belum Berjalan di Komputer</span>
                    </div>
                    <p class="text-[11px] text-white/70 leading-relaxed">
                        Silakan buka folder proyek dan jalankan file: <code class="px-1.5 py-0.5 rounded bg-black/50 text-amber-300 font-mono">JALANKAN-BOT-WA.bat</code> untuk menyalakan mesin Baileys.
                    </p>
                </div>

                <div class="p-3 rounded-xl bg-white/5 border border-white/10 text-left text-xs text-white/70 space-y-1.5">
                    <div class="font-bold text-emerald-400 flex items-center gap-1.5">
                        <i class="fa-solid fa-mobile-screen-button"></i>
                        <span>Petunjuk Scan Barcode di HP:</span>
                    </div>
                    <ol class="list-decimal list-inside text-[11px] text-white/60 space-y-0.5 leading-relaxed">
                        <li>Buka aplikasi WhatsApp di HP Anda.</li>
                        <li>Ketuk <strong>Titik Tiga</strong> (Android) atau <strong>Pengaturan</strong> (iPhone).</li>
                        <li>Pilih <strong>"Perangkat Tertaut"</strong> ➔ Ketuk <strong>"Tautkan Perangkat"</strong>.</li>
                        <li>Arahkan kamera ke barcode di atas (Otomatis terhubung!).</li>
                    </ol>
                </div>
            </div>

            {{-- TAB 2: PAIRING CODE 8-DIGIT (ALTERNATIF TANPA SCAN KAMERA) --}}
            <div id="tabCodeContent" class="hidden space-y-4 text-left">
                <div class="p-4 rounded-2xl bg-white/5 border border-white/10 space-y-3">
                    <div>
                        <label class="block text-xs font-semibold text-white/80 mb-1">Nomor WhatsApp Anda (Contoh: 081234567890)</label>
                        <div class="flex gap-2">
                            <input type="text" id="inputPairingPhone" placeholder="081234567890" class="flex-1 px-3.5 py-2.5 bg-black/40 border border-white/20 rounded-xl text-xs text-white placeholder-white/40 focus:outline-none focus:border-emerald-500">
                            <button type="button" onclick="requestPairingCode()" id="btnSubmitPairingCode" class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-lg transition-all shrink-0">
                                Minta Kode
                            </button>
                        </div>
                    </div>

                    {{-- Display Box Kode Pairing --}}
                    <div id="boxPairingCodeResult" class="hidden p-4 rounded-xl bg-emerald-500/10 border-2 border-emerald-500/40 text-center space-y-2">
                        <div class="text-[11px] text-white/60">Ketik kode ini di WhatsApp HP Anda:</div>
                        <div id="displayPairingCode" class="font-mono text-3xl font-black text-emerald-400 tracking-widest py-1 select-all">
                            ----
                        </div>
                        <button type="button" onclick="copyPairingCode()" class="px-3 py-1 rounded-lg bg-white/10 hover:bg-white/20 text-white text-[10px] font-semibold transition-colors">
                            <i class="fa-regular fa-copy mr-1"></i> Salin Kode
                        </button>
                    </div>
                </div>

                <div class="p-3 rounded-xl bg-white/5 border border-white/10 text-xs text-white/70 space-y-1.5">
                    <div class="font-bold text-emerald-400 flex items-center gap-1.5">
                        <i class="fa-solid fa-list-check"></i>
                        <span>Cara Memasukkan Kode di WhatsApp:</span>
                    </div>
                    <ol class="list-decimal list-inside text-[11px] text-white/60 space-y-0.5 leading-relaxed">
                        <li>Buka WhatsApp di HP ➔ <strong>Perangkat Tertaut</strong>.</li>
                        <li>Ketuk <strong>"Tautkan Perangkat"</strong>.</li>
                        <li>Ketuk <strong>"Tautkan dengan nomor telepon saja"</strong> di bagian bawah layar.</li>
                        <li>Masukkan 8 digit kode yang tampil di atas!</li>
                    </ol>
                </div>
            </div>

            <div class="pt-2">
                <button type="button" onclick="closeQrModal()" class="w-full py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs transition-all">
                    Tutup Sementara
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let currentBotState = 'disconnected';
    let pollInterval = null;

    // Inisialisasi saat halaman selesai dimuat
    document.addEventListener('DOMContentLoaded', () => {
        fetchRealBotStatus();
        pollInterval = setInterval(fetchRealBotStatus, 3000);
    });

    // 1. Ambil Status Real Bot WhatsApp dari Laravel
    function fetchRealBotStatus() {
        fetch('{{ route("superadmin.whatsapp.real-status") }}')
            .then(res => res.json())
            .then(data => {
                updateBotUi(data);
            })
            .catch(err => {
                // Fallback direct localhost jika ada CORS lokal
                fetch('http://127.0.0.1:3000/api/status')
                    .then(res => res.json())
                    .then(data => updateBotUi(data))
                    .catch(() => {
                        updateBotUi({ status: 'disconnected', user: { phone: null, name: null }, qr: null });
                    });
            });
    }

    // 2. Perbarui Tampilan UI secara Dinamis
    function updateBotUi(data) {
        currentBotState = data.status || 'disconnected';

        const dot = document.getElementById('gatewayPingDot');
        const title = document.getElementById('gatewayStatusTitle');
        const text = document.getElementById('gatewayStatusText');
        const phone = document.getElementById('gatewayPhoneText');
        const btn = document.getElementById('btnActionPair');

        const viewPairing = document.getElementById('viewPairing');
        const viewConnected = document.getElementById('viewConnected');
        const realQrImg = document.getElementById('realQrImage');
        const qrSpinner = document.getElementById('qrLoadingSpinner');
        const offlineAlert = document.getElementById('botOfflineAlert');

        if (currentBotState === 'connected') {
            dot.className = 'w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping';
            title.className = 'text-xl sm:text-2xl font-display font-black text-emerald-400 flex items-center gap-2';
            text.innerText = 'TERHUBUNG';
            phone.innerText = data.user?.phone ? `+${data.user.phone}` : 'WhatsApp Siap';
            btn.innerText = 'Lihat Sesi ➔';
            btn.className = 'text-xs text-emerald-400 hover:underline font-bold';

            // Tampilan Modal Sukses
            viewPairing.classList.add('hidden');
            viewConnected.classList.remove('hidden');
            document.getElementById('connUserPhone').innerText = `+${data.user?.phone || 'Aktif'}`;
            document.getElementById('connUserName').innerText = data.user?.name || 'Admin SMeCK';
        } else if (currentBotState === 'qr_ready') {
            dot.className = 'w-2.5 h-2.5 rounded-full bg-amber-400 animate-ping';
            title.className = 'text-xl sm:text-2xl font-display font-black text-amber-400 flex items-center gap-2';
            text.innerText = 'SIAP PAIRING';
            phone.innerText = 'Scan QR di HP Anda';
            btn.innerText = 'Tautkan HP ➔';
            btn.className = 'text-xs text-amber-400 hover:underline font-bold';

            viewConnected.classList.add('hidden');
            viewPairing.classList.remove('hidden');

            if (data.qr) {
                realQrImg.src = data.qr;
                realQrImg.classList.remove('hidden');
                qrSpinner.classList.add('hidden');
                offlineAlert.classList.add('hidden');
            }
        } else {
            // Disconnected / Offline
            dot.className = 'w-2.5 h-2.5 rounded-full bg-red-400';
            title.className = 'text-xl sm:text-2xl font-display font-black text-white/70 flex items-center gap-2';
            text.innerText = 'BELUM AKTIF';
            phone.innerText = 'Jalankan JALANKAN-BOT-WA.bat';
            btn.innerText = 'Buka Panduan ➔';
            btn.className = 'text-xs text-smeck-gold hover:underline font-bold';

            viewConnected.classList.add('hidden');
            viewPairing.classList.remove('hidden');
            realQrImg.classList.add('hidden');
            qrSpinner.classList.remove('hidden');
            offlineAlert.classList.remove('hidden');
        }
    }

    // 3. Tab Switcher Modal
    function switchPairingTab(tab) {
        const tabBtnQr = document.getElementById('tabBtnQr');
        const tabBtnCode = document.getElementById('tabBtnCode');
        const tabQrContent = document.getElementById('tabQrContent');
        const tabCodeContent = document.getElementById('tabCodeContent');

        if (tab === 'qr') {
            tabBtnQr.className = 'flex-1 py-2 rounded-lg bg-emerald-600 text-white transition-all flex items-center justify-center gap-2';
            tabBtnCode.className = 'flex-1 py-2 rounded-lg text-white/60 hover:text-white transition-all flex items-center justify-center gap-2';
            tabQrContent.classList.remove('hidden');
            tabCodeContent.classList.add('hidden');
        } else {
            tabBtnCode.className = 'flex-1 py-2 rounded-lg bg-emerald-600 text-white transition-all flex items-center justify-center gap-2';
            tabBtnQr.className = 'flex-1 py-2 rounded-lg text-white/60 hover:text-white transition-all flex items-center justify-center gap-2';
            tabCodeContent.classList.remove('hidden');
            tabQrContent.classList.add('hidden');
        }
    }

    // 4. Request Pairing Code (8-Digit)
    function requestPairingCode() {
        const input = document.getElementById('inputPairingPhone');
        const phone = input.value.trim();
        const btn = document.getElementById('btnSubmitPairingCode');

        if (!phone) {
            alert('Masukkan nomor WhatsApp Anda terlebih dahulu!');
            return;
        }

        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';

        fetch('{{ route("superadmin.whatsapp.real-pairing") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ phone: phone })
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.innerText = 'Minta Kode';

            if (data.success && data.code) {
                document.getElementById('displayPairingCode').innerText = data.code;
                document.getElementById('boxPairingCodeResult').classList.remove('hidden');
            } else {
                alert(data.message || 'Gagal membuat kode pairing.');
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerText = 'Minta Kode';
            alert('Gagal menghubungi bot. Pastikan file JALANKAN-BOT-WA.bat sedang berjalan.');
        });
    }

    function copyPairingCode() {
        const code = document.getElementById('displayPairingCode').innerText.trim();
        navigator.clipboard.writeText(code).then(() => {
            alert(`Kode pairing ${code} disalin ke clipboard! Masukkan di WhatsApp Anda.`);
        });
    }

    // 5. Putuskan Sesi Bot
    function disconnectBotSession() {
        if (!confirm('Apakah Anda yakin ingin memutuskan sesi WhatsApp bot ini?')) return;

        fetch('{{ route("superadmin.whatsapp.real-logout") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(() => {
            alert('Sesi WhatsApp berhasil diputuskan.');
            fetchRealBotStatus();
        })
        .catch(() => {
            fetchRealBotStatus();
        });
    }

    // 6. Submit Chat Simulator Message
    function submitChatMessage() {
        const input = document.getElementById('simMessageInput');
        const text = input.value.trim();
        if (!text) return;

        appendUserBubble(text);
        input.value = '';

        fetch('{{ route("superadmin.whatsapp.simulate") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ message: text })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                appendBotBubble(data.bot_reply_html, data.timestamp);
            }
        })
        .catch(err => {
            console.error(err);
            appendBotBubble('Maaf, terjadi kesalahan pada server bot.', 'Now');
        });
    }

    function sendQuickPrompt(cmd) {
        document.getElementById('simMessageInput').value = cmd;
        submitChatMessage();
    }

    function appendUserBubble(text) {
        const feed = document.getElementById('chatFeed');
        const now = new Date();
        const timeStr = String(now.getHours()).padStart(2, '0') + ':' + String(now.getMinutes()).padStart(2, '0');

        const bubble = document.createElement('div');
        bubble.className = 'flex items-start justify-end gap-2 animate-fade-in';
        bubble.innerHTML = `
            <div class="max-w-[85%] rounded-2xl rounded-tr-none p-3 bg-[#005c4b] text-white space-y-1 shadow">
                <div class="text-xs font-medium leading-relaxed">${escapeHtml(text)}</div>
                <div class="text-[9px] text-emerald-200 text-right font-mono flex items-center justify-end gap-1">
                    <span>${timeStr}</span>
                    <i class="fa-solid fa-check-double text-[9px] text-cyan-300"></i>
                </div>
            </div>
        `;
        feed.appendChild(bubble);
        feed.scrollTop = feed.scrollHeight;
    }

    function appendBotBubble(htmlContent, timeStr) {
        const feed = document.getElementById('chatFeed');
        const bubble = document.createElement('div');
        bubble.className = 'flex items-start gap-2 animate-fade-in';
        bubble.innerHTML = `
            <div class="max-w-[85%] rounded-2xl rounded-tl-none p-3.5 bg-[#202c33] text-white/90 border border-white/5 space-y-2 shadow">
                <div class="font-bold text-emerald-400 text-xs flex items-center gap-1.5">
                    <i class="fa-brands fa-whatsapp"></i>
                    <span>Bot SMeCK Hooligan</span>
                </div>
                <div class="leading-relaxed text-xs font-mono whitespace-pre-line">${htmlContent}</div>
                <div class="text-[9px] text-white/40 text-right font-mono">${timeStr}</div>
            </div>
        `;
        feed.appendChild(bubble);
        feed.scrollTop = feed.scrollHeight;
    }

    function clearChatSimulator() {
        const feed = document.getElementById('chatFeed');
        feed.innerHTML = `
            <div class="text-center my-2">
                <span class="px-3 py-1 rounded-full bg-white/10 text-white/60 text-[10px]">
                    Percakapan dibersihkan • Ketik perintah untuk mencoba
                </span>
            </div>
        `;
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.innerText = text;
        return div.innerHTML;
    }

    function openQrModal() {
        const modal = document.getElementById('modalQrConnect');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        fetchRealBotStatus();
    }

    function closeQrModal() {
        const modal = document.getElementById('modalQrConnect');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function fillWaTemplate(type) {
        const input = document.getElementById('directWaMessage');
        if (type === 'verif') {
            input.value = "Halo Saudaraku! 💚\n\nPendaftaran keanggotaan resmi SMeCK Hooligan Anda telah DISETUJUI oleh pengurus.\nNomor Induk Anggota (NIA) Anda telah terbit.\n\nSilakan masuk ke Portal Suporter untuk melihat KTA Digital resmi Anda:\nhttps://projek2.xie.my.id/login\n\nSalam Sada Roha!";
        } else if (type === 'match') {
            input.value = "📢 MAKLUMAT KOMANDO TRIBUN SMECK HOOLIGAN\n\nJelang laga akbar PSMS Medan, mari hijaukan Stadion Teladan! Seluruh suporter wajib mengenakan atribut Hijau-Putih dan satu komando di Tribun Timur.\n\nSalam Sada Roha!";
        } else if (type === 'away') {
            input.value = "🚌 PEMBERITAHUAN TUR AWAYDAYS PSMS MEDAN\n\nPendaftaran kuota bus awaydays resmi SMeCK Hooligan telah dibuka. Dapatkan tiket bus & tiket stadion harga suporter di portal resmi:\nhttps://projek2.xie.my.id/\n\nSalam Sada Roha!";
        }
    }

    function launchDirectWa() {
        let phone = document.getElementById('directWaPhone').value.trim().replace(/[^0-9]/g, '');
        const message = document.getElementById('directWaMessage').value.trim();

        if (!phone) {
            alert('Silakan masukkan nomor WhatsApp tujuan!');
            return;
        }

        if (phone.startsWith('0')) {
            phone = '62' + phone.substring(1);
        }

        if (!message) {
            alert('Silakan tuliskan isi pesan yang ingin dikirim!');
            return;
        }

        // Tombol indikator proses
        const btn = document.querySelector('button[onclick="launchDirectWa()"]');
        const originalHtml = btn ? btn.innerHTML : '';
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Mengirim via Bot...';
        }

        fetch('{{ route("superadmin.whatsapp.real-send") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ phone: phone, message: message })
        })
        .then(res => res.json())
        .then(data => {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = originalHtml;
            }

            if (data.success) {
                alert(`✅ PESAN BERHASIL DIKIRIM SECARA OTOMATIS!\n\nBot WhatsApp SMeCK Hooligan telah mengirimkan pesan tersebut ke nomor +${phone} tanpa perlu membuka aplikasi WhatsApp di HP atau web!`);
                document.getElementById('directWaMessage').value = '';
            } else {
                alert(data.message || 'Gagal mengirim pesan.');
            }
        })
        .catch(err => {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = originalHtml;
            }
            alert('Terjadi kesalahan saat menghubungi server bot.');
        });
    }
</script>
@endsection
