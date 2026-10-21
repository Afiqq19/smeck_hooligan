@extends('layouts.app')

@section('title', 'Verifikasi Anggota Baru')
@section('header_title', 'Verifikasi Pendaftaran Anggota Baru')
@section('header_subtitle', 'Validasi data KTP, kesesuaian wilayah, dan persetujuan penerbitan KTA fisik')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    <div class="p-6 sm:p-8 rounded-3xl bg-smeck-dark-900 border border-white/10">
        <h3 class="font-display font-bold text-lg text-white mb-4">Daftar Pendaftar Anggota Chapter</h3>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-white/10 text-white/40 uppercase">
                        <th class="py-3 px-4">Nama Lengkap</th>
                        <th class="py-3 px-4">NIK KTP</th>
                        <th class="py-3 px-4">Kontak / WA</th>
                        <th class="py-3 px-4">Gol. Darah</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Aksi Verifikasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($pendingUsers as $u)
                        <tr class="hover:bg-white/[0.02]">
                            <td class="py-3.5 px-4 font-bold text-white">
                                {{ $u->name }}
                                <span class="block text-[10px] text-white/40 font-normal">{{ $u->email }}</span>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-white/80">{{ $u->profile?->nik_ktp ?? '-' }}</td>
                            <td class="py-3.5 px-4 text-white/70">{{ $u->profile?->phone_number ?? '-' }}</td>
                            <td class="py-3.5 px-4 font-bold text-smeck-gold">{{ $u->profile?->blood_type ?? '-' }}</td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $u->status === 'active' ? 'bg-smeck-green-500/20 text-smeck-green-400' : 'bg-amber-500/20 text-amber-300' }}">
                                    {{ $u->status }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                @if($u->status !== 'active')
                                    <form action="{{ route('chapter.verifications.approve', $u->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 rounded-lg bg-smeck-green-600 hover:bg-smeck-green-500 text-white font-bold text-[11px] transition-colors">
                                            <i class="fa-solid fa-check mr-1"></i> Approve
                                        </button>
                                    </form>
                                @else
                                    <span class="text-white/40 text-[11px]">Telah Aktif</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-white/40">Tidak ada anggota yang terdaftar di basis ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
