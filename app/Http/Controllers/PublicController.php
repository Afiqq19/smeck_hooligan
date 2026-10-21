<?php

namespace App\Http\Controllers;

use App\Models\Chant;
use App\Models\Chapter;
use App\Models\MatchTicket;
use App\Models\SmeckPartner;
use Illuminate\Contracts\View\View;
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

        return view('pages.landing', compact(
            'featuredChants',
            'upcomingMatch',
            'chaptersCount',
            'partners'
        ));
    }

    /**
     * Endpoint API Verifikasi KTA & Tap NFC Suporter.
     */
    public function checkKta(Request $request)
    {
        $query = trim($request->get('query', ''));
        if (empty($query)) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan masukkan nomor UID NFC atau Nomor Induk Anggota (NIA).',
            ], 400);
        }

        // 1. Cari berdasarkan UID Chip NFC (Case-Insensitive)
        $card = \App\Models\NfcCard::whereRaw('LOWER(uid_number) = ?', [strtolower($query)])
            ->with(['user.profile', 'user.chapter'])
            ->first();

        // 2. Jika tidak ditemukan, cari berdasarkan NIA Anggota
        if (! $card) {
            $profile = \App\Models\MemberProfile::whereRaw('LOWER(nia) = ?', [strtolower($query)])
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

        // 3. Jika masih tidak ditemukan, coba cari berdasarkan NIK KTP (jika admin/pemilik mencari)
        if (! isset($user) || ! $user) {
            $profile = \App\Models\MemberProfile::where('nik_ktp', $query)
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
                'message' => "Data suporter dengan kartu/nomor '{$query}' tidak terdaftar dalam database resmi SMeCK Hooligan.",
            ], 404);
        }

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
                'uid_number' => $card?->uid_number ?? 'KTA Digital (Non-Fisik)',
                'card_vendor' => strtoupper($card?->card_vendor ?? 'Digital'),
                'card_status' => $card?->status ?? 'active',
                'user_status' => $user->status,
                'avatar_letter' => strtoupper(substr($user->name, 0, 1)),
                'is_verified' => true,
            ],
        ]);
    }
}
