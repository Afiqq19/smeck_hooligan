@extends('layouts.app')

@section('title', 'WhatsApp Bot Gateway SMeCK Hooligan')
@section('header_title', 'WhatsApp Bot Gateway Resmi SMeCK')
@section('header_subtitle', 'Layanan asisten otomatis 24 jam untuk cek keaslian KTA suporter, jadwal matchday, klasemen PT LIB, dan broadcast pengumuman (100% Gratis)')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">

    {{-- Top Metrics & Gateway Status --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Card 1: Status Bot --}}
        <div class="p-5 rounded-2xl bg-smeck-dark-900 border border-emerald-500/30 relative overflow-hidden">
            <div class="absolute -right-4 -bottom-4 w-20 h-20 bg-emerald-500/10 rounded-full blur-xl pointer-events-none"></div>
            <div class="flex items-center justify-between text-xs text-white/50 mb-1">
                <span>Status Gateway</span>
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping"></span>
            </div>
            <div class="text-2xl font-display font-black text-emerald-400 flex items-center gap-2">
                <i class="fa-brands fa-whatsapp text-3xl"></i>
                <span>ONLINE 24 JAM</span>
            </div>
            <div class="text-[11px] text-emerald-400/80 mt-1">Mesin Baileys / Direct API Siap</div>
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

{{-- MODAL SCAN QR CODE PERANGKAT TERTAUT --}}
<div id="modalQrConnect" class="fixed inset-0 z-50 bg-black/85 backdrop-blur-md hidden items-center justify-center p-4 overflow-y-auto">
    <div class="relative w-full max-w-md bg-smeck-dark-900 border border-emerald-500/40 rounded-3xl p-6 sm:p-8 shadow-2xl text-center">
        <button onclick="closeQrModal()" class="absolute top-5 right-5 text-white/40 hover:text-white text-xl">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <div class="w-14 h-14 mx-auto rounded-2xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-3xl mb-3 shadow-lg shadow-emerald-950/40">
            <i class="fa-brands fa-whatsapp"></i>
        </div>

        <h3 class="font-display font-black text-xl text-white">Hubungkan WhatsApp Pengurus</h3>
        <p class="text-xs text-white/50 mt-1 mb-5">
            Buka WhatsApp di HP Anda ➔ Perangkat Tertaut ➔ Tautkan Perangkat.
        </p>

        {{-- Simulated QR Code for Baileys Pairing --}}
        <div class="w-56 h-56 mx-auto p-3 bg-white rounded-2xl shadow-xl flex items-center justify-center border-2 border-emerald-500">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&margin=5&data=SMECK_HOOLIGAN_WA_BOT_AUTH_PAIRING_SESSION_TOKEN_1950"
                alt="QR Code WhatsApp" class="w-full h-full object-contain">
        </div>

        <div class="mt-4 p-3 rounded-xl bg-white/5 border border-white/10 text-left text-xs text-white/70 space-y-1">
            <div class="font-bold text-emerald-400 flex items-center gap-1.5">
                <i class="fa-solid fa-circle-check"></i>
                <span>Status Siap Pairing</span>
            </div>
            <p class="text-[11px] text-white/50">
                Setelah di-scan, nomor Anda akan otomatis tersambung dan siap membalas chat suporter 24 jam.
            </p>
        </div>

        <button type="button" onclick="closeQrModal(); alert('Koneksi WhatsApp Bot berhasil diverifikasi!');"
            class="mt-5 w-full py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-lg transition-all cursor-pointer">
            Saya Sudah Scan Barcode di HP
        </button>
    </div>
</div>

<script>
    // Submit Chat Simulator Message
    function submitChatMessage() {
        const input = document.getElementById('simMessageInput');
        const text = input.value.trim();
        if (!text) return;

        appendUserBubble(text);
        input.value = '';

        // Call Backend API
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

    // Modal QR
    function openQrModal() {
        const modal = document.getElementById('modalQrConnect');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeQrModal() {
        const modal = document.getElementById('modalQrConnect');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Direct WA Templates
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

        const url = `https://wa.me/${phone}?text=${encodeURIComponent(message)}`;
        window.open(url, '_blank');
    }
</script>
@endsection
