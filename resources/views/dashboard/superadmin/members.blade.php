@extends('layouts.app')

@section('title', 'Direktori Anggota Nasional')
@section('header_title', 'Direktori Anggota Nasional SMeCK Hooligan')
@section('header_subtitle', 'Basis data seluruh suporter terdaftar di seluruh chapter, manajemen KTA NFC fisik, verifikasi anggota baru, dan penetapan peran')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">

    {{-- Top Statistics Summary Bar (Clickable Quick Filters) --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <a href="{{ route('superadmin.members') }}" class="p-4 rounded-2xl bg-smeck-dark-900 border {{ !request('status') ? 'border-red-500/50 bg-red-500/10' : 'border-white/10' }} hover:border-red-500/30 transition-all flex items-center justify-between group">
            <div>
                <div class="text-[10px] font-bold uppercase text-white/50 tracking-wider">Total Suporter</div>
                <div class="font-display font-black text-2xl text-white mt-1">{{ $totalMembersCount }}</div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-white/5 group-hover:bg-red-500/20 text-white/60 group-hover:text-red-400 flex items-center justify-center text-sm transition-colors">
                <i class="fa-solid fa-users"></i>
            </div>
        </a>

        <a href="{{ route('superadmin.members', ['status' => 'active']) }}" class="p-4 rounded-2xl bg-smeck-dark-900 border {{ request('status') === 'active' ? 'border-smeck-green-500/50 bg-smeck-green-500/10' : 'border-white/10' }} hover:border-smeck-green-500/30 transition-all flex items-center justify-between group">
            <div>
                <div class="text-[10px] font-bold uppercase text-smeck-green-400 tracking-wider">Anggota Aktif</div>
                <div class="font-display font-black text-2xl text-smeck-green-400 mt-1">{{ $activeMembersCount }}</div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-smeck-green-500/20 text-smeck-green-400 flex items-center justify-center text-sm">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </a>

        <a href="{{ route('superadmin.members', ['status' => 'pending_approval']) }}" class="p-4 rounded-2xl bg-smeck-dark-900 border {{ request('status') === 'pending_approval' ? 'border-amber-500/50 bg-amber-500/10' : 'border-white/10' }} hover:border-amber-500/30 transition-all flex items-center justify-between group">
            <div>
                <div class="text-[10px] font-bold uppercase text-amber-400 tracking-wider">Menunggu Approval</div>
                <div class="font-display font-black text-2xl text-amber-400 mt-1">{{ $pendingMembersCount }}</div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center text-sm">
                <i class="fa-solid fa-user-clock"></i>
            </div>
        </a>

        <a href="{{ route('superadmin.members', ['status' => 'suspended']) }}" class="p-4 rounded-2xl bg-smeck-dark-900 border {{ request('status') === 'suspended' ? 'border-red-500/50 bg-red-500/10' : 'border-white/10' }} hover:border-red-500/30 transition-all flex items-center justify-between group">
            <div>
                <div class="text-[10px] font-bold uppercase text-red-400 tracking-wider">Kena Sanksi Suspend</div>
                <div class="font-display font-black text-2xl text-red-400 mt-1">{{ $suspendedMembersCount }}</div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-red-500/20 text-red-400 flex items-center justify-center text-sm">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
        </a>
    </div>

    {{-- Filter & Pencarian Nasional --}}
    <div class="p-5 sm:p-6 rounded-3xl bg-smeck-dark-900 border border-white/10 shadow-xl">
        <form action="{{ route('superadmin.members') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3">
                {{-- Search Query --}}
                <div class="lg:col-span-5">
                    <label class="block text-[11px] font-bold uppercase text-white/50 mb-1">Cari Suporter</label>
                    <div class="relative">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-white/30 text-xs"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIA, NIK, No. KTA, telepon..." class="w-full pl-9 pr-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-xs placeholder-white/30 focus:border-red-400 focus:outline-none">
                    </div>
                </div>

                {{-- Filter Chapter --}}
                <div class="lg:col-span-3">
                    <label class="block text-[11px] font-bold uppercase text-white/50 mb-1">Basis Chapter</label>
                    <select name="chapter_id" class="w-full px-3 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-xs focus:border-red-400 focus:outline-none">
                        <option value="" class="bg-smeck-dark-900">Semua Basis Wilayah</option>
                        @foreach($chapters as $ch)
                            <option value="{{ $ch->id }}" {{ request('chapter_id') == $ch->id ? 'selected' : '' }} class="bg-smeck-dark-900">{{ $ch->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Status Akun --}}
                <div class="lg:col-span-2">
                    <label class="block text-[11px] font-bold uppercase text-white/50 mb-1">Status Keanggotaan</label>
                    <select name="status" class="w-full px-3 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-xs focus:border-red-400 focus:outline-none">
                        <option value="" class="bg-smeck-dark-900">Semua Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }} class="bg-smeck-dark-900">Aktif</option>
                        <option value="pending_approval" {{ request('status') === 'pending_approval' ? 'selected' : '' }} class="bg-smeck-dark-900">Menunggu Approval</option>
                        <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }} class="bg-smeck-dark-900">Suspended / Sanksi</option>
                    </select>
                </div>

                {{-- Filter Golongan Darah --}}
                <div class="lg:col-span-2">
                    <label class="block text-[11px] font-bold uppercase text-white/50 mb-1">Gol. Darah</label>
                    <select name="blood_type" class="w-full px-3 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-xs focus:border-red-400 focus:outline-none">
                        <option value="" class="bg-smeck-dark-900">Semua</option>
                        <option value="A" {{ request('blood_type') === 'A' ? 'selected' : '' }} class="bg-smeck-dark-900">Golongan A</option>
                        <option value="B" {{ request('blood_type') === 'B' ? 'selected' : '' }} class="bg-smeck-dark-900">Golongan B</option>
                        <option value="AB" {{ request('blood_type') === 'AB' ? 'selected' : '' }} class="bg-smeck-dark-900">Golongan AB</option>
                        <option value="O" {{ request('blood_type') === 'O' ? 'selected' : '' }} class="bg-smeck-dark-900">Golongan O</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center justify-between pt-1">
                <div class="text-xs text-white/50">
                    Menampilkan <strong>{{ $members->total() }}</strong> suporter terdaftar
                </div>
                <div class="flex items-center gap-2">
                    @if(request()->hasAny(['search', 'chapter_id', 'blood_type', 'status', 'role']))
                        <a href="{{ route('superadmin.members') }}" class="px-3 py-2 rounded-xl bg-white/5 hover:bg-white/10 text-white/60 hover:text-white text-xs font-semibold transition-colors flex items-center gap-1.5">
                            <i class="fa-solid fa-rotate-left"></i>
                            <span>Reset</span>
                        </a>
                    @endif
                    <button type="submit" class="px-5 py-2 rounded-xl bg-red-600 hover:bg-red-500 text-white font-bold text-xs shadow-lg transition-colors flex items-center gap-2">
                        <i class="fa-solid fa-filter"></i>
                        <span>Filter Data</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Tabel Anggota Nasional Terstruktur & Rapi --}}
    <div class="rounded-3xl bg-smeck-dark-900 border border-white/10 overflow-hidden shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-white/5 border-b border-white/10 text-white/40 uppercase tracking-wider text-[11px]">
                        <th class="py-4 px-5">Suporter & Basis</th>
                        <th class="py-4 px-5">KTA NFC & Identitas</th>
                        <th class="py-4 px-5">Kontak & Medis</th>
                        <th class="py-4 px-5 text-center">Peran (Role)</th>
                        <th class="py-4 px-5 text-center">Status</th>
                        <th class="py-4 px-5 text-right">Aksi Manajemen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($members as $m)
                        @php
                            $card = $m->nfcCard;
                        @endphp
                        <tr class="hover:bg-white/[0.02] transition-colors">
                            {{-- 1. Info Suporter & Basis --}}
                            <td class="py-4 px-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-smeck-dark-800 border border-emerald-400/30 overflow-hidden flex items-center justify-center font-black text-sm text-white shrink-0 shadow">
                                        @if($m->profile?->profile_photo_path)
                                            <img src="{{ asset('storage/' . $m->profile->profile_photo_path) }}" alt="{{ $m->name }}" class="w-full h-full object-cover" style="object-position: center top;">
                                        @else
                                            <span class="text-smeck-green-400">{{ strtoupper(substr($m->name, 0, 1)) }}</span>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-bold text-white text-sm truncate">{{ $m->name }}</div>
                                        <div class="text-[11px] text-white/50 truncate">{{ $m->email }}</div>
                                        <div class="text-[11px] text-smeck-green-400 mt-0.5 flex items-center gap-1.5 font-medium">
                                            <i class="fa-solid fa-location-dot text-[10px]"></i>
                                            <span>{{ $m->chapter?->name ?? 'Chapter Teladan (Pusat)' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- 2. KTA NFC & Identitas --}}
                            <td class="py-4 px-5">
                                <div class="font-mono text-xs font-bold text-smeck-gold">
                                    <i class="fa-solid fa-id-badge mr-1 text-[10px]"></i>{{ $m->profile?->nia ?? 'Belum ada NIA' }}
                                </div>
                                @if($card)
                                    <div class="font-mono text-[11px] text-white/80 mt-1 flex items-center gap-1.5">
                                        <i class="fa-solid fa-credit-card text-white/30 text-[10px]"></i>
                                        <span>{{ $card->card_number ?? '6032-••••-••••-1950' }}</span>
                                    </div>
                                    <div class="text-[10px] text-white/40 uppercase mt-0.5 font-mono">
                                        {{ $card->card_vendor }} • UID: {{ substr($card->uid_number, 0, 8) }}••
                                    </div>
                                @else
                                    <div class="text-[10px] text-white/40 italic mt-1 flex items-center gap-1">
                                        <i class="fa-regular fa-circle-xmark text-amber-400/60"></i>
                                        <span>Kartu fisik belum terbit</span>
                                    </div>
                                @endif
                            </td>

                            {{-- 3. Kontak & Kesiapsiagaan Medis --}}
                            <td class="py-4 px-5">
                                <div class="text-white/80 text-xs">
                                    <i class="fa-solid fa-phone text-white/30 text-[10px] mr-1"></i>
                                    <span>{{ $m->profile?->phone_number ?? '-' }}</span>
                                </div>
                                <div class="text-[10px] text-white/40 mt-1">
                                    NIK: <span class="font-mono">{{ $m->profile?->nik_ktp ?? '-' }}</span>
                                </div>
                                <div class="text-[10px] text-red-400 font-bold mt-0.5 flex items-center gap-1">
                                    <i class="fa-solid fa-droplet text-red-500 text-[9px]"></i>
                                    <span>Gol. Darah: {{ $m->profile?->blood_type ?? '-' }}</span>
                                </div>
                            </td>

                            {{-- 4. Role & Peran --}}
                            <td class="py-4 px-5 text-center">
                                @if($m->role === 'admin_chapter')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30">
                                        <i class="fa-solid fa-shield text-[9px]"></i>
                                        <span>Pengurus Chapter</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-smeck-green-500/20 text-smeck-green-400 border border-smeck-green-500/30">
                                        <i class="fa-solid fa-user text-[9px]"></i>
                                        <span>Anggota Reguler</span>
                                    </span>
                                @endif
                            </td>

                            {{-- 5. Status Keanggotaan --}}
                            <td class="py-4 px-5 text-center">
                                @if($m->status === 'active')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-smeck-green-500/20 text-smeck-green-400 border border-smeck-green-500/30">
                                        <span class="w-1.5 h-1.5 rounded-full bg-smeck-green-400 animate-pulse"></span>
                                        <span>AKTIF</span>
                                    </span>
                                @elseif($m->status === 'pending_approval')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-amber-500/20 text-amber-300 border border-amber-500/30">
                                        <i class="fa-regular fa-clock text-[9px]"></i>
                                        <span>MENUNGGU</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-red-500/20 text-red-400 border border-red-500/30">
                                        <i class="fa-solid fa-ban text-[9px]"></i>
                                        <span>SUSPENDED</span>
                                    </span>
                                    @if($m->suspension_reason)
                                        <div class="text-[10px] text-red-400/90 mt-1 max-w-[140px] truncate mx-auto font-medium" title="{{ $m->suspension_reason }}">
                                            <i class="fa-solid fa-triangle-exclamation mr-1 text-red-500 text-[9px]"></i>{{ $m->suspension_reason }}
                                        </div>
                                    @endif
                                @endif
                            </td>

                            {{-- 6. Aksi Manajemen --}}
                            <td class="py-4 px-5 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    {{-- Approval Action if Pending --}}
                                    @if($m->status === 'pending_approval')
                                        <form action="{{ route('superadmin.members.approve', $m->id) }}" method="POST" onsubmit="return confirm('Setujui pendaftaran suporter {{ addslashes($m->name) }}?')">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1.5 rounded-xl bg-smeck-green-600 hover:bg-smeck-500 text-white font-bold text-[11px] shadow transition-colors flex items-center gap-1">
                                                <i class="fa-solid fa-check"></i>
                                                <span>Setujui</span>
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Tombol Edit Data Anggota (Nama, NIK, Kontak, Chapter, dll) --}}
                                    @php
                                        $editData = [
                                            'id' => $m->id,
                                            'name' => $m->name,
                                            'email' => $m->email,
                                            'nik_ktp' => $m->profile?->nik_ktp ?? '',
                                            'phone_number' => $m->profile?->phone_number ?? '',
                                            'chapter_id' => $m->chapter_id,
                                            'blood_type' => $m->profile?->blood_type ?? '',
                                            'shirt_size' => $m->profile?->shirt_size ?? '',
                                            'gender' => $m->profile?->gender ?? '',
                                            'place_of_birth' => $m->profile?->place_of_birth ?? '',
                                            'date_of_birth' => $m->profile?->date_of_birth ? $m->profile->date_of_birth->format('Y-m-d') : '',
                                            'address' => $m->profile?->address ?? '',
                                            'nia' => $m->profile?->nia ?? 'Belum ada NIA',
                                        ];
                                    @endphp
                                    <button type="button" onclick='openEditMemberModal({!! json_encode($editData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) !!})' class="px-2.5 py-1.5 rounded-xl bg-blue-500/10 hover:bg-blue-600 text-blue-400 hover:text-white border border-blue-500/20 font-semibold text-[11px] transition-colors flex items-center gap-1" title="Edit Data Suporter">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                        <span>Edit</span>
                                    </button>

                                    {{-- Role Toggle (Promote/Demote) --}}
                                    <form action="{{ route('superadmin.members.role', $m->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="role" value="{{ $m->role === 'admin_chapter' ? 'member' : 'admin_chapter' }}">
                                        <button type="submit" title="{{ $m->role === 'admin_chapter' ? 'Turunkan ke Anggota Biasa' : 'Angkat jadi Pengurus Basis' }}" class="px-2.5 py-1.5 rounded-xl {{ $m->role === 'admin_chapter' ? 'bg-white/10 hover:bg-white/20 text-white/70' : 'bg-amber-500/10 hover:bg-amber-500 text-amber-400 hover:text-white border border-amber-500/20' }} font-semibold text-[11px] transition-colors">
                                            {{ $m->role === 'admin_chapter' ? 'Demote' : 'Promote' }}
                                        </button>
                                    </form>

                                    {{-- Status Toggle (Suspend / Aktifkan) --}}
                                    <form action="{{ route('superadmin.members.status', $m->id) }}" method="POST" id="form-status-{{ $m->id }}">
                                        @csrf
                                        <input type="hidden" name="suspension_reason" id="reason-{{ $m->id }}" value="">
                                        <button type="button" onclick="handleStatusToggle({{ $m->id }}, '{{ addslashes($m->name) }}', '{{ $m->status }}')" class="px-2.5 py-1.5 rounded-xl {{ $m->status === 'active' ? 'bg-red-500/10 hover:bg-red-500 text-red-400 hover:text-white border border-red-500/20' : 'bg-smeck-green-500/10 hover:bg-smeck-green-500 text-smeck-green-400 hover:text-white border border-smeck-green-500/20' }} font-semibold text-[11px] transition-colors">
                                            {{ $m->status === 'active' ? 'Suspend' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-white/40 text-xs">
                                <i class="fa-solid fa-users-slash text-2xl mb-2 text-white/20 block"></i>
                                Tidak ditemukan suporter yang cocok dengan pencarian filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($members->hasPages())
            <div class="p-4 border-t border-white/5 bg-white/[0.01]">
                {{ $members->links() }}
            </div>
        @endif
    </div>

</div>

{{-- ========================================================================= --}}
{{-- MODAL EDIT DATA ANGGOTA SUPORTER (NAMA, NIK, KONTAK, CHAPTER, DLL) --}}
{{-- ========================================================================= --}}
<div id="modalEditMember" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-md hidden flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-smeck-dark-900 border border-white/10 rounded-3xl max-w-2xl w-full p-6 sm:p-8 shadow-2xl my-8 relative">
        <button type="button" onclick="closeEditMemberModal()" class="absolute top-6 right-6 text-white/40 hover:text-white text-lg">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <div class="mb-6">
            <h3 class="font-display font-black text-xl text-white flex items-center gap-2">
                <i class="fa-solid fa-user-pen text-blue-400"></i>
                <span>Edit Identitas Resmi Anggota</span>
            </h3>
            <p class="text-xs text-white/50 mt-1">Ubah nama, NIK KTP, kontak, chapter, dan data profil suporter secara valid.</p>
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

            {{-- Baris 3: Basis Chapter & Golongan Darah --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Basis / Chapter Wilayah</label>
                    <select id="edit_chapter_id" name="chapter_id" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-blue-400 focus:outline-none">
                        <option value="" class="bg-smeck-dark-900">Chapter Teladan (Pusat)</option>
                        @foreach($chapters as $c)
                            <option value="{{ $c->id }}" class="bg-smeck-dark-900">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
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
            </div>

            {{-- Baris 4: Jenis Kelamin & Ukuran Jersey --}}
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

            {{-- Baris 5: Tempat & Tanggal Lahir --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Tempat Lahir</label>
                    <input type="text" id="edit_place_of_birth" name="place_of_birth" placeholder="Contoh: Medan" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-blue-400 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Tanggal Lahir</label>
                    <input type="date" id="edit_date_of_birth" name="date_of_birth" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-blue-400 focus:outline-none">
                </div>
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

<script>
function openEditMemberModal(data) {
    document.getElementById('formEditMember').action = '/superadmin/members/' + data.id + '/update';
    document.getElementById('edit_name').value = data.name || '';
    document.getElementById('edit_email').value = data.email || '';
    document.getElementById('edit_nik_ktp').value = data.nik_ktp || '';
    document.getElementById('edit_phone_number').value = data.phone_number || '';
    document.getElementById('edit_chapter_id').value = data.chapter_id || '';
    document.getElementById('edit_blood_type').value = data.blood_type || '-';
    document.getElementById('edit_gender').value = data.gender || '';
    document.getElementById('edit_shirt_size').value = data.shirt_size || '-';
    document.getElementById('edit_place_of_birth').value = data.place_of_birth || '';
    document.getElementById('edit_date_of_birth').value = data.date_of_birth || '';
    document.getElementById('edit_address').value = data.address || '';
    document.getElementById('edit_password').value = '';

    document.getElementById('modalEditMember').classList.remove('hidden');
}

function closeEditMemberModal() {
    document.getElementById('modalEditMember').classList.add('hidden');
}

function handleStatusToggle(userId, userName, currentStatus) {
    const form = document.getElementById('form-status-' + userId);
    const reasonInput = document.getElementById('reason-' + userId);

    if (currentStatus === 'active') {
        const reason = prompt(
            '⚠️ MASUKKAN ALASAN SANKSI SUSPEND UNTUK ' + userName + ':\n\n' +
            'Contoh alasan:\n' +
            '1. Pelanggaran Kode Etik Organisasi & Tata Tertib Tribun SMeCK Hooligan\n' +
            '2. Keributan / Anarkisme di Tribun Suporter\n' +
            '3. Penyalahgunaan KTA / Pemalsuan Fasilitas Tiket\n' +
            '4. Sanksi Disiplin Basis Chapter\n\n' +
            'Alasan ini akan otomatis tampil saat KTA dicek via Bot WhatsApp & Web Portal.',
            'Pelanggaran Kode Etik Organisasi & Tata Tertib Tribun SMeCK Hooligan'
        );

        if (reason === null) return;
        reasonInput.value = reason.trim() || 'Pelanggaran Kode Etik Organisasi & Tata Tertib Tribun SMeCK Hooligan';
        form.submit();
    } else {
        if (confirm('Pulihkan status akun suporter ' + userName + ' menjadi AKTIF kembali? Kartu KTA NFC otomatis akan aktif.')) {
            form.submit();
        }
    }
}
</script>
@endsection
