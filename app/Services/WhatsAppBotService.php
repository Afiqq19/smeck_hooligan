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
        // Bot HANYA merespon jika dipanggil dengan: "@admin", "admin", "@bot", "bot", tag kontak (@nomor), ATAU perintah resmi suporter ("cek kta", "jadwal", "klasemen", "menu", dll).
        if ($isGroup) {
            $isBotTriggered = false;
            $cleanText = $text;

            // 1. Panggilan langsung dengan tag @admin, admin, @bot, bot, @smeck, @min, min, !bot, dll.
            if (preg_match('/^(@admin|admin|@bot|bot|@smeck|smeck|@min|min|!bot|\/bot|#bot|\.bot)\b\s*(.*)$/i', $text, $matches)) {
                $isBotTriggered = true;
                $cleanText = trim($matches[2]);
            }
            // 2. Tag nomor HP bot WhatsApp (misal: @6283140435796 [perintah])
            elseif (preg_match('/^@\d+\b\s*(.*)$/i', $text, $matches)) {
                $isBotTriggered = true;
                $cleanText = trim($matches[1]);
            }
            // 3. Perintah ber-prefix (!menu, !jadwal, !cek, dll)
            elseif (preg_match('/^(!|\/|#|\.)(menu|bantuan|help|jadwal|klasemen|cek|kta|daftar|chant)\b\s*(.*)$/i', $text, $matches)) {
                $isBotTriggered = true;
                $cleanText = trim($matches[2] . ' ' . $matches[3]);
            }
            // 4. Perintah resmi suporter langsung tanpa prefix di grup
            elseif (preg_match('/^(cek\s+kta|cek\b|kta\b|jadwal|klasemen|daftar|chant|anthem|lagu|menu|bantuan|help|info|panduan|[1-6]$|smc-|6032)/i', $text)) {
                $isBotTriggered = true;
                $cleanText = $text;
            }

            // Jika di grup orang mengobrol biasa tanpa memanggil bot atau perintah resmi, BOT WAJIB DIAM!
            if (! $isBotTriggered) {
                return null;
            }

            // Jika di grup hanya memanggil "@admin", "admin", "@bot", "bot" tanpa perintah lanjutan:
            if (empty($cleanText)) {
                return "🤖 *SIAP SAUDARAKU! ADMIN BOT SMECK HOOLIGAN HADIR!* 💚\n\n" .
                    "Ada yang bisa kami bantu untuk suporter PSMS Medan?\n" .
                    "Silakan gunakan perintah berikut di grup:\n" .
                    "• *cek kta nama/nomor* ➔ Cek keaslian & status KTA suporter\n" .
                    "• *jadwal* ➔ Cek jadwal laga terdekat PSMS Medan\n" .
                    "• *klasemen* ➔ Cek klasemen sementara Liga 2\n" .
                    "• *chant* ➔ Lirik lagu anthem tribun\n" .
                    "• *daftar* ➔ Registrasi anggota baru & KTA resmi\n" .
                    "• *menu* ➔ Panduan lengkap seluruh fitur\n\n" .
                    "💡 *Contoh:* `@admin cek kta syafiq` atau langsung ketik `cek kta syafiq`! 🐓";
            }

            // Lanjutkan pemrosesan menggunakan teks yang sudah dibersihkan
            $text = $cleanText;
            $upper = strtoupper($cleanText);
        }

        // =========================================================================
        // ATURAN 2: CHAT PRIBADI (JAPRI)
        // =========================================================================
        if (! $isGroup) {
            // Bersihkan jika diawali "@admin", "admin", "@bot", "bot", atau "!bot"
            if (preg_match('/^(@admin|admin|@bot|bot|!bot|\/bot)\s+(.*)$/i', $text, $matches)) {
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

        // 2. Salam Pembuka Suporter (Khusus jika menyapa bot / admin)
        if (in_array($upper, ['P', 'HALO', 'HAI', 'ASSALAMUALAIKUM', 'SALAM SADA ROHA', 'TES', 'TEST', 'PING', '@ADMIN', 'ADMIN', '@BOT', 'BOT', '@MIN', 'MIN'])) {
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
            return "⚠️ Perintah tidak dikenali di grup. Ketik *menu* untuk melihat panduan lengkap bot SMeCK Hooligan.";
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
            "👉 Ketik: *CEK KTA [Nomor KTA / NIA / Nama]* _(tanpa tanda kurung)_\n\n" .
            "💡 *CONTOH PENGETIKAN LANGSUNG:*\n" .
            "• `cek kta syafiq` ➔ Cek via Nama Anggota\n" .
            "• `cek kta 1950` ➔ Cukup 4 angka belakang nomor KTA\n" .
            "• `cek kta SMC-2024-001` ➔ Cek via Nomor Induk Anggota (NIA)\n" .
            "• `cek kta 6032-9800-2024-1950` ➔ Nomor kartu fisik lengkap\n\n" .
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
        // Bersihkan tanda kurung sudut (< >), kurung siku, dan tanda kutip yang sering diketik user
        $rawQuery = trim($rawQuery, "<>[](){}\"' \t\n\r\0\x0B");
        $rawQuery = preg_replace('/[<>\[\](){}\"\']/', '', $rawQuery);
        $rawQuery = trim($rawQuery);

        if (empty($rawQuery) || in_array(strtoupper($rawQuery), ['KTA', 'CEK', 'CEK KTA', 'NOMOR/NAMA', '<NOMOR/NAMA>', 'NOMOR', 'NAMA'])) {
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
            // Pencarian string nama lengkap terlebih dahulu
            $user = User::where('name', 'like', "%{$rawQuery}%")
                ->with(['profile', 'chapter', 'nfcCard'])
                ->first();

            // Jika belum ketemu, cari berdasarkan token kata nama (contoh: "MHD syafiq" -> mencari kata "syafiq")
            if (! $user) {
                $words = preg_split('/\s+/', $rawQuery);
                $keywords = array_filter($words, function ($w) {
                    $clean = strtolower(trim($w, "."));
                    return strlen($clean) >= 3 && !in_array($clean, ['mhd', 'muh', 'mohd', 'bin', 'binti', 'dan', 'smeck', 'kta']);
                });

                if (! empty($keywords)) {
                    $user = User::where(function ($q) use ($keywords) {
                        foreach ($keywords as $kw) {
                            $q->orWhere('name', 'like', "%{$kw}%");
                        }
                    })->with(['profile', 'chapter', 'nfcCard'])->first();
                }
            }

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
            $reason = ! empty($user->suspension_reason)
                ? $user->suspension_reason
                : 'Pelanggaran Kode Etik Organisasi & Tata Tertib Tribun SMeCK Hooligan';

            return "🚨 *PERINGATAN SANKSI: KTA DITANGGUHKAN (SUSPENDED)*\n\n" .
                "• *Nama Suporter:* {$user->name}\n" .
                "• *Nomor NIA:* " . ($profile?->nia ?? '-') . "\n" .
                "• *Basis Chapter:* " . ($user->chapter?->name ?? 'DPP SMeCK Hooligan') . "\n" .
                "• *Status Keanggotaan:* ❌ DIBEKUKAN / SUSPENDED\n" .
                "• *Alasan Sanksi:* ⚠️ {$reason}\n\n" .
                "⚠️ *PERHATIAN:* Akun ini sedang dalam masa sanksi penangguhan organisasi DPP SMeCK Hooligan. Hak akses tribun suporter, diskon tiket, dan kartu KTA chip NFC *TIDAK BERLAKU* sementara. Silakan hubungi Korlap Basis Anda untuk penyelesaian sanksi.";
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
            "👉 Ketik: *cek kta [nama atau nomor]* _(tanpa kurung)_\n" .
            "💡 *Contoh Langsung:*\n" .
            "   • `cek kta syafiq`\n" .
            "   • `cek kta 1950`\n" .
            "   • `cek kta SMC-2024-001`\n\n" .
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
