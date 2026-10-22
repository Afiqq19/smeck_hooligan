@extends('layouts.app')

@section('title', 'Profil Anggota')
@section('header_title', 'Biodata & Profil Suporter')
@section('header_subtitle', 'Kelola informasi pribadi, kontak darurat awaydays, dan ukuran jersey resmi')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div class="p-6 sm:p-8 rounded-3xl bg-smeck-dark-900 border border-white/10 shadow-2xl">
        {{-- Profile Header --}}
        <div class="flex items-center gap-5 pb-6 border-b border-white/10">
            <div class="w-16 h-16 rounded-2xl bg-smeck-green-600 border border-smeck-green-500/40 flex items-center justify-center font-display font-black text-2xl text-white shrink-0 shadow-lg shadow-smeck-green-700/20">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <h2 class="font-display font-black text-xl text-white">{{ $user->name }}</h2>
                <div class="text-xs text-smeck-gold font-mono font-bold mt-0.5">
                    <i class="fa-solid fa-id-badge mr-1"></i>NIA: {{ $user->profile?->nia ?? 'Belum ada NIA' }}
                </div>
                <div class="text-xs text-white/50 mt-0.5">
                    Basis: <strong class="text-smeck-green-400">{{ $user->chapter?->name ?? 'Chapter Medan Kota' }}</strong> • Bergabung sejak {{ $user->created_at ? $user->created_at->format('d M Y') : '2024' }}
                </div>
            </div>
        </div>

        {{-- Form Edit Profil Mandiri --}}
        <form action="{{ route('member.profile.update') }}" method="POST" enctype="multipart/form-data" class="mt-8 space-y-6">
            @csrf

            {{-- Info Akun Statis (Read-Only) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-white/40 mb-1.5">Nama Lengkap (Sesuai KTP)</label>
                    <input type="text" value="{{ $user->name }}" disabled class="w-full px-4 py-2.5 rounded-xl bg-white/[0.03] border border-white/5 text-white/60 text-sm cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-white/40 mb-1.5">Alamat Email Terdaftar</label>
                    <input type="text" value="{{ $user->email }}" disabled class="w-full px-4 py-2.5 rounded-xl bg-white/[0.03] border border-white/5 text-white/60 text-sm cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-white/40 mb-1.5">NIK KTP (Terverifikasi)</label>
                    <input type="text" value="{{ $user->profile?->nik_ktp ?? '-' }}" disabled class="w-full px-4 py-2.5 rounded-xl bg-white/[0.03] border border-white/5 text-white/60 font-mono text-sm cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-white/40 mb-1.5">Nomor Kartu KTA NFC</label>
                    <input type="text" value="{{ $user->nfcCard?->card_number ?? 'Belum Diterbitkan' }}" disabled class="w-full px-4 py-2.5 rounded-xl bg-white/[0.03] border border-white/5 text-smeck-gold font-mono font-bold text-sm cursor-not-allowed">
                </div>
            </div>

            <div class="pt-4 border-t border-white/10">
                <h4 class="font-display font-bold text-base text-white mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-user-pen text-smeck-green-400"></i>
                    <span>Informasi yang Dapat Anda Perbarui</span>
                </h4>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                    {{-- No WhatsApp --}}
                    <div>
                        <label class="block font-bold uppercase text-white/60 mb-1.5">Nomor WhatsApp / HP <span class="text-red-400">*</span></label>
                        <input type="text" name="phone_number" value="{{ old('phone_number', $user->profile?->phone_number) }}" required placeholder="08xxxxxxxxxx" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white font-mono text-sm focus:border-smeck-green-400 focus:outline-none">
                    </div>

                    {{-- Golongan Darah --}}
                    <div>
                        <label class="block font-bold uppercase text-white/60 mb-1.5">Golongan Darah</label>
                        <select name="blood_type" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-smeck-green-400 focus:outline-none">
                            <option value="A" class="bg-smeck-dark-900" {{ ($user->profile?->blood_type == 'A') ? 'selected' : '' }}>A</option>
                            <option value="B" class="bg-smeck-dark-900" {{ ($user->profile?->blood_type == 'B') ? 'selected' : '' }}>B</option>
                            <option value="AB" class="bg-smeck-dark-900" {{ ($user->profile?->blood_type == 'AB') ? 'selected' : '' }}>AB</option>
                            <option value="O" class="bg-smeck-dark-900" {{ ($user->profile?->blood_type == 'O' || !$user->profile?->blood_type) ? 'selected' : '' }}>O</option>
                        </select>
                    </div>

                    {{-- Ukuran Kaos Jersey --}}
                    <div>
                        <label class="block font-bold uppercase text-white/60 mb-1.5">Ukuran Kaos Jersey</label>
                        <select name="shirt_size" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-smeck-green-400 focus:outline-none">
                            <option value="S" class="bg-smeck-dark-900" {{ ($user->profile?->shirt_size == 'S') ? 'selected' : '' }}>S</option>
                            <option value="M" class="bg-smeck-dark-900" {{ ($user->profile?->shirt_size == 'M') ? 'selected' : '' }}>M</option>
                            <option value="L" class="bg-smeck-dark-900" {{ ($user->profile?->shirt_size == 'L' || !$user->profile?->shirt_size) ? 'selected' : '' }}>L (Standar)</option>
                            <option value="XL" class="bg-smeck-dark-900" {{ ($user->profile?->shirt_size == 'XL') ? 'selected' : '' }}>XL</option>
                            <option value="XXL" class="bg-smeck-dark-900" {{ ($user->profile?->shirt_size == 'XXL') ? 'selected' : '' }}>XXL</option>
                            <option value="3XL" class="bg-smeck-dark-900" {{ ($user->profile?->shirt_size == '3XL') ? 'selected' : '' }}>3XL</option>
                        </select>
                    </div>

                    {{-- Alamat Domisili --}}
                    <div class="md:col-span-3">
                        <label class="block font-bold uppercase text-white/60 mb-1.5">Alamat Tempat Tinggal / Domisili</label>
                        <textarea name="address" rows="2" placeholder="Nama jalan, kelurahan, kecamatan, kota..." class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-smeck-green-400 focus:outline-none">{{ old('address', $user->profile?->address) }}</textarea>
                    </div>

                    {{-- Kontak Darurat --}}
                    <div>
                        <label class="block font-bold uppercase text-white/60 mb-1.5">Nama Kontak Darurat</label>
                        <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name', $user->profile?->emergency_contact_name) }}" placeholder="Orang Tua / Saudara / Istri" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-smeck-green-400 focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-bold uppercase text-white/60 mb-1.5">Hubungan</label>
                        <input type="text" name="emergency_contact_relation" value="{{ old('emergency_contact_relation', $user->profile?->emergency_contact_relation) }}" placeholder="Contoh: Ayah / Istri" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-smeck-green-400 focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-bold uppercase text-white/60 mb-1.5">Nomor HP Darurat</label>
                        <input type="text" name="emergency_contact_number" value="{{ old('emergency_contact_number', $user->profile?->emergency_contact_number) }}" placeholder="08xxxxxxxxxx" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white font-mono text-sm focus:border-smeck-green-400 focus:outline-none">
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-white/10 flex items-center justify-end gap-3">
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-smeck-green-600 hover:bg-smeck-green-500 text-white font-bold text-xs shadow-lg shadow-smeck-green-700/30 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Simpan Perubahan Biodata</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

