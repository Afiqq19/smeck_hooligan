@extends('layouts.app')

@section('title', 'Kelola Anggota Basis & KTA NFC')
@section('header_title', 'Kelola Anggota Basis ' . ($chapter->name ?? ''))
@section('header_subtitle', 'Pendaftaran anggota baru langsung dari basis, aktivasi KTA NFC, blokir kartu hilang, & terbitkan kartu pengganti')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    {{-- Top Action & Statistik Ringkas --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="font-display font-black text-xl sm:text-2xl text-white">Database Anggota & Kartu KTA NFC</h2>
            <p class="text-xs text-white/50">Basis Wilayah: <span class="text-smeck-green-400 font-semibold">{{ $chapter->name ?? 'Chapter' }}</span></p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="openAddMemberModal()" class="px-5 py-2.5 rounded-xl bg-smeck-green-600 hover:bg-smeck-green-500 text-white font-bold text-xs shadow-lg shadow-smeck-green-700/30 transition-all flex items-center gap-2">
                <i class="fa-solid fa-user-plus"></i>
                <span>+ Tambah Anggota Basis</span>
            </button>
        </div>
    </div>

    {{-- Quick Stat Counters --}}
    @php
        $totalChapterMembers = $members->total();
        $activeCardsCount = \App\Models\NfcCard::whereHas('user', fn($q) => $q->where('chapter_id', $chapter->id ?? 1))->where('status', 'active')->count();
        $blockedCardsCount = \App\Models\NfcCard::whereHas('user', fn($q) => $q->where('chapter_id', $chapter->id ?? 1))->where('status', 'blocked')->count();
        $noCardCount = \App\Models\User::where('chapter_id', $chapter->id ?? 1)->where('role', 'member')->whereDoesntHave('nfcCard')->count();
    @endphp
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="p-4 rounded-2xl bg-smeck-dark-900 border border-white/10 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-400 flex items-center justify-center text-lg">
                <i class="fa-solid fa-users"></i>
            </div>
            <div>
                <div class="text-lg font-bold text-white">{{ $totalChapterMembers }}</div>
                <div class="text-[11px] text-white/50">Total Anggota</div>
            </div>
        </div>

        <div class="p-4 rounded-2xl bg-smeck-dark-900 border border-white/10 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-smeck-green-500/10 text-smeck-green-400 flex items-center justify-center text-lg">
                <i class="fa-solid fa-credit-card"></i>
            </div>
            <div>
                <div class="text-lg font-bold text-smeck-green-400">{{ $activeCardsCount }}</div>
                <div class="text-[11px] text-white/50">KTA NFC Aktif</div>
            </div>
        </div>

        <div class="p-4 rounded-2xl bg-smeck-dark-900 border border-white/10 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-red-500/10 text-red-400 flex items-center justify-center text-lg">
                <i class="fa-solid fa-ban"></i>
            </div>
            <div>
                <div class="text-lg font-bold text-red-400">{{ $blockedCardsCount }}</div>
                <div class="text-[11px] text-white/50">Kartu Hilang/Diblokir</div>
            </div>
        </div>

        <div class="p-4 rounded-2xl bg-smeck-dark-900 border border-white/10 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-white/5 text-white/60 flex items-center justify-center text-lg">
                <i class="fa-solid fa-id-card"></i>
            </div>
            <div>
                <div class="text-lg font-bold text-white/80">{{ $noCardCount }}</div>
                <div class="text-[11px] text-white/50">Belum Ada KTA Fisik</div>
            </div>
        </div>
    </div>

    {{-- Filter & Search Bar --}}
    <div class="p-4 rounded-2xl bg-smeck-dark-900 border border-white/10">
        <form method="GET" action="{{ route('chapter.members') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-white/30 text-xs"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berdasarkan nama, email, NIA, NIK, No. HP, atau UID NFC..." class="w-full pl-9 pr-4 py-2 bg-white/5 border border-white/10 rounded-xl text-white text-xs placeholder:text-white/30 focus:border-amber-400 focus:outline-none">
            </div>
            <button type="submit" class="px-5 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white font-semibold text-xs transition-colors flex items-center justify-center gap-2">
                <i class="fa-solid fa-filter"></i>
                <span>Cari</span>
            </button>
            @if(request('search'))
                <a href="{{ route('chapter.members') }}" class="px-4 py-2 rounded-xl bg-red-500/10 hover:bg-red-500/20 text-red-400 text-xs font-semibold flex items-center justify-center">
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- Tabel Anggota & KTA NFC --}}
    <div class="rounded-3xl bg-smeck-dark-900 border border-white/10 overflow-hidden shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-white/5 border-b border-white/10 text-white/40 uppercase tracking-wider text-[11px]">
                        <th class="py-4 px-5">Anggota & NIA</th>
                        <th class="py-4 px-5">Identitas & Kontak</th>
                        <th class="py-4 px-5">Nomor Kartu KTA</th>
                        <th class="py-4 px-5 text-center">Status Kartu</th>
                        <th class="py-4 px-5 text-right">Aksi Manajemen KTA</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($members as $m)
                        @php
                            $card = $m->nfcCard;
                        @endphp
                        <tr class="hover:bg-white/[0.02] transition-colors">
                            {{-- Info Anggota --}}
                            <td class="py-4 px-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-smeck-green-600/30 border border-smeck-green-500/40 text-smeck-green-400 flex items-center justify-center font-bold text-xs shrink-0">
                                        {{ strtoupper(substr($m->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-bold text-white text-sm truncate">{{ $m->name }}</div>
                                        <div class="text-[11px] text-white/40 truncate">{{ $m->email }}</div>
                                        <div class="font-mono text-[10px] text-smeck-gold mt-0.5">
                                            <i class="fa-solid fa-id-badge text-[9px] mr-1"></i>{{ $m->profile?->nia ?? 'Belum ada NIA' }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Identitas & Kontak --}}
                            <td class="py-4 px-5">
                                <div class="text-white/80 font-medium">
                                    <i class="fa-solid fa-phone text-white/30 text-[10px] mr-1.5"></i>{{ $m->profile?->phone_number ?? '-' }}
                                </div>
                                <div class="text-white/40 text-[11px] mt-0.5">
                                    NIK: <span class="font-mono">{{ $m->profile?->nik_ktp ?? '-' }}</span>
                                </div>
                                <div class="text-[10px] text-amber-400/80 mt-0.5">
                                    Gol. Darah: <span class="font-bold">{{ $m->profile?->blood_type ?? '-' }}</span> • Ukuran: <span class="font-bold">{{ $m->profile?->shirt_size ?? '-' }}</span>
                                </div>
                            </td>

                            {{-- Kartu KTA NFC --}}
                            <td class="py-4 px-5">
                                @if($card)
                                    <div class="font-mono font-bold text-sm text-smeck-gold tracking-wide">
                                        {{ $card->card_number ?? '6032-9800-2024-1950' }}
                                    </div>
                                    <div class="text-[10px] text-white/50 uppercase mt-0.5 flex items-center gap-2">
                                        <span><i class="fa-solid fa-credit-card mr-1 text-white/30"></i>{{ $card->card_vendor }}</span>
                                        <span>•</span>
                                        <span class="text-white/30 font-mono text-[9px]">UID: {{ substr($card->uid_number, 0, 8) }}••</span>
                                    </div>
                                    <div class="text-[10px] text-white/30">
                                        Terbit: {{ $card->issued_at ? $card->issued_at->format('d M Y') : '-' }}
                                    </div>
                                @else
                                    <span class="inline-flex items-center gap-1.5 text-white/40 italic">
                                        <i class="fa-regular fa-circle-xmark"></i> Belum Diterbitkan
                                    </span>
                                @endif
                            </td>

                            {{-- Status Kartu --}}
                            <td class="py-4 px-5 text-center">
                                @if(!$card)
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-white/10 text-white/50 border border-white/10">
                                        Tidak Ada Kartu
                                    </span>
                                @elseif($card->status === 'active')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-smeck-green-500/20 text-smeck-green-400 border border-smeck-green-500/30 inline-flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-smeck-green-400 animate-pulse"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-red-500/20 text-red-400 border border-red-500/30 inline-flex items-center gap-1.5">
                                        <i class="fa-solid fa-ban text-[9px]"></i>
                                        Diblokir / Hilang
                                    </span>
                                @endif
                            </td>

                            {{-- Aksi Manajemen KTA NFC --}}
                            <td class="py-4 px-5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    {{-- Jika Belum Punya Kartu: Tombol Terbitkan --}}
                                    @if(!$card)
                                        <button onclick="openReplaceModal({{ $m->id }}, '{{ addslashes($m->name) }}', 'Terbitkan KTA Baru')" class="px-3 py-1.5 rounded-lg bg-smeck-green-600 hover:bg-smeck-green-500 text-white font-semibold text-[11px] shadow transition-colors flex items-center gap-1.5">
                                            <i class="fa-solid fa-plus"></i>
                                            <span>Terbitkan Kartu</span>
                                        </button>
                                    @else
                                        {{-- Jika Kartu Aktif: Tombol Blokir (Hilang/Dicuri) --}}
                                        @if($card->status === 'active')
                                            <form action="{{ route('chapter.nfc.block', $card->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin MEMBLOKIR kartu KTA milik {{ $m->name }}? Kartu fisik lama tidak akan bisa di-tap di stadion atau partner SMeCK.')">
                                                @csrf
                                                <button type="submit" class="px-2.5 py-1.5 rounded-lg bg-red-500/10 hover:bg-red-500 text-red-400 hover:text-white border border-red-500/20 font-semibold text-[11px] transition-colors flex items-center gap-1" title="Lapor Kartu Hilang & Blokir">
                                                    <i class="fa-solid fa-ban"></i>
                                                    <span class="hidden sm:inline">Blokir (Hilang)</span>
                                                </button>
                                            </form>
                                        @else
                                            {{-- Jika Kartu Sedang Diblokir: Tombol Buka Blokir --}}
                                            <form action="{{ route('chapter.nfc.unblock', $card->id) }}" method="POST" onsubmit="return confirm('Aktifkan kembali kartu KTA milik {{ $m->name }}?')">
                                                @csrf
                                                <button type="submit" class="px-2.5 py-1.5 rounded-lg bg-smeck-green-500/10 hover:bg-smeck-green-600 text-smeck-green-400 hover:text-white border border-smeck-green-500/20 font-semibold text-[11px] transition-colors flex items-center gap-1" title="Buka Blokir Kartu">
                                                    <i class="fa-solid fa-unlock"></i>
                                                    <span class="hidden sm:inline">Buka Blokir</span>
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Tombol Ganti Kartu Baru (Re-Issue) --}}
                                        <button onclick="openReplaceModal({{ $m->id }}, '{{ addslashes($m->name) }}', 'Ganti Kartu KTA Fisik Baru')" class="px-2.5 py-1.5 rounded-lg bg-amber-500/10 hover:bg-amber-600 text-amber-400 hover:text-white border border-amber-500/20 font-semibold text-[11px] transition-colors flex items-center gap-1" title="Ganti Kartu Baru">
                                            <i class="fa-solid fa-arrows-rotate"></i>
                                            <span class="hidden sm:inline">Ganti Kartu</span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-white/40">
                                <i class="fa-solid fa-user-slash text-3xl mb-3 block text-white/20"></i>
                                Belum ada data anggota di chapter ini. Klik tombol <strong>+ Tambah Anggota Basis</strong> di atas untuk mendaftarkan anggota baru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($members->hasPages())
            <div class="p-4 border-t border-white/5 bg-white/[0.01]">
                {{ $members->links() }}
            </div>
        @endif
    </div>

</div>

{{-- ========================================================================= --}}
{{-- MODAL 1: FORM TAMBAH ANGGOTA BASIS BARU --}}
{{-- ========================================================================= --}}
<div id="modalAddMember" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-md hidden flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-smeck-dark-900 border border-white/10 rounded-3xl max-w-2xl w-full p-6 sm:p-8 shadow-2xl my-8 relative">
        <button onclick="closeAddMemberModal()" class="absolute top-6 right-6 text-white/40 hover:text-white text-lg">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <div class="mb-6">
            <h3 class="font-display font-black text-xl text-white flex items-center gap-2">
                <i class="fa-solid fa-user-plus text-smeck-green-400"></i>
                <span>Tambah Anggota Basis Baru</span>
            </h3>
            <p class="text-xs text-white/50 mt-1">Daftarkan suporter langsung ke Basis {{ $chapter->name ?? '' }}. Akun akan langsung aktif dan memiliki NIA resmi.</p>
        </div>

        <form action="{{ route('chapter.members.store') }}" method="POST" class="space-y-4">
            @csrf

            {{-- Baris 1: Nama & Email --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Nama Lengkap <span class="text-red-400">*</span></label>
                    <input type="text" name="name" required placeholder="Contoh: Rian Siregar" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-smeck-green-400 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Alamat Email <span class="text-red-400">*</span></label>
                    <input type="email" name="email" required placeholder="email@contoh.com" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-smeck-green-400 focus:outline-none">
                </div>
            </div>

            {{-- Baris 2: NIK KTP & Nomor WhatsApp --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">NIK KTP (16 Digit) <span class="text-red-400">*</span></label>
                    <input type="text" name="nik_ktp" required maxlength="16" minlength="16" placeholder="1271xxxxxxxxxxxx" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white font-mono text-sm focus:border-smeck-green-400 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Nomor WhatsApp / HP <span class="text-red-400">*</span></label>
                    <input type="text" name="phone_number" required placeholder="08xxxxxxxxxx" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white font-mono text-sm focus:border-smeck-green-400 focus:outline-none">
                </div>
            </div>

            {{-- Baris 3: Gol. Darah, Jenis Kelamin, Ukuran Baju --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Golongan Darah <span class="text-red-400">*</span></label>
                    <select name="blood_type" required class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-smeck-green-400 focus:outline-none">
                        <option value="A" class="bg-smeck-dark-900">A</option>
                        <option value="B" class="bg-smeck-dark-900">B</option>
                        <option value="AB" class="bg-smeck-dark-900">AB</option>
                        <option value="O" class="bg-smeck-dark-900" selected>O</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Jenis Kelamin <span class="text-red-400">*</span></label>
                    <select name="gender" required class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-smeck-green-400 focus:outline-none">
                        <option value="L" class="bg-smeck-dark-900" selected>Laki-laki</option>
                        <option value="P" class="bg-smeck-dark-900">Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Ukuran Kaos <span class="text-red-400">*</span></label>
                    <select name="shirt_size" required class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-smeck-green-400 focus:outline-none">
                        <option value="S" class="bg-smeck-dark-900">S</option>
                        <option value="M" class="bg-smeck-dark-900">M</option>
                        <option value="L" class="bg-smeck-dark-900" selected>L</option>
                        <option value="XL" class="bg-smeck-dark-900">XL</option>
                        <option value="XXL" class="bg-smeck-dark-900">XXL</option>
                        <option value="3XL" class="bg-smeck-dark-900">3XL</option>
                    </select>
                </div>
            </div>

            {{-- Baris 4: Tempat & Tanggal Lahir --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Tempat Lahir <span class="text-red-400">*</span></label>
                    <input type="text" name="place_of_birth" required placeholder="Contoh: Medan" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-smeck-green-400 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Tanggal Lahir <span class="text-red-400">*</span></label>
                    <input type="date" name="date_of_birth" required class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-smeck-green-400 focus:outline-none">
                </div>
            </div>

            {{-- Baris 5: Alamat Domisili --}}
            <div>
                <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Alamat Tempat Tinggal <span class="text-red-400">*</span></label>
                <textarea name="address" required rows="2" placeholder="Nama jalan, nomor rumah, kelurahan, kecamatan..." class="w-full px-4 py-2 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-smeck-green-400 focus:outline-none"></textarea>
            </div>

            {{-- Baris 6: Kontak Darurat & Pekerjaan --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Nama Kontak Darurat</label>
                    <input type="text" name="emergency_contact_name" placeholder="Orang Tua / Saudara / Istri" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-smeck-green-400 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Nomor HP Darurat</label>
                    <input type="text" name="emergency_contact_number" placeholder="08xxxxxxxxxx" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white font-mono text-sm focus:border-smeck-green-400 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Hubungan</label>
                    <input type="text" name="emergency_contact_relation" placeholder="Contoh: Ayah / Istri" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-smeck-green-400 focus:outline-none">
                </div>
            </div>

            {{-- Divider KTA Fisik NFC Opsional --}}
            <div class="pt-2 border-t border-white/10">
                <div class="flex items-center gap-2 mb-3">
                    <span class="w-2 h-2 rounded-full bg-smeck-green-400"></span>
                    <span class="text-xs font-bold text-smeck-green-400 uppercase tracking-wider">Aktivasi KTA NFC Langsung (Opsional)</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Nomor UID Chip Kartu Fisik</label>
                        <input type="text" name="uid_number" placeholder="Contoh: 04:A2:8B:1A:F3:60:80" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white font-mono uppercase text-sm focus:border-smeck-green-400 focus:outline-none">
                        <span class="text-[10px] text-white/40 mt-1 block">Kosongkan jika kartu fisik belum diserahkan.</span>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Jenis / Vendor Kartu</label>
                        <select name="card_vendor" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-smeck-green-400 focus:outline-none">
                            <option value="emoney" class="bg-smeck-dark-900" selected>Mandiri E-Money Custom SMeCK</option>
                            <option value="flazz" class="bg-smeck-dark-900">BCA Flazz Gen 2 SMeCK</option>
                            <option value="brizzi" class="bg-smeck-dark-900">BRIZZI BRI</option>
                            <option value="tapcash" class="bg-smeck-dark-900">BNI TapCash</option>
                            <option value="custom" class="bg-smeck-dark-900">RFID PVC Chip 13.56 MHz</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Submit Button --}}
            <div class="pt-4 flex items-center justify-end gap-3">
                <button type="button" onclick="closeAddMemberModal()" class="px-5 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 text-white text-xs font-semibold transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-smeck-green-600 hover:bg-smeck-green-500 text-white font-bold text-xs shadow-lg shadow-smeck-green-700/30 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-check"></i>
                    <span>Daftarkan Anggota</span>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ========================================================================= --}}
{{-- MODAL 2: GANTI KARTU KTA BARU / TERBITKAN KARTU PENGGANTI --}}
{{-- ========================================================================= --}}
<div id="modalReplaceCard" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-md hidden flex items-center justify-center p-4">
    <div class="bg-smeck-dark-900 border border-white/10 rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl relative">
        <button onclick="closeReplaceModal()" class="absolute top-6 right-6 text-white/40 hover:text-white text-lg">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <div class="mb-5">
            <div class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-400 flex items-center justify-center text-xl mb-3">
                <i class="fa-solid fa-arrows-rotate"></i>
            </div>
            <h3 id="replaceModalTitle" class="font-display font-black text-lg text-white">Ganti Kartu KTA NFC</h3>
            <p class="text-xs text-white/50 mt-1">
                Anggota: <strong id="replaceMemberName" class="text-amber-400"></strong>
            </p>
            <div class="p-3 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-300 text-[11px] mt-3">
                <i class="fa-solid fa-triangle-exclamation mr-1"></i> Kartu fisik lama anggota ini akan <strong>otomatis diblokir</strong> demi keamanan data.
            </div>
        </div>

        <form id="formReplaceCard" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Nomor UID Chip NFC Baru <span class="text-red-400">*</span></label>
                <input type="text" name="uid_number" required placeholder="Contoh: 04:A2:8B:1A:F3:60:80" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white font-mono uppercase text-sm focus:border-amber-400 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Jenis / Vendor Kartu Fisik <span class="text-red-400">*</span></label>
                <select name="card_vendor" required class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-amber-400 focus:outline-none">
                    <option value="emoney" class="bg-smeck-dark-900" selected>Mandiri E-Money Custom SMeCK</option>
                    <option value="flazz" class="bg-smeck-dark-900">BCA Flazz Gen 2 SMeCK</option>
                    <option value="brizzi" class="bg-smeck-dark-900">BRIZZI BRI</option>
                    <option value="tapcash" class="bg-smeck-dark-900">BNI TapCash</option>
                    <option value="custom" class="bg-smeck-dark-900">RFID PVC Chip 13.56 MHz</option>
                </select>
            </div>

            <div class="pt-3 flex items-center justify-end gap-3">
                <button type="button" onclick="closeReplaceModal()" class="px-4 py-2 rounded-xl bg-white/5 hover:bg-white/10 text-white text-xs font-semibold transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-amber-600 hover:bg-amber-500 text-white font-bold text-xs shadow-lg shadow-amber-700/30 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-arrows-rotate"></i>
                    <span>Terbitkan Kartu Pengganti</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAddMemberModal() {
        document.getElementById('modalAddMember').classList.remove('hidden');
    }

    function closeAddMemberModal() {
        document.getElementById('modalAddMember').classList.add('hidden');
    }

    function openReplaceModal(userId, memberName, title) {
        document.getElementById('replaceMemberName').innerText = memberName;
        document.getElementById('replaceModalTitle').innerText = title;
        document.getElementById('formReplaceCard').action = `/chapter/nfc/replace/${userId}`;
        document.getElementById('modalReplaceCard').classList.remove('hidden');
    }

    function closeReplaceModal() {
        document.getElementById('modalReplaceCard').classList.add('hidden');
    }
</script>
@endsection
