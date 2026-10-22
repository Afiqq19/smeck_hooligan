@extends('layouts.app')

@section('title', 'Iuran & Kas Sukarela')
@section('header_title', 'Iuran & Kas Suka Rela SMeCK')
@section('header_subtitle', 'Mendukung operasional kegiatan suporter, chant class, koreografi stadion, dan aksi sosial')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">

    {{-- Form + Summary Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        {{-- Form Upload Iuran --}}
        <div class="lg:col-span-7 p-6 sm:p-8 rounded-3xl bg-smeck-dark-900 border border-white/10 shadow-2xl">
            <h3 class="font-display font-black text-xl text-white mb-1">Formulir Kirim Iuran Kas Sukarela</h3>
            <p class="text-xs text-white/50 mb-6">Setiap rupiah yang disalurkan dicatat secara transparan untuk kebutuhan komunitas & basis.</p>

            <form action="{{ route('member.dues.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf

                {{-- Nominal Iuran & Quick Chips --}}
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-2">Nominal Iuran (Rp) <span class="text-red-400">*</span></label>
                    <input type="number" id="input_due_amount" name="amount" required min="10000" step="5000" placeholder="Contoh: 50000" class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white font-mono text-base font-bold text-smeck-gold focus:border-smeck-green-400 focus:outline-none">
                    
                    {{-- Quick Amount Chips --}}
                    <div class="flex flex-wrap items-center gap-2 mt-2">
                        <button type="button" onclick="setDueAmount(10000)" class="px-2.5 py-1 rounded-lg bg-white/5 hover:bg-white/10 text-white/70 hover:text-white text-xs font-mono font-semibold transition-colors">Rp 10.000</button>
                        <button type="button" onclick="setDueAmount(25000)" class="px-2.5 py-1 rounded-lg bg-white/5 hover:bg-white/10 text-white/70 hover:text-white text-xs font-mono font-semibold transition-colors">Rp 25.000</button>
                        <button type="button" onclick="setDueAmount(50000)" class="px-2.5 py-1 rounded-lg bg-white/5 hover:bg-white/10 text-white/70 hover:text-white text-xs font-mono font-semibold transition-colors">Rp 50.000</button>
                        <button type="button" onclick="setDueAmount(100000)" class="px-2.5 py-1 rounded-lg bg-white/5 hover:bg-white/10 text-white/70 hover:text-white text-xs font-mono font-semibold transition-colors">Rp 100.000</button>
                        <button type="button" onclick="setDueAmount(200000)" class="px-2.5 py-1 rounded-lg bg-white/5 hover:bg-white/10 text-white/70 hover:text-white text-xs font-mono font-semibold transition-colors">Rp 200.000</button>
                    </div>
                </div>

                {{-- Catatan / Keterangan --}}
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-2">Catatan / Peruntukan Donasi</label>
                    <input type="text" name="notes" placeholder="Contoh: Donasi Koreo Spanduk / Iuran Bulanan Basis" class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-smeck-green-400 focus:outline-none">
                </div>

                {{-- Upload Bukti Transfer Real --}}
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-2">Foto / Screenshot Bukti Transfer (Opsional)</label>
                    <input type="file" name="payment_proof" accept="image/*" class="w-full text-xs text-white/70 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-smeck-green-600 file:text-white hover:file:bg-smeck-green-500 cursor-pointer bg-white/5 border border-white/10 rounded-xl p-1.5">
                    <span class="text-[10px] text-white/40 mt-1 block">Format: JPG, PNG, WEBP (Maksimal 5MB)</span>
                </div>

                <button type="submit" class="w-full py-3.5 rounded-xl bg-smeck-green-600 hover:bg-smeck-green-500 text-white font-bold text-sm shadow-lg shadow-smeck-green-700/30 transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-paper-plane text-xs"></i>
                    <span>Kirim Laporan Iuran Kas</span>
                </button>
            </form>
        </div>

        {{-- Info Box Rekening Resmi & Quick Copy --}}
        <div class="lg:col-span-5 space-y-4">
            {{-- Kartu Rekening --}}
            <div class="p-6 rounded-3xl bg-gradient-to-br from-emerald-950/80 to-smeck-dark-900 border border-smeck-green-500/30 shadow-xl space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-smeck-gold/20 text-smeck-gold flex items-center justify-center text-xl shrink-0">
                        <i class="fa-solid fa-building-columns"></i>
                    </div>
                    <div>
                        <h4 class="font-display font-bold text-base text-white">Rekening Resmi Kas</h4>
                        <p class="text-xs text-white/50">Klik tombol salin untuk menyalin nomor rekening.</p>
                    </div>
                </div>

                <div class="space-y-3">
                    {{-- Rekening Mandiri --}}
                    <div class="p-3.5 rounded-2xl bg-white/[0.04] border border-white/10 flex items-center justify-between">
                        <div>
                            <div class="text-[10px] text-white/50 uppercase font-semibold">Bank Mandiri (Kas Pusat):</div>
                            <div class="font-mono font-bold text-white text-sm mt-0.5">106-00-1950-2003</div>
                            <div class="text-[10px] text-white/40">a.n SMeCK Hooligan Medan</div>
                        </div>
                        <button type="button" onclick="copyRekening('1060019502003', this)" class="px-3 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold transition-all flex items-center gap-1.5 shrink-0">
                            <i class="fa-regular fa-copy text-amber-400"></i>
                            <span>Salin</span>
                        </button>
                    </div>

                    {{-- Rekening BCA --}}
                    <div class="p-3.5 rounded-2xl bg-white/[0.04] border border-white/10 flex items-center justify-between">
                        <div>
                            <div class="text-[10px] text-white/50 uppercase font-semibold">Bank BCA:</div>
                            <div class="font-mono font-bold text-white text-sm mt-0.5">828-550-1950</div>
                            <div class="text-[10px] text-white/40">a.n Kas Suporter PSMS</div>
                        </div>
                        <button type="button" onclick="copyRekening('8285501950', this)" class="px-3 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold transition-all flex items-center gap-1.5 shrink-0">
                            <i class="fa-regular fa-copy text-amber-400"></i>
                            <span>Salin</span>
                        </button>
                    </div>
                </div>

                <div class="pt-3 border-t border-white/10 flex items-center justify-between">
                    <span class="text-xs text-white/50">Total Iuran Terverifikasi Anda:</span>
                    <span class="text-lg font-mono font-bold text-smeck-gold">
                        Rp {{ number_format($dues->where('status', 'verified')->sum('amount'), 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

    </div>

    {{-- History Table --}}
    <div class="p-6 sm:p-8 rounded-3xl bg-smeck-dark-900 border border-white/10 shadow-xl">
        <h3 class="font-display font-bold text-lg text-white mb-4 flex items-center gap-2">
            <i class="fa-solid fa-clock-rotate-left text-amber-400"></i>
            <span>Riwayat Transaksi Iuran & Donasi Kas Saya</span>
        </h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-white/10 text-white/40 uppercase tracking-wider text-[11px]">
                        <th class="py-3.5 px-4">Tanggal & Waktu</th>
                        <th class="py-3.5 px-4">Nominal</th>
                        <th class="py-3.5 px-4">Keterangan / Peruntukan</th>
                        <th class="py-3.5 px-4 text-center">Bukti Transfer</th>
                        <th class="py-3.5 px-4 text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($dues as $d)
                        @php
                            $proofPath = $d->payment_proof_path ?? $d->proof_file;
                        @endphp
                        <tr class="hover:bg-white/[0.02] transition-colors">
                            <td class="py-3.5 px-4 text-white/60 font-mono">{{ $d->created_at->format('d/m/Y H:i') }}</td>
                            <td class="py-3.5 px-4 font-mono font-bold text-smeck-gold text-sm">Rp {{ number_format($d->amount, 0, ',', '.') }}</td>
                            <td class="py-3.5 px-4 text-white/80 max-w-xs truncate">{{ $d->notes ?? 'Iuran sukarela' }}</td>
                            <td class="py-3.5 px-4 text-center">
                                @if($proofPath)
                                    <a href="{{ asset('storage/' . $proofPath) }}" target="_blank" class="px-2.5 py-1 rounded-lg bg-white/5 hover:bg-white/15 text-white/80 hover:text-white text-[11px] font-semibold border border-white/10 transition-colors inline-flex items-center gap-1">
                                        <i class="fa-solid fa-image text-amber-400"></i>
                                        <span>Lihat Bukti</span>
                                    </a>
                                @else
                                    <span class="text-white/30 text-[10px] italic">Tanpa Foto</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $d->status === 'verified' ? 'bg-smeck-green-500/20 text-smeck-green-400 border border-smeck-green-500/30' : 'bg-amber-500/20 text-amber-300 border border-amber-500/30' }}">
                                    {{ $d->status === 'verified' ? 'TERVERIFIKASI' : 'MENUNGGU VERIFIKASI' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-white/40">
                                <i class="fa-solid fa-receipt text-3xl mb-2 block text-white/20"></i>
                                Belum ada riwayat transaksi iuran kas yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@push('scripts')
<script>
    function setDueAmount(amount) {
        document.getElementById('input_due_amount').value = amount;
    }

    function copyRekening(rekNumber, btn) {
        navigator.clipboard.writeText(rekNumber).then(() => {
            const originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-check text-smeck-green-400"></i> <span class="text-smeck-green-400">Tersalin!</span>';
            setTimeout(() => {
                btn.innerHTML = originalHtml;
            }, 2000);
        });
    }
</script>
@endpush
@endsection

