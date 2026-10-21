@extends('layouts.app')

@section('title', 'Kas Chapter')
@section('header_title', 'Rekapitulasi Kas & Iuran Chapter')
@section('header_subtitle', 'Laporan dan verifikasi uang masuk dari iuran sukarela anggota basis')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div class="p-6 rounded-3xl bg-smeck-dark-900 border border-white/10">
            <div class="text-xs text-white/40 uppercase font-semibold">Total Kas Terverifikasi</div>
            <div class="font-display font-black text-3xl text-smeck-gold font-mono mt-1">Rp {{ number_format($totalAmount, 0, ',', '.') }}</div>
            <div class="text-[11px] text-white/50 mt-1">Siap dialokasikan untuk operasional chapter</div>
        </div>
        <div class="p-6 rounded-3xl bg-smeck-dark-900 border border-white/10">
            <div class="text-xs text-white/40 uppercase font-semibold">Transaksi Iuran Masuk</div>
            <div class="font-display font-black text-3xl text-white mt-1">{{ $dues->count() }} Kali</div>
            <div class="text-[11px] text-white/50 mt-1">Dari donatur & anggota basis</div>
        </div>
    </div>

    <div class="p-6 sm:p-8 rounded-3xl bg-smeck-dark-900 border border-white/10">
        <h3 class="font-display font-bold text-lg text-white mb-4">Tabel Rekapitulasi Iuran Anggota</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-white/10 text-white/40 uppercase">
                        <th class="py-3 px-4">Tanggal</th>
                        <th class="py-3 px-4">Nama Anggota</th>
                        <th class="py-3 px-4">Nominal</th>
                        <th class="py-3 px-4">Catatan</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($dues as $d)
                        <tr class="hover:bg-white/[0.02]">
                            <td class="py-3.5 px-4 text-white/60">{{ $d->created_at->format('d/m/Y') }}</td>
                            <td class="py-3.5 px-4 font-bold text-white">{{ $d->user->name }}</td>
                            <td class="py-3.5 px-4 font-mono font-bold text-smeck-gold">Rp {{ number_format($d->amount, 0, ',', '.') }}</td>
                            <td class="py-3.5 px-4 text-white/70">{{ $d->notes ?? '-' }}</td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $d->status === 'verified' ? 'bg-smeck-green-500/20 text-smeck-green-400' : 'bg-amber-500/20 text-amber-300' }}">
                                    {{ $d->status === 'verified' ? 'Terverifikasi' : 'Pending' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                @if($d->status !== 'verified')
                                    <form action="{{ route('chapter.dues.verify', $d->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 rounded-lg bg-smeck-green-600 hover:bg-smeck-green-500 text-white font-bold text-[11px]">
                                            Verifikasi
                                        </button>
                                    </form>
                                @else
                                    <span class="text-white/30 text-[11px]"><i class="fa-solid fa-check text-smeck-green-400"></i> Sah</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-white/40">Belum ada catatan kas masuk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
