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
     */
    public function handleIncomingMessage(string $senderNumber, string $incomingText): string
    {
        $text = trim($incomingText);
        $upper = strtoupper($text);

        // 1. Perintah Cek KTA
        if (str_starts_with($upper, 'CEK KTA') || str_starts_with($upper, 'KTA ') || str_starts_with($upper, 'CEK ')) {
            $query = trim(preg_replace('/^(CEK\s+KTA|KTA|CEK)\s+/i', '', $text));
            return $this->handleCheckKta($query);
        }

        // 2. Perintah Jadwal Pertandingan
        if (in_array($upper, ['JADWAL', 'JADWAL MATCH', 'PERTANDINGAN', 'MAIN', 'LAWAN'])) {
            return $this->handleSchedule();
        }

        // 3. Perintah Klasemen Liga 2
        if (in_array($upper, ['KLASEMEN', 'POIN', 'PERINGKAT', 'STANDINGS'])) {
            return $this->handleStandings();
        }

        // 4. Perintah Lagu & Chants Tribun
        if (in_array($upper, ['CHANT', 'CHANTS', 'LAGU', 'ANTHEM', 'LIRIK'])) {
            return $this->handleChants();
        }

        // 5. Perintah Pendaftaran Anggota Baru
        if (in_array($upper, ['DAFTAR', 'GABUNG', 'REGISTRASI', 'JOIN', 'ANGGOTA BARU'])) {
            return $this->handleRegister();
        }

        // 6. Perintah Bantuan / Menu Default
        return $this->handleHelp();
    }

    /**
     * Handler: Pengecekan KTA Suporter Resmi
     */
    public function handleCheckKta(string $query): string
    {
        if (empty($query)) {
            return "⚠️ *FORMAT CEK KTA KURANG LENGKAP*\n\nSilakan ketik:\n*CEK KTA <Nomor NIA / UID / NIK>*\n\nContoh:\n• `CEK KTA SMCK-2024-1950`\n• `CEK KTA 1271081206980005`";
        }

        $rawQuery = trim($query);
        $cleanQuery = str_replace([' ', '-', ':', '.'], '', strtolower($rawQuery));

        // 1. Cari via Nomor Kartu Fisik / UID NFC
        $card = NfcCard::where(function ($q) use ($rawQuery, $cleanQuery) {
            $q->whereRaw('LOWER(uid_number) = ?', [strtolower($rawQuery)])
                ->orWhereRaw('REPLACE(REPLACE(LOWER(uid_number), ":", ""), "-", "") = ?', [$cleanQuery])
                ->orWhereRaw('LOWER(card_number) = ?', [strtolower($rawQuery)])
                ->orWhereRaw('REPLACE(REPLACE(LOWER(card_number), " ", ""), "-", "") = ?', [$cleanQuery]);
        })->with(['user.profile', 'user.chapter'])->first();

        // 2. Cari via NIA
        if (! $card) {
            $profile = MemberProfile::whereRaw('LOWER(nia) = ?', [strtolower($rawQuery)])
                ->orWhereRaw('REPLACE(LOWER(nia), "-", "") = ?', [$cleanQuery])
                ->with(['user.chapter', 'user.nfcCard'])
                ->first();

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

        if (! isset($user) || ! $user) {
            return "❌ *KTA TIDAK TERDAFTAR*\n\nData suporter dengan nomor/kartu `{$rawQuery}` *TIDAK DITEMUKAN* dalam database resmi SMeCK Hooligan.\n\n⚠️ Waspada KTA palsu! Pastikan Anda mendaftar melalui portal resmi: https://projek2.xie.my.id/register";
        }

        // Cek apakah akun ditangguhkan / suspend
        $isSuspended = ($user->status === 'suspended') || ($card?->status === 'blocked');

        if ($isSuspended) {
            return "🚨 *PERINGATAN SANKSI DISIPLIN: KTA DITANGGUHKAN / SUSPENDED*\n\n" .
                "• *Nama Suporter:* {$user->name}\n" .
                "• *Nomor NIA:* " . ($profile?->nia ?? '-') . "\n" .
                "• *Basis Chapter:* " . ($user->chapter?->name ?? 'DPP SMeCK') . "\n" .
                "• *Status Keanggotaan:* ❌ DIBEKUKAN / SUSPENDED\n\n" .
                "⚠️ *Akun ini sedang dalam sanksi penangguhan organisasi! Hak akses tribun dan manfaat suporter TIDAK BERLAKU sementara.* Silakan hubungi Korlap Basis Anda.";
        }

        $displayCardNo = $card?->card_number ?? '6032-••••-••••-1950';

        return "🟢 *DATA KTA RESMI SMECK HOOLIGAN (VALID)* ✅\n\n" .
            "• *Nama Anggota:* {$user->name}\n" .
            "• *Nomor NIA:* " . ($profile?->nia ?? 'BELUM DITERBITKAN') . "\n" .
            "• *Basis / Wilayah:* " . ($user->chapter?->name ?? 'DPP SMeCK') . "\n" .
            "• *Golongan Darah:* " . ($profile?->blood_type ?? '-') . " (Siap Medis Tribun)\n" .
            "• *Nomor Kartu KTA:* {$displayCardNo}\n" .
            "• *Status KTA:* 🟢 AKTIF & BERLAKU RESMI\n" .
            "• *Tgl Bergabung:* " . ($profile?->joined_date ? $profile->joined_date->format('d M Y') : 'Aktif') . "\n\n" .
            "🔗 *Cek Online:* " . url('/api/check-kta?query=' . urlencode($profile?->nia ?? $user->name)) . "\n\n" .
            "Salam Sada Roha! 🐓⚽";
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
     * Handler: Bantuan & Menu Perintah
     */
    public function handleHelp(): string
    {
        return "🤖 *BOT RESMI SMECK HOOLIGAN (PSMS MEDAN)* 💚\n\n" .
            "Halo Saudaraku! Kirim pesan kata kunci berikut untuk mendapatkan informasi otomatis:\n\n" .
            "• *CEK KTA <nomor>* ➡️ Cek keaslian KTA & status aktif suporter\n" .
            "• *JADWAL* ➡️ Info laga terdekat PSMS & kuota tiket\n" .
            "• *KLASEMEN* ➡️ Update poin & zona promosi Liga 2 PT LIB\n" .
            "• *DAFTAR* ➡️ Tautan pendaftaran anggota suporter baru\n" .
            "• *CHANT* ➡️ Lirik lagu & anthem kebanggaan tribun\n\n" .
            "🌐 *Portal Resmi:* https://projek2.xie.my.id/\n" .
            "Salam Sada Roha!";
    }
}
