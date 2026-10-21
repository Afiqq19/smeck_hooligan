@extends('layouts.app')

@section('title', 'Direktori Anggota Nasional')
@section('header_title', 'Direktori Anggota Nasional SMeCK Hooligan')
@section('header_subtitle', 'Basis data seluruh suporter terdaftar di seluruh chapter, manajemen kartu KTA fisik, dan penetapan role pengurus')

@section('content')
<div class="space-y-8 max-w-7xl mx-auto">

    {{-- Filter & Pencarian Nasional --}}
    <div class="p-6 rounded-3xl bg-smeck-dark-900 border border-white/10 shadow-xl">
        <form action="{{ route('superadmin.members') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                {{-- Search Query --}}
                <div class="lg:col-span-2">
                    <label class="block text-[11px] font-bold uppercase text-white/50 mb-1.5">Pencarian Cepat</label>
                    <div class="relative">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-white/30 text-xs"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama, NIA, No. Kartu KTA, NIK, No. HP..." class="w-full pl-9 pr-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-xs focus:border-red-400 focus:outline-none">
                    </div>
                </div>

                {{-- Filter Chapter --}}
                <div>
                    <label class="block text-[11px] font-bold uppercase text-white/50 mb-1.5">Basis / Chapter</label>
                    <select name="chapter_id" class="w-full px-3 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-xs focus:border-red-400 focus:outline-none">
                        <option value="" class="bg-smeck-dark-900">Semua Basis</option>
                        @foreach($chapters as $ch)
                            <option value="{{ $ch->id }}" {{ request('chapter_id') == $ch->id ? 'selected' : '' }} class="bg-smeck-dark-900">{{ $ch->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Golongan Darah --}}
                <div>
                    <label class="block text-[11px] font-bold uppercase text-white/50 mb-1.5">Golongan Darah</label>
                    <select name="blood_type" class="w-full px-3 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-xs focus:border-red-400 focus:outline-none">
                        <option value="" class="bg-smeck-dark-900">Semua Golongan</option>
                        <option value="A" {{ request('blood_type') === 'A' ? 'selected' : '' }} class="bg-smeck-dark-900">Golongan A</option>
                        <option value="B" {{ request('blood_type') === 'B' ? 'selected' : '' }} class="bg-smeck-dark-900">Golongan B</option>
                        <option value="AB" {{ request('blood_type') === 'AB' ? 'selected' : '' }} class="bg-smeck-dark-900">Golongan AB</option>
                        <option value="O" {{ request('blood_type') === 'O' ? 'selected' : '' }} class="bg-smeck-dark-900">Golongan O</option>
                    </select>
                </div>

                {{-- Filter Status Akun --}}
                <div>
                    <label class="block text-[11px] font-bold uppercase text-white/50 mb-1.5">Status Akun</label>
                    <select name="status" class="w-full px-3 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-xs focus:border-red-400 focus:outline-none">
                        <option value="" class="bg-smeck-dark-900">Semua Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }} class="bg-smeck-dark-900">Aktif</option>
                        <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }} class="bg-smeck-dark-900">Suspended</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center justify-between pt-2">
                <div class="text-xs text-white/40">
                    Menampilkan <strong>{{ $members->total() }}</strong> suporter terdaftar
                </div>
                <div class="flex items-center gap-2">
                    @if(request()->hasAny(['search', 'chapter_id', 'blood_type', 'status']))
                        <a href="{{ route('superadmin.members') }}" class="px-3 py-1.5 rounded-xl bg-white/5 hover:bg-white/10 text-white/60 hover:text-white text-xs">
                            Reset Filter
                        </a>
                    @endif
                    <button type="submit" class="px-4 py-2 rounded-xl bg-red-600 hover:bg-red-500 text-white font-bold text-xs shadow-lg transition-colors flex items-center gap-2">
                        <i class="fa-solid fa-filter"></i>
                        <span>Terapkan Filter</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Tabel Anggota Nasional --}}
    <div class="rounded-3xl bg-smeck-dark-900 border border-white/10 overflow-hidden shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-white/5 border-b border-white/10 text-white/40 uppercase tracking-wider text-[11px]">
                        <th class="py-4 px-5">Suporter & NIA</th>
                        <th class="py-4 px-5">Basis Wilayah</th>
                        <th class="py-4 px-5">Nomor Kartu KTA</th>
                        <th class="py-4 px-5">Identitas & Kontak</th>
                        <th class="py-4 px-5">Role & Peran</th>
                        <th class="py-4 px-5 text-center">Status</th>
                        <th class="py-4 px-5 text-right">Aksi DPP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($members as $m)
                        @php
                            $card = $m->nfcCard;
                        @endphp
                        <tr class="hover:bg-white/[0.02] transition-colors">
                            {{-- Info Suporter --}}
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

                            {{-- Basis Wilayah --}}
                            <td class="py-4 px-5">
                                <div class="text-white font-semibold">
                                    {{ $m->chapter?->name ?? 'DPP Pusat' }}
                                </div>
                                <div class="text-[10px] text-white/40 mt-0.5">
                                    Gabung: {{ $m->profile?->joined_date ? $m->profile->joined_date->format('d M Y') : $m->created_at->format('d M Y') }}
                                </div>
                            </td>

                            {{-- Nomor Kartu KTA --}}
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
                                @else
                                    <span class="inline-flex items-center gap-1.5 text-white/40 italic">
                                        <i class="fa-regular fa-circle-xmark"></i> Belum Diterbitkan
                                    </span>
                                @endif
                            </td>

                            {{-- Identitas & Kontak --}}
                            <td class="py-4 px-5">
                                <div class="text-white/80">
                                    <i class="fa-solid fa-phone text-white/30 text-[10px] mr-1"></i>{{ $m->profile?->phone_number ?? '-' }}
                                </div>
                                <div class="text-[10px] text-white/40 mt-0.5">
                                    NIK: <span class="font-mono">{{ $m->profile?->nik_ktp ?? '-' }}</span>
                                </div>
                                <div class="text-[10px] text-red-400 font-bold mt-0.5">
                                    Gol. Darah: {{ $m->profile?->blood_type ?? '-' }}
                                </div>
                            </td>

                            {{-- Role --}}
                            <td class="py-4 px-5">
                                @if($m->role === 'admin_chapter')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30">
                                        <i class="fa-solid fa-shield mr-1"></i> Pengurus Chapter
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-smeck-green-500/20 text-smeck-green-400 border border-smeck-green-500/30">
                                        <i class="fa-solid fa-user mr-1"></i> Anggota Reguler
                                    </span>
                                @endif
                            </td>

                            {{-- Status Akun --}}
                            <td class="py-4 px-5 text-center">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $m->status === 'active' ? 'bg-smeck-green-500/20 text-smeck-green-400' : 'bg-red-500/20 text-red-400' }}">
                                    {{ $m->status }}
                                </span>
                            </td>

                            {{-- Aksi DPP --}}
                            <td class="py-4 px-5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    {{-- Role Toggle --}}
                                    <form action="{{ route('superadmin.members.role', $m->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="role" value="{{ $m->role === 'admin_chapter' ? 'member' : 'admin_chapter' }}">
                                        <button type="submit" title="{{ $m->role === 'admin_chapter' ? 'Jadikan Anggota Biasa' : 'Angkat Jadi Pengurus Chapter' }}" class="px-2.5 py-1 rounded-lg {{ $m->role === 'admin_chapter' ? 'bg-white/10 hover:bg-white/20 text-white/70' : 'bg-amber-500/10 hover:bg-amber-500 text-amber-400 hover:text-white' }} font-semibold text-[11px] transition-colors">
                                            {{ $m->role === 'admin_chapter' ? 'Demote' : 'Promote' }}
                                        </button>
                                    </form>

                                    {{-- Status Toggle --}}
                                    <form action="{{ route('superadmin.members.status', $m->id) }}" method="POST" onsubmit="return confirm('Ubah status suporter {{ addslashes($m->name) }}?')">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1 rounded-lg {{ $m->status === 'active' ? 'bg-red-500/10 hover:bg-red-500 text-red-400 hover:text-white' : 'bg-smeck-green-500/10 hover:bg-smeck-green-500 text-smeck-green-400 hover:text-white' }} font-semibold text-[11px] transition-colors">
                                            {{ $m->status === 'active' ? 'Suspend' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-white/40 text-xs">
                                <i class="fa-solid fa-users-slash text-2xl mb-2 text-white/20 block"></i>
                                Tidak ditemukan suporter yang sesuai dengan filter pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($members->hasPages())
            <div class="p-4 border-t border-white/5">
                {{ $members->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
