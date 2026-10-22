import makeWASocket, {
    DisconnectReason,
    useMultiFileAuthState,
    fetchLatestBaileysVersion,
    jidNormalizedUser,
    delay
} from '@whiskeysockets/baileys';
import pino from 'pino';
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';
import express from 'express';
import cors from 'cors';
import qrcode from 'qrcode';
import qrcodeTerminal from 'qrcode-terminal';
import axios from 'axios';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// Konfigurasi Environment & Paths
const PORT = process.env.PORT || 3000;
const WEBHOOK_URL = process.env.WEBHOOK_URL || 'https://projek2.xie.my.id/api/whatsapp/webhook';
const LOCAL_WEBHOOK_URL = 'http://127.0.0.1:8000/api/whatsapp/webhook';
const AUTH_DIR = path.join(__dirname, 'session_auth');
const LARAVEL_STORAGE_DIR = path.join(__dirname, '..', 'storage', 'app', 'whatsapp');
const LARAVEL_PUBLIC_DIR = path.join(__dirname, '..', 'public', 'storage');

// Pastikan folder penyimpanan ada
if (!fs.existsSync(AUTH_DIR)) fs.mkdirSync(AUTH_DIR, { recursive: true });
if (!fs.existsSync(LARAVEL_STORAGE_DIR)) fs.mkdirSync(LARAVEL_STORAGE_DIR, { recursive: true });
if (!fs.existsSync(LARAVEL_PUBLIC_DIR)) fs.mkdirSync(LARAVEL_PUBLIC_DIR, { recursive: true });

// State Bot Global
let sock = null;
let currentStatus = 'disconnected'; // 'disconnected' | 'connecting' | 'qr_ready' | 'connected'
let rawQrCode = null;
let qrDataUrl = null;
let connectedUser = {
    phone: null,
    name: null,
    connectedAt: null,
};
let isPairingCodeRequested = false;

function persistState() {
    const statePayload = {
        status: currentStatus,
        user: connectedUser,
        qr: qrDataUrl,
        rawQr: rawQrCode,
        updatedAt: new Date().toISOString(),
    };

    try {
        fs.writeFileSync(path.join(LARAVEL_STORAGE_DIR, 'session.json'), JSON.stringify(statePayload, null, 2));
    } catch (err) {
        console.error('Gagal menulis session.json:', err.message);
    }

    // Push update ke server Laravel live dan local
    axios.post('https://projek2.xie.my.id/api/whatsapp/status-update', statePayload, { timeout: 4000 }).catch(() => {});
    axios.post('http://127.0.0.1:8000/api/whatsapp/status-update', statePayload, { timeout: 1500 }).catch(() => {});
}

// Inisialisasi Koneksi Baileys
async function startWhatsApp() {
    console.log('\n=============================================================');
    console.log('🟢 [SMeCK HOOLIGAN] MEMULAI GATEWAY WHATSAPP REAL BAILEYS...');
    console.log('=============================================================\n');

    currentStatus = 'connecting';
    persistState();

    const { state, saveCreds } = await useMultiFileAuthState(AUTH_DIR);
    const { version, isLatest } = await fetchLatestBaileysVersion();
    console.log(`Menggunakan WA Web Protocol Versi: ${version.join('.')} (Latest: ${isLatest})`);

    sock = makeWASocket({
        version,
        logger: pino({ level: 'silent' }), // Heningkan log internal agar terminal bersih
        printQRInTerminal: false,
        auth: state,
        browser: ['SMeCK Hooligan Official', 'Chrome', '120.0.0.0'],
        syncFullHistory: false,
        markOnlineOnConnect: true,
        generateHighQualityLinkPreview: true,
    });

    // Simpan credentials setiap ada perubahan kunci auth
    sock.ev.on('creds.update', saveCreds);

    // Monitoring Perubahan Status Koneksi & Event QR
    sock.ev.on('connection.update', async (update) => {
        const { connection, lastDisconnect, qr } = update;

        if (qr) {
            rawQrCode = qr;
            currentStatus = 'qr_ready';

            // 1. Cetak QR di terminal console
            console.log('\n📱 SCAN KODE QR DI BAWAH DENGAN WHATSAPP ANDA:');
            console.log('👉 WhatsApp -> Titik Tiga / Pengaturan -> Perangkat Tertaut -> Tautkan Perangkat\n');
            qrcodeTerminal.generate(qr, { small: true });

            // 2. Render QR ke Data URL Base64 dan File PNG untuk Web Dashboard
            try {
                qrDataUrl = await qrcode.toDataURL(qr, { margin: 2, scale: 8 });
                const qrBuffer = await qrcode.toBuffer(qr, { margin: 2, scale: 8 });
                fs.writeFileSync(path.join(LARAVEL_PUBLIC_DIR, 'whatsapp-qr.png'), qrBuffer);
                fs.writeFileSync(path.join(LARAVEL_STORAGE_DIR, 'whatsapp-qr.png'), qrBuffer);
            } catch (err) {
                console.error('Gagal generate QR image:', err.message);
            }

            persistState();
        }

        if (connection === 'close') {
            const statusCode = lastDisconnect?.error?.output?.statusCode;
            const shouldReconnect = statusCode !== DisconnectReason.loggedOut;

            console.log(`⚠️ Koneksi WhatsApp terputus. Alasan: ${statusCode} | Reconnect: ${shouldReconnect}`);

            currentStatus = 'disconnected';
            qrDataUrl = null;
            rawQrCode = null;
            connectedUser = { phone: null, name: null, connectedAt: null };
            persistState();

            if (shouldReconnect) {
                console.log('🔄 Mencoba menghubungkan ulang ke WhatsApp dalam 3 detik...');
                setTimeout(startWhatsApp, 3000);
            } else {
                console.log('🛑 Sesi Logout terdeteksi. Menghapus data sesi lama...');
                fs.rmSync(AUTH_DIR, { recursive: true, force: true });
                fs.mkdirSync(AUTH_DIR, { recursive: true });
                setTimeout(startWhatsApp, 3000);
            }
        } else if (connection === 'open') {
            const userJid = sock.user?.id ? jidNormalizedUser(sock.user.id) : 'Unknown';
            const userPhone = userJid.replace('@s.whatsapp.net', '');
            const userName = sock.user?.name || 'Admin SMeCK';

            currentStatus = 'connected';
            qrDataUrl = null;
            rawQrCode = null;
            connectedUser = {
                phone: userPhone,
                name: userName,
                connectedAt: new Date().toISOString(),
            };
            persistState();

            console.log('\n=============================================================');
            console.log(`✅ [BERHASIL TERHUBUNG] WHATSAPP RESMI SMECK HOOLIGAN AKTIF!`);
            console.log(`📱 Nomor WhatsApp: +${userPhone}`);
            console.log(`👤 Nama Akun: ${userName}`);
            console.log(`🤖 Bot siap membalas chat suporter secara otomatis 24 Jam!`);
            console.log('=============================================================\n');
        }
    });

    // Handler Pesan Masuk (Auto-Responder Otomatis)
    sock.ev.on('messages.upsert', async ({ messages, type }) => {
        if (type !== 'notify') return;

        for (const m of messages) {
            // Abaikan status broadcast atau pesan kosong
            if (!m.message || m.key.remoteJid === 'status@broadcast') continue;

            // Abaikan pesan yang dikirim oleh bot itu sendiri
            if (m.key.fromMe) continue;

            const remoteJid = m.key.remoteJid;
            const isGroup = remoteJid.endsWith('@g.us');
            const senderPhone = remoteJid.replace('@s.whatsapp.net', '').replace('@g.us', '');

            // Ekstrak teks pesan
            const messageText = (
                m.message?.conversation ||
                m.message?.extendedTextMessage?.text ||
                m.message?.imageMessage?.caption ||
                m.message?.buttonsResponseMessage?.selectedButtonId ||
                m.message?.templateButtonReplyMessage?.selectedId ||
                ''
            ).trim();

            if (!messageText) continue;

            console.log(`\n📩 [PESAN MASUK] Dari: ${senderPhone} (${isGroup ? 'GRUP' : 'PRIBADI'}): "${messageText}"`);

            // Kirim pesan ke API Webhook Laravel (dukung live & local)
            let replyText = null;
            try {
                // Coba live server terlebih dahulu
                const response = await axios.post(WEBHOOK_URL, {
                    message: messageText,
                    sender: senderPhone,
                    is_group: isGroup,
                    push_name: m.pushName || 'Suporter',
                }, { timeout: 8000 });

                if (response.data && response.data.reply) {
                    replyText = response.data.reply;
                }
            } catch (errLive) {
                // Fallback ke server lokal jika live offline / unreachable
                try {
                    const fallbackRes = await axios.post(LOCAL_WEBHOOK_URL, {
                        message: messageText,
                        sender: senderPhone,
                        is_group: isGroup,
                        push_name: m.pushName || 'Suporter',
                    }, { timeout: 5000 });
                    if (fallbackRes.data && fallbackRes.data.reply) {
                        replyText = fallbackRes.data.reply;
                    }
                } catch (errLocal) {
                    console.error('❌ Gagal memanggil webhook Laravel:', errLive.message, '| Local:', errLocal.message);
                }
            }

            // Jika ada balasan otomatis dari sistem SMeCK Hooligan
            if (replyText) {
                console.log(`🤖 [BOT MEMBALAS OTOMATIS]:\n${replyText.substring(0, 100)}...`);

                try {
                    // Indikator "Sedang mengetik..." layaknya manusia
                    await sock.sendPresenceUpdate('composing', remoteJid);
                    await delay(800);

                    // Kirim balasan dengan quote ke pesan suporter
                    await sock.sendMessage(remoteJid, {
                        text: replyText
                    }, { quoted: m });

                    await sock.sendPresenceUpdate('paused', remoteJid);
                    console.log(`✅ Balasan terkirim ke ${senderPhone}!`);
                } catch (sendErr) {
                    console.error(`❌ Gagal mengirim balasan ke ${senderPhone}:`, sendErr.message);
                }
            }
        }
    });
}

// =========================================================================
// EXPRESS HTTP SERVER UNTUK DASHBOARD LARAVEL (Port 3000)
// =========================================================================
const app = express();
app.use(cors());
app.use(express.json());

// 1. Status Bot & Data QR Code
app.get('/api/status', (req, res) => {
    res.json({
        status: currentStatus,
        user: connectedUser,
        qr: qrDataUrl,
        hasSession: fs.existsSync(path.join(AUTH_DIR, 'creds.json')),
        updatedAt: new Date().toISOString(),
    });
});

// 2. Request Pairing Code (Alternatif Cerdas tanpa Scan QR Kamera)
app.post('/api/pairing-code', async (req, res) => {
    let { phone } = req.body;
    if (!phone) {
        return res.status(400).json({ success: false, message: 'Nomor WhatsApp wajib diisi.' });
    }

    // Format nomor WhatsApp standar internasional (contoh 0812 -> 62812)
    phone = phone.replace(/[^0-9]/g, '');
    if (phone.startsWith('0')) {
        phone = '62' + phone.substring(1);
    }

    if (!sock) {
        return res.status(500).json({ success: false, message: 'Socket WhatsApp belum siap.' });
    }

    try {
        console.log(`🔑 Meminta Pairing Code untuk nomor: ${phone}...`);
        const code = await sock.requestPairingCode(phone);
        // Format kode agar rapi ABCD-1234
        const formattedCode = code?.match(/.{1,4}/g)?.join('-') || code;
        console.log(`✅ KODE PAIRING WHATSAPP: ${formattedCode}`);

        return res.json({
            success: true,
            code: formattedCode,
            phone: phone,
            message: `Masukkan kode ${formattedCode} di WhatsApp Anda (Tautkan Perangkat > Tautkan dengan nomor telepon).`
        });
    } catch (err) {
        console.error('Gagal generate pairing code:', err.message);
        return res.status(500).json({
            success: false,
            message: 'Gagal membuat kode pairing: ' + err.message
        });
    }
});

// 3. API Kirim Pesan Langsung dari Laravel
app.post('/api/send-message', async (req, res) => {
    let { phone, message } = req.body;
    if (!phone || !message) {
        return res.status(400).json({ success: false, message: 'Nomor dan pesan wajib diisi.' });
    }

    phone = phone.replace(/[^0-9]/g, '');
    if (phone.startsWith('0')) phone = '62' + phone.substring(1);
    const jid = `${phone}@s.whatsapp.net`;

    if (currentStatus !== 'connected' || !sock) {
        return res.status(400).json({ success: false, message: 'Bot WhatsApp belum terhubung.' });
    }

    try {
        await sock.sendMessage(jid, { text: message });
        return res.json({ success: true, message: `Pesan berhasil dikirim ke +${phone}` });
    } catch (err) {
        return res.status(500).json({ success: false, message: err.message });
    }
});

// 4. API Logout Sesi
app.post('/api/logout', async (req, res) => {
    try {
        if (sock) {
            await sock.logout();
        }
        fs.rmSync(AUTH_DIR, { recursive: true, force: true });
        fs.mkdirSync(AUTH_DIR, { recursive: true });
        currentStatus = 'disconnected';
        connectedUser = { phone: null, name: null, connectedAt: null };
        qrDataUrl = null;
        rawQrCode = null;
        persistState();

        setTimeout(startWhatsApp, 2000);
        return res.json({ success: true, message: 'Sesi WhatsApp berhasil diputuskan.' });
    } catch (err) {
        return res.status(500).json({ success: false, message: err.message });
    }
});

// Jalankan HTTP Server
app.listen(PORT, () => {
    console.log(`🌐 Server API Bot WhatsApp berjalan di http://localhost:${PORT}`);
});

// Mulai Baileys
startWhatsApp().catch((err) => {
    console.error('Fatal Error saat memulai Baileys:', err);
});
