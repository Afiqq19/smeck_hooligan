@extends('layouts.app')

@section('title', 'Alokasi Tiket & Tur Awaydays')
@section('header_title', 'Alokasi Tiket & Tur Awaydays Basis')
@section('header_subtitle', 'Monitoring kuota tiket dari Pengurus Pusat, rekap pesanan tiket, & manifest penumpang tur bus awaydays')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    
    {{-- Header & Penjelasan Universal Tiket --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="font-display font-black text-xl sm:text-2xl text-white">Distribusi Tiket & Manifest Awaydays</h2>
            <p class="text-xs text-white/50">Jatah tiket laga resmi PSMS Medan & manifest suporter basis untuk laga kandang maupun tur tandang.</p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="openManifestPrintModal()" class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs border border-white/10 transition-all flex items-center gap-2">
                <i class="fa-solid fa-print text-amber-400"></i>
                <span>Cetak Manifest Penumpang (A4)</span>
            </button>
        </div>
    </div>

    {{-- Banner Info: Tiket Universal --}}
    <div class="p-4 rounded-2xl bg-smeck-green-950/40 border border-smeck-green-500/30 flex items-start sm:items-center gap-3">
        <div class="w-9 h-9 rounded-xl bg-smeck-green-500/20 text-smeck-green-400 flex items-center justify-center text-lg shrink-0">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <div class="text-xs text-white/80">
            <strong class="text-smeck-green-400">Sistem Tiket Terbuka & Universal:</strong> Seluruh suporter SMeCK Hooligan dari basis mana pun bebas memesan tiket di manapun pertandingannya (Kandang Stadion Teladan maupun Awaydays Tur Luar Kota).
        </div>
    </div>

    {{-- 4 Stat Counters --}}
    @php
        $totalAllocated = $allocations->sum('allocated_quota');
        $totalDistributed = $allocations->sum('distributed_quota');
        $remainingQuota = $totalAllocated - $totalDistributed;
        $totalOrdersCount = $orders->count();
    @endphp
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="p-4 rounded-2xl bg-smeck-dark-900 border border-white/10">
            <div class="text-[11px] text-white/50 uppercase font-semibold">Total Alokasi Pusat</div>
            <div class="font-display font-black text-2xl text-white mt-1">{{ $totalAllocated }} Lembar</div>
            <div class="text-[10px] text-white/40 mt-0.5">Dari Pengurus Pusat</div>
        </div>

        <div class="p-4 rounded-2xl bg-smeck-dark-900 border border-white/10">
            <div class="text-[11px] text-white/50 uppercase font-semibold">Telah Terdistribusi</div>
            <div class="font-display font-black text-2xl text-smeck-green-400 mt-1">{{ $totalDistributed }} Lembar</div>
            <div class="text-[10px] text-white/40 mt-0.5">Dipesan anggota basis</div>
        </div>

        <div class="p-4 rounded-2xl bg-smeck-dark-900 border border-white/10">
            <div class="text-[11px] text-white/50 uppercase font-semibold">Sisa Jatah Kuota</div>
            <div class="font-display font-black text-2xl text-smeck-gold mt-1">{{ $remainingQuota }} Lembar</div>
            <div class="text-[10px] text-white/40 mt-0.5">Tersedia untuk anggota</div>
        </div>

        <div class="p-4 rounded-2xl bg-smeck-dark-900 border border-white/10">
            <div class="text-[11px] text-white/50 uppercase font-semibold">Total Pesanan Anggota</div>
            <div class="font-display font-black text-2xl text-amber-400 mt-1">{{ $totalOrdersCount }} Pemesan</div>
            <div class="text-[10px] text-white/40 mt-0.5">Data manifest siap jalan</div>
        </div>
    </div>

    {{-- Section 1: Alokasi Kuota Per Pertandingan --}}
    <div>
        <h3 class="font-display font-bold text-lg text-white mb-4 flex items-center gap-2">
            <i class="fa-solid fa-ticket text-amber-400"></i>
            <span>Alokasi Kuota Tiket Resmi per Pertandingan</span>
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($allocations as $al)
                @php
                    $percent = $al->allocated_quota > 0 ? round(($al->distributed_quota / $al->allocated_quota) * 100) : 0;
                    $sisa = $al->allocated_quota - $al->distributed_quota;
                @endphp
                <div class="p-6 rounded-3xl bg-smeck-dark-900 border border-white/10 space-y-4 hover:border-white/20 transition-all shadow-xl">
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase bg-smeck-green-500/20 text-smeck-green-400 border border-smeck-green-500/30">
                            {{ $al->matchTicket->category ?? 'Tribun SMeCK' }}
                        </span>
                        <span class="font-mono text-xs text-white/60">
                            <i class="fa-regular fa-calendar mr-1 text-white/30"></i>{{ $al->matchTicket->match_time->format('d M Y, H:i') }} WIB
                        </span>
                    </div>

                    <div>
                        <h3 class="font-display font-black text-xl text-white">
                            {{ $al->matchTicket->match_title }}
                        </h3>
                        <p class="text-xs text-white/50 mt-1">
                            <i class="fa-solid fa-location-dot mr-1 text-amber-400"></i>{{ $al->matchTicket->stadium_name ?? 'Stadion Teladan Medan' }}
                        </p>
                    </div>

                    {{-- Quota Numbers --}}
                    <div class="grid grid-cols-3 gap-2.5 text-center">
                        <div class="p-3 rounded-2xl bg-white/[0.03] border border-white/5">
                            <div class="text-[10px] text-white/40 uppercase">Alokasi Pusat</div>
                            <div class="text-base font-bold text-white mt-0.5">{{ $al->allocated_quota }}</div>
                        </div>
                        <div class="p-3 rounded-2xl bg-white/[0.03] border border-white/5">
                            <div class="text-[10px] text-white/40 uppercase">Terdistribusi</div>
                            <div class="text-base font-bold text-smeck-green-400 mt-0.5">{{ $al->distributed_quota }}</div>
                        </div>
                        <div class="p-3 rounded-2xl bg-white/[0.03] border border-white/5">
                            <div class="text-[10px] text-white/40 uppercase">Sisa Kuota</div>
                            <div class="text-base font-bold text-smeck-gold mt-0.5">{{ $sisa }}</div>
                        </div>
                    </div>

                    {{-- Progress Bar --}}
                    <div>
                        <div class="flex items-center justify-between text-[11px] mb-1">
                            <span class="text-white/50">Penyerapan Kuota Basis:</span>
                            <span class="font-bold text-white">{{ $percent }}%</span>
                        </div>
                        <div class="w-full h-2 rounded-full bg-white/10 overflow-hidden">
                            <div class="h-full rounded-full bg-gradient-to-r from-smeck-green-500 to-amber-400" style="width: {{ min(100, $percent) }}%"></div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-2 p-8 text-center rounded-3xl bg-smeck-dark-900 border border-white/10 text-white/40 text-xs">
                    <i class="fa-solid fa-ticket text-3xl mb-2 block text-white/20"></i>
                    Belum ada alokasi tiket resmi dari Pusat untuk chapter ini.
                </div>
            @endforelse
        </div>
    </div>

    {{-- Section 2: Manifest Pemesan Tiket & Tur Awaydays --}}
    <div class="p-6 sm:p-8 rounded-3xl bg-smeck-dark-900 border border-white/10 shadow-xl">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
            <div>
                <h3 class="font-display font-bold text-lg text-white flex items-center gap-2">
                    <i class="fa-solid fa-users-line text-smeck-gold"></i>
                    <span>Manifest Pemesan Tiket & Tur Bus Awaydays</span>
                </h3>
                <p class="text-xs text-white/50">Daftar anggota basis yang memesan tiket pertandingan beserta data kontak darurat & ukuran kaos.</p>
            </div>
            <span class="text-xs text-amber-400 font-mono font-bold">{{ $orders->count() }} Data Pemesan</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-white/10 text-white/40 uppercase tracking-wider text-[11px]">
                        <th class="py-3.5 px-4">Nama Suporter & NIA</th>
                        <th class="py-3.5 px-4">Pertandingan</th>
                        <th class="py-3.5 px-4 text-center">Jumlah</th>
                        <th class="py-3.5 px-4">Total Biaya</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4">Kontak / WA</th>
                        <th class="py-3.5 px-4 text-center">Size Kaos</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($orders as $o)
                        <tr class="hover:bg-white/[0.02] transition-colors">
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-white">{{ $o->user->name }}</div>
                                <div class="text-[10px] font-mono text-smeck-gold">{{ $o->user->profile?->nia ?? 'Belum ada NIA' }}</div>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-semibold text-white/90 truncate max-w-xs">{{ $o->matchTicket->match_title }}</div>
                                <div class="text-[10px] text-white/40 font-mono">{{ $o->matchTicket->match_time->format('d/m/Y') }}</div>
                            </td>
                            <td class="py-3.5 px-4 text-center font-bold text-white">
                                {{ $o->quantity }} Tiket
                            </td>
                            <td class="py-3.5 px-4 font-mono font-bold text-smeck-gold">
                                Rp {{ number_format($o->total_price, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($o->status === 'paid' || $o->status === 'approved')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-smeck-green-500/20 text-smeck-green-400 border border-smeck-green-500/30">
                                        Lunas
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-amber-500/20 text-amber-300 border border-amber-500/30">
                                        {{ $o->status }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 font-mono text-white/70">
                                {{ $o->user->profile?->phone_number ?? '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2 py-0.5 rounded bg-white/10 text-white font-bold text-[10px]">
                                    {{ $o->user->profile?->shirt_size ?? '-' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-white/40">
                                <i class="fa-solid fa-bus text-3xl mb-2 block text-white/20"></i>
                                Belum ada pesanan tiket dari anggota basis untuk pertandingan yang sedang dibuka.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- ========================================================================= --}}
{{-- MODAL PRINT MANIFEST PENUMPANG BUS AWAYDAYS (A4) --}}
{{-- ========================================================================= --}}
<div id="modalPrintManifest" class="fixed inset-0 z-50 bg-black/90 backdrop-blur-md hidden flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white text-slate-900 rounded-2xl max-w-4xl w-full p-6 sm:p-8 shadow-2xl relative my-8 print:p-0 print:m-0 print:shadow-none print:w-full">
        {{-- Tombol Tindakan --}}
        <div class="flex items-center justify-between pb-4 border-b border-slate-200 mb-6 print:hidden">
            <div>
                <h4 class="font-bold text-base text-slate-800">Preview Dokumen Manifest Penumpang Tur Awaydays</h4>
                <p class="text-xs text-slate-500">Format resmi untuk koordinator bus, manifes kepolisian, dan asuransi tur.</p>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="window.print()" class="px-4 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs shadow flex items-center gap-2">
                    <i class="fa-solid fa-print"></i>
                    <span>Cetak Manifest (Print)</span>
                </button>
                <button onclick="closeManifestPrintModal()" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs">
                    Tutup
                </button>
            </div>
        </div>

        {{-- Printable Content --}}
        <div class="font-sans">
            <div class="flex items-center justify-between pb-4 border-b-2 border-slate-900 mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-emerald-800 text-amber-400 flex items-center justify-center text-xl font-black">
                        SM
                    </div>
                    <div>
                        <h2 class="font-black text-lg text-slate-900 tracking-wider">SUPORTER MEDAN CINTA KINANTAN (SMeCK)</h2>
                        <h3 class="font-bold text-xs text-emerald-800 uppercase tracking-widest">MANIFEST TUR AWAYDAYS: {{ strtoupper(auth()->user()->chapter->name ?? 'BASIS') }}</h3>
                        <p class="text-[10px] text-slate-500">Dokumen Perjalanan Tur Awaydays PSMS Medan • Hot Line: 0812-6000-1950</p>
                    </div>
                </div>
                <div class="text-right text-[11px] text-slate-600">
                    <div>Tanggal: {{ now()->format('d/m/Y') }}</div>
                    <div>Total Rombongan: {{ $orders->sum('quantity') }} Orang</div>
                </div>
            </div>

            <table class="w-full text-left text-[11px] border border-slate-300 border-collapse">
                <thead>
                    <tr class="bg-slate-100 border-b border-slate-300 text-slate-700 font-bold uppercase text-[10px]">
                        <th class="py-2 px-2 border-r border-slate-300 w-8 text-center">No</th>
                        <th class="py-2 px-2.5 border-r border-slate-300">Nama Penumpang</th>
                        <th class="py-2 px-2.5 border-r border-slate-300">NIA Resmi</th>
                        <th class="py-2 px-2.5 border-r border-slate-300">Pertandingan</th>
                        <th class="py-2 px-2.5 border-r border-slate-300 text-center">Jml</th>
                        <th class="py-2 px-2.5 border-r border-slate-300">No. WhatsApp</th>
                        <th class="py-2 px-2.5 border-r border-slate-300 text-center">Size</th>
                        <th class="py-2 px-2.5">Kontak Darurat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($orders as $idx => $od)
                        <tr class="text-slate-800">
                            <td class="py-1.5 px-2 border-r border-slate-300 text-center font-mono">{{ $idx + 1 }}</td>
                            <td class="py-1.5 px-2.5 border-r border-slate-300 font-bold">{{ $od->user->name }}</td>
                            <td class="py-1.5 px-2.5 border-r border-slate-300 font-mono">{{ $od->user->profile?->nia ?? '-' }}</td>
                            <td class="py-1.5 px-2.5 border-r border-slate-300">{{ $od->matchTicket->match_title }}</td>
                            <td class="py-1.5 px-2.5 border-r border-slate-300 text-center font-bold">{{ $od->quantity }}</td>
                            <td class="py-1.5 px-2.5 border-r border-slate-300 font-mono">{{ $od->user->profile?->phone_number ?? '-' }}</td>
                            <td class="py-1.5 px-2.5 border-r border-slate-300 text-center font-bold">{{ $od->user->profile?->shirt_size ?? '-' }}</td>
                            <td class="py-1.5 px-2.5 text-[10px]">
                                {{ $od->user->profile?->emergency_contact_name ?? '-' }} ({{ $od->user->profile?->emergency_contact_number ?? '-' }})
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-6 text-center text-slate-400">Belum ada pemesan tiket tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Signature --}}
            <div class="mt-8 pt-4 flex items-center justify-between text-xs text-slate-800">
                <div class="text-center w-48">
                    <div>Koordinator Lapangan Tur,</div>
                    <div class="h-16"></div>
                    <div class="font-bold underline">({{ auth()->user()->name }})</div>
                    <div class="text-[10px] text-slate-500">Ketua Basis</div>
                </div>
                <div class="text-center w-48">
                    <div>Mengetahui,</div>
                    <div class="font-bold">Divisi Awaydays Pusat</div>
                    <div class="h-16"></div>
                    <div class="font-bold underline">(...................................)</div>
                    <div class="text-[10px] text-slate-500">DPP SMeCK Hooligan</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function openManifestPrintModal() {
        document.getElementById('modalPrintManifest').classList.remove('hidden');
    }

    function closeManifestPrintModal() {
        document.getElementById('modalPrintManifest').classList.add('hidden');
    }
</script>
@endsection

