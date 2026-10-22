<?php

namespace App\Http\Controllers\Chapter;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\ChapterDocumentation;
use App\Models\EventAttendance;
use App\Models\MatchTicket;
use App\Models\MemberProfile;
use App\Models\NfcCard;
use App\Models\TicketAllocation;
use App\Models\TicketOrder;
use App\Models\User;
use App\Models\VoluntaryDue;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ChapterDashboardController extends Controller
{
    /**
     * Dashboard Wilayah Chapter.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $chapterId = $user->chapter_id ?? 1;
        $chapter = Chapter::find($chapterId);

        $totalMembers = User::where('chapter_id', $chapterId)->where('role', 'member')->count();
        $pendingMembers = User::where('chapter_id', $chapterId)->where('status', 'pending_approval')->count();
        $totalKas = VoluntaryDue::where('chapter_id', $chapterId)->where('status', 'verified')->sum('amount');
        $pendingKas = VoluntaryDue::where('chapter_id', $chapterId)->where('status', 'pending')->sum('amount');
        $recentAttendances = EventAttendance::where('chapter_id', $chapterId)->with('user.profile')->latest()->take(6)->get();

        // Kesiapsiagaan Medis Rombongan Tribun Basis: Golongan Darah
        $memberIds = User::where('chapter_id', $chapterId)->pluck('id');
        $bloodTypeStats = [
            'A' => MemberProfile::whereIn('user_id', $memberIds)->where('blood_type', 'A')->count(),
            'B' => MemberProfile::whereIn('user_id', $memberIds)->where('blood_type', 'B')->count(),
            'AB' => MemberProfile::whereIn('user_id', $memberIds)->where('blood_type', 'AB')->count(),
            'O' => MemberProfile::whereIn('user_id', $memberIds)->where('blood_type', 'O')->count(),
        ];

        // Komposisi Ukuran Baju Jersey Kebanggaan Chapter (S - 3XL)
        $shirtSizeStats = [
            'S' => MemberProfile::whereIn('user_id', $memberIds)->where('shirt_size', 'S')->count(),
            'M' => MemberProfile::whereIn('user_id', $memberIds)->where('shirt_size', 'M')->count(),
            'L' => MemberProfile::whereIn('user_id', $memberIds)->where('shirt_size', 'L')->count(),
            'XL' => MemberProfile::whereIn('user_id', $memberIds)->where('shirt_size', 'XL')->count(),
            'XXL' => MemberProfile::whereIn('user_id', $memberIds)->where('shirt_size', 'XXL')->count(),
            '3XL' => MemberProfile::whereIn('user_id', $memberIds)->where('shirt_size', '3XL')->count(),
        ];

        // Alokasi Tiket Stadion dari DPP Pusat
        $allocations = TicketAllocation::where('chapter_id', $chapterId)->with('matchTicket')->latest()->get();

        return view('dashboard.chapter.index', compact(
            'chapter',
            'totalMembers',
            'pendingMembers',
            'totalKas',
            'pendingKas',
            'recentAttendances',
            'bloodTypeStats',
            'shirtSizeStats',
            'allocations'
        ));
    }

    /**
     * Verifikasi Pendaftaran Anggota Baru.
     */
    public function verifications(Request $request): View
    {
        $chapterId = $request->user()->chapter_id ?? 1;
        $pendingUsers = User::where('chapter_id', $chapterId)
            ->where('status', 'pending_approval')
            ->with('profile')
            ->latest()
            ->paginate(15);

        return view('dashboard.chapter.verifications', compact('pendingUsers'));
    }

    /**
     * Setujui / Ubah Status Anggota.
     */
    public function approveMember(Request $request, int $id): RedirectResponse
    {
        $user = User::with('nfcCard')->findOrFail($id);
        $user->update(['status' => 'active']);

        if ($user->nfcCard) {
            $user->nfcCard->update(['status' => 'active']);
        }

        // Pastikan punya NIA
        if ($user->profile && empty($user->profile->nia)) {
            $year = date('Y');
            $user->profile->update([
                'nia' => sprintf('SMCK-%s-%04d', $year, $user->id),
            ]);
        }

        return back()->with('success', "Anggota {$user->name} berhasil diverifikasi & diaktifkan status keanggotaannya!");
    }

    /**
     * Tolak Pendaftaran Anggota Baru / Minta Revisi.
     */
    public function rejectMember(Request $request, int $id): RedirectResponse
    {
        $user = User::with('nfcCard')->findOrFail($id);
        $user->update(['status' => 'suspended']);

        if ($user->nfcCard) {
            $user->nfcCard->update(['status' => 'blocked']);
        }

        return back()->with('success', "Pendaftaran {$user->name} telah ditolak / ditangguhkan untuk revisi berkas.");
    }

    /**
     * Scan Presensi NFC (Tangkap hasil scan kartu panitia saat nobar / stadion).
     */
    public function nfcScanner(Request $request): View
    {
        $chapterId = $request->user()->chapter_id ?? 1;
        $recentScans = EventAttendance::where('chapter_id', $chapterId)->with('user.profile')->latest()->take(15)->get();
        $upcomingMatches = MatchTicket::where('status', 'open')->orderBy('match_time', 'asc')->get();

        return view('dashboard.chapter.scanner', compact('recentScans', 'upcomingMatches'));
    }

    /**
     * Proses Input Tap NFC (Mendukung UID Chip, Nomor Kartu KTA, atau NIA).
     */
    public function processScan(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'uid_number' => ['required', 'string'],
            'event_name' => ['required', 'string'],
        ]);

        $rawInput = trim($validated['uid_number']);
        $cleanInput = str_replace([' ', '-', ':', '.'], '', strtolower($rawInput));

        // 1. Cari via UID Chip atau Nomor Kartu Fisik
        $card = NfcCard::where(function ($q) use ($rawInput, $cleanInput) {
            $q->whereRaw('LOWER(uid_number) = ?', [strtolower($rawInput)])
                ->orWhereRaw('REPLACE(REPLACE(LOWER(uid_number), ":", ""), "-", "") = ?', [$cleanInput])
                ->orWhereRaw('LOWER(card_number) = ?', [strtolower($rawInput)])
                ->orWhereRaw('REPLACE(REPLACE(LOWER(card_number), " ", ""), "-", "") = ?', [$cleanInput]);
        })->with('user.profile')->first();

        // 2. Fallback via NIA suporter
        if (! $card) {
            $profile = MemberProfile::whereRaw('LOWER(nia) = ?', [strtolower($rawInput)])
                ->orWhereRaw('REPLACE(LOWER(nia), "-", "") = ?', [$cleanInput])
                ->first();
            if ($profile) {
                $card = NfcCard::where('user_id', $profile->user_id)->with('user.profile')->first();
            }
        }

        if (! $card) {
            return back()->with('error', "Identitas '{$validated['uid_number']}' tidak terdaftar dalam database anggota SMeCK!");
        }

        if ($card->status === 'blocked' || $card->user?->status === 'suspended') {
            $reason = ($card->user?->status === 'suspended') 
                ? ($card->user?->suspension_reason ?: 'Pelanggaran Kode Etik Organisasi & Tata Tertib Tribun SMeCK Hooligan') 
                : 'DIBLOKIR / HILANG';
            return back()->with('error', "PERINGATAN SANKSI DISIPLIN: KTA suporter {$card->user?->name} DITANGGUHKAN! Alasan: {$reason}. Akses ditolak.");
        }

        EventAttendance::create([
            'chapter_id' => $request->user()->chapter_id ?? 1,
            'user_id' => $card->user_id,
            'event_name' => $validated['event_name'],
            'scanned_at' => now(),
        ]);

        $memberUser = $card->user;

        return back()->with('success', "TAP BERHASIL! [{$memberUser->name} - {$memberUser->profile?->nia}] tercatat hadir pada {$validated['event_name']}.");
    }

    /**
     * Kas Chapter (Rekapitulasi Uang Masuk).
     */
    public function dues(Request $request): View
    {
        $chapterId = $request->user()->chapter_id ?? 1;
        $dues = VoluntaryDue::where('chapter_id', $chapterId)->with('user')->latest()->paginate(15);
        $totalAmount = VoluntaryDue::where('chapter_id', $chapterId)->where('status', 'verified')->sum('amount');
        $pendingAmount = VoluntaryDue::where('chapter_id', $chapterId)->where('status', 'pending')->sum('amount');

        // Daftar anggota untuk input iuran tunai di basecamp
        $chapterMembers = User::where('chapter_id', $chapterId)->where('role', 'member')->orderBy('name')->get();

        return view('dashboard.chapter.dues', compact('dues', 'totalAmount', 'pendingAmount', 'chapterMembers'));
    }

    /**
     * Catat Iuran Kas Tunai (Cash) di Basecamp / Kopdar.
     */
    public function storeCashDue(Request $request): RedirectResponse
    {
        $chapterId = $request->user()->chapter_id ?? 1;
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'amount' => ['required', 'numeric', 'min:5000'],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);

        VoluntaryDue::create([
            'user_id' => $validated['user_id'],
            'chapter_id' => $chapterId,
            'amount' => $validated['amount'],
            'status' => 'verified', // Langsung verified karena diterima tunai di tangan
            'notes' => ($validated['notes'] ? $validated['notes'] . ' ' : '') . '(Diterima Tunai di Basecamp)',
        ]);

        $member = User::find($validated['user_id']);

        return back()->with('success', 'Iuran tunai Rp ' . number_format($validated['amount'], 0, ',', '.') . " dari {$member->name} berhasil dicatat & masuk kas chapter!");
    }

    /**
     * Verifikasi Status Iuran Kas Online.
     */
    public function verifyDue(Request $request, int $id): RedirectResponse
    {
        $due = VoluntaryDue::findOrFail($id);
        $due->update(['status' => 'verified']);

        return back()->with('success', 'Iuran anggota berhasil diverifikasi.');
    }

    /**
     * [Prompt 6] Kuota Tiket Chapter & Manifest Pemesan.
     */
    public function ticketQuota(Request $request): View
    {
        $chapterId = $request->user()->chapter_id ?? 1;
        $allocations = TicketAllocation::where('chapter_id', $chapterId)->with('matchTicket')->latest()->get();
        $orders = TicketOrder::where('chapter_id', $chapterId)->with('matchTicket', 'user.profile')->latest()->get();

        return view('dashboard.chapter.tickets', compact('allocations', 'orders'));
    }

    /**
     * Check-in Masal / Rombongan Bus Awaydays & Tribun Chapter (Solusi 1).
     */
    public function bulkCheckinTickets(Request $request): RedirectResponse
    {
        $chapterId = $request->user()->chapter_id ?? 1;
        
        $orderIds = $request->input('order_ids', []);
        $checkinAll = $request->boolean('checkin_all');

        $query = TicketOrder::where('chapter_id', $chapterId)
            ->where('is_checked_in', false)
            ->with(['user', 'matchTicket']);

        if (! $checkinAll) {
            if (empty($orderIds)) {
                return back()->with('error', 'Silakan pilih minimal satu suporter dalam rombongan untuk di-check in!');
            }
            $query->whereIn('id', $orderIds);
        }

        $orders = $query->get();

        if ($orders->isEmpty()) {
            return back()->with('info', 'Semua suporter rombongan bus yang dipilih sudah berstatus Check-In Hadir.');
        }

        $count = 0;
        $now = now();

        foreach ($orders as $order) {
            $order->update([
                'is_checked_in' => true,
                'checked_in_at' => $now,
            ]);

            $eventName = $order->matchTicket ? 'Laga: ' . $order->matchTicket->match_title : 'Rombongan Tour Awaydays';

            // Catat presensi otomatis jika belum ada hari ini
            $exists = EventAttendance::where('user_id', $order->user_id)
                ->where('event_name', $eventName)
                ->whereDate('scanned_at', $now->toDateString())
                ->exists();

            if (! $exists) {
                EventAttendance::create([
                    'chapter_id' => $chapterId,
                    'user_id' => $order->user_id,
                    'event_name' => $eventName,
                    'scanned_at' => $now,
                ]);
            }

            $count++;
        }

        return back()->with('success', "CHECK-IN ROMBONGAN BERHASIL! Sebanyak {$count} suporter rombongan bus berhasil diabsen hadir serentak tanpa perlu tap kartu satu per satu!");
    }

    /**
     * [Prompt 6] Dokumentasi Kegiatan Chapter.
     */
    public function documentations(Request $request): View
    {
        $chapterId = $request->user()->chapter_id ?? 1;
        $docs = ChapterDocumentation::where('chapter_id', $chapterId)->latest()->get();

        return view('dashboard.chapter.documentations', compact('docs'));
    }

    /**
     * Simpan Dokumentasi Kegiatan.
     */
    public function storeDocumentation(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'activity_type' => ['required', 'in:kopdar,nobar,baksos,awaydays,lainnya'],
            'activity_date' => ['required', 'date'],
            'description' => ['required', 'string'],
        ]);

        ChapterDocumentation::create([
            'chapter_id' => $request->user()->chapter_id ?? 1,
            'user_id' => $request->user()->id,
            'title' => $validated['title'],
            'activity_type' => $validated['activity_type'],
            'activity_date' => $validated['activity_date'],
            'description' => $validated['description'],
        ]);

        return back()->with('success', 'Laporan dokumentasi kegiatan berhasil diunggah ke Pusat!');
    }

    /**
     * Kelola Anggota Chapter (Daftar, Status KTA & Aksi Kartu).
     */
    public function members(Request $request): View
    {
        $chapterId = $request->user()->chapter_id ?? 1;
        $chapter = Chapter::find($chapterId);

        $query = User::where('chapter_id', $chapterId)
            ->where('role', 'member')
            ->with(['profile', 'nfcCard']);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('profile', function ($pq) use ($search) {
                        $pq->where('nia', 'like', "%{$search}%")
                            ->orWhere('nik_ktp', 'like', "%{$search}%")
                            ->orWhere('phone_number', 'like', "%{$search}%");
                    })
                    ->orWhereHas('nfcCard', function ($cq) use ($search) {
                        $cq->where('uid_number', 'like', "%{$search}%")
                            ->orWhere('card_number', 'like', "%{$search}%");
                    });
            });
        }

        $members = $query->latest()->paginate(15);

        return view('dashboard.chapter.members', compact('chapter', 'members'));
    }

    /**
     * Tambah Anggota Baru Langsung dari Basis / Chapter.
     */
    public function storeMember(Request $request): RedirectResponse
    {
        $chapterId = $request->user()->chapter_id ?? 1;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['nullable', 'string', 'min:6'],
            'nik_ktp' => ['required', 'digits:16', 'unique:member_profiles,nik_ktp'],
            'phone_number' => ['required', 'string', 'max:20', 'unique:member_profiles,phone_number'],
            'blood_type' => ['required', 'in:A,B,AB,O'],
            'place_of_birth' => ['required', 'string', 'max:100'],
            'date_of_birth' => ['required', 'date'],
            'gender' => ['required', 'in:L,P'],
            'address' => ['required', 'string'],
            'emergency_contact_name' => ['nullable', 'string', 'max:100'],
            'emergency_contact_number' => ['nullable', 'string', 'max:20'],
            'emergency_contact_relation' => ['nullable', 'string', 'max:50'],
            'shirt_size' => ['required', 'in:S,M,L,XL,XXL,3XL'],
            'occupation' => ['nullable', 'string', 'max:100'],
            // Optional KTA NFC Card
            'uid_number' => ['nullable', 'string', 'unique:nfc_cards,uid_number'],
            'card_vendor' => ['nullable', 'in:emoney,flazz,brizzi,tapcash,custom'],
        ], [
            'email.unique' => 'Alamat email ini sudah terdaftar. Silakan gunakan email lain.',
            'phone_number.unique' => 'Nomor WhatsApp / HP ini sudah terdaftar pada anggota lain. Silakan gunakan nomor HP lain.',
            'nik_ktp.unique' => 'Nomor NIK KTP ini sudah terdaftar dalam sistem anggota SMeCK Hooligan. Satu NIK hanya berlaku untuk 1 anggota.',
            'nik_ktp.digits' => 'Nomor NIK KTP harus tepat 16 digit angka.',
        ]);

        // Buat User Baru (Langsung Active karena didaftarkan Pengurus Basis)
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password'] ?: 'smeck1950'),
            'chapter_id' => $chapterId,
            'role' => 'member',
            'status' => 'active',
        ]);

        // Generate NIA Resmi: SMCK-YYYY-XXXX
        $year = date('Y');
        $lastProfile = MemberProfile::where('nia', 'like', "SMCK-{$year}-%")->latest('id')->first();
        if ($lastProfile && preg_match('/SMCK-\d{4}-(\d+)/', $lastProfile->nia, $matches)) {
            $nextSequence = (int) $matches[1] + 1;
        } else {
            $nextSequence = MemberProfile::count() + 1;
        }
        $nia = sprintf('SMCK-%s-%04d', $year, $nextSequence);

        MemberProfile::create([
            'user_id' => $user->id,
            'nia' => $nia,
            'nik_ktp' => $validated['nik_ktp'],
            'place_of_birth' => $validated['place_of_birth'],
            'date_of_birth' => $validated['date_of_birth'],
            'gender' => $validated['gender'],
            'blood_type' => $validated['blood_type'],
            'address' => $validated['address'],
            'phone_number' => $validated['phone_number'],
            'emergency_contact_name' => $validated['emergency_contact_name'],
            'emergency_contact_number' => $validated['emergency_contact_number'],
            'emergency_contact_relation' => $validated['emergency_contact_relation'],
            'shirt_size' => $validated['shirt_size'],
            'occupation' => $validated['occupation'],
            'joined_date' => now(),
        ]);

        // Tautkan kartu fisik NFC jika UID diisi
        if (! empty($validated['uid_number'])) {
            $cardNumber = sprintf('6032-%04d-%04d-%04d', rand(1000, 9999), rand(1000, 9999), rand(1000, 9999));
            NfcCard::create([
                'user_id' => $user->id,
                'uid_number' => strtoupper(trim($validated['uid_number'])),
                'card_number' => $cardNumber,
                'card_vendor' => $validated['card_vendor'] ?? 'emoney',
                'status' => 'active',
                'issued_at' => now(),
            ]);
        }

        return back()->with('success', "Anggota baru '{$user->name}' (NIA: {$nia}) berhasil didaftarkan langsung ke Basis!");
    }

    /**
     * Blokir Kartu NFC Anggota (Laporan Hilang / Dicuri / Rusak).
     */
    public function blockCard(Request $request, int $cardId): RedirectResponse
    {
        $card = NfcCard::findOrFail($cardId);
        $card->update(['status' => 'blocked']);

        return back()->with('success', "Kartu NFC UID {$card->uid_number} milik {$card->user->name} berhasil DIBLOKIR!");
    }

    /**
     * Buka Blokir Kartu NFC (Jika kartu ditemukan kembali).
     */
    public function unblockCard(Request $request, int $cardId): RedirectResponse
    {
        $card = NfcCard::findOrFail($cardId);
        $card->update(['status' => 'active']);

        return back()->with('success', "Kartu NFC UID {$card->uid_number} milik {$card->user->name} berhasil DIAKTIFKAN kembali!");
    }

    /**
     * Ganti Kartu NFC Anggota (Penerbitan Kartu Pengganti).
     */
    public function replaceCard(Request $request, int $userId): RedirectResponse
    {
        $validated = $request->validate([
            'uid_number' => ['required', 'string', 'unique:nfc_cards,uid_number'],
            'card_vendor' => ['required', 'in:emoney,flazz,brizzi,tapcash,custom'],
        ]);

        $user = User::findOrFail($userId);

        // Blokir kartu lama jika ada
        NfcCard::where('user_id', $user->id)->update(['status' => 'blocked']);

        // Terbitkan kartu NFC baru dengan nomor kartu resmi
        $cardNumber = sprintf('6032-%04d-%04d-%04d', rand(1000, 9999), rand(1000, 9999), rand(1000, 9999));
        $newCard = NfcCard::create([
            'user_id' => $user->id,
            'uid_number' => strtoupper(trim($validated['uid_number'])),
            'card_number' => $cardNumber,
            'card_vendor' => $validated['card_vendor'],
            'status' => 'active',
            'issued_at' => now(),
        ]);

        return back()->with('success', "Kartu KTA fisik baru Nomor {$cardNumber} (UID: {$newCard->uid_number}) berhasil diterbitkan untuk {$user->name}. Kartu lama telah diblokir.");
    }

    /**
     * Edit / Perbarui Data Anggota Basis (Admin Chapter).
     */
    public function updateMember(Request $request, int $id): RedirectResponse
    {
        $chapterId = $request->user()->chapter_id ?? 1;
        $user = User::where('chapter_id', $chapterId)->with('profile')->findOrFail($id);
        $profileId = $user->profile?->id;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'nik_ktp' => ['required', 'string', 'max:25', 'unique:member_profiles,nik_ktp,' . ($profileId ?? 'NULL') . ',id'],
            'phone_number' => ['required', 'string', 'max:25', 'unique:member_profiles,phone_number,' . ($profileId ?? 'NULL') . ',id'],
            'blood_type' => ['nullable', 'in:A,B,AB,O,-'],
            'shirt_size' => ['nullable', 'in:S,M,L,XL,XXL,3XL,-'],
            'gender' => ['nullable', 'in:L,P'],
            'place_of_birth' => ['nullable', 'string', 'max:100'],
            'date_of_birth' => ['nullable', 'date'],
            'address' => ['nullable', 'string'],
            'password' => ['nullable', 'string', 'min:6'],
        ], [
            'email.unique' => 'Alamat email ini sudah terdaftar pada anggota lain. Silakan gunakan email lain.',
            'phone_number.unique' => 'Nomor WhatsApp / HP ini sudah terdaftar pada anggota lain. Silakan gunakan nomor HP lain.',
            'nik_ktp.unique' => 'Nomor NIK KTP ini sudah terdaftar pada anggota lain. Silakan periksa kembali NIK yang dimasukkan.',
        ]);

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];
        if (! empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }
        $user->update($userData);

        $profileData = [
            'nik_ktp' => $validated['nik_ktp'],
            'phone_number' => $validated['phone_number'],
            'blood_type' => ($validated['blood_type'] ?? '') === '-' ? null : ($validated['blood_type'] ?? null),
            'shirt_size' => ($validated['shirt_size'] ?? '') === '-' ? null : ($validated['shirt_size'] ?? null),
            'gender' => $validated['gender'] ?? null,
            'place_of_birth' => $validated['place_of_birth'] ?? null,
            'date_of_birth' => $validated['date_of_birth'] ?? null,
            'address' => $validated['address'] ?? null,
        ];

        if ($user->profile) {
            $user->profile->update($profileData);
        } else {
            $profileData['user_id'] = $user->id;
            MemberProfile::create($profileData);
        }

        return back()->with('success', "Data anggota basis {$user->name} berhasil diperbarui!");
    }
}
