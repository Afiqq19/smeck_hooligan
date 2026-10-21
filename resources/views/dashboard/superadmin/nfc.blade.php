@extends('layouts.app')

@section('title', 'Cetak & Aktivasi NFC')
@section('header_title', 'Cetak & Aktivasi Kartu NFC Fisik')
@section('header_subtitle', 'Tautkan nomor UID chip fisik baru ke akun anggota untuk hak akses stadion & merchant partner')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">

    {{-- Form Tautkan UID NFC ke Anggota --}}
    <div class="p-6 sm:p-8 rounded-3xl bg-smeck-dark-900 border border-white/10">
        <h3 class="font-display font-bold text-lg text-white mb-2">Form Aktivasi Chip KTA NFC Fisik</h3>
        <p class="text-xs text-white/50 mb-6">Pilih anggota yang telah diverifikasi dan scan/masukkan nomor UID kartu fisik yang diterbitkan.</p>

        <form action="{{ route('superadmin.nfc.link') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Pilih Anggota <span class="text-red-400">*</span></label>
                    <select name="user_id" required class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-red-400 focus:outline-none">
                        <option value="" disabled selected>-- Pilih Anggota Tanpa Kartu --</option>
                        @forelse($membersWithoutCard as $mwc)
                            <option value="{{ $mwc->id }}" class="bg-smeck-dark-900">{{ $mwc->name }} ({{ $mwc->profile?->nia ?? 'Tanpa NIA' }}) - {{ $mwc->chapter?->name }}</option>
                        @empty
                            <option value="" disabled class="bg-smeck-dark-900">Semua anggota sudah memiliki kartu NFC</option>
                        @endforelse
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Nomor UID Chip NFC <span class="text-red-400">*</span></label>
                    <input type="text" name="uid_number" required placeholder="Contoh: 04:A2:8B:1A:F3:60:80" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white font-mono uppercase text-sm focus:border-red-400 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Vendor / Jenis Kartu <span class="text-red-400">*</span></label>
                    <select name="card_vendor" required class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-red-400 focus:outline-none">
                        <option value="emoney" class="bg-smeck-dark-900">Mandiri E-Money Custom SMeCK</option>
                        <option value="flazz" class="bg-smeck-dark-900">BCA Flazz Gen 2 SMeCK</option>
                        <option value="brizzi" class="bg-smeck-dark-900">BRIZZI BRI</option>
                        <option value="tapcash" class="bg-smeck-dark-900">BNI TapCash</option>
                        <option value="custom" class="bg-smeck-dark-900">RFID PVC Chip 13.56 MHz</option>
                    </select>
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-red-600 hover:bg-red-500 text-white font-bold text-xs shadow-lg transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-link"></i>
                    <span>Tautkan & Terbitkan Kartu NFC</span>
                </button>
            </div>
        </form>
    </div>

    {{-- Tabel Kartu Aktif --}}
    <div class="p-6 sm:p-8 rounded-3xl bg-smeck-dark-900 border border-white/10">
        <h3 class="font-display font-bold text-lg text-white mb-4">Kartu Fisik NFC yang Sedang Beredar</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-white/10 text-white/40 uppercase">
                        <th class="py-3 px-4">UID Kartu</th>
                        <th class="py-3 px-4">Nama Pemegang</th>
                        <th class="py-3 px-4">NIA Anggota</th>
                        <th class="py-3 px-4">Chapter</th>
                        <th class="py-3 px-4">Vendor</th>
                        <th class="py-3 px-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($activeCards as $card)
                        <tr class="hover:bg-white/[0.02]">
                            <td class="py-3.5 px-4 font-mono font-bold text-red-400">{{ $card->uid_number }}</td>
                            <td class="py-3.5 px-4 font-bold text-white">{{ $card->user->name }}</td>
                            <td class="py-3.5 px-4 font-mono text-smeck-gold">{{ $card->user->profile?->nia ?? '-' }}</td>
                            <td class="py-3.5 px-4 text-white/60">{{ $card->user->chapter?->name ?? 'Pusat' }}</td>
                            <td class="py-3.5 px-4 font-semibold uppercase text-white/80">{{ $card->card_vendor }}</td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $card->status === 'active' ? 'bg-smeck-green-500/20 text-smeck-green-400' : 'bg-red-500/20 text-red-400' }}">
                                    {{ $card->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-white/40">Belum ada kartu fisik yang ditautkan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
