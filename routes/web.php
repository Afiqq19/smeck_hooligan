<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Chapter\ChapterDashboardController;
use App\Http\Controllers\Member\MemberDashboardController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\SuperAdmin\SuperAdminDashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - SMeCK Hooligan Official Portal
|--------------------------------------------------------------------------
| Seluruh rute aplikasi portal suporter PSMS Medan (SMeCK Hooligan).
| Menggunakan multi-role middleware (super_admin, admin_chapter, member).
*/

// =========================================================================
// 1. RUTE PUBLIK (DAPAT DIAKSES TANPA LOGIN)
// =========================================================================
Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/api/check-kta', [PublicController::class, 'checkKta'])->name('api.check-kta');
Route::post('/api/check-kta', [PublicController::class, 'checkKta']);

// =========================================================================
// 2. RUTE AUTENTIKASI (LOGIN & REGISTER)
// =========================================================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Verifikasi Email Anggota Baru
Route::get('/email/verify', [AuthController::class, 'showVerifyNotice'])->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])->name('verification.verify');
Route::post('/email/verification-notification', [AuthController::class, 'resendVerification'])->name('verification.send');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// =========================================================================
// 3. RUTE GATEWAY DASHBOARD (REDIRECT BERDASARKAN ROLE PENGGUNA)
// =========================================================================
Route::get('/dashboard', function () {
    $user = auth()->user();
    if (! $user) {
        return redirect()->route('login');
    }

    return match ($user->role) {
        'super_admin' => redirect()->route('superadmin.dashboard'),
        'admin_chapter' => redirect()->route('chapter.dashboard'),
        default => redirect()->route('member.dashboard'),
    };
})->middleware('auth')->name('dashboard');

// =========================================================================
// 4. ROUTE GROUP: MEMBER (PORTAL ANGGOTA SUPORTER)
// =========================================================================
// Prefix: /member | Middleware: auth, role:member,super_admin,admin_chapter
Route::middleware(['auth', 'role:member,super_admin,admin_chapter'])->prefix('member')->name('member.')->group(function () {
    // Menu Utama Member
    Route::get('/dashboard', [MemberDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [MemberDashboardController::class, 'profile'])->name('profile');
    Route::post('/profile', [MemberDashboardController::class, 'updateProfile'])->name('profile.update');
    Route::get('/dues', [MemberDashboardController::class, 'dues'])->name('dues');
    Route::post('/dues', [MemberDashboardController::class, 'storeDues'])->name('dues.store');
    Route::get('/emergency-card', [MemberDashboardController::class, 'emergencyCard'])->name('emergency');
    Route::post('/emergency-card/toggle', [MemberDashboardController::class, 'toggleCardBlock'])->name('emergency.toggle');

    // Menu Ekstra Budaya Tribun (Prompt 6)
    Route::get('/chants', [MemberDashboardController::class, 'chants'])->name('chants');
    Route::get('/tickets', [MemberDashboardController::class, 'tickets'])->name('tickets');
    Route::post('/tickets/order', [MemberDashboardController::class, 'orderTicket'])->name('tickets.order');
    Route::get('/store', [MemberDashboardController::class, 'store'])->name('store');
    Route::get('/self-checkin', [MemberDashboardController::class, 'selfCheckin'])->name('checkin.self');
});

// =========================================================================
// 5. ROUTE GROUP: CHAPTER (PENGURUS BASIS / WILAYAH)
// =========================================================================
// Prefix: /chapter | Middleware: auth, role:admin_chapter,super_admin
Route::middleware(['auth', 'role:admin_chapter,super_admin'])->prefix('chapter')->name('chapter.')->group(function () {
    // Menu Utama Pengurus Wilayah
    Route::get('/dashboard', [ChapterDashboardController::class, 'index'])->name('dashboard');
    Route::get('/verifications', [ChapterDashboardController::class, 'verifications'])->name('verifications');
    Route::post('/verifications/{id}/approve', [ChapterDashboardController::class, 'approveMember'])->name('verifications.approve');
    Route::post('/verifications/{id}/reject', [ChapterDashboardController::class, 'rejectMember'])->name('verifications.reject');
    Route::get('/nfc-scan', [ChapterDashboardController::class, 'nfcScanner'])->name('scanner');
    Route::post('/nfc-scan/process', [ChapterDashboardController::class, 'processScan'])->name('scanner.process');
    Route::get('/dues', [ChapterDashboardController::class, 'dues'])->name('dues');
    Route::post('/dues/cash', [ChapterDashboardController::class, 'storeCashDue'])->name('dues.cash');
    Route::post('/dues/{id}/verify', [ChapterDashboardController::class, 'verifyDue'])->name('dues.verify');

    // Manajemen Anggota Basis & KTA NFC
    Route::get('/members', [ChapterDashboardController::class, 'members'])->name('members');
    Route::post('/members', [ChapterDashboardController::class, 'storeMember'])->name('members.store');
    Route::post('/nfc/block/{cardId}', [ChapterDashboardController::class, 'blockCard'])->name('nfc.block');
    Route::post('/nfc/unblock/{cardId}', [ChapterDashboardController::class, 'unblockCard'])->name('nfc.unblock');
    Route::post('/nfc/replace/{userId}', [ChapterDashboardController::class, 'replaceCard'])->name('nfc.replace');

    // Menu Ekstra Budaya Tribun (Prompt 6) & Check-in Rombongan (Solusi 1)
    Route::get('/tickets', [ChapterDashboardController::class, 'ticketQuota'])->name('tickets');
    Route::post('/tickets/bulk-checkin', [ChapterDashboardController::class, 'bulkCheckinTickets'])->name('tickets.bulk_checkin');
    Route::get('/documentations', [ChapterDashboardController::class, 'documentations'])->name('documentations');
    Route::post('/documentations', [ChapterDashboardController::class, 'storeDocumentation'])->name('documentations.store');
});

// =========================================================================
// 6. ROUTE GROUP: SUPER ADMIN (PENGURUS PUSAT SMeCK HOOLIGAN)
// =========================================================================
// Prefix: /superadmin | Middleware: auth, role:super_admin
Route::middleware(['auth', 'role:super_admin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    // 1. Dashboard Utama Pusat
    Route::get('/dashboard', [SuperAdminDashboardController::class, 'index'])->name('dashboard');

    // 2. Direktori Anggota Nasional
    Route::get('/members', [SuperAdminDashboardController::class, 'members'])->name('members');
    Route::post('/members/{id}/approve', [SuperAdminDashboardController::class, 'approveMember'])->name('members.approve');
    Route::post('/members/{id}/role', [SuperAdminDashboardController::class, 'updateMemberRole'])->name('members.role');
    Route::post('/members/{id}/status', [SuperAdminDashboardController::class, 'toggleMemberStatus'])->name('members.status');

    // 3. Master Chapter Wilayah
    Route::get('/chapters', [SuperAdminDashboardController::class, 'chapters'])->name('chapters');
    Route::post('/chapters', [SuperAdminDashboardController::class, 'storeChapter'])->name('chapters.store');

    // 4. KTA NFC Issuing & Batch Print Preview
    Route::get('/nfc-activation', [SuperAdminDashboardController::class, 'nfcActivation'])->name('nfc');
    Route::post('/nfc-activation/link', [SuperAdminDashboardController::class, 'linkNfcCard'])->name('nfc.link');
    Route::post('/nfc/toggle/{cardId}', [SuperAdminDashboardController::class, 'toggleCardStatus'])->name('nfc.toggle');
    Route::post('/nfc/replace/{userId}', [SuperAdminDashboardController::class, 'replaceCard'])->name('nfc.replace');

    // 5. Tiket Pertandingan & Alokasi Quota
    Route::get('/tickets', [SuperAdminDashboardController::class, 'ticketDistribution'])->name('tickets');
    Route::post('/tickets/match', [SuperAdminDashboardController::class, 'storeMatch'])->name('tickets.match');
    Route::post('/tickets/allocate', [SuperAdminDashboardController::class, 'storeTicketAllocation'])->name('tickets.allocate');

    // 6. Galeri Koreo & Dokumentasi Nasional
    Route::get('/documentations', [SuperAdminDashboardController::class, 'documentations'])->name('documentations');
    Route::post('/documentations', [SuperAdminDashboardController::class, 'storeDocumentation'])->name('documentations.store');

    // 7. Pustaka Chant & Lagu Tribun
    Route::get('/chants', [SuperAdminDashboardController::class, 'chants'])->name('chants');
    Route::post('/chants', [SuperAdminDashboardController::class, 'storeChant'])->name('chants.store');
    Route::post('/chants/{id}/featured', [SuperAdminDashboardController::class, 'toggleChantFeatured'])->name('chants.featured');

    // 8. Kas & Donasi Koreo Nasional
    Route::get('/finance', [SuperAdminDashboardController::class, 'finance'])->name('finance');

    // 9. Mitra SMeCK Partner
    Route::get('/partners', [SuperAdminDashboardController::class, 'partners'])->name('partners');
    Route::post('/partners', [SuperAdminDashboardController::class, 'storePartner'])->name('partners.store');
    Route::post('/partners/{id}/toggle', [SuperAdminDashboardController::class, 'togglePartner'])->name('partners.toggle');

    // 10. Maklumat & Broadcast Pengumuman DPP
    Route::get('/broadcast', [SuperAdminDashboardController::class, 'broadcast'])->name('broadcast');
    Route::post('/broadcast/send', [SuperAdminDashboardController::class, 'sendBroadcast'])->name('broadcast.send');

    // 11. Klasemen Liga 2 (Opsi B: PT LIB Scraper Auto-Sync)
    Route::get('/standings', [SuperAdminDashboardController::class, 'standings'])->name('standings');
    Route::post('/standings/sync', [SuperAdminDashboardController::class, 'syncStandings'])->name('standings.sync');
    Route::post('/standings/{id}/update', [SuperAdminDashboardController::class, 'updateStanding'])->name('standings.update');

    // 12. Manajemen Berita & Maklumat Pers DPP (Pilihan 3)
    Route::get('/news', [SuperAdminDashboardController::class, 'news'])->name('news');
    Route::post('/news', [SuperAdminDashboardController::class, 'storeNews'])->name('news.store');
    Route::delete('/news/{id}', [SuperAdminDashboardController::class, 'deleteNews'])->name('news.delete');
    Route::post('/news/{id}/featured', [SuperAdminDashboardController::class, 'toggleNewsFeatured'])->name('news.featured');
    Route::post('/news/{id}/toggle', [SuperAdminDashboardController::class, 'toggleNewsPublished'])->name('news.toggle');

    // 13. Modul WhatsApp Bot Gateway Resmi (Node.js Baileys / Direct WA)
    Route::get('/whatsapp-bot', [SuperAdminDashboardController::class, 'whatsappBot'])->name('whatsapp');
    Route::post('/whatsapp-bot/simulate', [SuperAdminDashboardController::class, 'simulateBotChat'])->name('whatsapp.simulate');
    Route::get('/whatsapp-bot/real-status', [SuperAdminDashboardController::class, 'realBotStatus'])->name('whatsapp.real-status');
    Route::post('/whatsapp-bot/real-pairing', [SuperAdminDashboardController::class, 'realPairingCode'])->name('whatsapp.real-pairing');
    Route::post('/whatsapp-bot/real-send', [SuperAdminDashboardController::class, 'realSendMessage'])->name('whatsapp.real-send');
    Route::post('/whatsapp-bot/real-logout', [SuperAdminDashboardController::class, 'realLogout'])->name('whatsapp.real-logout');
});

// Rute Baca Berita Publik
Route::get('/berita/{slug}', [PublicController::class, 'showNews'])->name('news.show');
Route::get('/api/news/{id}', [PublicController::class, 'getNewsJson'])->name('api.news.show');

// Webhook WhatsApp Bot API (Incoming messages, status updates & outbox queue)
Route::post('/api/whatsapp/webhook', [SuperAdminDashboardController::class, 'apiWhatsAppWebhook'])->name('api.whatsapp.webhook');
Route::get('/api/whatsapp/webhook', [SuperAdminDashboardController::class, 'apiWhatsAppWebhook']);
Route::post('/api/whatsapp/status-update', [SuperAdminDashboardController::class, 'apiWhatsAppStatusUpdate'])->name('api.whatsapp.status-update');
Route::get('/api/whatsapp/outbox', [SuperAdminDashboardController::class, 'apiWhatsAppGetOutbox'])->name('api.whatsapp.outbox');
Route::post('/api/whatsapp/outbox-ack', [SuperAdminDashboardController::class, 'apiWhatsAppAckOutbox'])->name('api.whatsapp.outbox-ack');

// =========================================================================
// 7. ROUTE DEPLOYMENT & SERVER UTILITY (AUTO DEPLOY & STORAGE FALLBACK)
// =========================================================================
require __DIR__ . '/deploy.php';

