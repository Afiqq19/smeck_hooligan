@extends('layouts.app')

@section('title', 'Kas & Iuran Basis')
@section('header_title', 'Kas & Iuran Basis ' . ($chapter->name ?? ''))
@section('header_subtitle', 'Pencatatan iuran sukarela tunai basecamp, rekapitulasi dana kas, dan verifikasi transfer anggota')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    
    {{-- Header Action --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="font-display font-black text-xl sm:text-2xl text-white">Transparansi Keuangan Basis</h2>
            <p class="text-xs text-white/50">Kelola uang kas masuk dari anggota untuk kebutuhan operasional, spanduk, banner, & kegiatan basis.</p>
        </div>
        <div>
            <button onclick="openCashDueModal()" class="px-5 py-2.5 rounded-xl bg-smeck-green-600 hover:bg-smeck-green-500 text-white font-bold text-xs shadow-lg shadow-smeck-green-700/30 transition-all flex items-center gap-2">
                <i class="fa-solid fa-hand-holding-dollar text-sm"></i>
                <span>+ Catat Iuran Tunai (Basecamp)</span>
            </button>
        </div>
    </div>

    {{-- 3 Kartu Statistik Ringkas --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="p-6 rounded-3xl bg-smeck-dark-900 border border-white/10 relative overflow-hidden">
            <div class="text-xs text-white/40 uppercase font-semibold flex items-center gap-1.5">
                <i class="fa-solid fa-vault text-smeck-gold"></i>
                <span>Total Kas Terverifikasi</span>
            </div>
            <div class="font-display font-black text-3xl text-smeck-gold font-mono mt-2">Rp {{ number_format($totalAmount, 0, ',', '.') }}</div>
            <div class="text-[11px] text-white/50 mt-1 flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-smeck-green-400"></span>
                <span>Saldo kas aktif siap dialokasikan</span>
            </div>
        </div>

        <div class="p-6 rounded-3xl bg-smeck-dark-900 border border-white/10 relative overflow-hidden">
            <div class="text-xs text-white/40 uppercase font-semibold flex items-center gap-1.5">
                <i class="fa-solid fa-hourglass-half text-amber-400"></i>
                <span>Pending Verifikasi</span>
            </div>
            <div class="font-display font-black text-3xl text-amber-400 font-mono mt-2">Rp {{ number_format($pendingAmount, 0, ',', '.') }}</div>
            <div class="text-[11px] text-white/50 mt-1">Perlu dicek bukti transfernya</div>
        </div>

        <div class="p-6 rounded-3xl bg-smeck-dark-900 border border-white/10 relative overflow-hidden">
            <div class="text-xs text-white/40 uppercase font-semibold flex items-center gap-1.5">
                <i class="fa-solid fa-receipt text-smeck-green-400"></i>
                <span>Total Transaksi Iuran</span>
            </div>
            <div class="font-display font-black text-3xl text-white mt-2">{{ $dues->total() }} Transaksi</div>
            <div class="text-[11px] text-white/50 mt-1">Dari seluruh anggota basis</div>
        </div>
    </div>

    {{-- Tabel Rekapitulasi Iuran --}}
    <div class="p-6 sm:p-8 rounded-3xl bg-smeck-dark-900 border border-white/10 shadow-xl">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-display font-bold text-lg text-white flex items-center gap-2">
                <i class="fa-solid fa-list-check text-smeck-gold"></i>
                <span>Riwayat Transaksi Iuran Anggota</span>
            </h3>
            <span class="text-xs text-white/40">Basis {{ $chapter->name ?? '' }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-white/10 text-white/40 uppercase tracking-wider text-[11px]">
                        <th class="py-3.5 px-4">Tanggal & Waktu</th>
                        <th class="py-3.5 px-4">Anggota & NIA</th>
                        <th class="py-3.5 px-4">Nominal</th>
                        <th class="py-3.5 px-4">Catatan / Keterangan</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($dues as $d)
                        <tr class="hover:bg-white/[0.02] transition-colors">
                            <td class="py-3.5 px-4 text-white/60 font-mono text-[11px]">
                                {{ $d->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-white">{{ $d->user->name }}</div>
                                <div class="text-[10px] font-mono text-smeck-gold">{{ $d->user->profile?->nia ?? 'Belum ada NIA' }}</div>
                            </td>
                            <td class="py-3.5 px-4 font-mono font-bold text-sm text-smeck-gold">
                                Rp {{ number_format($d->amount, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4 text-white/70 max-w-xs truncate">
                                {{ $d->notes ?? 'Iuran sukarela basis' }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($d->status === 'verified')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-smeck-green-500/20 text-smeck-green-400 border border-smeck-green-500/30">
                                        <i class="fa-solid fa-check text-[9px] mr-0.5"></i> Sah
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-amber-500/20 text-amber-300 border border-amber-500/30">
                                        <i class="fa-solid fa-clock text-[9px] mr-0.5"></i> Pending
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    {{-- Tombol Bukti Transfer jika ada --}}
                                    @php
                                        $proof = $d->payment_proof_path ?? $d->proof_file;
                                    @endphp
                                    @if($proof)
                                        <button type="button" onclick="openProofModal('{{ asset('storage/' . $proof) }}', '{{ $d->user->name }}', '{{ number_format($d->amount, 0, ',', '.') }}', {{ $d->id }}, '{{ $d->status }}')" class="px-2.5 py-1 rounded-lg bg-white/5 hover:bg-white/15 text-white/80 hover:text-white text-[11px] font-semibold border border-white/10 transition-colors flex items-center gap-1">
                                            <i class="fa-solid fa-image text-amber-400"></i>
                                            <span class="hidden sm:inline">Bukti</span>
                                        </button>
                                    @endif

                                    @if($d->status !== 'verified')
                                        <form action="{{ route('chapter.dues.verify', $d->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Verifikasi iuran sebesar Rp {{ number_format($d->amount, 0, ',', '.') }} dari {{ $d->user->name }}?')">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 rounded-lg bg-smeck-green-600 hover:bg-smeck-green-500 text-white font-bold text-[11px] shadow transition-colors flex items-center gap-1">
                                                <i class="fa-solid fa-check"></i>
                                                <span>Verifikasi</span>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-white/30 text-[11px] italic">Tuntas</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-white/40">
                                <i class="fa-solid fa-receipt text-3xl mb-2 block text-white/20"></i>
                                Belum ada catatan iuran kas masuk di basis ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($dues->hasPages())
            <div class="mt-4 pt-4 border-t border-white/5">
                {{ $dues->links() }}
            </div>
        @endif
    </div>

</div>

{{-- ========================================================================= --}}
{{-- MODAL 1: CATAT IURAN TUNAI DI BASECAMP / KOPDAR --}}
{{-- ========================================================================= --}}
<div id="modalCashDue" class="fixed inset-0 z-50 bg-black/85 backdrop-blur-md hidden flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-smeck-dark-900 border border-white/10 rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl relative my-8">
        <button onclick="closeCashDueModal()" class="absolute top-6 right-6 text-white/40 hover:text-white text-lg">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <div class="mb-5">
            <div class="w-12 h-12 rounded-2xl bg-smeck-green-500/10 text-smeck-green-400 flex items-center justify-center text-2xl mb-3">
                <i class="fa-solid fa-hand-holding-dollar"></i>
            </div>
            <h3 class="font-display font-black text-xl text-white">Catat Iuran Tunai Basecamp</h3>
            <p class="text-xs text-white/50 mt-1">Uang tunai (cash) yang diserahkan anggota langsung di posko, kopdar, atau stadion langsung sah berstatus terverifikasi.</p>
        </div>

        <form action="{{ route('chapter.dues.cash') }}" method="POST" class="space-y-4">
            @csrf

            {{-- Pilih Anggota --}}
            <div>
                <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Nama Anggota Pembayar <span class="text-red-400">*</span></label>
                <select name="user_id" required class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-smeck-green-400 focus:outline-none">
                    <option value="" disabled selected class="bg-smeck-dark-900">-- Pilih Anggota Basis --</option>
                    @foreach($chapterMembers as $cm)
                        <option value="{{ $cm->id }}" class="bg-smeck-dark-900">{{ $cm->name }} (NIA: {{ $cm->profile?->nia ?? '-' }})</option>
                    @endforeach
                </select>
            </div>

            {{-- Nominal & Quick Chips --}}
            <div>
                <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Nominal Iuran (Rp) <span class="text-red-400">*</span></label>
                <input type="number" id="cash_amount_input" name="amount" min="5000" step="5000" required placeholder="Contoh: 20000" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white font-mono text-base font-bold text-smeck-gold focus:border-smeck-green-400 focus:outline-none">
                
                {{-- Quick Buttons --}}
                <div class="flex items-center gap-2 mt-2">
                    <button type="button" onclick="document.getElementById('cash_amount_input').value=10000" class="px-2.5 py-1 rounded-lg bg-white/5 hover:bg-white/10 text-white/70 hover:text-white text-xs font-mono font-semibold transition-colors">Rp 10.000</button>
                    <button type="button" onclick="document.getElementById('cash_amount_input').value=20000" class="px-2.5 py-1 rounded-lg bg-white/5 hover:bg-white/10 text-white/70 hover:text-white text-xs font-mono font-semibold transition-colors">Rp 20.000</button>
                    <button type="button" onclick="document.getElementById('cash_amount_input').value=50000" class="px-2.5 py-1 rounded-lg bg-white/5 hover:bg-white/10 text-white/70 hover:text-white text-xs font-mono font-semibold transition-colors">Rp 50.000</button>
                    <button type="button" onclick="document.getElementById('cash_amount_input').value=100000" class="px-2.5 py-1 rounded-lg bg-white/5 hover:bg-white/10 text-white/70 hover:text-white text-xs font-mono font-semibold transition-colors">Rp 100.000</button>
                </div>
            </div>

            {{-- Catatan / Keterangan --}}
            <div>
                <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Catatan / Keperluan</label>
                <input type="text" name="notes" placeholder="Contoh: Iuran Bulanan Basis, Donasi Spanduk Nobar, dll." class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-smeck-green-400 focus:outline-none">
            </div>

            <div class="pt-4 flex items-center justify-end gap-3">
                <button type="button" onclick="closeCashDueModal()" class="px-4 py-2 rounded-xl bg-white/5 hover:bg-white/10 text-white text-xs font-semibold transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-smeck-green-600 hover:bg-smeck-green-500 text-white font-bold text-xs shadow-lg shadow-smeck-green-700/30 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-check"></i>
                    <span>Simpan Kas Tunai</span>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ========================================================================= --}}
{{-- MODAL 2: PREVIEW BUKTI TRANSFER --}}
{{-- ========================================================================= --}}
<div id="modalProofPreview" class="fixed inset-0 z-50 bg-black/90 backdrop-blur-md hidden flex items-center justify-center p-4">
    <div class="bg-smeck-dark-900 border border-white/10 rounded-3xl max-w-md w-full p-6 shadow-2xl relative">
        <button onclick="closeProofModal()" class="absolute top-5 right-5 text-white/40 hover:text-white text-lg">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <h3 class="font-display font-bold text-base text-white mb-1">Bukti Transfer Iuran</h3>
        <p class="text-xs text-white/50 mb-4">Pengirim: <strong id="proofMemberName" class="text-white"></strong> • <span id="proofAmount" class="font-mono text-smeck-gold font-bold"></span></p>

        <div class="rounded-2xl overflow-hidden border border-white/10 bg-black flex items-center justify-center max-h-80">
            <img id="proofImageElement" src="" alt="Bukti Transfer" class="max-h-80 w-auto object-contain">
        </div>

        <div class="mt-5 flex items-center justify-between">
            <button type="button" onclick="closeProofModal()" class="px-4 py-2 rounded-xl bg-white/5 hover:bg-white/10 text-white text-xs font-semibold">
                Tutup
            </button>
            <div id="proofActionContainer"></div>
        </div>
    </div>
</div>

<script>
    function openCashDueModal() {
        document.getElementById('modalCashDue').classList.remove('hidden');
    }

    function closeCashDueModal() {
        document.getElementById('modalCashDue').classList.add('hidden');
    }

    function openProofModal(imgUrl, memberName, amount, dueId, status) {
        document.getElementById('proofMemberName').innerText = memberName;
        document.getElementById('proofAmount').innerText = 'Rp ' + amount;
        document.getElementById('proofImageElement').src = imgUrl;

        const container = document.getElementById('proofActionContainer');
        if (status !== 'verified') {
            container.innerHTML = `
                <form action="/chapter/dues/${dueId}/verify" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <button type="submit" class="px-4 py-2 rounded-xl bg-smeck-green-600 hover:bg-smeck-green-500 text-white font-bold text-xs shadow flex items-center gap-1.5">
                        <i class="fa-solid fa-check"></i>
                        <span>Verifikasi Sah Sekarang</span>
                    </button>
                </form>
            `;
        } else {
            container.innerHTML = `<span class="text-smeck-green-400 text-xs font-bold"><i class="fa-solid fa-circle-check"></i> Sudah Terverifikasi</span>`;
        }

        document.getElementById('modalProofPreview').classList.remove('hidden');
    }

    function closeProofModal() {
        document.getElementById('modalProofPreview').classList.add('hidden');
    }
</script>
@endsection

