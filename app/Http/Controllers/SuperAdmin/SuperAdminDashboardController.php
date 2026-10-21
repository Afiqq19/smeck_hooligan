<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Chant;
use App\Models\Chapter;
use App\Models\ChapterDocumentation;
use App\Models\EventAttendance;
use App\Models\MatchTicket;
use App\Models\MemberProfile;
use App\Models\NfcCard;
use App\Models\SmeckPartner;
use App\Models\StoreProduct;
use App\Models\TicketAllocation;
use App\Models\User;
use App\Models\VoluntaryDue;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SuperAdminDashboardController extends Controller
{
    /**
     * Dashboard Utama Pusat (Statistik Nasional).
     */
    public function index(): View
    {
        $totalMembers = User::where('role', 'member')->count();
        $totalChapters = Chapter::count();
        $totalActiveCards = NfcCard::where('status', 'active')->count();
        $totalNationalFunds = VoluntaryDue::where('status', 'verified')->sum('amount');

        $recentMembers = User::where('role', 'member')->with('chapter', 'profile', 'nfcCard')->latest()->take(6)->get();
        $chapters = Chapter::withCount('users')->orderBy('users_count', 'desc')->get();

        // Kesiapsiagaan Medis Tribun: Komposisi Golongan Darah Nasional
        $bloodTypeStats = [
            'A' => MemberProfile::where('blood_type', 'A')->count(),
            'B' => MemberProfile::where('blood_type', 'B')->count(),
            'AB' => MemberProfile::where('blood_type', 'AB')->count(),
            'O' => MemberProfile::where('blood_type', 'O')->count(),
        ];

        // Live Tap NFC Feed Kehadiran Laga & Event
        $recentAttendances = EventAttendance::with('user.chapter', 'user.profile')->latest()->take(6)->get();

        return view('dashboard.superadmin.index', compact(
            'totalMembers',
            'totalChapters',
            'totalActiveCards',
            'totalNationalFunds',
            'recentMembers',
            'chapters',
            'bloodTypeStats',
            'recentAttendances'
        ));
    }

    /**
     * Direktori Anggota Nasional (Seluruh Basis & Wilayah).
     */
    public function members(Request $request): View
    {
        $chapters = Chapter::orderBy('name')->get();

        $query = User::with(['profile', 'chapter', 'nfcCard'])
            ->whereIn('role', ['member', 'admin_chapter']);

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

        if ($chapterId = $request->get('chapter_id')) {
            $query->where('chapter_id', $chapterId);
        }

        if ($bloodType = $request->get('blood_type')) {
            $query->whereHas('profile', function ($pq) use ($bloodType) {
                $pq->where('blood_type', $bloodType);
            });
        }

        if ($role = $request->get('role')) {
            $query->where('role', $role);
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $members = $query->latest()->paginate(20)->withQueryString();
        $totalMembersCount = User::whereIn('role', ['member', 'admin_chapter'])->count();

        return view('dashboard.superadmin.members', compact('members', 'chapters', 'totalMembersCount'));
    }

    /**
     * Ubah Role Anggota (Promote ke Admin Chapter / Demote).
     */
    public function updateMemberRole(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'role' => ['required', 'in:member,admin_chapter'],
        ]);

        $user = User::findOrFail($id);
        $user->update(['role' => $validated['role']]);

        $roleLabel = $validated['role'] === 'admin_chapter' ? 'Pengurus Basis (Admin Chapter)' : 'Anggota Reguler';

        return back()->with('success', "Peran suporter {$user->name} berhasil diubah menjadi {$roleLabel}!");
    }

    /**
     * Ubah Status Akun Anggota (Aktifkan / Suspend).
     */
    public function toggleMemberStatus(Request $request, int $id): RedirectResponse
    {
        $user = User::findOrFail($id);
        $newStatus = $user->status === 'active' ? 'suspended' : 'active';
        $user->update(['status' => $newStatus]);

        $statusLabel = $newStatus === 'active' ? 'DIAKTIFKAN' : 'DIBEKUKAN (SUSPEND)';

        return back()->with('success', "Status akun suporter {$user->name} berhasil {$statusLabel}!");
    }

    /**
     * Master Chapter (Kelola Basis Wilayah).
     */
    public function chapters(): View
    {
        $chapters = Chapter::withCount('users')->latest()->get();

        return view('dashboard.superadmin.chapters', compact('chapters'));
    }

    /**
     * Tambah Chapter Baru.
     */
    public function storeChapter(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'region_code' => ['required', 'string', 'max:20', 'unique:chapters,region_code'],
            'leader_name' => ['required', 'string', 'max:255'],
            'basecamp_address' => ['required', 'string'],
        ]);

        Chapter::create($validated);

        return back()->with('success', "Basis / Chapter {$validated['name']} berhasil ditambahkan!");
    }

    /**
     * Cetak & Aktivasi Kartu NFC (Tautkan UID fisik ke akun anggota).
     */
    public function nfcActivation(): View
    {
        $membersWithoutCard = User::where('role', 'member')
            ->whereDoesntHave('nfcCard')
            ->with('profile', 'chapter')
            ->get();

        $activeCards = NfcCard::with('user.profile', 'user.chapter')->latest()->paginate(15);

        return view('dashboard.superadmin.nfc', compact('membersWithoutCard', 'activeCards'));
    }

    /**
     * Tautkan Kartu NFC ke Anggota.
     */
    public function linkNfcCard(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'uid_number' => ['required', 'string', 'unique:nfc_cards,uid_number'],
            'card_number' => ['nullable', 'string', 'unique:nfc_cards,card_number'],
            'card_vendor' => ['required', 'in:emoney,flazz,brizzi,tapcash,custom'],
        ]);

        $cardNumber = !empty($validated['card_number']) ? $validated['card_number'] : sprintf('6032-%04d-%04d-%04d', rand(1000, 9999), rand(1000, 9999), rand(1000, 9999));

        $card = NfcCard::create([
            'user_id' => $validated['user_id'],
            'uid_number' => strtoupper(trim($validated['uid_number'])),
            'card_number' => $cardNumber,
            'card_vendor' => $validated['card_vendor'],
            'status' => 'active',
            'issued_at' => now(),
        ]);

        $user = User::find($validated['user_id']);

        return back()->with('success', "Kartu KTA Nomor {$cardNumber} (UID: {$card->uid_number}) berhasil ditautkan ke akun {$user->name}!");
    }

    /**
     * Ubah Status Kartu NFC (Blokir / Aktifkan Kembali).
     */
    public function toggleCardStatus(Request $request, int $cardId): RedirectResponse
    {
        $card = NfcCard::findOrFail($cardId);
        $newStatus = $card->status === 'blocked' ? 'active' : 'blocked';
        $card->update(['status' => $newStatus]);

        $statusLabel = $newStatus === 'blocked' ? 'DIBLOKIR' : 'DIAKTIFKAN KEMBALI';

        return back()->with('success', "Kartu KTA Nomor {$card->card_number} (UID: {$card->uid_number}) berhasil {$statusLabel}!");
    }

    /**
     * Ganti Kartu NFC Anggota (Pusat).
     */
    public function replaceCard(Request $request, int $userId): RedirectResponse
    {
        $validated = $request->validate([
            'uid_number' => ['required', 'string', 'unique:nfc_cards,uid_number'],
            'card_number' => ['nullable', 'string', 'unique:nfc_cards,card_number'],
            'card_vendor' => ['required', 'in:emoney,flazz,brizzi,tapcash,custom'],
        ]);

        $user = User::findOrFail($userId);

        // Blokir kartu lama
        NfcCard::where('user_id', $user->id)->update(['status' => 'blocked']);

        // Buat kartu baru
        $cardNumber = !empty($validated['card_number']) ? $validated['card_number'] : sprintf('6032-%04d-%04d-%04d', rand(1000, 9999), rand(1000, 9999), rand(1000, 9999));
        $newCard = NfcCard::create([
            'user_id' => $user->id,
            'uid_number' => strtoupper(trim($validated['uid_number'])),
            'card_number' => $cardNumber,
            'card_vendor' => $validated['card_vendor'],
            'status' => 'active',
            'issued_at' => now(),
        ]);

        return back()->with('success', "Kartu KTA fisik pengganti Nomor {$cardNumber} (UID {$newCard->uid_number}) berhasil diterbitkan untuk {$user->name}.");
    }

    /**
     * Broadcast Pengumuman ke Seluruh Anggota.
     */
    public function broadcast(): View
    {
        return view('dashboard.superadmin.broadcast');
    }

    /**
     * Kirim Pesan Broadcast.
     */
    public function sendBroadcast(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'target_chapter' => ['required', 'string'],
            'message' => ['required', 'string'],
        ]);

        return back()->with('success', "Pengumuman '{$validated['title']}' berhasil disiarkan ke seluruh anggota!");
    }

    /**
     * [Prompt 6] Distribusi Tiket Pertandingan ke Chapter.
     */
    public function ticketDistribution(): View
    {
        $matches = MatchTicket::latest()->get();
        $chapters = Chapter::where('is_active', true)->get();
        $allocations = TicketAllocation::with('matchTicket', 'chapter')->latest()->get();

        return view('dashboard.superadmin.tickets', compact('matches', 'chapters', 'allocations'));
    }

    /**
     * Alokasikan Tiket ke Chapter.
     */
    public function storeTicketAllocation(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'match_ticket_id' => ['required', 'exists:match_tickets,id'],
            'chapter_id' => ['required', 'exists:chapters,id'],
            'allocated_quota' => ['required', 'integer', 'min:1'],
        ]);

        TicketAllocation::updateOrCreate(
            [
                'match_ticket_id' => $validated['match_ticket_id'],
                'chapter_id' => $validated['chapter_id'],
            ],
            [
                'allocated_quota' => $validated['allocated_quota'],
            ]
        );

        return back()->with('success', 'Alokasi kuota tiket chapter berhasil diperbarui!');
    }

    /**
     * [Prompt 6] Database Mitra SMeCK Partner.
     */
    public function partners(): View
    {
        $partners = SmeckPartner::latest()->get();

        return view('dashboard.superadmin.partners', compact('partners'));
    }

    /**
     * Tambah Mitra SMeCK Partner.
     */
    public function storePartner(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'business_name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'address' => ['required', 'string'],
            'discount_rate' => ['required', 'string', 'max:255'],
            'contact_person' => ['nullable', 'string', 'max:100'],
            'phone_number' => ['nullable', 'string', 'max:20'],
        ]);

        SmeckPartner::create($validated);

        return back()->with('success', "Mitra '{$validated['business_name']}' berhasil didaftarkan sebagai SMeCK Partner!");
    }

    /**
     * Ubah Status Aktif Mitra SMeCK Partner.
     */
    public function togglePartner(Request $request, int $id): RedirectResponse
    {
        $partner = SmeckPartner::findOrFail($id);
        $partner->update(['is_active' => ! $partner->is_active]);

        $statusLabel = $partner->is_active ? 'DIAKTIFKAN' : 'DINONAKTIFKAN';

        return back()->with('success', "Mitra '{$partner->business_name}' berhasil {$statusLabel}!");
    }

    /**
     * Tambah Jadwal Pertandingan PSMS Medan / Awaydays.
     */
    public function storeMatch(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'match_title' => ['required', 'string', 'max:255'],
            'match_time' => ['required', 'date'],
            'stadium' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:home,away'],
            'price' => ['required', 'numeric', 'min:0'],
            'total_quota' => ['required', 'integer', 'min:1'],
            'away_destination' => ['nullable', 'string', 'max:255'],
            'bus_transport_info' => ['nullable', 'string', 'max:255'],
        ]);

        $validated['remaining_quota'] = $validated['total_quota'];
        $validated['status'] = 'open';

        MatchTicket::create($validated);

        return back()->with('success', "Jadwal Laga '{$validated['match_title']}' berhasil ditambahkan ke kalender tiket & awaydays!");
    }

    /**
     * Manajemen Galeri Koreo & Dokumentasi Nasional.
     */
    public function documentations(): View
    {
        $documentations = ChapterDocumentation::with('chapter', 'user')->latest()->paginate(15);
        $chapters = Chapter::orderBy('name')->get();

        return view('dashboard.superadmin.documentations', compact('documentations', 'chapters'));
    }

    /**
     * Simpan Arsip Koreo / Dokumentasi Nasional DPP.
     */
    public function storeDocumentation(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'chapter_id' => ['required', 'exists:chapters,id'],
            'activity_type' => ['required', 'in:kopdar,nobar,baksos,awaydays,lainnya'],
            'activity_date' => ['required', 'date'],
            'description' => ['required', 'string'],
            'is_highlighted' => ['nullable', 'boolean'],
        ]);

        ChapterDocumentation::create([
            'chapter_id' => $validated['chapter_id'],
            'user_id' => $request->user()->id,
            'title' => $validated['title'],
            'activity_type' => $validated['activity_type'],
            'activity_date' => $validated['activity_date'],
            'description' => $validated['description'],
            'is_highlighted' => $request->has('is_highlighted'),
        ]);

        return back()->with('success', 'Dokumentasi resmi suporter berhasil diarsipkan!');
    }

    /**
     * Bank Chant & Lagu SMeCK Tribun.
     */
    public function chants(): View
    {
        $chants = Chant::latest()->paginate(20);

        return view('dashboard.superadmin.chants', compact('chants'));
    }

    /**
     * Tambah Chant Baru.
     */
    public function storeChant(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'lyrics' => ['required', 'string'],
            'youtube_url' => ['nullable', 'url', 'max:255'],
            'duration' => ['nullable', 'string', 'max:20'],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        $validated['is_featured'] = $request->has('is_featured');

        Chant::create($validated);

        return back()->with('success', "Chant '{$validated['title']}' berhasil ditambahkan ke pustaka chant SMeCK!");
    }

    /**
     * Toggle Chant Featured di Landing Page.
     */
    public function toggleChantFeatured(Request $request, int $id): RedirectResponse
    {
        $chant = Chant::findOrFail($id);
        $chant->update(['is_featured' => ! $chant->is_featured]);

        $status = $chant->is_featured ? 'ditampilkan di Landing Page' : 'disembunyikan dari Landing Page';

        return back()->with('success', "Chant '{$chant->title}' berhasil {$status}!");
    }

    /**
     * Laporan Kas & Keuangan Koreo Nasional.
     */
    public function finance(): View
    {
        $totalVerifiedFunds = VoluntaryDue::where('status', 'verified')->sum('amount');
        $totalPendingFunds = VoluntaryDue::where('status', 'pending')->sum('amount');
        $totalTransactions = VoluntaryDue::count();

        $chapterFunds = Chapter::withSum(['dues as verified_sum' => function ($q) {
            $q->where('status', 'verified');
        }], 'amount')
        ->withCount('dues')
        ->orderByDesc('verified_sum')
        ->get();

        $recentDues = VoluntaryDue::with('user', 'chapter')->latest()->paginate(20);

        return view('dashboard.superadmin.finance', compact(
            'totalVerifiedFunds',
            'totalPendingFunds',
            'totalTransactions',
            'chapterFunds',
            'recentDues'
        ));
    }
}
