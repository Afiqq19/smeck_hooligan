@extends('layouts.app')

@section('title', 'Profil Anggota')
@section('header_title', 'Biodata & Profil Anggota')
@section('header_subtitle', 'Kelola informasi pribadi, kontak darurat, dan ukuran merchandise resmi')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div class="p-6 sm:p-8 rounded-3xl bg-smeck-dark-900 border border-white/10">
        <div class="flex items-center gap-5 pb-6 border-b border-white/10">
            <div class="w-16 h-16 rounded-2xl bg-smeck-green-600 flex items-center justify-center font-display font-black text-2xl text-white">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <h2 class="font-display font-bold text-xl text-white">{{ $user->name }}</h2>
                <div class="text-xs text-smeck-green-400 font-mono mt-0.5">NIA: {{ $user->profile?->nia ?? 'SMCK-2024-1950' }}</div>
                <div class="text-xs text-white/50">{{ $user->chapter?->name ?? 'Chapter Medan Kota' }} • Bergabung sejak {{ $user->profile?->joined_date?->format('d M Y') ?? '2024' }}</div>
            </div>
        </div>

        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
            <div>
                <label class="block text-xs font-bold uppercase text-white/40 mb-1">Nama Lengkap</label>
                <div class="p-3.5 rounded-xl bg-white/5 text-white font-medium">{{ $user->name }}</div>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase text-white/40 mb-1">Email</label>
                <div class="p-3.5 rounded-xl bg-white/5 text-white font-medium">{{ $user->email }}</div>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase text-white/40 mb-1">Nomor WhatsApp</label>
                <div class="p-3.5 rounded-xl bg-white/5 text-white font-medium">{{ $user->profile?->phone_number ?? '-' }}</div>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase text-white/40 mb-1">NIK KTP (16 Digit)</label>
                <div class="p-3.5 rounded-xl bg-white/5 text-white font-mono font-medium">{{ $user->profile?->nik_ktp ?? '-' }}</div>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase text-white/40 mb-1">Golongan Darah</label>
                <div class="p-3.5 rounded-xl bg-white/5 text-smeck-gold font-bold">Tipe {{ $user->profile?->blood_type ?? 'O' }}</div>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase text-white/40 mb-1">Ukuran Kaos / Jersey</label>
                <div class="p-3.5 rounded-xl bg-white/5 text-smeck-green-400 font-bold">Size {{ $user->profile?->shirt_size ?? 'L' }}</div>
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs font-bold uppercase text-white/40 mb-1">Alamat Domisili</label>
                <div class="p-3.5 rounded-xl bg-white/5 text-white font-medium">{{ $user->profile?->address ?? 'Medan, Sumatera Utara' }}</div>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase text-white/40 mb-1">Kontak Darurat (Nama)</label>
                <div class="p-3.5 rounded-xl bg-white/5 text-white font-medium">{{ $user->profile?->emergency_contact_name ?? '-' }} ({{ $user->profile?->emergency_contact_relation ?? 'Keluarga' }})</div>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase text-white/40 mb-1">Nomor Kontak Darurat</label>
                <div class="p-3.5 rounded-xl bg-white/5 text-white font-medium">{{ $user->profile?->emergency_contact_number ?? '-' }}</div>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-white/10 flex justify-end">
            <button type="button" class="px-5 py-2.5 rounded-xl bg-smeck-green-600 hover:bg-smeck-green-500 text-white font-bold text-xs shadow-lg transition-colors">
                <i class="fa-solid fa-floppy-disk mr-1.5"></i> Perbarui Data Profil
            </button>
        </div>
    </div>
</div>
@endsection
