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

// =========================================================================
// 2. RUTE AUTENTIKASI (LOGIN & REGISTER)
// =========================================================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

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
    Route::get('/dues', [MemberDashboardController::class, 'dues'])->name('dues');
    Route::post('/dues', [MemberDashboardController::class, 'storeDues'])->name('dues.store');
    Route::get('/emergency-card', [MemberDashboardController::class, 'emergencyCard'])->name('emergency');
    Route::post('/emergency-card/toggle', [MemberDashboardController::class, 'toggleCardBlock'])->name('emergency.toggle');

    // Menu Ekstra Budaya Tribun (Prompt 6)
    Route::get('/chants', [MemberDashboardController::class, 'chants'])->name('chants');
    Route::get('/tickets', [MemberDashboardController::class, 'tickets'])->name('tickets');
    Route::get('/store', [MemberDashboardController::class, 'store'])->name('store');
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
    Route::get('/nfc-scan', [ChapterDashboardController::class, 'nfcScanner'])->name('scanner');
    Route::post('/nfc-scan/process', [ChapterDashboardController::class, 'processScan'])->name('scanner.process');
    Route::get('/dues', [ChapterDashboardController::class, 'dues'])->name('dues');
    Route::post('/dues/{id}/verify', [ChapterDashboardController::class, 'verifyDue'])->name('dues.verify');

    // Menu Ekstra Budaya Tribun (Prompt 6)
    Route::get('/tickets', [ChapterDashboardController::class, 'ticketQuota'])->name('tickets');
    Route::get('/documentations', [ChapterDashboardController::class, 'documentations'])->name('documentations');
    Route::post('/documentations', [ChapterDashboardController::class, 'storeDocumentation'])->name('documentations.store');
});

// =========================================================================
// 6. ROUTE GROUP: SUPER ADMIN (PENGURUS PUSAT SMeCK HOOLIGAN)
// =========================================================================
// Prefix: /superadmin | Middleware: auth, role:super_admin
Route::middleware(['auth', 'role:super_admin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    // Menu Utama Pengurus Pusat
    Route::get('/dashboard', [SuperAdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/chapters', [SuperAdminDashboardController::class, 'chapters'])->name('chapters');
    Route::post('/chapters', [SuperAdminDashboardController::class, 'storeChapter'])->name('chapters.store');
    Route::get('/nfc-activation', [SuperAdminDashboardController::class, 'nfcActivation'])->name('nfc');
    Route::post('/nfc-activation/link', [SuperAdminDashboardController::class, 'linkNfcCard'])->name('nfc.link');
    Route::get('/broadcast', [SuperAdminDashboardController::class, 'broadcast'])->name('broadcast');
    Route::post('/broadcast/send', [SuperAdminDashboardController::class, 'sendBroadcast'])->name('broadcast.send');

    // Menu Ekstra Budaya Tribun (Prompt 6)
    Route::get('/tickets', [SuperAdminDashboardController::class, 'ticketDistribution'])->name('tickets');
    Route::post('/tickets/allocate', [SuperAdminDashboardController::class, 'storeTicketAllocation'])->name('tickets.allocate');
    Route::get('/partners', [SuperAdminDashboardController::class, 'partners'])->name('partners');
    Route::post('/partners', [SuperAdminDashboardController::class, 'storePartner'])->name('partners.store');
});

// =========================================================================
// 7. ROUTE DEPLOYMENT & SERVER UTILITY (AUTO DEPLOY & STORAGE FALLBACK)
// =========================================================================
require __DIR__ . '/deploy.php';

