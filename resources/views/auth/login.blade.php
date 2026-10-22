@extends('layouts.guest')

@section('title', 'Masuk — SMeCK Hooligan')
@section('meta_description', 'Masuk ke portal anggota SMeCK Hooligan untuk akses KTA Digital, tiket, dan informasi kegiatan.')

@section('content')
<section class="min-h-screen pt-20 flex items-stretch">
    {{-- Split Screen Layout --}}
    <div class="w-full grid grid-cols-1 lg:grid-cols-2 min-h-[calc(100vh-5rem)]">

        {{-- LEFT COLUMN: Suporter Choreo Cover Image with Green Overlay (Desktop only / hidden on mobile) --}}
        <div class="hidden lg:relative lg:flex flex-col justify-between p-12 overflow-hidden bg-smeck-dark-900 border-r border-white/5">
            {{-- Background Image / Visual --}}
            <div class="absolute inset-0 z-0">
                {{-- Real Suporter Choreo Cover --}}
                <div class="absolute inset-0 bg-cover bg-center scale-105" style="background-image: url('{{ asset('images/auth-cover.jpg') }}');"></div>
                {{-- Transparent Green & Dark Cinematic Overlays --}}
                <div class="absolute inset-0 bg-gradient-to-t from-smeck-dark-950 via-smeck-dark-950/75 to-smeck-green-950/80"></div>
                <div class="absolute inset-0 bg-gradient-to-r from-smeck-green-950/70 via-smeck-dark-950/60 to-smeck-dark-950"></div>
                {{-- Decorative Green Ambient Glow --}}
                <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-smeck-green-500/20 rounded-full blur-3xl animate-pulse-slow"></div>
                <div class="absolute top-1/3 -right-20 w-80 h-80 bg-smeck-green-400/15 rounded-full blur-3xl"></div>
            </div>

            {{-- Top Branding Tag --}}
            <div class="relative z-10 flex items-center justify-between">
                <div class="inline-flex items-center gap-2.5 px-4 py-2 rounded-full glass-green border border-smeck-green-500/30">
                    <span class="w-2.5 h-2.5 rounded-full bg-smeck-green-400 animate-ping"></span>
                    <span class="text-xs uppercase tracking-wider font-bold text-smeck-green-300">Tribun Teladan Bersuara</span>
                </div>
                <div class="w-9 h-9 rounded-xl overflow-hidden border border-smeck-green-500/40 p-0.5 bg-black">
                    <img src="{{ asset('images/logo-crest.jpg') }}" alt="SMeCK Logo" class="w-full h-full object-cover rounded-lg">
                </div>
            </div>

            {{-- Center Visual Quote & Chants --}}
            <div class="relative z-10 my-auto py-12">
                <div class="space-y-4 max-w-lg">
                    <div class="flex items-center gap-2 text-smeck-gold text-sm font-semibold tracking-widest uppercase">
                        <i class="fa-solid fa-shield-halved"></i>
                        <span>Sada Roha • Sada Roha</span>
                    </div>
                    <h2 class="font-display font-black text-4xl xl:text-5xl text-white leading-tight">
                        SATU HATI,<br>
                        <span class="text-gradient-smeck">SATU KEBANGGAAN!</span>
                    </h2>
                    <p class="text-white/70 text-base leading-relaxed">
                        Ayam Kinantan tak akan pernah berjuang sendirian. Masuk untuk kelola KTA Digital, tap presensi stadion, dan nikmati diskon khusus merchant SMeCK Partner.
                    </p>
                </div>

                {{-- Quick Feature Highlights --}}
                <div class="mt-8 grid grid-cols-2 gap-4 max-w-md">
                    <div class="p-3.5 rounded-xl bg-white/5 border border-white/10 backdrop-blur-sm">
                        <div class="text-smeck-green-400 text-lg mb-1"><i class="fa-solid fa-id-card"></i></div>
                        <div class="text-white font-semibold text-sm">KTA NFC Terintegrasi</div>
                        <div class="text-white/40 text-xs">Akses tribun tanpa antre tiket</div>
                    </div>
                    <div class="p-3.5 rounded-xl bg-white/5 border border-white/10 backdrop-blur-sm">
                        <div class="text-smeck-gold text-lg mb-1"><i class="fa-solid fa-ticket"></i></div>
                        <div class="text-white font-semibold text-sm">Prioritas Tiket & Away</div>
                        <div class="text-white/40 text-xs">Booking tur tandang resmi</div>
                    </div>
                </div>
            </div>

            {{-- Bottom Footer Note --}}
            <div class="relative z-10 flex items-center justify-between text-xs text-white/40 border-t border-white/10 pt-4">
                <span>© {{ date('Y') }} SMeCK Hooligan Official</span>
                <span class="text-smeck-green-400 font-mono">EST. 2003</span>
            </div>
        </div>

        {{-- RIGHT COLUMN: Minimalist Login Form --}}
        <div class="flex items-center justify-center p-6 sm:p-10 lg:p-16 bg-smeck-dark-950">
            <div class="w-full max-w-md space-y-8 animate-slide-up">

                {{-- Header with Official Crest --}}
                <div class="text-center sm:text-left">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-12 h-12 rounded-2xl overflow-hidden border-2 border-smeck-green-500/50 shadow-xl p-0.5 bg-black shrink-0">
                            <img src="{{ asset('images/logo-crest.jpg') }}" alt="SMeCK Hooligan" class="w-full h-full object-cover rounded-xl">
                        </div>
                        <div>
                            <span class="font-display font-bold text-lg text-white">SMeCK <span class="text-smeck-green-400">Hooligan</span></span>
                            <span class="block text-[11px] text-white/40 font-mono">Portal Suporter PSMS</span>
                        </div>
                    </div>

                    <h1 class="font-display font-black text-3xl sm:text-4xl text-white tracking-tight">
                        Selamat Datang
                    </h1>
                    <p class="mt-2 text-sm text-white/60">
                        Masukkan akun untuk masuk ke portal suporter
                    </p>
                </div>

                {{-- Session Alert / Error Flash --}}
                @if (session('status'))
                    <div class="p-4 rounded-xl bg-smeck-green-500/15 border border-smeck-green-500/30 text-smeck-green-300 text-sm">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="p-4 rounded-xl bg-red-500/10 border border-red-500/30 text-red-300 text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-triangle-exclamation text-xs"></i>
                                <span>{{ $error }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Form --}}
                <form action="{{ url('/login') }}" method="POST" class="space-y-6">
                    @csrf

                    {{-- Email / NIA Input --}}
                    <div>
                        <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-white/80 mb-2">
                            Alamat Email atau NIA
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-white/30">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                autocomplete="email"
                                required
                                value="{{ old('email') }}"
                                placeholder="nama@email.com"
                                class="w-full pl-10 pr-4 py-3 bg-white/5 border border-white/15 rounded-xl text-white placeholder-white/30 text-sm focus:outline-none focus:border-smeck-green-400 focus:ring-2 focus:ring-smeck-green-500/20 transition-all duration-200"
                            >
                        </div>
                    </div>

                    {{-- Password Input --}}
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-white/80">
                                Kata Sandi
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-xs font-medium text-smeck-green-400 hover:text-smeck-green-300 transition-colors">
                                    Lupa sandi?
                                </a>
                            @endif
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-white/30">
                                <i class="fa-solid fa-lock"></i>
                            </div>
                            <input
                                id="password"
                                name="password"
                                type="password"
                                autocomplete="current-password"
                                required
                                placeholder="••••••••"
                                class="w-full pl-10 pr-11 py-3 bg-white/5 border border-white/15 rounded-xl text-white placeholder-white/30 text-sm focus:outline-none focus:border-smeck-green-400 focus:ring-2 focus:ring-smeck-green-500/20 transition-all duration-200"
                            >
                            <button type="button" onclick="togglePasswordVisibility('password', 'password-toggle-icon')" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-white/40 hover:text-white transition-colors">
                                <i id="password-toggle-icon" class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Remember Me & Fast Action --}}
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2.5 cursor-pointer select-none">
                            <input
                                id="remember_me"
                                name="remember"
                                type="checkbox"
                                class="w-4 h-4 rounded border-white/20 bg-white/5 text-smeck-green-500 focus:ring-smeck-green-500 focus:ring-offset-0 focus:ring-offset-smeck-dark-950"
                            >
                            <span class="text-xs text-white/70">Ingat Saya</span>
                        </label>
                        <span class="text-xs text-white/40">Keamanan SSL Terenkripsi</span>
                    </div>

                    {{-- Submit Button --}}
                    <button
                        type="submit"
                        id="btn-login-submit"
                        class="w-full py-3.5 px-4 rounded-xl bg-smeck-green-500 hover:bg-smeck-green-400 text-white font-bold text-sm tracking-wide shadow-lg hover:shadow-smeck-green-500/30 active:scale-[0.99] transition-all duration-200 flex items-center justify-center gap-2 btn-glow"
                    >
                        <span>Masuk Sekarang</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </button>
                </form>

                {{-- Register Link --}}
                <div class="pt-4 text-center border-t border-white/10">
                    <p class="text-sm text-white/60">
                        Belum punya akun?
                        <a href="{{ url('/register') }}" class="font-semibold text-smeck-green-400 hover:text-smeck-green-300 underline underline-offset-4 transition-colors">
                            Daftar di sini
                        </a>
                    </p>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    function togglePasswordVisibility(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
@endpush
