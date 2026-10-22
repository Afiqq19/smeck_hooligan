@extends('layouts.guest')

@section('title', 'Verifikasi Email — SMeCK Hooligan')
@section('meta_description', 'Verifikasi alamat email Anda untuk mengaktifkan akun dan KTA Digital resmi suporter SMeCK Hooligan.')

@section('content')
<section class="min-h-screen pt-24 pb-16 flex items-center justify-center relative overflow-hidden bg-smeck-dark-950 px-4">
    {{-- Glow Lights --}}
    <div class="absolute top-1/4 left-1/3 -translate-y-1/2 w-96 h-96 bg-smeck-green-600/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-10 right-1/4 w-80 h-80 bg-smeck-gold/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-lg relative z-10">
        {{-- Card Container --}}
        <div class="p-8 sm:p-10 rounded-3xl bg-smeck-dark-900 border border-smeck-green-500/30 text-center shadow-2xl relative overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-smeck-green-500 via-smeck-gold to-smeck-green-400"></div>

            {{-- Icon --}}
            <div class="w-20 h-20 mx-auto rounded-3xl bg-smeck-green-500/15 border border-smeck-green-500/30 text-smeck-green-400 flex items-center justify-center text-4xl mb-6 shadow-xl shadow-smeck-green-900/30 animate-pulse">
                <i class="fa-solid fa-envelope-circle-check"></i>
            </div>

            {{-- Title & Email --}}
            <h2 class="font-display font-black text-2xl sm:text-3xl text-white">Cek Kotak Masuk Email Anda!</h2>
            <p class="text-xs sm:text-sm text-white/60 mt-2 mb-4 leading-relaxed">
                Tautan verifikasi aktivasi akun resmi telah dikirimkan ke alamat email Anda:
            </p>

            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/5 border border-white/10 font-mono text-smeck-gold font-bold text-sm mb-6">
                <i class="fa-regular fa-envelope text-white/50"></i>
                <span>{{ session('registered_email') ?? auth()->user()?->email ?? 'email.anda@gmail.com' }}</span>
            </div>

            {{-- Success Message if Resent --}}
            @if (session('status') == 'verification-link-sent' || session('success'))
                <div class="p-4 rounded-2xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-300 text-xs font-semibold mb-6 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-circle-check text-base"></i>
                    <span>{{ session('success') ?? 'Tautan verifikasi baru telah berhasil dikirim ke email Anda!' }}</span>
                </div>
            @endif

            {{-- 3 Step Instructions --}}
            <div class="space-y-2.5 text-left text-xs bg-white/[0.02] border border-white/5 rounded-2xl p-4 mb-6 text-white/70">
                <div class="flex items-start gap-2.5">
                    <span class="w-5 h-5 rounded-full bg-smeck-green-500/20 text-smeck-green-400 font-bold flex items-center justify-center text-[10px] shrink-0 mt-0.5">1</span>
                    <span>Buka aplikasi <strong>Gmail / Email</strong> di smartphone atau laptop Anda.</span>
                </div>
                <div class="flex items-start gap-2.5">
                    <span class="w-5 h-5 rounded-full bg-smeck-green-500/20 text-smeck-green-400 font-bold flex items-center justify-center text-[10px] shrink-0 mt-0.5">2</span>
                    <span>Cari email dari <strong>SMeCK Hooligan Official</strong> <em>(cek juga folder Spam/Promosi jika belum muncul)</em>.</span>
                </div>
                <div class="flex items-start gap-2.5">
                    <span class="w-5 h-5 rounded-full bg-smeck-green-500/20 text-smeck-green-400 font-bold flex items-center justify-center text-[10px] shrink-0 mt-0.5">3</span>
                    <span>Klik tombol hijau <strong>"Verifikasi & Masuk ke Akun Saya"</strong>. Akun Anda langsung aktif dan KTA Digital siap digunakan!</span>
                </div>
            </div>

            {{-- Actions --}}
            <div class="space-y-3">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <input type="hidden" name="email" value="{{ session('registered_email') ?? auth()->user()?->email }}">
                    <button type="submit" class="w-full py-3.5 px-6 rounded-xl bg-smeck-green-600 hover:bg-smeck-green-500 text-white font-bold text-xs sm:text-sm shadow-xl shadow-smeck-green-900/30 transition-all flex items-center justify-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-paper-plane text-xs"></i>
                        <span>Kirim Ulang Email Verifikasi</span>
                    </button>
                </form>

                {{-- Helper Shortcut Demo Testing --}}
                @if(isset($demoVerificationUrl))
                    <div class="pt-3 border-t border-white/10">
                        <span class="text-[10px] text-white/40 block mb-2">Simulasi Pengujian Cepat:</span>
                        <a href="{{ $demoVerificationUrl }}" class="w-full py-2.5 px-4 rounded-xl bg-white/10 hover:bg-white/15 text-smeck-gold font-bold text-xs transition-colors flex items-center justify-center gap-2 border border-smeck-gold/30">
                            <i class="fa-solid fa-bolt"></i>
                            <span>Klik Di Sini: Simulasi Verifikasi Langsung</span>
                        </a>
                    </div>
                @endif

                <div class="pt-2 text-center text-xs text-white/40">
                    <a href="{{ url('/') }}" class="hover:text-white transition-colors underline">Kembali ke Beranda</a>
                    <span class="mx-2">•</span>
                    <a href="{{ route('login') }}" class="hover:text-smeck-green-400 transition-colors underline">Masuk ke Akun Lain</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
