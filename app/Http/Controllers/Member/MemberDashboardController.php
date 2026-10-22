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
        $psmsStanding = \App\Models\LeagueStanding::where('is_our_team', true)->first();

        return view('dashboard.member.index', compact('user', 'recentDues', 'recentAttendances', 'upcomingMatch', 'psmsStanding'));
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
     * Update Biodata & Profil Suporter Mandiri.
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validate([
            'phone_number' => ['nullable', 'string', 'max:20'],
            'blood_type' => ['nullable', 'string', 'in:A,B,AB,O'],
            'shirt_size' => ['nullable', 'string', 'in:S,M,L,XL,XXL,3XL'],
            'address' => ['nullable', 'string', 'max:500'],
            'emergency_contact_name' => ['nullable', 'string', 'max:100'],
            'emergency_contact_number' => ['nullable', 'string', 'max:20'],
            'emergency_contact_relation' => ['nullable', 'string', 'max:50'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
        ]);

        $profile = $user->profile ?? new \App\Models\MemberProfile(['user_id' => $user->id]);

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profiles', 'public');
            $profile->profile_photo_path = $path;
        }

        $profile->fill([
            'phone_number' => $validated['phone_number'] ?? $profile->phone_number,
            'blood_type' => $validated['blood_type'] ?? $profile->blood_type,
            'shirt_size' => $validated['shirt_size'] ?? $profile->shirt_size,
            'address' => $validated['address'] ?? $profile->address,
            'emergency_contact_name' => $validated['emergency_contact_name'] ?? $profile->emergency_contact_name,
            'emergency_contact_number' => $validated['emergency_contact_number'] ?? $profile->emergency_contact_number,
            'emergency_contact_relation' => $validated['emergency_contact_relation'] ?? $profile->emergency_contact_relation,
        ]);

        $profile->save();

        return back()->with('success', 'Biodata profil dan ukuran jersey Anda berhasil diperbarui!');
    }

    /**
     * Simpan Iuran Suka Rela Baru dengan Bukti Transfer.
     */
    public function storeDues(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:10000'],
            'notes' => ['nullable', 'string', 'max:255'],
            'payment_proof' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ]);

        $proofPath = null;
        if ($request->hasFile('payment_proof')) {
            $proofPath = $request->file('payment_proof')->store('dues_proofs', 'public');
        }

        VoluntaryDue::create([
            'user_id' => $request->user()->id,
            'chapter_id' => $request->user()->chapter_id ?? 1,
            'amount' => $validated['amount'],
            'payment_proof_path' => $proofPath,
            'status' => 'pending',
            'notes' => $validated['notes'],
        ]);

        return back()->with('success', 'Bukti iuran sukarela berhasil dikirim dan menunggu verifikasi Bendahara Basis!');
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
        $myOrders = \App\Models\TicketOrder::where('user_id', auth()->id())->with('matchTicket')->latest()->get();

        return view('dashboard.member.tickets', compact('matches', 'myOrders'));
    }

    /**
     * Pesan Tiket Pertandingan PSMS (Home maupun Away).
     */
    public function orderTicket(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'match_ticket_id' => ['required', 'exists:match_tickets,id'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:5'],
        ]);

        $match = MatchTicket::findOrFail($validated['match_ticket_id']);

        if ($match->remaining_quota < 1) {
            return back()->with('error', 'Maaf, kuota tiket laga ini sudah habis.');
        }

        $qty = $validated['quantity'] ?? 1;
        $totalAmount = $match->price * $qty;

        \App\Models\TicketOrder::create([
            'match_ticket_id' => $match->id,
            'user_id' => $request->user()->id,
            'chapter_id' => $request->user()->chapter_id ?? 1,
            'total_amount' => $totalAmount,
            'payment_status' => 'paid',
        ]);

        $match->decrement('remaining_quota', $qty);

        return back()->with('success', "Tiket untuk '{$match->match_title}' berhasil dipesan! Tunjukkan KTA fisik Anda di koordinator Chapter saat pengambilan tiket.");
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
