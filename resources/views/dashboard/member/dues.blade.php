@extends('layouts.app')

@section('title', 'Iuran & Kas Sukarela')
@section('header_title', 'Iuran & Kas Suka Rela SMeCK')
@section('header_subtitle', 'Mendukung operasional kegiatan suporter, chant class, koreografi stadion, dan aksi sosial')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">

    {{-- Form + Summary Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        {{-- Form Upload Iuran --}}
        <div class="lg:col-span-7 p-6 sm:p-8 rounded-3xl bg-smeck-dark-900 border border-white/10">
            <h3 class="font-display font-bold text-lg text-white mb-2">Formulir Kirim Iuran Kas Sukarela</h3>
            <p class="text-xs text-white/50 mb-6">Setiap rupiah yang disalurkan dicatat secara transparan untuk kebutuhan komunitas.</p>

            <form action="{{ route('member.dues.store') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-2">Nominal Iuran (Rp) <span class="text-red-400">*</span></label>
                    <input type="number" name="amount" required min="10000" step="5000" placeholder="Contoh: 50000" class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white font-mono text-sm focus:border-smeck-green-400 focus:outline-none">
                    <span class="text-[11px] text-white/40 mt-1 block">Minimal nominal Rp 10.000</span>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-2">Catatan / Peruntukan</label>
                    <input type="text" name="notes" placeholder="Contoh: Donasi Koreo Giant Flag / Kas Bulanan" class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-smeck-green-400 focus:outline-none">
                </div>

                <div class="p-4 rounded-2xl bg-white/[0.02] border border-white/10 space-y-2">
                    <div class="text-xs font-bold text-smeck-gold uppercase tracking-wider">Rekening Resmi Kas Pusat / Chapter:</div>
                    <div class="text-sm font-mono text-white">Bank Mandiri: <strong>106-00-1950-2003</strong> (a.n SMeCK Hooligan Medan)</div>
                    <div class="text-sm font-mono text-white">BCA: <strong>828-550-1950</strong> (a.n Kas Suporter PSMS)</div>
                </div>

                <button type="submit" class="w-full py-3.5 rounded-xl bg-smeck-green-600 hover:bg-smeck-green-500 text-white font-bold text-sm shadow-lg shadow-smeck-green-700/30 transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-paper-plane text-xs"></i>
                    <span>Kirim Laporan Iuran Kas</span>
                </button>
            </form>
        </div>

        {{-- Info Box Rekening --}}
        <div class="lg:col-span-5 space-y-4">
            <div class="p-6 rounded-3xl bg-gradient-to-br from-smeck-green-950/80 to-smeck-dark-900 border border-smeck-green-500/30">
                <div class="w-12 h-12 rounded-2xl bg-smeck-gold/20 text-smeck-gold flex items-center justify-center text-xl mb-4">
                    <i class="fa-solid fa-hand-holding-dollar"></i>
                </div>
                <h4 class="font-display font-bold text-base text-white">Transparansi Dana Suporter</h4>
                <p class="text-xs text-white/60 mt-1 leading-relaxed">
                    Dana kas sukarela digunakan untuk pembuatan koreo tribun, subsidi awaydays anggota, serta santunan sosial dan tali asih suporter.
                </p>
                <div class="mt-4 pt-4 border-t border-white/10 flex items-center justify-between">
                    <span class="text-xs text-white/40">Total Iuran Anda:</span>
                    <span class="text-lg font-mono font-bold text-smeck-gold">
                        Rp {{ number_format($dues->where('status', 'verified')->sum('amount'), 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

    </div>

    {{-- History Table --}}
    <div class="p-6 sm:p-8 rounded-3xl bg-smeck-dark-900 border border-white/10">
        <h3 class="font-display font-bold text-lg text-white mb-4">Riwayat Setoran Iuran & Donasi</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-white/10 text-white/40 uppercase">
                        <th class="py-3 px-4">Tanggal</th>
                        <th class="py-3 px-4">Nominal</th>
                        <th class="py-3 px-4">Keterangan</th>
                        <th class="py-3 px-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($dues as $d)
                        <tr class="hover:bg-white/[0.02]">
                            <td class="py-3.5 px-4 text-white/60">{{ $d->created_at->format('d/m/Y H:i') }}</td>
                            <td class="py-3.5 px-4 font-mono font-bold text-white">Rp {{ number_format($d->amount, 0, ',', '.') }}</td>
                            <td class="py-3.5 px-4 text-white/80">{{ $d->notes ?? '-' }}</td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $d->status === 'verified' ? 'bg-smeck-green-500/20 text-smeck-green-400' : 'bg-amber-500/20 text-amber-300' }}">
                                    {{ $d->status === 'verified' ? 'Terverifikasi' : 'Menunggu' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-6 text-center text-white/40">Belum ada riwayat transaksi iuran kas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
