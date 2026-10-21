@extends('layouts.guest')

@section('title', 'Pendaftaran Anggota Baru — SMeCK Hooligan')
@section('meta_description', 'Formulir resmi registrasi anggota SMeCK Hooligan suporter PSMS Medan. Dapatkan KTA fisik NFC dan KTA Digital.')

@section('content')
<div class="min-h-screen pt-24 pb-16 bg-gradient-to-b from-smeck-dark-900 via-neutral-100 to-neutral-200">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Top Title & Breadcrumb with Official Crest --}}
        <div class="text-center mb-8">
            <div class="w-16 h-16 mx-auto rounded-2xl overflow-hidden border-2 border-smeck-green-600 shadow-xl p-0.5 bg-black flex items-center justify-center mb-4">
                <img src="{{ asset('images/logo-crest.jpg') }}" alt="SMeCK Hooligan" class="w-full h-full object-cover rounded-xl">
            </div>
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-smeck-green-500/10 border border-smeck-green-500/20 text-smeck-green-700 text-xs font-semibold uppercase tracking-wider mb-3">
                <i class="fa-solid fa-address-card"></i> Registrasi KTA SMeCK Hooligan
            </div>
            <h1 class="font-display font-black text-3xl sm:text-4xl text-neutral-900 tracking-tight">
                Formulir Pendaftaran Anggota
            </h1>
            <p class="mt-2 text-sm sm:text-base text-neutral-600 max-w-xl mx-auto">
                Lengkapi biodata dengan data yang valid sesuai KTP untuk penerbitan Kartu Tanda Anggota (KTA) fisik berchip NFC & KTA Digital resmi.
            </p>
        </div>

        {{-- Big White Card in Center --}}
        <div class="bg-white rounded-3xl shadow-2xl border border-neutral-200/80 overflow-hidden">
            {{-- Green Accent Top Bar --}}
            <div class="h-2.5 bg-gradient-to-r from-smeck-green-700 via-smeck-green-500 to-smeck-green-300"></div>

            <div class="p-6 sm:p-10 lg:p-12">

                {{-- Alert Errors --}}
                @if ($errors->any())
                    <div class="mb-8 p-4 rounded-2xl bg-red-50 border border-red-200 text-red-700 text-sm">
                        <div class="font-bold flex items-center gap-2 mb-1">
                            <i class="fa-solid fa-circle-exclamation"></i> Terdapat kesalahan pada formulir:
                        </div>
                        <ul class="list-disc list-inside space-y-1 text-xs">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ url('/register') }}" method="POST" class="space-y-8">
                    @csrf

                    {{-- SECTION 1: Akun Login --}}
                    <div>
                        <div class="flex items-center gap-2.5 pb-3 border-b border-neutral-200 mb-6">
                            <div class="w-7 h-7 rounded-lg bg-smeck-green-500/10 text-smeck-green-700 flex items-center justify-center font-bold text-xs">
                                1
                            </div>
                            <h3 class="font-display font-bold text-base text-neutral-900 uppercase tracking-wider">
                                Informasi Akun Login
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Nama Lengkap --}}
                            <div class="md:col-span-2">
                                <label for="name" class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-2">
                                    Nama Lengkap (Sesuai KTP) <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    required
                                    value="{{ old('name') }}"
                                    placeholder="Contoh: Muhammad Syafiq"
                                    class="w-full px-4 py-3 bg-neutral-50 border border-neutral-300 rounded-xl text-neutral-900 placeholder-neutral-400 text-sm focus:outline-none focus:bg-white focus:border-smeck-green-600 focus:ring-2 focus:ring-smeck-green-500/20 transition-all"
                                >
                            </div>

                            {{-- Email --}}
                            <div>
                                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-2">
                                    Alamat Email Aktif <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    required
                                    value="{{ old('email') }}"
                                    placeholder="nama@domain.com"
                                    class="w-full px-4 py-3 bg-neutral-50 border border-neutral-300 rounded-xl text-neutral-900 placeholder-neutral-400 text-sm focus:outline-none focus:bg-white focus:border-smeck-green-600 focus:ring-2 focus:ring-smeck-green-500/20 transition-all"
                                >
                            </div>

                            {{-- Nomor Handphone / WhatsApp --}}
                            <div>
                                <label for="phone_number" class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-2">
                                    Nomor WhatsApp Aktif <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="phone_number"
                                    name="phone_number"
                                    required
                                    value="{{ old('phone_number') }}"
                                    placeholder="081234567890"
                                    class="w-full px-4 py-3 bg-neutral-50 border border-neutral-300 rounded-xl text-neutral-900 placeholder-neutral-400 text-sm focus:outline-none focus:bg-white focus:border-smeck-green-600 focus:ring-2 focus:ring-smeck-green-500/20 transition-all"
                                >
                            </div>

                            {{-- Password --}}
                            <div>
                                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-2">
                                    Kata Sandi <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    required
                                    placeholder="Minimal 8 karakter"
                                    class="w-full px-4 py-3 bg-neutral-50 border border-neutral-300 rounded-xl text-neutral-900 placeholder-neutral-400 text-sm focus:outline-none focus:bg-white focus:border-smeck-green-600 focus:ring-2 focus:ring-smeck-green-500/20 transition-all"
                                >
                            </div>

                            {{-- Konfirmasi Password --}}
                            <div>
                                <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-2">
                                    Konfirmasi Kata Sandi <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="password"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    required
                                    placeholder="Ulangi kata sandi"
                                    class="w-full px-4 py-3 bg-neutral-50 border border-neutral-300 rounded-xl text-neutral-900 placeholder-neutral-400 text-sm focus:outline-none focus:bg-white focus:border-smeck-green-600 focus:ring-2 focus:ring-smeck-green-500/20 transition-all"
                                >
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 2: Data Kependudukan & Keanggotaan SMeCK --}}
                    <div>
                        <div class="flex items-center gap-2.5 pb-3 border-b border-neutral-200 mb-6">
                            <div class="w-7 h-7 rounded-lg bg-smeck-green-500/10 text-smeck-green-700 flex items-center justify-center font-bold text-xs">
                                2
                            </div>
                            <h3 class="font-display font-bold text-base text-neutral-900 uppercase tracking-wider">
                                Data Kependudukan & Basis Wilayah
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- NIK KTP (16 Angka) --}}
                            <div>
                                <label for="nik_ktp" class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-2">
                                    NIK KTP (Maksimal 16 Angka) <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="nik_ktp"
                                    name="nik_ktp"
                                    maxlength="16"
                                    pattern="\d{16}"
                                    required
                                    value="{{ old('nik_ktp') }}"
                                    placeholder="1271xxxxxxxxxxxx"
                                    class="w-full px-4 py-3 bg-neutral-50 border border-neutral-300 rounded-xl text-neutral-900 placeholder-neutral-400 text-sm font-mono tracking-wider focus:outline-none focus:bg-white focus:border-smeck-green-600 focus:ring-2 focus:ring-smeck-green-500/20 transition-all"
                                >
                                <span class="text-[11px] text-neutral-500 mt-1 block">Wajib 16 digit angka sesuai e-KTP</span>
                            </div>

                            {{-- Golongan Darah --}}
                            <div>
                                <label for="blood_type" class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-2">
                                    Golongan Darah <span class="text-red-500">*</span>
                                </label>
                                <select
                                    id="blood_type"
                                    name="blood_type"
                                    required
                                    class="w-full px-4 py-3 bg-neutral-50 border border-neutral-300 rounded-xl text-neutral-900 text-sm focus:outline-none focus:bg-white focus:border-smeck-green-600 focus:ring-2 focus:ring-smeck-green-500/20 transition-all"
                                >
                                    <option value="" disabled {{ old('blood_type') ? '' : 'selected' }}>-- Pilih Golongan Darah --</option>
                                    <option value="A" {{ old('blood_type') == 'A' ? 'selected' : '' }}>A</option>
                                    <option value="B" {{ old('blood_type') == 'B' ? 'selected' : '' }}>B</option>
                                    <option value="AB" {{ old('blood_type') == 'AB' ? 'selected' : '' }}>AB</option>
                                    <option value="O" {{ old('blood_type') == 'O' ? 'selected' : '' }}>O</option>
                                </select>
                            </div>

                            {{-- Pilihan Basis / Chapter --}}
                            <div class="md:col-span-2">
                                <label for="chapter_id" class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-2">
                                    Pilihan Basis / Chapter Wilayah <span class="text-red-500">*</span>
                                </label>
                                <select
                                    id="chapter_id"
                                    name="chapter_id"
                                    required
                                    class="w-full px-4 py-3 bg-neutral-50 border border-neutral-300 rounded-xl text-neutral-900 text-sm focus:outline-none focus:bg-white focus:border-smeck-green-600 focus:ring-2 focus:ring-smeck-green-500/20 transition-all"
                                >
                                    <option value="" disabled {{ old('chapter_id') ? '' : 'selected' }}>-- Pilih Basis / Chapter SMeCK Terdekat --</option>
                                    @if(isset($chapters) && count($chapters) > 0)
                                        @foreach($chapters as $ch)
                                            <option value="{{ $ch->id }}" {{ old('chapter_id') == $ch->id ? 'selected' : '' }}>
                                                {{ $ch->name }} ({{ $ch->region_code }}) - {{ $ch->basecamp_address }}
                                            </option>
                                        @endforeach
                                    @else
                                        {{-- Fallback default chapters if database table not yet populated --}}
                                        <option value="1">Chapter Medan Kota (Pusat) - Jl. Teladan No. 1</option>
                                        <option value="2">Chapter Medan Tembung - Jl. Letda Sujono</option>
                                        <option value="3">Chapter Medan Marelan - Jl. Marelan Raya</option>
                                        <option value="4">Chapter Medan Johor - Jl. Karya Wisata</option>
                                        <option value="5">Chapter Deli Serdang / Lubuk Pakam</option>
                                        <option value="6">Chapter Binjai - Tanah Seribu</option>
                                        <option value="7">Chapter Jabodetabek (Perantau Jakarta)</option>
                                    @endif
                                </select>
                                <span class="text-[11px] text-neutral-500 mt-1 block">Chapter akan memverifikasi berkas pendaftaran dan menyerahkan kartu fisik NFC</span>
                            </div>

                            {{-- Ukuran Kaos Merchandise SMeCK --}}
                            <div>
                                <label for="shirt_size" class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-2">
                                    Ukuran Kaos Jersey / Polo SMeCK
                                </label>
                                <select
                                    id="shirt_size"
                                    name="shirt_size"
                                    class="w-full px-4 py-3 bg-neutral-50 border border-neutral-300 rounded-xl text-neutral-900 text-sm focus:outline-none focus:bg-white focus:border-smeck-green-600 focus:ring-2 focus:ring-smeck-green-500/20 transition-all"
                                >
                                    <option value="S">S (Lebar 48 cm, Panjang 68 cm)</option>
                                    <option value="M">M (Lebar 50 cm, Panjang 70 cm)</option>
                                    <option value="L" selected>L (Lebar 52 cm, Panjang 72 cm)</option>
                                    <option value="XL">XL (Lebar 54 cm, Panjang 74 cm)</option>
                                    <option value="XXL">XXL (Lebar 56 cm, Panjang 76 cm)</option>
                                    <option value="3XL">3XL (Lebar 58 cm, Panjang 78 cm)</option>
                                </select>
                            </div>

                            {{-- Jenis Kelamin --}}
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-2">
                                    Jenis Kelamin <span class="text-red-500">*</span>
                                </label>
                                <div class="grid grid-cols-2 gap-4">
                                    <label class="flex items-center gap-3 p-3 rounded-xl border border-neutral-300 bg-neutral-50 cursor-pointer hover:bg-neutral-100 transition-all">
                                        <input type="radio" name="gender" value="L" checked class="text-smeck-green-600 focus:ring-smeck-green-500">
                                        <span class="text-sm font-medium text-neutral-800">Laki-Laki</span>
                                    </label>
                                    <label class="flex items-center gap-3 p-3 rounded-xl border border-neutral-300 bg-neutral-50 cursor-pointer hover:bg-neutral-100 transition-all">
                                        <input type="radio" name="gender" value="P" class="text-smeck-green-600 focus:ring-smeck-green-500">
                                        <span class="text-sm font-medium text-neutral-800">Perempuan</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Persetujuan Syarat & Ketentuan --}}
                    <div class="p-4 rounded-2xl bg-neutral-50 border border-neutral-200">
                        <label class="flex items-start gap-3 cursor-pointer select-none">
                            <input
                                type="checkbox"
                                name="terms"
                                required
                                class="mt-1 w-4 h-4 rounded border-neutral-300 text-smeck-green-600 focus:ring-smeck-green-500"
                            >
                            <span class="text-xs text-neutral-600 leading-relaxed">
                                Saya bersumpah setia menjunjung tinggi nama baik PSMS Medan dan organisasi suporter SMeCK Hooligan, menjaga persaudaraan "Salam Sada Roha", serta menyetujui seluruh aturan dan kode etik tribun.
                            </span>
                        </label>
                    </div>

                    {{-- Tombol Daftar Sekarang Lebar di Bagian Bawah --}}
                    <div class="pt-2">
                        <button
                            type="submit"
                            id="btn-register-submit"
                            class="w-full py-4 px-6 rounded-2xl bg-smeck-green-600 hover:bg-smeck-green-700 text-white font-display font-black text-base sm:text-lg tracking-wide shadow-xl shadow-smeck-green-700/20 active:scale-[0.99] transition-all duration-200 flex items-center justify-center gap-3"
                        >
                            <i class="fa-solid fa-user-plus"></i>
                            <span>Daftar Sekarang & Cetak KTA</span>
                        </button>
                    </div>

                    {{-- Link ke Login --}}
                    <div class="text-center pt-2">
                        <p class="text-sm text-neutral-600">
                            Sudah memiliki akun anggota?
                            <a href="{{ url('/login') }}" class="font-bold text-smeck-green-700 hover:text-smeck-green-800 underline underline-offset-4">
                                Masuk ke Portal
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>

        {{-- Info Box di Bawah Card --}}
        <div class="mt-8 text-center text-xs text-neutral-500">
            Pendaftaran online akan diverifikasi oleh Admin Chapter dalam 1x24 jam. Kartu NFC fisik dapat diambil di Basecamp Chapter pilihan Anda.
        </div>
    </div>
</div>
@endsection
