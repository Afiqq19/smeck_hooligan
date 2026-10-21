<?php

namespace App\Http\Controllers\Chapter;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\ChapterDocumentation;
use App\Models\EventAttendance;
use App\Models\NfcCard;
use App\Models\TicketAllocation;
use App\Models\User;
use App\Models\VoluntaryDue;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
}
