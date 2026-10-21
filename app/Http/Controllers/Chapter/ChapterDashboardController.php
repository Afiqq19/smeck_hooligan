<?php

namespace App\Http\Controllers\Chapter;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\ChapterDocumentation;
use App\Models\EventAttendance;
use App\Models\MemberProfile;
use App\Models\NfcCard;
use App\Models\TicketAllocation;
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
        $recentAttendances = EventAttendance::where('chapter_id', $chapterId)->with('user.profile')->latest()->take(5)->get();

        return view('dashboard.chapter.index', compact('chapter', 'totalMembers', 'pendingMembers', 'totalKas', 'recentAttendances'));
    }

    /**
     * Verifikasi Pendaftaran Anggota Baru.
     */
    public function verifications(Request $request): View
    {
        $chapterId = $request->user()->chapter_id ?? 1;
        $pendingUsers = User::where('chapter_id', $chapterId)
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
        $user = User::findOrFail($id);
        $user->update(['status' => 'active']);

        return back()->with('success', "Anggota {$user->name} berhasil diverifikasi & diaktifkan!");
    }

    /**
     * Scan Presensi NFC (Tangkap hasil scan kartu panitia saat nobar / stadion).
     */
    public function nfcScanner(Request $request): View
    {
        $chapterId = $request->user()->chapter_id ?? 1;
        $recentScans = EventAttendance::where('chapter_id', $chapterId)->with('user.profile')->latest()->take(10)->get();

        return view('dashboard.chapter.scanner', compact('recentScans'));
    }

    /**
     * Proses Input Tap NFC.
     */
    public function processScan(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'uid_number' => ['required', 'string'],
            'event_name' => ['required', 'string'],
        ]);

        $card = NfcCard::where('uid_number', $validated['uid_number'])->first();

        if (! $card) {
            return back()->with('error', "UID NFC '{$validated['uid_number']}' tidak terdaftar dalam database anggota SMeCK!");
        }

        if ($card->status === 'blocked') {
            return back()->with('error', "PERINGATAN: Kartu ini dilaporkan HILANG / DIBLOKIR!");
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
        $dues = VoluntaryDue::where('chapter_id', $chapterId)->with('user')->latest()->get();
        $totalAmount = VoluntaryDue::where('chapter_id', $chapterId)->where('status', 'verified')->sum('amount');

        return view('dashboard.chapter.dues', compact('dues', 'totalAmount'));
    }

    /**
     * Verifikasi Status Iuran Kas.
     */
    public function verifyDue(Request $request, int $id): RedirectResponse
    {
        $due = VoluntaryDue::findOrFail($id);
        $due->update(['status' => 'verified']);

        return back()->with('success', 'Iuran anggota berhasil diverifikasi.');
    }

    /**
     * [Prompt 6] Kuota Tiket Chapter.
     */
    public function ticketQuota(Request $request): View
    {
        $chapterId = $request->user()->chapter_id ?? 1;
        $allocations = TicketAllocation::where('chapter_id', $chapterId)->with('matchTicket')->latest()->get();

        return view('dashboard.chapter.tickets', compact('allocations'));
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
                        $cq->where('uid_number', 'like', "%{$search}%");
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
            'phone_number' => ['required', 'string', 'max:20'],
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
            NfcCard::create([
                'user_id' => $user->id,
                'uid_number' => strtoupper(trim($validated['uid_number'])),
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

        // Terbitkan kartu NFC baru
        $newCard = NfcCard::create([
            'user_id' => $user->id,
            'uid_number' => strtoupper(trim($validated['uid_number'])),
            'card_vendor' => $validated['card_vendor'],
            'status' => 'active',
            'issued_at' => now(),
        ]);

        return back()->with('success', "Kartu KTA fisik baru UID {$newCard->uid_number} berhasil diterbitkan untuk {$user->name}. Kartu lama telah diblokir.");
    }
}
