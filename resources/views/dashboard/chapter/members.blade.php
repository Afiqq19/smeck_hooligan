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
        <div class="flex flex-wrap items-center gap-2.5">
            <button onclick="openPrintRosterModal()" class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs border border-white/10 transition-all flex items-center gap-2">
                <i class="fa-solid fa-print text-amber-400"></i>
                <span>Cetak Roster (A4)</span>
            </button>
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
                        <th class="py-4 px-5">Identitas Medis & Kontak</th>
                        <th class="py-4 px-5">Nomor Kartu KTA</th>
                        <th class="py-4 px-5 text-center">Status Kartu</th>
                        <th class="py-4 px-5 text-right">Aksi Manajemen KTA</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($members as $m)
                        @php
                            $card = $m->nfcCard;
                            $memberJson = json_encode([
                                'id' => $m->id,
                                'name' => $m->name,
                                'email' => $m->email,
                                'nia' => $m->profile?->nia ?? 'Belum ada NIA',
                                'nik' => $m->profile?->nik_ktp ?? '-',
                                'nik_raw' => $m->profile?->nik_ktp ?? '',
                                'phone' => $m->profile?->phone_number ?? '-',
                                'phone_raw' => $m->profile?->phone_number ?? '',
                                'blood_type' => $m->profile?->blood_type ?? '-',
                                'blood_type_raw' => $m->profile?->blood_type ?? '',
                                'shirt_size' => $m->profile?->shirt_size ?? '-',
                                'shirt_size_raw' => $m->profile?->shirt_size ?? '',
                                'gender' => $m->profile?->gender == 'L' ? 'Laki-laki' : ($m->profile?->gender == 'P' ? 'Perempuan' : '-'),
                                'gender_raw' => $m->profile?->gender ?? '',
                                'birth' => ($m->profile?->place_of_birth ?? '-') . ', ' . ($m->profile?->date_of_birth ? $m->profile->date_of_birth->format('d M Y') : '-'),
                                'place_of_birth_raw' => $m->profile?->place_of_birth ?? '',
                                'date_of_birth_raw' => $m->profile?->date_of_birth ? $m->profile->date_of_birth->format('Y-m-d') : '',
                                'address' => $m->profile?->address ?? '-',
                                'address_raw' => $m->profile?->address ?? '',
                                'emergency_name' => $m->profile?->emergency_contact_name ?? '-',
                                'emergency_phone' => $m->profile?->emergency_contact_number ?? '-',
                                'emergency_rel' => $m->profile?->emergency_contact_relation ?? '-',
                                'card_number' => $card ? $card->card_number : 'Belum Ada',
                                'card_uid' => $card ? $card->uid_number : 'Belum Ada',
                                'card_vendor' => $card ? strtoupper($card->card_vendor) : '-',
                                'card_status' => $card ? $card->status : 'none',
                                'joined' => $m->created_at ? $m->created_at->format('d M Y') : '-'
                            ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
                        @endphp
                        <tr class="hover:bg-white/[0.02] transition-colors">
                            {{-- Info Anggota --}}
                            <td class="py-4 px-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-smeck-green-600/30 border border-smeck-green-500/40 text-smeck-green-400 flex items-center justify-center font-bold text-xs shrink-0">
                                        {{ strtoupper(substr($m->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-bold text-white text-sm truncate flex items-center gap-1.5">
                                            <span>{{ $m->name }}</span>
                                            <button type="button" onclick='showMemberDetail({!! $memberJson !!})' class="text-amber-400 hover:text-amber-300 text-[11px]" title="Lihat Profil Lengkap">
                                                <i class="fa-solid fa-circle-info"></i>
                                            </button>
                                        </div>
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
                                <div class="text-[10px] text-amber-400/90 mt-0.5 flex items-center gap-2">
                                    <span class="px-1.5 py-0.5 rounded bg-amber-400/10 text-amber-300 font-bold">Gol: {{ $m->profile?->blood_type ?? '-' }}</span>
                                    <span class="px-1.5 py-0.5 rounded bg-white/10 text-white/70 font-bold">Size: {{ $m->profile?->shirt_size ?? '-' }}</span>
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
                                        <span class="text-white/40 font-mono text-[9px]">UID: {{ substr($card->uid_number, 0, 8) }}••</span>
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
                                <div class="flex items-center justify-end gap-1.5">
                                    {{-- Tombol Detail Profil --}}
                                    <button type="button" onclick='showMemberDetail({!! $memberJson !!})' class="px-2.5 py-1.5 rounded-lg bg-white/5 hover:bg-white/15 text-white/70 hover:text-white text-[11px] font-semibold transition-colors" title="Lihat Profil">
                                        <i class="fa-regular fa-eye"></i>
                                    </button>

                                    {{-- Tombol Edit Data Suporter (Nama, NIK, dll) --}}
                                    <button type="button" onclick='openEditMemberModal({!! $memberJson !!})' class="px-2.5 py-1.5 rounded-lg bg-blue-500/10 hover:bg-blue-600 text-blue-400 hover:text-white border border-blue-500/20 font-semibold text-[11px] transition-colors flex items-center gap-1" title="Edit Data Suporter">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                        <span class="hidden sm:inline">Edit</span>
                                    </button>

                                    {{-- Jika Belum Punya Kartu: Tombol Terbitkan --}}
                                    @if(!$card)
                                        <button onclick="openReplaceModal({{ $m->id }}, '{{ addslashes($m->name) }}', 'Terbitkan KTA Baru')" class="px-3 py-1.5 rounded-lg bg-smeck-green-600 hover:bg-smeck-green-500 text-white font-semibold text-[11px] shadow transition-colors flex items-center gap-1.5">
                                            <i class="fa-solid fa-plus"></i>
                                            <span>Terbitkan KTA</span>
                                        </button>
                                    @else
                                        {{-- Jika Kartu Aktif: Tombol Blokir (Hilang/Dicuri) --}}
                                        @if($card->status === 'active')
                                            <form action="{{ route('chapter.nfc.block', $card->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin MEMBLOKIR kartu KTA milik {{ $m->name }}? Kartu fisik lama tidak akan bisa di-tap di stadion atau partner SMeCK.')">
                                                @csrf
                                                <button type="submit" class="px-2.5 py-1.5 rounded-lg bg-red-500/10 hover:bg-red-500 text-red-400 hover:text-white border border-red-500/20 font-semibold text-[11px] transition-colors flex items-center gap-1" title="Lapor Kartu Hilang & Blokir">
                                                    <i class="fa-solid fa-ban"></i>
                                                    <span class="hidden sm:inline">Blokir</span>
                                                </button>
                                            </form>
                                        @else
                                            {{-- Jika Kartu Sedang Diblokir: Tombol Buka Blokir --}}
                                            <form action="{{ route('chapter.nfc.unblock', $card->id) }}" method="POST" onsubmit="return confirm('Aktifkan kembali kartu KTA milik {{ $m->name }}?')">
                                                @csrf
                                                <button type="submit" class="px-2.5 py-1.5 rounded-lg bg-smeck-green-500/10 hover:bg-smeck-green-600 text-smeck-green-400 hover:text-white border border-smeck-green-500/20 font-semibold text-[11px] transition-colors flex items-center gap-1" title="Buka Blokir Kartu">
                                                    <i class="fa-solid fa-unlock"></i>
                                                    <span class="hidden sm:inline">Buka</span>
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Tombol Ganti Kartu Baru (Re-Issue) --}}
                                        <button onclick="openReplaceModal({{ $m->id }}, '{{ addslashes($m->name) }}', 'Ganti Kartu KTA Fisik Baru')" class="px-2.5 py-1.5 rounded-lg bg-amber-500/10 hover:bg-amber-600 text-amber-400 hover:text-white border border-amber-500/20 font-semibold text-[11px] transition-colors flex items-center gap-1" title="Ganti Kartu Baru">
                                            <i class="fa-solid fa-arrows-rotate"></i>
                                            <span class="hidden sm:inline">Ganti</span>
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

{{-- ========================================================================= --}}
{{-- MODAL 3: DETAIL PROFIL LENGKAP SUPORTER --}}
{{-- ========================================================================= --}}
<div id="modalMemberDetail" class="fixed inset-0 z-50 bg-black/85 backdrop-blur-md hidden flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-smeck-dark-900 border border-white/10 rounded-3xl max-w-xl w-full p-6 sm:p-8 shadow-2xl relative my-8">
        <button onclick="closeMemberDetail()" class="absolute top-6 right-6 text-white/40 hover:text-white text-lg">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <div class="flex items-center gap-4 mb-6">
            <div id="detailAvatar" class="w-14 h-14 rounded-2xl bg-smeck-green-600/30 border border-smeck-green-500/40 text-smeck-green-400 flex items-center justify-center font-display font-black text-2xl shrink-0">
                A
            </div>
            <div>
                <h3 id="detailName" class="font-display font-black text-xl text-white">Nama Suporter</h3>
                <div class="flex items-center gap-2 mt-1">
                    <span id="detailNia" class="px-2 py-0.5 rounded bg-smeck-gold/20 text-smeck-gold font-mono text-xs font-bold">NIA: -</span>
                    <span id="detailStatus" class="px-2 py-0.5 rounded bg-smeck-green-500/20 text-smeck-green-400 text-[11px] font-bold uppercase">Aktif</span>
                </div>
            </div>
        </div>

        {{-- Detail Grid --}}
        <div class="space-y-4 text-xs">
            {{-- Info KTA NFC --}}
            <div class="p-4 rounded-2xl bg-white/[0.03] border border-white/5 space-y-2">
                <div class="text-[11px] uppercase tracking-wider font-bold text-smeck-gold flex items-center gap-1.5">
                    <i class="fa-solid fa-credit-card"></i>
                    <span>Informasi KTA NFC Fisik</span>
                </div>
                <div class="grid grid-cols-2 gap-3 pt-1">
                    <div>
                        <span class="text-white/40 block">Nomor Kartu:</span>
                        <span id="detailCardNumber" class="font-mono font-bold text-white text-sm">6032-XXXX-XXXX-XXXX</span>
                    </div>
                    <div>
                        <span class="text-white/40 block">Chip UID:</span>
                        <span id="detailCardUid" class="font-mono text-white/80">04:XX:XX:XX:XX</span>
                    </div>
                    <div>
                        <span class="text-white/40 block">Vendor Fisik:</span>
                        <span id="detailCardVendor" class="text-white/80 font-semibold">MANDIRI E-MONEY</span>
                    </div>
                    <div>
                        <span class="text-white/40 block">Status KTA:</span>
                        <span id="detailCardStatus" class="font-bold text-smeck-green-400">AKTIF</span>
                    </div>
                </div>
            </div>

            {{-- Info Pribadi & Medis --}}
            <div class="p-4 rounded-2xl bg-white/[0.03] border border-white/5 space-y-2">
                <div class="text-[11px] uppercase tracking-wider font-bold text-amber-400 flex items-center gap-1.5">
                    <i class="fa-solid fa-notes-medical"></i>
                    <span>Data Medis & Pribadi</span>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 pt-1">
                    <div>
                        <span class="text-white/40 block">Golongan Darah:</span>
                        <span id="detailBloodType" class="text-amber-300 font-bold text-sm">O</span>
                    </div>
                    <div>
                        <span class="text-white/40 block">Ukuran Kaos:</span>
                        <span id="detailShirtSize" class="text-white font-bold text-sm">XL</span>
                    </div>
                    <div>
                        <span class="text-white/40 block">Jenis Kelamin:</span>
                        <span id="detailGender" class="text-white/80">Laki-laki</span>
                    </div>
                    <div class="col-span-2 sm:col-span-3">
                        <span class="text-white/40 block">NIK KTP:</span>
                        <span id="detailNik" class="font-mono text-white">1271xxxxxxxxxxxx</span>
                    </div>
                    <div class="col-span-2 sm:col-span-3">
                        <span class="text-white/40 block">Tempat, Tanggal Lahir:</span>
                        <span id="detailBirth" class="text-white/80">Medan, 17 Agustus 1998</span>
                    </div>
                    <div class="col-span-2 sm:col-span-3">
                        <span class="text-white/40 block">Alamat Domisili:</span>
                        <span id="detailAddress" class="text-white/80 leading-relaxed">-</span>
                    </div>
                </div>
            </div>

            {{-- Kontak Darurat --}}
            <div class="p-4 rounded-2xl bg-white/[0.03] border border-white/5 space-y-2">
                <div class="text-[11px] uppercase tracking-wider font-bold text-red-400 flex items-center gap-1.5">
                    <i class="fa-solid fa-phone-volume"></i>
                    <span>Kontak Darurat (Emergency Awaydays)</span>
                </div>
                <div class="grid grid-cols-2 gap-3 pt-1">
                    <div>
                        <span class="text-white/40 block">Nama Kontak:</span>
                        <span id="detailEmergencyName" class="text-white font-semibold">-</span>
                    </div>
                    <div>
                        <span class="text-white/40 block">Hubungan:</span>
                        <span id="detailEmergencyRel" class="text-white/80">-</span>
                    </div>
                    <div class="col-span-2">
                        <span class="text-white/40 block">Nomor Telepon:</span>
                        <span id="detailEmergencyPhone" class="font-mono text-amber-400 font-bold">-</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end">
            <button type="button" onclick="closeMemberDetail()" class="px-5 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-semibold transition-colors">
                Tutup
            </button>
        </div>
    </div>
</div>

{{-- ========================================================================= --}}
{{-- MODAL 4: CETAK ROSTER ANGGOTA BASIS (A4 PRINT PREVIEW) --}}
{{-- ========================================================================= --}}
<div id="modalPrintRoster" class="fixed inset-0 z-50 bg-black/90 backdrop-blur-md hidden flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white text-slate-900 rounded-2xl max-w-4xl w-full p-6 sm:p-8 shadow-2xl relative my-8 print:p-0 print:m-0 print:shadow-none print:w-full">
        {{-- Tombol Tutup & Cetak (Sembunyi saat di-print) --}}
        <div class="flex items-center justify-between pb-4 border-b border-slate-200 mb-6 print:hidden">
            <div>
                <h4 class="font-bold text-base text-slate-800">Preview Dokumen Roster Anggota Basis</h4>
                <p class="text-xs text-slate-500">Format siap cetak A4 untuk keperluan surat jalan tur awaydays atau kepolisian.</p>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="window.print()" class="px-4 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs shadow flex items-center gap-2">
                    <i class="fa-solid fa-print"></i>
                    <span>Cetak Sekarang (Print)</span>
                </button>
                <button onclick="closePrintRosterModal()" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs">
                    Tutup
                </button>
            </div>
        </div>

        {{-- Printable Paper Area --}}
        <div id="printableArea" class="font-sans">
            {{-- Kop Surat Resmi --}}
            <div class="flex items-center justify-between pb-4 border-b-2 border-slate-900 mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-emerald-800 text-amber-400 flex items-center justify-center text-xl font-black">
                        SM
                    </div>
                    <div>
                        <h2 class="font-black text-lg text-slate-900 tracking-wider">SUPORTER MEDAN CINTA KINANTAN (SMeCK)</h2>
                        <h3 class="font-bold text-xs text-emerald-800 uppercase tracking-widest">PENGURUS BASIS: {{ strtoupper($chapter->name ?? 'CHAPTER') }}</h3>
                        <p class="text-[10px] text-slate-500">Sekretariat / Basecamp Basis SMeCK Hooligan • Kontak Darurat: 0812-6000-1950</p>
                    </div>
                </div>
                <div class="text-right text-[11px] text-slate-600">
                    <div>Dicetak: {{ now()->format('d/m/Y H:i') }} WIB</div>
                    <div>Dokumen Resmi Basis</div>
                </div>
            </div>

            <div class="text-center my-4">
                <h3 class="font-black text-sm uppercase underline tracking-wider">ROSTER RESMI ANGGOTA BASIS</h3>
                <p class="text-[11px] text-slate-600 mt-0.5">Daftar Anggota Sah Terdaftar dengan KTA NFC & Rekam Medis Darurat</p>
            </div>

            <table class="w-full text-left text-[11px] border border-slate-300 border-collapse">
                <thead>
                    <tr class="bg-slate-100 border-b border-slate-300 text-slate-700 font-bold uppercase text-[10px]">
                        <th class="py-2 px-2.5 border-r border-slate-300 w-8 text-center">No</th>
                        <th class="py-2 px-2.5 border-r border-slate-300">NIA Resmi</th>
                        <th class="py-2 px-2.5 border-r border-slate-300">Nama Lengkap</th>
                        <th class="py-2 px-2.5 border-r border-slate-300 text-center">Gol</th>
                        <th class="py-2 px-2.5 border-r border-slate-300 text-center">Size</th>
                        <th class="py-2 px-2.5 border-r border-slate-300">No. WhatsApp</th>
                        <th class="py-2 px-2.5 border-r border-slate-300">Nomor Kartu KTA</th>
                        <th class="py-2 px-2.5">Kontak Darurat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($members as $index => $m)
                        <tr class="text-slate-800">
                            <td class="py-1.5 px-2 border-r border-slate-300 text-center font-mono">{{ $index + 1 }}</td>
                            <td class="py-1.5 px-2.5 border-r border-slate-300 font-mono font-bold">{{ $m->profile?->nia ?? '-' }}</td>
                            <td class="py-1.5 px-2.5 border-r border-slate-300 font-semibold">{{ $m->name }}</td>
                            <td class="py-1.5 px-2 border-r border-slate-300 text-center font-bold">{{ $m->profile?->blood_type ?? '-' }}</td>
                            <td class="py-1.5 px-2 border-r border-slate-300 text-center">{{ $m->profile?->shirt_size ?? '-' }}</td>
                            <td class="py-1.5 px-2.5 border-r border-slate-300 font-mono">{{ $m->profile?->phone_number ?? '-' }}</td>
                            <td class="py-1.5 px-2.5 border-r border-slate-300 font-mono text-[10px]">{{ $m->nfcCard?->card_number ?? 'Belum Terbit' }}</td>
                            <td class="py-1.5 px-2.5 text-[10px]">
                                {{ $m->profile?->emergency_contact_name ?? '-' }} ({{ $m->profile?->emergency_contact_number ?? '-' }})
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-6 text-center text-slate-400">Tidak ada data anggota.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Tanda Tangan Pengurus --}}
            <div class="mt-8 pt-4 flex items-center justify-between text-xs text-slate-800">
                <div class="text-center w-48">
                    <div>Mengetahui,</div>
                    <div class="font-bold">Ketua Basis SMeCK</div>
                    <div class="h-16"></div>
                    <div class="font-bold underline">({{ auth()->user()->name }})</div>
                    <div class="text-[10px] text-slate-500">Ketua Chapter {{ $chapter->name ?? '' }}</div>
                </div>
                <div class="text-center w-48">
                    <div>Medan, {{ now()->format('d M Y') }}</div>
                    <div class="font-bold">Sekretaris Basis</div>
                    <div class="h-16"></div>
                    <div class="font-bold underline">(...................................)</div>
                    <div class="text-[10px] text-slate-500">SMeCK Hooligan</div>
                </div>
            </div>
        </div>
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

    function showMemberDetail(data) {
        document.getElementById('detailAvatar').innerText = data.name.charAt(0).toUpperCase();
        document.getElementById('detailName').innerText = data.name;
        document.getElementById('detailNia').innerText = 'NIA: ' + data.nia;
        document.getElementById('detailCardNumber').innerText = data.card_number;
        document.getElementById('detailCardUid').innerText = data.card_uid;
        document.getElementById('detailCardVendor').innerText = data.card_vendor;
        document.getElementById('detailCardStatus').innerText = data.card_status === 'active' ? 'AKTIF' : (data.card_status === 'blocked' ? 'DIBLOKIR / HILANG' : 'BELUM DITERBITKAN');
        document.getElementById('detailCardStatus').className = data.card_status === 'active' ? 'font-bold text-smeck-green-400' : 'font-bold text-red-400';
        
        document.getElementById('detailBloodType').innerText = data.blood_type;
        document.getElementById('detailShirtSize').innerText = data.shirt_size;
        document.getElementById('detailGender').innerText = data.gender;
        document.getElementById('detailNik').innerText = data.nik;
        document.getElementById('detailBirth').innerText = data.birth;
        document.getElementById('detailAddress').innerText = data.address;

        document.getElementById('detailEmergencyName').innerText = data.emergency_name;
        document.getElementById('detailEmergencyRel').innerText = data.emergency_rel;
        document.getElementById('detailEmergencyPhone').innerText = data.emergency_phone;

        document.getElementById('modalMemberDetail').classList.remove('hidden');
    }

    function closeMemberDetail() {
        document.getElementById('modalMemberDetail').classList.add('hidden');
    }

    function openPrintRosterModal() {
        document.getElementById('modalPrintRoster').classList.remove('hidden');
    }

    function closePrintRosterModal() {
        document.getElementById('modalPrintRoster').classList.add('hidden');
    }

    function openEditMemberModal(data) {
        document.getElementById('formEditMember').action = '/chapter/members/' + data.id + '/update';
        document.getElementById('edit_name').value = data.name || '';
        document.getElementById('edit_email').value = data.email || '';
        document.getElementById('edit_nik_ktp').value = data.nik_raw || (data.nik !== '-' ? data.nik : '') || '';
        document.getElementById('edit_phone_number').value = data.phone_raw || (data.phone !== '-' ? data.phone : '') || '';
        document.getElementById('edit_blood_type').value = data.blood_type_raw || (data.blood_type !== '-' ? data.blood_type : '-') || '-';
        document.getElementById('edit_gender').value = data.gender_raw || '';
        document.getElementById('edit_shirt_size').value = data.shirt_size_raw || (data.shirt_size !== '-' ? data.shirt_size : '-') || '-';
        document.getElementById('edit_place_of_birth').value = data.place_of_birth_raw || '';
        document.getElementById('edit_date_of_birth').value = data.date_of_birth_raw || '';
        document.getElementById('edit_address').value = data.address_raw || (data.address !== '-' ? data.address : '') || '';
        document.getElementById('edit_password').value = '';

        document.getElementById('modalEditMember').classList.remove('hidden');
    }

    function closeEditMemberModal() {
        document.getElementById('modalEditMember').classList.add('hidden');
    }
</script>

{{-- ========================================================================= --}}
{{-- MODAL EDIT DATA ANGGOTA BASIS --}}
{{-- ========================================================================= --}}
<div id="modalEditMember" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-md hidden flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-smeck-dark-900 border border-white/10 rounded-3xl max-w-2xl w-full p-6 sm:p-8 shadow-2xl my-8 relative">
        <button type="button" onclick="closeEditMemberModal()" class="absolute top-6 right-6 text-white/40 hover:text-white text-lg">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <div class="mb-6">
            <h3 class="font-display font-black text-xl text-white flex items-center gap-2">
                <i class="fa-solid fa-user-pen text-blue-400"></i>
                <span>Edit Identitas Anggota Basis</span>
            </h3>
            <p class="text-xs text-white/50 mt-1">Ubah nama, NIK KTP, kontak, dan data suporter di Basis {{ $chapter->name ?? '' }}.</p>
        </div>

        <form id="formEditMember" method="POST" action="" class="space-y-4">
            @csrf

            {{-- Baris 1: Nama Lengkap & Email --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Nama Lengkap <span class="text-red-400">*</span></label>
                    <input type="text" id="edit_name" name="name" required placeholder="Nama suporter" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-blue-400 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Alamat Email <span class="text-red-400">*</span></label>
                    <input type="email" id="edit_email" name="email" required placeholder="email@contoh.com" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-blue-400 focus:outline-none">
                </div>
            </div>

            {{-- Baris 2: NIK KTP (16 Digit) & Nomor WhatsApp --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">NIK KTP (16 Digit) <span class="text-red-400">*</span></label>
                    <input type="text" id="edit_nik_ktp" name="nik_ktp" required maxlength="16" placeholder="Contoh: 1209201912040002" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm font-mono focus:border-blue-400 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Nomor WhatsApp / HP <span class="text-red-400">*</span></label>
                    <input type="text" id="edit_phone_number" name="phone_number" required placeholder="Contoh: 081234567890" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-blue-400 focus:outline-none">
                </div>
            </div>

            {{-- Baris 3: Golongan Darah & Ukuran Jersey --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Golongan Darah</label>
                    <select id="edit_blood_type" name="blood_type" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-blue-400 focus:outline-none">
                        <option value="-" class="bg-smeck-dark-900">Belum Diketahui (-)</option>
                        <option value="A" class="bg-smeck-dark-900">Golongan A</option>
                        <option value="B" class="bg-smeck-dark-900">Golongan B</option>
                        <option value="AB" class="bg-smeck-dark-900">Golongan AB</option>
                        <option value="O" class="bg-smeck-dark-900">Golongan O</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Ukuran Baju / Jersey</label>
                    <select id="edit_shirt_size" name="shirt_size" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-blue-400 focus:outline-none">
                        <option value="-" class="bg-smeck-dark-900">- Pilih Ukuran -</option>
                        <option value="S" class="bg-smeck-dark-900">S</option>
                        <option value="M" class="bg-smeck-dark-900">M</option>
                        <option value="L" class="bg-smeck-dark-900">L</option>
                        <option value="XL" class="bg-smeck-dark-900">XL</option>
                        <option value="XXL" class="bg-smeck-dark-900">XXL</option>
                        <option value="3XL" class="bg-smeck-dark-900">3XL</option>
                    </select>
                </div>
            </div>

            {{-- Baris 4: Jenis Kelamin & Tempat Lahir --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Jenis Kelamin</label>
                    <select id="edit_gender" name="gender" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-blue-400 focus:outline-none">
                        <option value="" class="bg-smeck-dark-900">- Pilih Gender -</option>
                        <option value="L" class="bg-smeck-dark-900">Laki-laki</option>
                        <option value="P" class="bg-smeck-dark-900">Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Tempat Lahir</label>
                    <input type="text" id="edit_place_of_birth" name="place_of_birth" placeholder="Contoh: Medan" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-blue-400 focus:outline-none">
                </div>
            </div>

            {{-- Baris 5: Tanggal Lahir --}}
            <div>
                <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Tanggal Lahir</label>
                <input type="date" id="edit_date_of_birth" name="date_of_birth" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-blue-400 focus:outline-none">
            </div>

            {{-- Baris 6: Alamat Lengkap --}}
            <div>
                <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Alamat Lengkap</label>
                <textarea id="edit_address" name="address" rows="2" placeholder="Alamat domisili anggota" class="w-full px-4 py-2 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-blue-400 focus:outline-none"></textarea>
            </div>

            {{-- Baris 7: Reset Password (Opsional) --}}
            <div>
                <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Reset Kata Sandi Baru <span class="text-white/40 text-[11px] lowercase">(opsional, kosongkan jika tidak ingin diubah)</span></label>
                <input type="password" id="edit_password" name="password" placeholder="Minimal 6 karakter" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-blue-400 focus:outline-none">
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-white/10">
                <button type="button" onclick="closeEditMemberModal()" class="px-5 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-semibold text-xs transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs shadow-lg transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

