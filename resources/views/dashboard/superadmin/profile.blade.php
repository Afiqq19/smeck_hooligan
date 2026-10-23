@extends('layouts.app')

@section('title', 'Profil Pengurus Pusat')
@section('header_title', 'Profil Pengurus Pusat (DPP)')
@section('header_subtitle', 'Kelola data identitas, pas foto resmi, dan kredensial akses Pengurus Pusat SMeCK Hooligan')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div class="p-6 sm:p-8 rounded-3xl bg-smeck-dark-900 border border-white/10 shadow-2xl">
        {{-- Profile Header --}}
        <div class="flex items-center gap-5 pb-6 border-b border-white/10">
            <div class="w-16 h-16 rounded-2xl bg-smeck-dark-800 border-2 border-red-500 overflow-hidden flex items-center justify-center font-display font-black text-2xl text-white shrink-0 shadow-lg shadow-red-700/20 relative">
                @if($user->profile?->profile_photo_path)
                    <img src="{{ asset('storage/' . $user->profile->profile_photo_path) }}" alt="{{ $user->name }}" class="w-full h-full object-cover" style="object-position: center top;">
                @else
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                @endif
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="font-display font-black text-xl text-white">{{ $user->name }}</h2>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-red-500/20 text-red-300 border border-red-500/30">
                        DEWAN PIMPINAN PUSAT
                    </span>
                </div>
                <div class="text-xs text-smeck-gold font-mono font-bold mt-0.5">
                    <i class="fa-solid fa-id-badge mr-1"></i>NIA: {{ $user->profile?->nia ?? 'DPP-PUSAT-001' }}
                </div>
                <div class="text-xs text-white/50 mt-0.5">
                    Otoritas: <strong class="text-red-400">Super Administrator (Pusat)</strong> • Terdaftar sejak {{ $user->created_at ? $user->created_at->format('d M Y') : '2024' }}
                </div>
            </div>
        </div>

        {{-- Form Edit Profil Pusat --}}
        <form action="{{ route('superadmin.profile.update') }}" method="POST" enctype="multipart/form-data" class="mt-8 space-y-6">
            @csrf

            {{-- Unggah Pas Foto Resmi --}}
            <div class="p-5 rounded-2xl bg-white/[0.04] border border-white/10 flex flex-col sm:flex-row items-center gap-5">
                <div class="w-20 h-24 sm:w-24 sm:h-28 rounded-2xl border-2 border-red-500 bg-black overflow-hidden shrink-0 shadow-xl relative flex items-center justify-center">
                    @if($user->profile?->profile_photo_path)
                        <img id="photo-preview-img" src="{{ asset('storage/' . $user->profile->profile_photo_path) }}" alt="{{ $user->name }}" class="w-full h-full object-cover" style="object-position: center top;">
                    @else
                        <div id="photo-preview-placeholder" class="w-full h-full bg-gradient-to-br from-red-950 to-black flex flex-col items-center justify-center text-white p-2 text-center">
                            <i class="fa-solid fa-camera text-red-400 text-xl"></i>
                            <span class="text-[9px] text-white/50 mt-1 uppercase font-bold">Belum Ada Foto</span>
                        </div>
                        <img id="photo-preview-img" src="" alt="Preview" class="w-full h-full object-cover hidden" style="object-position: center top;">
                    @endif
                </div>
                <div class="flex-1 text-center sm:text-left">
                    <div class="flex items-center justify-center sm:justify-start gap-2 mb-1">
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-red-500/20 text-red-300 border border-red-500/30">FOTO RESMI PENGURUS PUSAT</span>
                    </div>
                    <label class="block font-display font-black text-white text-sm mb-1">
                        Pas Foto Profil Pusat
                    </label>
                    <p class="text-[11px] text-white/60 mb-3 max-w-lg">
                        Foto ini akan ditampilkan pada sistem manajemen Pusat, log verifikasi, dan identitas pengurus. Format: <strong>JPG, PNG, WebP</strong> (Maks. 3 MB).
                    </p>
                    <div class="flex flex-col sm:flex-row items-center gap-3">
                        <label class="cursor-pointer px-4 py-2 rounded-xl bg-red-600 hover:bg-red-500 text-white font-bold text-xs shadow flex items-center gap-2 transition-all">
                            <i class="fa-solid fa-cloud-arrow-up"></i>
                            <span>Pilih / Ganti Pas Foto</span>
                            <input type="file" name="profile_photo" id="profile_photo_input" accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden" onchange="previewKtaPhoto(event)">
                        </label>
                        <span id="photo-file-name" class="text-xs text-white/50 font-mono truncate max-w-xs">Belum ada file baru dipilih</span>
                    </div>
                </div>
            </div>

            {{-- Info Akun & Kredensial --}}
            <div class="pt-4 border-t border-white/10 space-y-4">
                <h4 class="font-display font-bold text-base text-white flex items-center gap-2">
                    <i class="fa-solid fa-shield-halved text-red-400"></i>
                    <span>Informasi Kredensial & Biodata</span>
                </h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                    <div>
                        <label class="block font-bold uppercase text-white/60 mb-1.5">Nama Lengkap Pengurus <span class="text-red-400">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-red-400 focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-bold uppercase text-white/40 mb-1.5">Alamat Email Terdaftar</label>
                        <input type="text" value="{{ $user->email }}" disabled class="w-full px-4 py-2.5 rounded-xl bg-white/[0.03] border border-white/5 text-white/50 text-sm cursor-not-allowed">
                    </div>
                    <div>
                        <label class="block font-bold uppercase text-white/60 mb-1.5">Nomor WhatsApp / HP Pengurus</label>
                        <input type="text" name="phone_number" value="{{ old('phone_number', $user->profile?->phone_number) }}" placeholder="08xxxxxxxxxx" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white font-mono text-sm focus:border-red-400 focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-bold uppercase text-white/60 mb-1.5">Golongan Darah</label>
                        <select name="blood_type" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-red-400 focus:outline-none">
                            <option value="-" class="bg-smeck-dark-900">- Pilih Golongan Darah -</option>
                            <option value="A" class="bg-smeck-dark-900" {{ ($user->profile?->blood_type == 'A') ? 'selected' : '' }}>A</option>
                            <option value="B" class="bg-smeck-dark-900" {{ ($user->profile?->blood_type == 'B') ? 'selected' : '' }}>B</option>
                            <option value="AB" class="bg-smeck-dark-900" {{ ($user->profile?->blood_type == 'AB') ? 'selected' : '' }}>AB</option>
                            <option value="O" class="bg-smeck-dark-900" {{ ($user->profile?->blood_type == 'O') ? 'selected' : '' }}>O</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold uppercase text-white/60 mb-1.5">Ukuran Kaos Jersey</label>
                        <select name="shirt_size" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-red-400 focus:outline-none">
                            <option value="-" class="bg-smeck-dark-900">- Pilih Ukuran Kaos -</option>
                            <option value="S" class="bg-smeck-dark-900" {{ ($user->profile?->shirt_size == 'S') ? 'selected' : '' }}>S</option>
                            <option value="M" class="bg-smeck-dark-900" {{ ($user->profile?->shirt_size == 'M') ? 'selected' : '' }}>M</option>
                            <option value="L" class="bg-smeck-dark-900" {{ ($user->profile?->shirt_size == 'L') ? 'selected' : '' }}>L</option>
                            <option value="XL" class="bg-smeck-dark-900" {{ ($user->profile?->shirt_size == 'XL') ? 'selected' : '' }}>XL</option>
                            <option value="XXL" class="bg-smeck-dark-900" {{ ($user->profile?->shirt_size == 'XXL') ? 'selected' : '' }}>XXL</option>
                            <option value="3XL" class="bg-smeck-dark-900" {{ ($user->profile?->shirt_size == '3XL') ? 'selected' : '' }}>3XL</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold uppercase text-white/60 mb-1.5">Alamat Domisili</label>
                        <input type="text" name="address" value="{{ old('address', $user->profile?->address) }}" placeholder="Kota Medan, Sumatera Utara" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-red-400 focus:outline-none">
                    </div>
                </div>

                {{-- Ganti Password --}}
                <div class="pt-4 border-t border-white/10">
                    <div class="text-xs font-bold text-white/80 mb-2">Ganti Kata Sandi (Kosongkan jika tidak ingin mengubah)</div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                        <div>
                            <label class="block font-bold uppercase text-white/40 mb-1">Kata Sandi Baru</label>
                            <input type="password" name="password" placeholder="Minimal 6 karakter" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-red-400 focus:outline-none">
                        </div>
                        <div>
                            <label class="block font-bold uppercase text-white/40 mb-1">Ulangi Kata Sandi Baru</label>
                            <input type="password" name="password_confirmation" placeholder="Ulangi kata sandi" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-red-400 focus:outline-none">
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-white/10 flex items-center justify-end gap-3">
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-red-600 hover:bg-red-500 text-white font-bold text-xs shadow-lg shadow-red-700/30 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Simpan Perubahan Profil Pusat</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function previewKtaPhoto(event) {
        const file = event.target.files[0];
        if (file) {
            const fileNameEl = document.getElementById('photo-file-name');
            if (fileNameEl) fileNameEl.textContent = file.name;

            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById('photo-preview-img');
                const placeholder = document.getElementById('photo-preview-placeholder');
                if (placeholder) placeholder.classList.add('hidden');
                if (img) {
                    img.src = e.target.result;
                    img.classList.remove('hidden');
                }
            };
            reader.readAsDataURL(file);
        }
    }
</script>
@endpush
