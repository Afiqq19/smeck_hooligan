<?php

namespace App\Services;

use App\Models\Chant;
use App\Models\LeagueStanding;
use App\Models\MatchTicket;
use App\Models\MemberProfile;
use App\Models\NfcCard;
use App\Models\User;

class WhatsAppBotService
{
    /**
     * Memproses pesan teks masuk dan mengembalikan balasan bot otomatis.
     * Menggunakan proteksi anti-spam cerdas untuk grup dan chat pribadi.
     */
    public function handleIncomingMessage(
        string $senderNumber,
        string $incomingText,
        bool $isGroup = false,
        ?string $groupJid = null
    ): ?string {
        $text = trim($incomingText);
        $upper = strtoupper($text);

        // =========================================================================
        // ATURAN 1: PROTEKSI GRUP WHATSAPP (isGroup === true)
        // =========================================================================
        // Di dalam grup, bot TIDAK BOLEH membalas obrolan biasa sesama anggota!
        // Bot HANYA merespon jika dipanggil dengan: "!bot", "bot", "/bot", atau tanda "!menu", "!jadwal", dll.
        if ($isGroup) {
            $isBotTriggered = false;
            $cleanText = $text;

            if (preg_match('/^(!bot|\/bot|#bot|\.bot|bot|!smeck|smeck)\b\s*(.*)$/i', $text, $matches)) {
                $isBotTriggered = true;
                $cleanText = trim($matches[2]);
            } elseif (preg_match('/^(!|\/|#|\.)(menu|bantuan|help|jadwal|klasemen|cek|kta|daftar|chant)\b\s*(.*)$/i', $text, $matches)) {
                $isBotTriggered = true;
                $cleanText = trim($matches[2] . ' ' . $matches[3]);
            }

            // Jika di grup orang mengobrol biasa tanpa memanggil kata 'bot' / tanda seru, BOT WAJIB DIAM!
            if (! $isBotTriggered) {
                return null;
            }

            // Jika di grup hanya mengetik "bot" atau "!bot" tanpa perintah lanjutan:
            if (empty($cleanText)) {
                return "🤖 *BOT SMECK HOOLIGAN SIAP!* 💚\n\n" .
                    "Halo Saudaraku! Gunakan perintah berikut di grup suporter:\n" .
                    "• `!jadwal` ➔ Cek laga terdekat PSMS\n" .
                    "• `!klasemen` ➔ Cek klasemen Liga 2\n" .
                    "• `!cek kta <nomor/nama>` ➔ Cek keaslian KTA\n" .
                    "• `!chant` ➔ Lirik lagu suporter\n" .
                    "• `!menu` ➔ Panduan lengkap bot";
            }

            // Lanjutkan pemrosesan menggunakan teks yang sudah dibersihkan dari kata 'bot'
            $text = $cleanText;
            $upper = strtoupper($cleanText);
        }

        // =========================================================================
        // ATURAN 2: CHAT PRIBADI (JAPRI)
        // =========================================================================
        if (! $isGroup) {
            // Bersihkan jika diawali "bot " atau "!bot "
            if (preg_match('/^(!bot|\/bot|bot)\s+(.*)$/i', $text, $matches)) {
                $text = trim($matches[2]);
                $upper = strtoupper($text);
            }
        }

        // =========================================================================
        // PEMROSESAN PERINTAH RESMI
        // =========================================================================

        // 1. Shortcut Angka Menu Utama (1 - 6)
        if ($upper === '1') {
            return $this->handleKtaTutorial();
        }
        if ($upper === '2') {
            return $this->handleSchedule();
        }
        if ($upper === '3') {
            return $this->handleStandings();
        }
        if ($upper === '4') {
            return $this->handleRegister();
        }
        if ($upper === '5') {
            return $this->handleChants();
        }
        if ($upper === '6' || in_array($upper, ['MENU', 'HELP', 'BANTUAN', 'INFO', 'PANDUAN', '!MENU', '!HELP'])) {
            return $this->handleHelp();
        }

        // 2. Salam Pembuka Suporter (Khusus jika menyapa bot)
        if (in_array($upper, ['P', 'HALO', 'HAI', 'ASSALAMUALAIKUM', 'SALAM SADA ROHA', 'TES', 'TEST', 'PING'])) {
            return $this->handleHelp();
        }

        // 3. Jika user mengetik "CEK KTA", "KTA", atau "CEK" tanpa nomor/nama
        if (in_array($upper, ['CEK KTA', 'KTA', 'CEK', 'CEK KTA DONG', 'CARA CEK KTA', 'CARA CEK', 'CEK KTA GIMANA', 'CEKKTA', '!CEK KTA'])) {
            return $this->handleKtaTutorial();
        }

        // 4. Perintah Cek KTA dengan parameter (Contoh: "CEK KTA 1950", "KTA SMC-2024-001", "CEK Bani Ginting")
        if (preg_match('/^(!?CEK\s+KTA|!?KTA|!?CEK)\s+(.+)$/i', $text, $matches)) {
            $query = trim($matches[2]);
            return $this->handleCheckKta($query);
        }

        // 5. Perintah Jadwal Pertandingan
        if (in_array($upper, ['JADWAL', 'JADWAL MATCH', 'PERTANDINGAN', 'MAIN', 'LAWAN', 'JADWAL PSMS', 'KAPAN MAIN', '!JADWAL'])) {
            return $this->handleSchedule();
        }

        // 6. Perintah Klasemen Liga 2 (PT LIB)
        if (in_array($upper, ['KLASEMEN', 'POIN', 'PERINGKAT', 'STANDINGS', 'KLASEMEN LIGA 2', 'KLASEMEN PSMS', 'KLASEMEN SEMENTARA', '!KLASEMEN'])) {
            return $this->handleStandings();
        }

        // 7. Perintah Lagu & Chants Tribun
        if (in_array($upper, ['CHANT', 'CHANTS', 'LAGU', 'ANTHEM', 'LIRIK', 'LIRIK LAGU', 'SALAM SADA ROHA', '!CHANT'])) {
            return $this->handleChants();
        }

        // 8. Perintah Pendaftaran Anggota Baru
        if (in_array($upper, ['DAFTAR', 'GABUNG', 'REGISTRASI', 'JOIN', 'ANGGOTA BARU', 'CARA DAFTAR', 'GABUNG SUPORTER', '!DAFTAR'])) {
            return $this->handleRegister();
        }

        // 9. Cek apakah ada nomor KTA yang diketik langsung (misal: 6032-9800-2024-1950 atau SMC-2024-001)
        if (preg_match('/^(SMC|6032|\d{4,16})/i', $text)) {
            return $this->handleCheckKta($text);
        }

        // =========================================================================
        // JIKA BUKAN PERINTAH RESMI BOT:
        // =========================================================================
        // Jika orang hanya ngobrol biasa ("makan yuk", "lagi di mana?", "oke", "siap", "wkwk"):
        // BOT WAJIB DIAM! Jangan ikut campur agar tidak mengganggu obrolan pribadi/grup.
        if ($isGroup) {
            return "⚠️ Perintah tidak dikenali di grup. Ketik `!menu` untuk melihat daftar perintah resmi bot.";
        }

        return null;
    }

    /**
     * Handler: Tutorial & Panduan Lengkap Cara Cek KTA
     */
    public function handleKtaTutorial(): string
    {
        return "🔍 *PANDUAN LENGKAP CARA CEK KTA SUPORTER* 💚\n\n" .
            "Untuk mengecek keaslian, status aktif/suspend, dan data anggota resmi SMeCK Hooligan, formatnya sangat mudah:\n\n" .
            "👉 Ketik: *CEK KTA <Nomor KTA / NIA / Nama>*\n\n" .
            "💡 *CONTOH PENGETIKAN (Bisa Dicoba Langsung):*\n" .
            "• `CEK KTA 1950` ➔ Cukup 4 angka belakang kartu KTA\n" .
            "• `CEK KTA SMC-2024-001` ➔ Cek via Nomor Induk Anggota (NIA)\n" .
            "• `CEK KTA 6032-9800-2024-1950` ➔ Nomor kartu fisik lengkap\n" .
            "• `CEK KTA Bani Ginting` ➔ Cek via Nama Suporter\n\n" .
            "✅ *Data yang akan tampil:*\n" .
            "- Nama Lengkap Anggota & Chapter Domisili\n" .
            "- Status KTA (🟢 Aktif / 🚨 Suspended)\n" .
            "- Golongan Darah (Untuk Data Medis di Tribun)\n\n" .
            "Silakan ketik sekarang dengan format di atas, Saudaraku! 🐓";
    }

    /**
     * Handler: Pengecekan KTA Suporter Resmi
     */
    public function handleCheckKta(string $query): string
    {
        $rawQuery = trim($query);

        if (empty($rawQuery) || in_array(strtoupper($rawQuery), ['KTA', 'CEK', 'CEK KTA'])) {
            return $this->handleKtaTutorial();
        }

        $cleanQuery = str_replace([' ', '-', ':', '.'], '', strtolower($rawQuery));

        // 1. Cari via Nomor Kartu Fisik / UID NFC / Partial 4 Digit
        $card = NfcCard::where(function ($q) use ($rawQuery, $cleanQuery) {
            $q->whereRaw('LOWER(uid_number) = ?', [strtolower($rawQuery)])
                ->orWhereRaw('REPLACE(REPLACE(LOWER(uid_number), ":", ""), "-", "") = ?', [$cleanQuery])
                ->orWhereRaw('LOWER(card_number) = ?', [strtolower($rawQuery)])
                ->orWhereRaw('REPLACE(REPLACE(LOWER(card_number), " ", ""), "-", "") = ?', [$cleanQuery])
                ->orWhere('card_number', 'like', "%{$rawQuery}%");
        })->with(['user.profile', 'user.chapter'])->first();

        // 2. Cari via NIA (Nomor Induk Anggota)
        if (! $card) {
            $profile = MemberProfile::where(function ($q) use ($rawQuery, $cleanQuery) {
                $q->whereRaw('LOWER(nia) = ?', [strtolower($rawQuery)])
                    ->orWhereRaw('REPLACE(LOWER(nia), "-", "") = ?', [$cleanQuery])
                    ->orWhere('nia', 'like', "%{$rawQuery}%");
            })->with(['user.chapter', 'user.nfcCard'])->first();

            if ($profile && $profile->user) {
                $user = $profile->user;
                $card = $user->nfcCard;
            }
        } else {
            $user = $card->user;
            $profile = $user?->profile;
        }

        // 3. Cari via NIK KTP
        if (! isset($user) || ! $user) {
            $profile = MemberProfile::where('nik_ktp', $rawQuery)
                ->with(['user.chapter', 'user.nfcCard'])
                ->first();

            if ($profile && $profile->user) {
                $user = $profile->user;
                $card = $user->nfcCard;
            }
        }

        // 4. Cari via Nama Anggota (User::where('name', 'like', ...))
        if (! isset($user) || ! $user) {
            $user = User::where('name', 'like', "%{$rawQuery}%")
                ->with(['profile', 'chapter', 'nfcCard'])
                ->first();

            if ($user) {
                $profile = $user->profile;
                $card = $user->nfcCard;
            }
        }

        // Jika tidak ditemukan di database
        if (! isset($user) || ! $user) {
            return "❌ *KTA TIDAK TERDAFTAR*\n\n" .
                "Data suporter dengan kata kunci `{$rawQuery}` *TIDAK DITEMUKAN* dalam database resmi SMeCK Hooligan.\n\n" .
                "💡 *Contoh format yang benar:*\n" .
                "👉 `CEK KTA 1950`\n" .
                "👉 `CEK KTA SMC-2024-001`\n\n" .
                "⚠️ *Waspada KTA palsu!* Pastikan Anda mendaftar melalui portal resmi:\n" .
                "https://projek2.xie.my.id/register";
        }

        // Cek apakah akun ditangguhkan / suspend
        $isSuspended = ($user->status === 'suspended') || ($card?->status === 'blocked');

        if ($isSuspended) {
            return "🚨 *PERINGATAN SANKSI: KTA DITANGGUHKAN (SUSPENDED)*\n\n" .
                "• *Nama Suporter:* {$user->name}\n" .
                "• *Nomor NIA:* " . ($profile?->nia ?? '-') . "\n" .
                "• *Basis Chapter:* " . ($user->chapter?->name ?? 'DPP SMeCK Hooligan') . "\n" .
                "• *Status Keanggotaan:* ❌ DIBEKUKAN / SUSPENDED\n\n" .
                "⚠️ *PERHATIAN:* Akun ini sedang dalam sanksi penangguhan organisasi DPP SMeCK Hooligan. Hak akses tribun suporter, diskon tiket, dan kartu NFC *TIDAK BERLAKU* sementara. Silakan hubungi Korlap Basis Anda untuk penyelesaian sanksi.";
        }

        $displayCardNo = $card?->card_number ?? '6032-••••-••••-1950';

        return "🟢 *DATA KTA RESMI SMECK HOOLIGAN (VALID)* ✅\n\n" .
            "• *Nama Anggota:* {$user->name}\n" .
            "• *Nomor NIA:* " . ($profile?->nia ?? 'TERDAFTAR') . "\n" .
            "• *Basis / Wilayah:* " . ($user->chapter?->name ?? 'DPP SMeCK Hooligan') . "\n" .
            "• *Golongan Darah:* " . ($profile?->blood_type ?? '-') . " (Siap Medis Tribun)\n" .
            "• *Nomor Kartu KTA:* {$displayCardNo}\n" .
            "• *Status Kartu NFC:* 🟢 AKTIF & SAH DIGUNAKAN\n" .
            "• *Tgl Bergabung:* " . ($profile?->joined_date ? $profile->joined_date->format('d M Y') : 'Anggota Aktif') . "\n\n" .
            "Salam Sada Roha! Ayam Kinantan Selamanya! 🐓💚";
    }

    /**
     * Handler: Jadwal Pertandingan PSMS Medan
     */
    public function handleSchedule(): string
    {
        $match = MatchTicket::where('status', 'open')->orderBy('match_time', 'asc')->first();

        if (! $match) {
            return "📅 *JADWAL PERTANDINGAN PSMS MEDAN*\n\nSaat ini belum ada jadwal pertandingan resmi yang dibuka untuk tiket.\n\nPantau terus update laga terbaru di portal resmi:\nhttps://projek2.xie.my.id/";
        }

        $typeLabel = $match->type === 'away' ? '🚌 TUR TANDANG (AWAYDAYS)' : '🏟️ LAGA KANDANG (HOME)';
        $dateFormatted = $match->match_time ? $match->match_time->translatedFormat('l, d F Y - H:i') : '-';

        return "📅 *JADWAL RESMI LAGA PSMS MEDAN*\n\n" .
            "⚽ *{$match->match_title}*\n" .
            "📌 *Kategori:* {$typeLabel}\n" .
            "🏟️ *Stadion:* {$match->stadium}\n" .
            "⏰ *Waktu:* {$dateFormatted} WIB\n" .
            "🎟️ *Harga Tiket Anggota:* Rp " . number_format($match->price, 0, ',', '.') . "\n" .
            "📊 *Sisa Kuota:* {$match->remaining_quota} Tiket Tersedia\n\n" .
            "👉 Pesan tiket resmi sekarang lewat Portal Suporter:\n" .
            "https://projek2.xie.my.id/login\n\n" .
            "Salam Sada Roha! Ayam Kinantan Selamanya! 🐓💚";
    }

    /**
     * Handler: Klasemen Resmi Liga 2 PT LIB
     */
    public function handleStandings(): string
    {
        $standings = LeagueStanding::orderBy('position')->get();
        $psms = $standings->firstWhere('is_our_team', true);

        if ($standings->isEmpty() || ! $psms) {
            return "📊 *KLASEMEN LIGA 2 INDONESIA (GRUP BARAT)*\n\nData klasemen sedang disinkronkan langsung dari server PT LIB.\nSilakan pantau live di: https://projek2.xie.my.id/";
        }

        $zone = match ($psms->position) {
            1 => '🏆 Juara Grup (Lolos Langsung Promosi Liga 1)',
            2 => '⚡ Peringkat 2 (Lolos Play-Off Promosi vs Grup Lain)',
            default => "Peringkat #{$psms->position} Wilayah Barat",
        };

        $msg = "📊 *KLASEMEN SEMENTARA LIGA 2 (PT LIB)*\n\n" .
            "💚 *POSISI PSMS MEDAN:*\n" .
            "• Peringkat: *#{$psms->position}*\n" .
            "• Total Poin: *{$psms->points} Poin* ({$psms->played} Main)\n" .
            "• Rekor: {$psms->won} Menang | {$psms->drawn} Seri | {$psms->lost} Kalah\n" .
            "• Selisih Gol: {$psms->goal_difference} ({$psms->goals_for}:{$psms->goals_against})\n" .
            "• Status Zona: {$zone}\n\n" .
            "📋 *Top 3 Wilayah Barat:*\n";

        foreach ($standings->take(3) as $st) {
            $flag = $st->is_our_team ? '🟢' : '⚪';
            $msg .= "{$flag} #{$st->position} {$st->team_name} - {$st->points} Pts ({$st->played} M)\n";
        }

        $msg .= "\n🔗 Pantau klasemen lengkap 10 klub:\nhttps://projek2.xie.my.id/";

        return $msg;
    }

    /**
     * Handler: Chant & Lagu Suporter
     */
    public function handleChants(): string
    {
        $chant = Chant::where('is_featured', true)->first();

        if (! $chant) {
            return "🎵 *ANTHEM SMECK HOOLIGAN — SALAM SADA ROHA*\n\nSatu hati satu jiwa, bersatu untuk PSMS!\nTakkan pernah lelah kami bernyanyi di sini...\n\nSalam Sada Roha! 💚";
        }

        return "🎵 *PUSTAKA CHANT TRIBUN SMECK HOOLIGAN*\n\n" .
            "🔥 *{$chant->title}*\n" .
            "📌 *Kategori:* {$chant->category}\n\n" .
            "{$chant->lyrics}\n\n" .
            "Salam Sada Roha! Ribak Sude!";
    }

    /**
     * Handler: Registrasi Anggota Baru
     */
    public function handleRegister(): string
    {
        return "📝 *PENDAFTARAN ANGGOTA BARU SMECK HOOLIGAN*\n\n" .
            "Gabung bersama ribuan saudara suporter PSMS Medan se-Nusantara! Dapatkan KTA Digital resmi dan KTA Fisik chip NFC.\n\n" .
            "👉 *Daftar online di sini:*\n" .
            "https://projek2.xie.my.id/register\n\n" .
            "Syarat mudah:\n" .
            "1. Siapkan KTP (NIK 16 digit)\n" .
            "2. Email aktif untuk verifikasi 1-klik\n" .
            "3. Pilih Basis / Chapter domisili Anda\n\n" .
            "Salam Sada Roha! 🐓⚽";
    }

    /**
     * Handler: Bantuan & Menu Perintah Interaktif Lengkap dengan Contoh
     */
    public function handleHelp(): string
    {
        return "🤖 *LAYANAN BOT RESMI SMECK HOOLIGAN (PSMS MEDAN)* 💚\n" .
            "_(Satu Hati, Satu Jiwa — Salam Sada Roha!)_\n\n" .
            "Halo Saudaraku! Kami siap melayani suporter 24 Jam secara otomatis.\n" .
            "Ketik kata kunci atau cukup ketik *ANGKA (1 - 5)* untuk memilih menu:\n\n" .
            "━━━━━━━━━━━━━━━━━━━━━━━━━━\n" .
            "1️⃣ *CEK KEASLIAN KTA ANGGOTA*\n" .
            "Untuk mengecek kartu resmi, aktif/suspend, dan gol. darah:\n" .
            "👉 Ketik: *CEK KTA <Nomor KTA / NIA / Nama>*\n" .
            "💡 *Contoh Langsung:*\n" .
            "   • `CEK KTA 1950`\n" .
            "   • `CEK KTA SMC-2024-001`\n" .
            "   • `CEK KTA Bani Ginting`\n\n" .
            "━━━━━━━━━━━━━━━━━━━━━━━━━━\n" .
            "2️⃣ *JADWAL LAGA PSMS MEDAN*\n" .
            "Untuk melihat jadwal laga terdekat, stadion, dan tiket:\n" .
            "👉 Cukup ketik: *JADWAL* (atau ketik angka *2*)\n\n" .
            "━━━━━━━━━━━━━━━━━━━━━━━━━━\n" .
            "3️⃣ *KLASEMEN RESMI PT LIB*\n" .
            "Untuk update poin PSMS, selisih gol, dan zona promosi:\n" .
            "👉 Cukup ketik: *KLASEMEN* (atau ketik angka *3*)\n\n" .
            "━━━━━━━━━━━━━━━━━━━━━━━━━━\n" .
            "4️⃣ *PENDAFTARAN ANGGOTA BARU*\n" .
            "Untuk info syarat dan link registrasi suporter resmi:\n" .
            "👉 Cukup ketik: *DAFTAR* (atau ketik angka *4*)\n\n" .
            "━━━━━━━━━━━━━━━━━━━━━━━━━━\n" .
            "5️⃣ *CHANT & LIRIK TRIBUN*\n" .
            "Untuk mendapatkan lirik lagu anthem kebanggaan:\n" .
            "👉 Cukup ketik: *CHANT* (atau ketik angka *5*)\n\n" .
            "━━━━━━━━━━━━━━━━━━━━━━━━━━\n" .
            "❓ *MENU PANDUAN*\n" .
            "Untuk menampilkan panduan ini kapan saja:\n" .
            "👉 Ketik: *MENU* atau *BANTUAN* (atau ketik angka *6*)\n" .
            "━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n" .
            "🌐 *Portal Resmi:* https://projek2.xie.my.id/\n" .
            "Ayam Kinantan Selamanya! 🐓⚽";
    }
}
