<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\MemberProfile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

        // Buat Akun Pengguna
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'chapter_id' => $validated['chapter_id'],
            'role' => 'member',
            'status' => 'active', // langsung aktif untuk kemudahan demo
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

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('member.dashboard')->with('success', 'Selamat bergabung di keluarga besar SMeCK Hooligan! Salam Sada Roha!');
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
