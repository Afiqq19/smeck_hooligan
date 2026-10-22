<?php

namespace App\Http\Controllers;

use App\Models\Chant;
use App\Models\Chapter;
use App\Models\LeagueStanding;
use App\Models\MatchTicket;
use App\Models\NewsArticle;
use App\Models\SmeckPartner;
use App\Services\PtLibScraperService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    /**
     * Menampilkan Landing Page Utama SMeCK Hooligan.
     */
    public function index(): View
    {
        $featuredChants = Chant::where('is_featured', true)->take(3)->get();
        $upcomingMatch = MatchTicket::where('status', 'open')->orderBy('match_time', 'asc')->first();
        $chaptersCount = Chapter::where('is_active', true)->count();
        $partners = SmeckPartner::where('is_active', true)->take(4)->get();

        // Opsi B: Ambil Klasemen Resmi Liga 2 PT LIB
        $standings = LeagueStanding::orderBy('position')->get();
        if ($standings->isEmpty()) {
            app(PtLibScraperService::class)->syncStandings();
            $standings = LeagueStanding::orderBy('position')->get();
        }
        $psmsStanding = $standings->firstWhere('is_our_team', true);

        // Pilihan 3: Berita Terkini & Maklumat DPP
        $latestNews = NewsArticle::where('is_published', true)
            ->orderBy('is_featured', 'desc')
            ->latest()
            ->take(5)
            ->get();

        return view('pages.landing', compact(
            'featuredChants',
            'upcomingMatch',
            'chaptersCount',
            'partners',
            'standings',
            'psmsStanding',
            'latestNews'
        ));
    }

    /**
     * Halaman Publik Baca Berita / Maklumat Pers DPP.
     */
    public function showNews(string $slug): View
    {
        $article = NewsArticle::where('slug', $slug)->where('is_published', true)->firstOrFail();
        $article->increment('views_count');

        $relatedNews = NewsArticle::where('is_published', true)
            ->where('id', '!=', $article->id)
            ->latest()
            ->take(3)
            ->get();

        return view('pages.news-detail', compact('article', 'relatedNews'));
    }

    /**
     * API Get Data Berita untuk Modal Baca Cepat di Landing Page.
     */
    public function getNewsJson(int $id): JsonResponse
    {
        $article = NewsArticle::where('id', $id)->where('is_published', true)->first();

        if (! $article) {
            return response()->json(['success' => false, 'message' => 'Berita tidak ditemukan.'], 404);
        }

        $article->increment('views_count');

        return response()->json([
            'success' => true,
            'article' => [
                'id' => $article->id,
                'title' => $article->title,
                'slug' => $article->slug,
                'category' => $article->category,
                'summary' => $article->summary,
                'content' => nl2br(e($article->content)),
                'raw_content' => $article->content,
                'image_url' => $article->image_url,
                'author_name' => $article->author_name,
                'views_count' => $article->views_count,
                'published_date' => $article->published_at ? $article->published_at->format('d M Y') : $article->created_at->format('d M Y'),
                'share_url' => route('news.show', $article->slug),
            ]
        ]);
    }

    /**
     * Endpoint API Verifikasi KTA & Tap NFC Suporter.
     */
    public function checkKta(Request $request)
    {
        $rawQuery = trim($request->input('query', $request->input('identifier', '')));
        $cleanQuery = str_replace([' ', '-', ':', '.'], '', strtolower($rawQuery));

        if (empty($rawQuery)) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan masukkan nomor kartu KTA, nomor UID NFC, atau Nomor Induk Anggota (NIA).',
            ], 400);
        }

        // 1. Cari berdasarkan Nomor Kartu Fisik (card_number) atau UID Chip NFC
        $card = \App\Models\NfcCard::where(function ($q) use ($rawQuery, $cleanQuery) {
            $q->whereRaw('LOWER(uid_number) = ?', [strtolower($rawQuery)])
                ->orWhereRaw('REPLACE(REPLACE(LOWER(uid_number), ":", ""), "-", "") = ?', [$cleanQuery])
                ->orWhereRaw('LOWER(card_number) = ?', [strtolower($rawQuery)])
                ->orWhereRaw('REPLACE(REPLACE(LOWER(card_number), " ", ""), "-", "") = ?', [$cleanQuery]);
        })->with(['user.profile', 'user.chapter'])->first();

        // 2. Jika tidak ditemukan, cari berdasarkan NIA Anggota
        if (! $card) {
            $profile = \App\Models\MemberProfile::whereRaw('LOWER(nia) = ?', [strtolower($rawQuery)])
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

        // 3. Jika masih tidak ditemukan, coba cari berdasarkan NIK KTP
        if (! isset($user) || ! $user) {
            $profile = \App\Models\MemberProfile::where('nik_ktp', $rawQuery)
                ->with(['user.chapter', 'user.nfcCard'])
                ->first();

            if ($profile && $profile->user) {
                $user = $profile->user;
                $card = $user->nfcCard;
            }
        }

        if (! isset($user) || ! $user) {
            return response()->json([
                'success' => false,
                'message' => "Data suporter dengan kartu/nomor '{$rawQuery}' tidak terdaftar dalam database resmi SMeCK Hooligan.",
            ], 404);
        }

        // Format nomor kartu yang elegan & aman untuk publik (sembunyikan raw chip UID)
        $displayCardNo = $card?->card_number;
        if (! $displayCardNo) {
            $displayCardNo = '6032-9800-2024-1950';
            if ($card) {
                $card->update(['card_number' => $displayCardNo]);
            }
        }

        // Masked version untuk privasi: 6032 •••• •••• 1950
        $parts = explode('-', str_replace(' ', '-', $displayCardNo));
        $maskedCard = count($parts) >= 4
            ? $parts[0] . ' •••• •••• ' . $parts[3]
            : substr($displayCardNo, 0, 4) . ' •••• •••• ' . substr($displayCardNo, -4);

        // Cek status keaktifan suporter & kartu
        $isSuspended = ($user->status === 'suspended') || ($card?->status === 'blocked');
        $effectiveCardStatus = $isSuspended ? 'suspended' : ($card?->status ?? 'active');

        return response()->json([
            'success' => true,
            'data' => [
                'name' => $user->name,
                'nia' => $profile?->nia ?? 'BELUM DITERBITKAN',
                'chapter' => $user->chapter?->name ?? 'DPP SMeCK Hooligan Pusat',
                'chapter_code' => $user->chapter?->region_code ?? 'PUSAT',
                'chapter_address' => $user->chapter?->basecamp_address ?? 'Medan, Sumatera Utara',
                'chapter_leader' => $user->chapter?->leader_name ?? 'DPP SMeCK',
                'blood_type' => $profile?->blood_type ?? '-',
                'shirt_size' => $profile?->shirt_size ?? '-',
                'joined_date' => $profile?->joined_date ? $profile->joined_date->format('d F Y') : ($user->created_at ? $user->created_at->format('d F Y') : '-'),
                'card_number' => $displayCardNo,
                'card_number_masked' => $maskedCard,
                'card_vendor' => strtoupper($card?->card_vendor ?? 'Digital Official'),
                'card_status' => $effectiveCardStatus,
                'user_status' => $user->status,
                'avatar_letter' => strtoupper(substr($user->name, 0, 1)),
                'is_verified' => ! $isSuspended,
                'is_suspended' => $isSuspended,
                'status_message' => $isSuspended
                    ? 'PERINGATAN: KTA INI TIDAK AKTIF / DITANGGUHKAN (SUSPENDED) KARENA SANKSI DISIPLIN ORGANISASI!'
                    : 'ANGGOTA RESMI TERVERIFIKASI DPP SMECK HOOLIGAN',
            ],
        ]);
    }
}
