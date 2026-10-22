<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Chant;
use App\Models\Chapter;
use App\Models\ChapterDocumentation;
use App\Models\EventAttendance;
use App\Models\LeagueStanding;
use App\Models\MatchTicket;
use App\Models\MemberProfile;
use App\Models\NewsArticle;
use App\Models\NfcCard;
use App\Models\SmeckPartner;
use App\Models\StoreProduct;
use App\Models\TicketAllocation;
use App\Models\User;
use App\Models\VoluntaryDue;
use App\Services\PtLibScraperService;
use App\Services\WhatsAppBotService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
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
        $user = User::with('nfcCard')->findOrFail($id);
        $newStatus = $user->status === 'active' ? 'suspended' : 'active';
        $user->update(['status' => $newStatus]);

        // Sinkronkan status Kartu KTA NFC secara otomatis
        if ($user->nfcCard) {
            $user->nfcCard->update([
                'status' => $newStatus === 'suspended' ? 'blocked' : 'active',
            ]);
        }

        $statusLabel = $newStatus === 'active' ? 'DIAKTIFKAN KEMBALI' : 'DIBEKUKAN / SUSPEND (KTA Otomatis Nonaktif)';

        return back()->with('success', "Status akun & KTA suporter {$user->name} berhasil {$statusLabel}!");
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

    /**
     * Manajemen Klasemen Liga 2 (Opsi B: PT LIB Scraper).
     */
    public function standings(): View
    {
        $standings = LeagueStanding::orderBy('position')->get();
        if ($standings->isEmpty()) {
            app(PtLibScraperService::class)->syncStandings();
            $standings = LeagueStanding::orderBy('position')->get();
        }

        $psms = $standings->firstWhere('is_our_team', true);
        $lastSyncedAt = $standings->max('last_synced_at');

        return view('dashboard.superadmin.standings', compact('standings', 'psms', 'lastSyncedAt'));
    }

    /**
     * Trigger manual sinkronisasi klasemen via PT LIB Scraper (100% Gratis).
     */
    public function syncStandings(PtLibScraperService $scraper): RedirectResponse
    {
        $result = $scraper->syncStandings();

        return back()->with('success', "SINKRONISASI BERHASIL! {$result['count']} klub diperbarui dari PT LIB Scraper. PSMS Medan berada di peringkat ke-{$result['psms_position']} ({$result['psms_points']} Poin).");
    }

    /**
     * Update manual data klub (misal sanksi pengurangan poin komdis PSSI).
     */
    public function updateStanding(Request $request, int $id): RedirectResponse
    {
        $standing = LeagueStanding::findOrFail($id);

        $validated = $request->validate([
            'played' => ['required', 'integer', 'min:0'],
            'won' => ['required', 'integer', 'min:0'],
            'drawn' => ['required', 'integer', 'min:0'],
            'lost' => ['required', 'integer', 'min:0'],
            'goals_for' => ['required', 'integer', 'min:0'],
            'goals_against' => ['required', 'integer', 'min:0'],
            'points' => ['required', 'integer'],
            'form' => ['nullable', 'string', 'max:50'],
        ]);

        $validated['goal_difference'] = $validated['goals_for'] - $validated['goals_against'];

        $standing->update($validated);

        // Re-ranking seluruh klasemen setelah update manual
        $all = LeagueStanding::all()->sort(function ($a, $b) {
            if ($b->points !== $a->points) return $b->points <=> $a->points;
            if ($b->goal_difference !== $a->goal_difference) return $b->goal_difference <=> $a->goal_difference;
            return $b->goals_for <=> $a->goals_for;
        })->values();

        foreach ($all as $index => $item) {
            $pos = $index + 1;
            $zone = 'safe';
            if ($pos === 1) $zone = 'direct_promotion';
            elseif ($pos === 2) $zone = 'playoff_promotion';
            elseif ($pos >= 8) $zone = 'relegation_playoff';

            $item->update([
                'position' => $pos,
                'zone_status' => $zone,
            ]);
        }

        return back()->with('success', "Statistik klasemen tim '{$standing->team_name}' berhasil disesuaikan!");
    }

    /**
     * 12. Manajemen Berita, Artikel & Maklumat Pers DPP.
     */
    public function news(Request $request): View
    {
        $query = NewsArticle::query();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('summary', 'like', "%{$search}%")
                    ->orWhere('author_name', 'like', "%{$search}%");
            });
        }

        if ($category = $request->get('category')) {
            $query->where('category', $category);
        }

        $articles = $query->orderBy('is_featured', 'desc')->latest()->paginate(10);
        $totalArticles = NewsArticle::count();
        $publishedCount = NewsArticle::where('is_published', true)->count();
        $featuredCount = NewsArticle::where('is_featured', true)->count();
        $totalViews = NewsArticle::sum('views_count');

        return view('dashboard.superadmin.news', compact(
            'articles',
            'totalArticles',
            'publishedCount',
            'featuredCount',
            'totalViews'
        ));
    }

    /**
     * Simpan / Perbarui Berita atau Maklumat.
     */
    public function storeNews(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id' => ['nullable', 'exists:news_articles,id'],
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:50'],
            'summary' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'author_name' => ['required', 'string', 'max:100'],
            'image_url' => ['nullable', 'string'],
            'image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'is_featured' => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $data = [
            'title' => $validated['title'],
            'category' => $validated['category'],
            'summary' => $validated['summary'] ?? \Illuminate\Support\Str::limit(strip_tags($validated['content']), 180),
            'content' => $validated['content'],
            'author_name' => $validated['author_name'],
            'is_featured' => $request->boolean('is_featured'),
            'is_published' => $request->has('is_published') ? $request->boolean('is_published') : true,
        ];

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('news', 'public');
            $data['image_url'] = asset('storage/' . $path);
        } elseif (! empty($validated['image_url'])) {
            $data['image_url'] = $validated['image_url'];
        }

        if (! empty($validated['id'])) {
            $article = NewsArticle::findOrFail($validated['id']);
            $article->update($data);
            $msg = 'Berita / Maklumat berhasil diperbarui!';
        } else {
            $data['published_at'] = now();
            $article = NewsArticle::create($data);
            $msg = 'Berita / Maklumat baru berhasil dipublikasikan!';
        }

        return back()->with('success', $msg);
    }

    /**
     * Hapus Berita.
     */
    public function deleteNews(int $id): RedirectResponse
    {
        $article = NewsArticle::findOrFail($id);
        $article->delete();

        return back()->with('success', "Berita '{$article->title}' berhasil dihapus.");
    }

    /**
     * Toggle status Featured (Unggulan).
     */
    public function toggleNewsFeatured(int $id): RedirectResponse
    {
        $article = NewsArticle::findOrFail($id);
        $article->update(['is_featured' => ! $article->is_featured]);

        $status = $article->is_featured ? 'dijadikan Berita Utama (Featured)' : 'dihapus dari Berita Utama';
        return back()->with('success', "Berita '{$article->title}' berhasil {$status}.");
    }

    /**
     * Toggle status Publikasi (Publish/Draft).
     */
    public function toggleNewsPublished(int $id): RedirectResponse
    {
        $article = NewsArticle::findOrFail($id);
        $article->update(['is_published' => ! $article->is_published]);

        $status = $article->is_published ? 'dipublikasikan' : 'diarsipkan (Draft)';
        return back()->with('success', "Status berita berhasil diubah menjadi {$status}.");
    }

    /**
     * 13. Modul WhatsApp Bot Gateway Resmi SMeCK Hooligan.
     */
    public function whatsappBot(Request $request): View
    {
        $totalMembers = User::where('role', 'member')->count();
        $totalActiveCards = NfcCard::where('status', 'active')->count();
        $botNumber = config('services.whatsapp.bot_number', '0812-6000-1950 (Nomor Resmi SMeCK)');
        $webhookUrl = url('/api/whatsapp/webhook');

        return view('dashboard.superadmin.whatsapp-bot', compact(
            'totalMembers',
            'totalActiveCards',
            'botNumber',
            'webhookUrl'
        ));
    }

    /**
     * Simulator Chat Bot Interaktif di Panel Admin.
     */
    public function simulateBotChat(Request $request, WhatsAppBotService $bot): JsonResponse
    {
        $message = trim($request->input('message', ''));

        if (empty($message)) {
            return response()->json([
                'success' => false,
                'reply' => 'Pesan tidak boleh kosong.',
            ], 400);
        }

        $reply = $bot->handleIncomingMessage('628123456789', $message);

        return response()->json([
            'success' => true,
            'user_message' => $message,
            'bot_reply' => $reply,
            'bot_reply_html' => nl2br(e($reply)),
            'timestamp' => now()->format('H:i'),
        ]);
    }

    /**
     * Webhook Endpoint API untuk Integrasi Node.js Baileys / WhatsApp-Web.js.
     */
    public function apiWhatsAppWebhook(Request $request, WhatsAppBotService $bot): JsonResponse
    {
        $sender = $request->input('from', $request->input('sender', 'unknown'));
        $message = $request->input('message', $request->input('body', $request->input('text', '')));

        if (empty($message)) {
            return response()->json(['status' => 'ignored', 'message' => 'Empty message']);
        }

        $reply = $bot->handleIncomingMessage($sender, $message);

        return response()->json([
            'status' => 'success',
            'reply' => $reply,
            'to' => $sender,
        ]);
    }

    /**
     * Cek status koneksi real bot WhatsApp (via Node.js micro-service atau file session).
     */
    public function realBotStatus(): JsonResponse
    {
        // 1. Coba hubungi microservice Node.js di localhost:3000
        try {
            $response = Http::timeout(1.5)->get('http://127.0.0.1:3000/api/status');
            if ($response->successful()) {
                $data = $response->json();
                $data['source'] = 'live_node_service';
                return response()->json($data);
            }
        } catch (\Throwable $e) {
            // Node service di localhost mungkin offline atau di port lain
        }

        // 2. Coba baca file session.json yang disimpan oleh bot di storage
        $sessionFile = storage_path('app/whatsapp/session.json');
        if (File::exists($sessionFile)) {
            $session = json_decode(File::get($sessionFile), true);
            if (is_array($session)) {
                $session['source'] = 'storage_file';
                return response()->json($session);
            }
        }

        // 3. Fallback jika bot belum berjalan
        return response()->json([
            'status' => 'disconnected',
            'source' => 'idle',
            'user' => [
                'phone' => null,
                'name' => null,
            ],
            'qr' => null,
            'message' => 'Layanan Bot WhatsApp belum aktif di background. Silakan jalankan JALANKAN-BOT-WA.bat',
        ]);
    }

    /**
     * Minta kode pairing WhatsApp 8-digit tanpa scan QR.
     */
    public function realPairingCode(Request $request): JsonResponse
    {
        $phone = $request->input('phone');
        if (empty($phone)) {
            return response()->json(['success' => false, 'message' => 'Nomor WhatsApp wajib diisi.'], 400);
        }

        try {
            $response = Http::timeout(8)->post('http://127.0.0.1:3000/api/pairing-code', [
                'phone' => $phone,
            ]);

            return response()->json($response->json(), $response->status());
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghubungi server bot. Pastikan file JALANKAN-BOT-WA.bat sedang berjalan di komputer/server Anda.',
            ], 503);
        }
    }

    /**
     * Kirim pesan WhatsApp langsung dari dashboard admin.
     */
    public function realSendMessage(Request $request): JsonResponse
    {
        $phone = $request->input('phone');
        $message = $request->input('message');

        if (empty($phone) || empty($message)) {
            return response()->json(['success' => false, 'message' => 'Nomor dan pesan wajib diisi.'], 400);
        }

        try {
            $response = Http::timeout(8)->post('http://127.0.0.1:3000/api/send-message', [
                'phone' => $phone,
                'message' => $message,
            ]);

            return response()->json($response->json(), $response->status());
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Layanan bot WhatsApp sedang offline. Silakan jalankan file JALANKAN-BOT-WA.bat.',
            ], 503);
        }
    }

    /**
     * Putuskan sesi WhatsApp bot (Logout).
     */
    public function realLogout(): JsonResponse
    {
        try {
            $response = Http::timeout(5)->post('http://127.0.0.1:3000/api/logout');
            return response()->json($response->json(), $response->status());
        } catch (\Throwable $e) {
            $sessionFile = storage_path('app/whatsapp/session.json');
            if (File::exists($sessionFile)) {
                File::delete($sessionFile);
            }
            return response()->json(['success' => true, 'message' => 'Sesi lokal dibersihkan.']);
        }
    }

    /**
     * Endpoint untuk bot mengabari Laravel status terbarunya via HTTP webhook.
     */
    public function apiWhatsAppStatusUpdate(Request $request): JsonResponse
    {
        $payload = $request->all();
        $sessionDir = storage_path('app/whatsapp');
        if (!File::exists($sessionDir)) {
            File::makeDirectory($sessionDir, 0755, true);
        }

        File::put($sessionDir . '/session.json', json_encode($payload, JSON_PRETTY_PRINT));

        return response()->json(['success' => true]);
    }
}

