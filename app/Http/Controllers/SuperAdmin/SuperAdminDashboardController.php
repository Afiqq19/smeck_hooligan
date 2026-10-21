<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\MatchTicket;
use App\Models\NfcCard;
use App\Models\SmeckPartner;
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
        $chapters = Chapter::withCount('users')->get();

        return view('dashboard.superadmin.index', compact(
            'totalMembers',
            'totalChapters',
            'totalActiveCards',
            'totalNationalFunds',
            'recentMembers',
            'chapters'
        ));
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
            'card_vendor' => ['required', 'in:emoney,flazz,brizzi,tapcash,custom'],
        ]);

        NfcCard::create([
            'user_id' => $validated['user_id'],
            'uid_number' => strtoupper($validated['uid_number']),
            'card_vendor' => $validated['card_vendor'],
            'status' => 'active',
            'issued_at' => now(),
        ]);

        $user = User::find($validated['user_id']);

        return back()->with('success', "Kartu NFC UID {$validated['uid_number']} berhasil ditautkan ke akun {$user->name}!");
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

        return back()->with('success', "Kartu UID {$card->uid_number} berhasil {$statusLabel}!");
    }

    /**
     * Ganti Kartu NFC Anggota (Pusat).
     */
    public function replaceCard(Request $request, int $userId): RedirectResponse
    {
        $validated = $request->validate([
            'uid_number' => ['required', 'string', 'unique:nfc_cards,uid_number'],
            'card_vendor' => ['required', 'in:emoney,flazz,brizzi,tapcash,custom'],
        ]);

        $user = User::findOrFail($userId);

        // Blokir kartu lama
        NfcCard::where('user_id', $user->id)->update(['status' => 'blocked']);

        // Buat kartu baru
        $newCard = NfcCard::create([
            'user_id' => $user->id,
            'uid_number' => strtoupper(trim($validated['uid_number'])),
            'card_vendor' => $validated['card_vendor'],
            'status' => 'active',
            'issued_at' => now(),
        ]);

        return back()->with('success', "Kartu KTA fisik pengganti UID {$newCard->uid_number} berhasil diterbitkan untuk {$user->name}.");
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
}
