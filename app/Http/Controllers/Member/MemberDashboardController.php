<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Chant;
use App\Models\MatchTicket;
use App\Models\NfcCard;
use App\Models\StoreProduct;
use App\Models\VoluntaryDue;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MemberDashboardController extends Controller
{
    /**
     * Tampilan Utama Dashboard Anggota (KTA Digital & QR Code).
     */
    public function index(Request $request): View
    {
        $user = $request->user()->load(['profile', 'chapter', 'nfcCard', 'voluntaryDues', 'attendances']);

        $recentDues = $user->voluntaryDues()->latest()->take(3)->get();
        $recentAttendances = $user->attendances()->latest()->take(3)->get();
        $upcomingMatch = MatchTicket::where('status', 'open')->orderBy('match_time', 'asc')->first();

        return view('dashboard.member.index', compact('user', 'recentDues', 'recentAttendances', 'upcomingMatch'));
    }

    /**
     * Halaman Profil Saya.
     */
    public function profile(Request $request): View
    {
        $user = $request->user()->load('profile', 'chapter');

        return view('dashboard.member.profile', compact('user'));
    }

    /**
     * Halaman Iuran & Kas Suka Rela.
     */
    public function dues(Request $request): View
    {
        $user = $request->user()->load('chapter');
        $dues = VoluntaryDue::where('user_id', $user->id)->latest()->get();

        return view('dashboard.member.dues', compact('user', 'dues'));
    }

    /**
     * Simpan Iuran Suka Rela Baru.
     */
    public function storeDues(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:10000'],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);

        VoluntaryDue::create([
            'user_id' => $request->user()->id,
            'chapter_id' => $request->user()->chapter_id ?? 1,
            'amount' => $validated['amount'],
            'status' => 'pending',
            'notes' => $validated['notes'],
        ]);

        return back()->with('success', 'Bukti iuran sukarela berhasil dikirim dan menunggu verifikasi Bendahara Chapter!');
    }

    /**
     * Lapor Kartu Darurat (Blokir NFC jika hilang).
     */
    public function emergencyCard(Request $request): View
    {
        $user = $request->user()->load('nfcCard');

        return view('dashboard.member.emergency', compact('user'));
    }

    /**
     * Proses Blokir / Buka Blokir Kartu NFC.
     */
    public function toggleCardBlock(Request $request): RedirectResponse
    {
        $user = $request->user();
        $card = NfcCard::where('user_id', $user->id)->first();

        if (! $card) {
            return back()->with('error', 'Kartu NFC fisik Anda belum diterbitkan oleh Admin Pusat.');
        }

        $newStatus = $card->status === 'blocked' ? 'active' : 'blocked';
        $card->update(['status' => $newStatus]);

        $message = $newStatus === 'blocked'
            ? 'Kartu fisik NFC Anda telah DINONAKTIFKAN/DIBLOKIR demi keamanan.'
            : 'Kartu fisik NFC Anda telah DIAKTIFKAN kembali.';

        return back()->with('success', $message);
    }

    /**
     * [Prompt 6] Pustaka Chants Tribun PSMS Medan.
     */
    public function chants(): View
    {
        $chants = Chant::latest()->get();

        return view('dashboard.member.chants', compact('chants'));
    }

    /**
     * [Prompt 6] Tiket & Pendaftaran Awaydays.
     */
    public function tickets(): View
    {
        $matches = MatchTicket::where('status', 'open')->orderBy('match_time', 'asc')->get();

        return view('dashboard.member.tickets', compact('matches'));
    }

    /**
     * [Prompt 6] SMeCK Store Merchandise.
     */
    public function store(): View
    {
        $products = StoreProduct::where('is_active', true)->latest()->get();

        return view('dashboard.member.store', compact('products'));
    }
}
