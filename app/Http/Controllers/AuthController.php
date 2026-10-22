<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\MemberProfile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Tampilkan Halaman Login.
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * Proses Login Pengguna.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']], $remember)) {
            $user = Auth::user();

            // Cek apakah email sudah diverifikasi (khusus role member)
            if (! $user->hasVerifiedEmail() && $user->role === 'member') {
                session([
                    'registered_email' => $user->email,
                    'verify_user_id' => $user->id,
                ]);
                Auth::logout();

                return redirect()->route('verification.notice')
                    ->with('error', 'Alamat email Anda belum diverifikasi. Silakan periksa inbox email Anda atau kirim ulang tautan verifikasi.');
            }

            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * Tampilkan Halaman Pendaftaran Anggota Baru.
     */
    public function showRegisterForm(): View
    {
        $chapters = Chapter::where('is_active', true)->get();

        return view('auth.register', compact('chapters'));
    }

    /**
     * Proses Pendaftaran Anggota Baru.
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone_number' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'nik_ktp' => ['required', 'string', 'size:16', 'unique:member_profiles,nik_ktp'],
            'blood_type' => ['required', 'in:A,B,AB,O'],
            'chapter_id' => ['required', 'exists:chapters,id'],
            'shirt_size' => ['nullable', 'string', 'max:10'],
            'gender' => ['required', 'in:L,P'],
        ]);

        // Buat Akun Pengguna (email_verified_at tetap NULL sampai klik link di email)
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'chapter_id' => $validated['chapter_id'],
            'role' => 'member',
            'status' => 'active',
        ]);

        // Generate NIA (Nomor Induk Anggota SMeCK)
        $currentYear = date('Y');
        $randomSeq = str_pad($user->id, 4, '0', STR_PAD_LEFT);
        $nia = "SMCK-{$currentYear}-{$randomSeq}";

        // Simpan Profil Anggota
        MemberProfile::create([
            'user_id' => $user->id,
            'nia' => $nia,
            'nik_ktp' => $validated['nik_ktp'],
            'phone_number' => $validated['phone_number'],
            'blood_type' => $validated['blood_type'],
            'shirt_size' => $validated['shirt_size'] ?? 'L',
            'gender' => $validated['gender'],
            'joined_date' => now(),
        ]);

        // Kirim email notifikasi verifikasi resmi Laravel
        try {
            $user->sendEmailVerificationNotification();
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Email verification send failed: '.$e->getMessage());
        }

        // Kirim juga notifikasi ke WhatsApp pengguna otomatis jika nomor HP diisi
        try {
            if (! empty($validated['phone_number'])) {
                $verificationUrl = URL::temporarySignedRoute(
                    'verification.verify',
                    now()->addMinutes(1440),
                    [
                        'id' => $user->id,
                        'hash' => sha1($user->getEmailForVerification()),
                    ]
                );

                $outboxDir = storage_path('app/whatsapp');
                if (! File::exists($outboxDir)) {
                    File::makeDirectory($outboxDir, 0755, true);
                }
                $outboxFile = $outboxDir . '/outbox.json';
                $queue = File::exists($outboxFile) ? json_decode(File::get($outboxFile), true) : [];
                $waMessage = "🟢 *PORTAL RESMI SMECK HOOLIGAN*\n\n"
                    . "Halo Sdra/i *{$user->name}*!\n"
                    . "Selamat! Pendaftaran Anda di Portal Suporter SMeCK Hooligan telah berhasil diterima.\n\n"
                    . "📋 *NIA Resmi:* `{$nia}`\n"
                    . "📧 *Email Terdaftar:* `{$user->email}`\n\n"
                    . "Untuk mengaktifkan akun dan mengakses KTA Digital Anda, silakan klik tautan verifikasi resmi berikut:\n"
                    . "👉 {$verificationUrl}\n\n"
                    . "Ayam Kinantan Tak Kan Pernah Berjuang Sendirian!\n"
                    . "*Sada Roha • Salam Sada Roha!*";
                $queue[] = [
                    'id' => uniqid('msg_'),
                    'phone' => $validated['phone_number'],
                    'message' => $waMessage,
                    'created_at' => now()->toIso8601String(),
                ];
                File::put($outboxFile, json_encode($queue, JSON_PRETTY_PRINT));
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('WhatsApp registration notification failed: '.$e->getMessage());
        }

        // Simpan info registrasi di session
        session([
            'registered_email' => $user->email,
            'verify_user_id' => $user->id,
        ]);

        return redirect()->route('verification.notice')
            ->with('success', 'Pendaftaran berhasil! Tautan verifikasi telah dikirimkan ke email & WhatsApp Anda. Silakan klik link tersebut untuk langsung login.');
    }

    /**
     * Tampilkan Halaman Pemberitahuan Verifikasi Email.
     */
    public function showVerifyNotice(Request $request): View|RedirectResponse
    {
        $userId = session('verify_user_id') ?? auth()->id();
        $user = $userId ? User::find($userId) : null;

        if ($user && $user->hasVerifiedEmail()) {
            return redirect()->route('member.dashboard');
        }

        return view('auth.verify-email');
    }

    /**
     * Proses Verifikasi Email melalui Link Bertanda Tangan (Signed URL).
     * Saat diklik, langsung verifikasi dan login otomatis.
     */
    public function verifyEmail(Request $request, $id, $hash): RedirectResponse
    {
        $user = User::findOrFail($id);

        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            abort(403, 'Tautan verifikasi tidak valid atau telah dimodifikasi.');
        }

        if (! $request->hasValidSignature()) {
            session([
                'registered_email' => $user->email,
                'verify_user_id' => $user->id,
            ]);

            return redirect()->route('verification.notice')
                ->with('error', 'Tautan verifikasi telah kedaluwarsa. Silakan klik tombol kirim ulang di bawah ini.');
        }

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        // Login otomatis pengguna setelah verifikasi berhasil
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('member.dashboard')
            ->with('success', 'Email berhasil diverifikasi! Selamat datang di Portal Resmi SMeCK Hooligan.');
    }

    /**
     * Kirim Ulang Tautan Verifikasi Email.
     */
    public function resendVerification(Request $request): RedirectResponse
    {
        $email = $request->input('email') ?? session('registered_email') ?? auth()->user()?->email;
        $user = $email ? User::where('email', $email)->first() : null;

        if (! $user) {
            return back()->with('error', 'Akun pengguna tidak ditemukan.');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('member.dashboard');
        }

        try {
            $user->sendEmailVerificationNotification();
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Email verification resend failed: '.$e->getMessage());
        }

        // Kirim ulang juga notifikasi WhatsApp jika nomor HP tersedia
        try {
            $phone = $user->profile?->phone_number;
            if ($phone) {
                $verificationUrl = URL::temporarySignedRoute(
                    'verification.verify',
                    now()->addMinutes(1440),
                    [
                        'id' => $user->id,
                        'hash' => sha1($user->getEmailForVerification()),
                    ]
                );

                $outboxDir = storage_path('app/whatsapp');
                if (! File::exists($outboxDir)) {
                    File::makeDirectory($outboxDir, 0755, true);
                }
                $outboxFile = $outboxDir . '/outbox.json';
                $queue = File::exists($outboxFile) ? json_decode(File::get($outboxFile), true) : [];
                $waMessage = "🟢 *KIRIM ULANG VERIFIKASI SMECK HOOLIGAN*\n\n"
                    . "Halo *{$user->name}*,\n"
                    . "Berikut adalah tautan verifikasi akun terbaru Anda:\n"
                    . "👉 {$verificationUrl}\n\n"
                    . "Silakan klik link tersebut untuk mengaktifkan akun dan KTA Digital Anda.";
                $queue[] = [
                    'id' => uniqid('msg_'),
                    'phone' => $phone,
                    'message' => $waMessage,
                    'created_at' => now()->toIso8601String(),
                ];
                File::put($outboxFile, json_encode($queue, JSON_PRETTY_PRINT));
            }
        } catch (\Throwable $e) {
            // ignore
        }

        session([
            'registered_email' => $user->email,
            'verify_user_id' => $user->id,
        ]);

        return back()->with('status', 'verification-link-sent')
            ->with('success', 'Tautan verifikasi baru berhasil dikirim ke alamat email & WhatsApp: '.$user->email);
    }

    /**
     * Keluar dari Sistem (Logout).
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('status', 'Anda telah berhasil keluar.');
    }
}
