<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Deployment & Server Utility Routes - SMeCK Hooligan Official
|--------------------------------------------------------------------------
| Khusus untuk otomasi pembaruan kode di server (Git pull, Composer, Migration,
| Optimize Cache) serta route helper storage file.
| Dipisahkan dari web.php agar struktur routing tetap rapi dan terisolasi.
*/

// =========================================================================
// 1. ROUTE UTAMA: AUTO DEPLOY / UPDATE KODE DI SERVER LAPTOP 2 / PRODUCTION
// =========================================================================
Route::get('/update-rahasia-smeckhooligan', function () {
    $repoDir = base_path(); // Alamat folder root Laravel di server

    // AUTO-PATCH .env untuk Timezone & APP_NAME SMeCK Hooligan
    $envFile = base_path('.env');
    if (file_exists($envFile)) {
        $env = file_get_contents($envFile);
        $env = preg_replace('/^APP_TIMEZONE=.*/m', 'APP_TIMEZONE=Asia/Jakarta', $env);
        $env = preg_replace('/^APP_NAME=.*/m', 'APP_NAME="SMeCK Hooligan Official"', $env);
        if (!str_contains($env, 'APP_TIMEZONE=')) {
            $env .= "\nAPP_TIMEZONE=Asia/Jakarta\n";
        }
        if (!str_contains($env, 'APP_NAME=')) {
            $env .= "\nAPP_NAME=\"SMeCK Hooligan Official\"\n";
        }
        file_put_contents($envFile, $env);
    }

    // 1. Mantra Sakti mengatasi "Dubious Ownership" di server Git
    shell_exec("git config --global --add safe.directory \"$repoDir\"");

    // 2. Eksekusi Perintah Pembaruan Git dari Repository GitHub
    $output1 = shell_exec("cd \"$repoDir\" && git fetch --all 2>&1");
    // Gunakan 'git reset' yang aman (mereset ke branch default main)
    $output2 = shell_exec("cd \"$repoDir\" && git reset --hard origin/main 2>&1");

    // 3. Pakai Composer dengan environment temporary
    putenv('COMPOSER_HOME=/tmp');
    $output3 = shell_exec("cd \"$repoDir\" && composer install --no-interaction --prefer-dist --optimize-autoloader 2>&1");

    // 4. Migrate Database & Seeder
    $output4 = shell_exec("cd \"$repoDir\" && php artisan migrate --force 2>&1");

    // Eksekusi DatabaseSeeder jika diperlukan
    $output_dbseed = file_exists(database_path('seeders/DatabaseSeeder.php'))
        ? shell_exec("cd \"$repoDir\" && php artisan db:seed --force 2>&1")
        : "DatabaseSeeder tidak ditemukan.";

    // 5. Jalankan clear cache & optimize (Penting untuk production!)
    $output_optimize = shell_exec("cd \"$repoDir\" && php artisan optimize:clear 2>&1 && php artisan optimize 2>&1");
    $output_link = shell_exec("cd \"$repoDir\" && php artisan storage:link --force 2>&1");

    return "<h1 style='color:#16a34a; font-family:sans-serif;'>✅ Berhasil Menarik Kodingan Baru & Update Sistem SMeCK Hooligan oleh MSS!</h1>
            <p style='font-family:sans-serif; color:#666;'>Repositori: <strong>https://github.com/Afiqq19/smeck_hooligan.git</strong></p>
            <h3>Laporan Log Pembaruan:</h3>
            <pre style='background:#0d1117;color:#3fb950;padding:20px;border-radius:12px;font-family:Consolas, monospace;overflow-x:auto;border:1px solid #30363d;'>
[1. GIT FETCH & RESET HARD ORIGIN/MAIN]
" . htmlspecialchars((string) $output1) . "
" . htmlspecialchars((string) $output2) . "

[2. COMPOSER INSTALL OPTIMIZED]
" . htmlspecialchars((string) $output3) . "

[3. DATABASE MIGRATE & SEED]
" . htmlspecialchars((string) $output4) . "
" . htmlspecialchars((string) $output_dbseed) . "

[4. OPTIMIZE & STORAGE LINK]
" . htmlspecialchars((string) $output_optimize) . "
" . htmlspecialchars((string) $output_link) . "
            </pre>
            <p style='font-family:sans-serif; margin-top:20px;'>
                <a href='/' style='background:#15803d;color:white;padding:12px 20px;text-decoration:none;border-radius:8px;font-weight:bold;margin-right:10px;'>🏠 Menuju Landing Page</a>
                <a href='/login' style='background:#1e293b;color:white;padding:12px 20px;text-decoration:none;border-radius:8px;font-weight:bold;'>🔑 Halaman Login</a>
            </p>";
});

// Alias untuk update rahasia (mendukung URL /update-rahasia-smeck & /update-rahasia-mss)
Route::get('/update-rahasia-smeck', function () {
    return redirect('/update-rahasia-smeckhooligan');
});

Route::get('/update-rahasia-mss', function () {
    return redirect('/update-rahasia-smeckhooligan');
});

// Route alias untuk update lama
Route::get('/update-rahasia-tracerstudy', function () {
    return redirect('/update-rahasia-smeckhooligan');
});

// =========================================================================
// 2. ROUTE RAHASIA PEMBUAT AKUN SUPER ADMIN INSTAN
// =========================================================================
Route::get('/buat-akun-admin-smeck', function () {
    // 1. Akun MSS Utama
    $email1 = 'projek.msyafiq19@gmail.com';
    $pass1 = 'rahasia123';
    $user1 = User::firstOrNew(['email' => $email1]);
    $user1->name = 'M. Syafiq (Super Admin MSS)';
    $user1->password = Hash::make($pass1);
    $user1->role = 'super_admin';
    $user1->status = 'active';
    $user1->email_verified_at = now();
    $user1->save();

    // 2. Akun SMeCK Official DPP
    $email2 = 'admin@smeck.id';
    $pass2 = 'password';
    $user2 = User::firstOrNew(['email' => $email2]);
    $user2->name = 'Bani Ginting (Super Admin DPP)';
    $user2->password = Hash::make($pass2);
    $user2->role = 'super_admin';
    $user2->status = 'active';
    $user2->email_verified_at = now();
    $user2->save();

    return "<h1 style='color:#16a34a; font-family:sans-serif;'>✅ 2 Akun Super Admin SMeCK Berhasil Dibuat / Diperbarui!</h1>
            <div style='background:#111827;color:white;padding:20px;border-radius:12px;font-family:sans-serif;border:1px solid #374151;max-width:550px;margin-bottom:15px;'>
                <h3 style='color:#38bdf8;margin-top:0;'>1. Akun MSS (Sesuai Panduan Hosting)</h3>
                <p><strong>Email:</strong> <span style='color:#4ade80;'>{$email1}</span></p>
                <p><strong>Password:</strong> <span style='color:#fbbf24;'>{$pass1}</span></p>
                <p><strong>Role:</strong> <span style='color:#f87171;'>super_admin</span> (Status: Aktif & Terverifikasi)</p>
            </div>
            <div style='background:#111827;color:white;padding:20px;border-radius:12px;font-family:sans-serif;border:1px solid #374151;max-width:550px;'>
                <h3 style='color:#38bdf8;margin-top:0;'>2. Akun SMeCK DPP</h3>
                <p><strong>Email:</strong> <span style='color:#4ade80;'>{$email2}</span></p>
                <p><strong>Password:</strong> <span style='color:#fbbf24;'>{$pass2}</span></p>
                <p><strong>Role:</strong> <span style='color:#f87171;'>super_admin</span> (Status: Aktif & Terverifikasi)</p>
                <p style='margin-top:20px;'><a href='/login' style='background:#16a34a;color:white;padding:10px 18px;text-decoration:none;border-radius:8px;font-weight:bold;display:inline-block;'>👉 Menuju Halaman Login</a></p>
            </div>";
});

// Alias pembuat akun admin lama
Route::get('/buat-akun-admin-mss', function () {
    return redirect('/buat-akun-admin-smeck');
});

// =========================================================================
// 3. ROUTE RAHASIA MENJALANKAN DATABASE SEEDER
// =========================================================================
Route::get('/seed-rahasia-smeck', function () {
    $repoDir = base_path();
    $output = shell_exec("cd \"$repoDir\" && php artisan db:seed --force 2>&1");

    return "<h1 style='color:#16a34a; font-family:sans-serif;'>✅ Database Seeder SMeCK Berhasil Dijalankan!</h1>
            <pre style='background:#0d1117;color:#3fb950;padding:20px;border-radius:12px;font-family:Consolas, monospace;overflow-x:auto;border:1px solid #30363d;'>"
            . htmlspecialchars((string) $output) .
            "</pre>
            <p><a href='/login' style='background:#16a34a;color:white;padding:10px 18px;text-decoration:none;border-radius:8px;font-weight:bold;'>👉 Menuju Login</a></p>";
});

Route::get('/seed-rahasia-smeckhooligan', function () {
    return redirect('/seed-rahasia-smeck');
});

Route::get('/seed-rahasia-mss', function () {
    return redirect('/seed-rahasia-smeck');
});

// =========================================================================
// 4. ROUTE PEMBANTU STORAGE (MENJAMIN GAMBAR/ATTACHMENT TIDAK 404 DI SERVER)
// =========================================================================
Route::get('storage/{path}', function ($path) {
    $candidates = [
        public_path('storage/' . $path),
        storage_path('app/public/' . $path),
        storage_path('app/private/public/' . $path),
        storage_path('app/private/' . $path),
        storage_path('app/' . $path),
    ];

    foreach ($candidates as $filePath) {
        if (file_exists($filePath) && !is_dir($filePath)) {
            $mime = mime_content_type($filePath) ?: 'application/octet-stream';
            $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
            if ($ext === 'heic' || $ext === 'heif') {
                $mime = 'image/heic';
            }
            return response()->file($filePath, [
                'Content-Type' => $mime,
                'Access-Control-Allow-Origin' => '*',
            ]);
        }
    }
    abort(404, 'File lampiran tidak ditemukan di server.');
})->where('path', '.*');
