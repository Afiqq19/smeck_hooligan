@extends('layouts.app')

@section('title', 'Kas & Donasi Koreo')
@section('header_title', 'Laporan Kas & Donasi Koreo Nasional')
@section('header_subtitle', 'Rekapitulasi keuangan sukarela anggota suporter, transparansi kas chapter, dan alokasi dana koreo laga akbar')

@section('content')
<div class="space-y-8 max-w-7xl mx-auto">

    {{-- Kartu Metrik Finansial Nasional --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="p-6 rounded-3xl bg-smeck-dark-900 border border-smeck-green-500/20 shadow-xl">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-white/50 uppercase">Total Kas Terverifikasi</span>
                <div class="w-8 h-8 rounded-lg bg-smeck-green-500/20 text-smeck-green-400 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-vault"></i>
                </div>
            </div>
            <div class="font-display font-black text-2xl sm:text-3xl text-smeck-green-400 font-mono mt-3">
                Rp {{ number_format($totalVerifiedFunds, 0, ',', '.') }}
            </div>
            <div class="text-[11px] text-white/40 mt-1">Saldo aktif siap alokasi koreo & operasional</div>
        </div>

        <div class="p-6 rounded-3xl bg-smeck-dark-900 border border-amber-500/20 shadow-xl">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-white/50 uppercase">Menunggu Verifikasi</span>
                <div class="w-8 h-8 rounded-lg bg-amber-500/20 text-amber-400 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
            </div>
            <div class="font-display font-black text-2xl sm:text-3xl text-amber-400 font-mono mt-3">
                Rp {{ number_format($totalPendingFunds, 0, ',', '.') }}
            </div>
            <div class="text-[11px] text-white/40 mt-1">Bukti transfer iuran diproses pengurus basis</div>
        </div>

        <div class="p-6 rounded-3xl bg-smeck-dark-900 border border-white/10 shadow-xl">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-white/50 uppercase">Total Transaksi Masuk</span>
                <div class="w-8 h-8 rounded-lg bg-blue-500/20 text-blue-400 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-receipt"></i>
                </div>
            </div>
            <div class="font-display font-black text-2xl sm:text-3xl text-white font-mono mt-3">
                {{ $totalTransactions }}
            </div>
            <div class="text-[11px] text-white/40 mt-1">Kali kontribusi sukarela suporter</div>
        </div>
    </div>

    {{-- Kontribusi Kas Berdasarkan Chapter --}}
    <div class="p-6 sm:p-8 rounded-3xl bg-smeck-dark-900 border border-white/10 shadow-xl space-y-4">
        <div class="flex items-center justify-between mb-2">
            <div>
                <h3 class="font-display font-bold text-lg text-white">Akumulasi Kas per Basis Wilayah</h3>
                <p class="text-xs text-white/40">Peringkat kontribusi iuran kas sukarela terverifikasi per chapter</p>
            </div>
            <span class="text-xs text-smeck-gold font-bold">Transparansi SMeCK Hooligan</span>
        </div>

        <div class="space-y-4">
            @forelse($chapterFunds as $cf)
                @php
                    $percentage = $totalVerifiedFunds > 0 ? min(100, max(5, ($cf->verified_sum / $totalVerifiedFunds) * 100)) : 0;
                @endphp
                <div>
                    <div class="flex items-center justify-between text-xs mb-1.5">
                        <span class="font-semibold text-white flex items-center gap-1.5">
                            <i class="fa-solid fa-building-columns text-smeck-green-400 text-[10px]"></i>
                            {{ $cf->name }}
                        </span>
                        <div class="flex items-center gap-3">
                            <span class="text-white/40 font-mono text-[11px]">{{ $cf->dues_count }} transaksi</span>
                            <span class="text-smeck-gold font-mono font-bold">Rp {{ number_format($cf->verified_sum ?? 0, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <div class="w-full h-2.5 rounded-full bg-white/10 overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-smeck-green-600 via-emerald-400 to-smeck-gold rounded-full transition-all" style="width: {{ $percentage }}%"></div>
                    </div>
                </div>
            @empty
                <div class="text-center py-6 text-white/40 text-xs">Belum ada data kas chapter terverifikasi.</div>
            @endforelse
        </div>
    </div>

    {{-- Tabel Riwayat Iuran Terbaru --}}
    <div class="rounded-3xl bg-smeck-dark-900 border border-white/10 overflow-hidden shadow-2xl">
        <div class="p-6 border-b border-white/10 flex items-center justify-between">
            <div>
                <h3 class="font-display font-bold text-lg text-white">Log Transaksi Iuran Sukarela Nasional</h3>
                <p class="text-xs text-white/40">Seluruh riwayat iuran anggota yang tercatat di sistem</p>
            </div>
            <span class="text-xs text-white/50 font-mono">{{ $recentDues->total() }} Catatan</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-white/5 border-b border-white/10 text-white/40 uppercase tracking-wider text-[11px]">
                        <th class="py-4 px-5">Suporter</th>
                        <th class="py-4 px-5">Basis Wilayah</th>
                        <th class="py-4 px-5">Jumlah Donasi</th>
                        <th class="py-4 px-5">Catatan / Peruntukan</th>
                        <th class="py-4 px-5 text-center">Status</th>
                        <th class="py-4 px-5 text-right">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($recentDues as $due)
                        <tr class="hover:bg-white/[0.02] transition-colors">
                            <td class="py-4 px-5">
                                <div class="font-bold text-white">{{ $due->user?->name ?? 'Suporter' }}</div>
                                <div class="font-mono text-[10px] text-smeck-gold">{{ $due->user?->profile?->nia ?? '-' }}</div>
                            </td>
                            <td class="py-4 px-5 text-white/80">
                                {{ $due->chapter?->name ?? 'Chapter Pusat' }}
                            </td>
                            <td class="py-4 px-5 font-mono font-bold text-smeck-green-400 text-sm">
                                Rp {{ number_format($due->amount, 0, ',', '.') }}
                            </td>
                            <td class="py-4 px-5 text-white/60 max-w-xs truncate">
                                {{ $due->notes ?: 'Kas Sukarela Anggota' }}
                            </td>
                            <td class="py-4 px-5 text-center">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $due->status === 'verified' ? 'bg-smeck-green-500/20 text-smeck-green-400' : 'bg-amber-500/20 text-amber-400' }}">
                                    {{ $due->status }}
                                </span>
                            </td>
                            <td class="py-4 px-5 text-right font-mono text-[10px] text-white/40">
                                {{ $due->created_at->format('d M Y H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-white/40 text-xs">
                                Belum ada riwayat transaksi iuran kas sukarela.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($recentDues->hasPages())
            <div class="p-4 border-t border-white/5">
                {{ $recentDues->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
