@extends('layouts.app')

@section('title', 'Manajemen Tiket & Gelang Tribun')
@section('header_title', 'Manajemen Tiket & Posko Penukaran Gelang Stadion')
@section('header_subtitle', 'Pantau jatah kuota tiket suporter dari PSMS Medan, riwayat pemesanan anggota, dan posko gate penukaran gelang resmi')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">

    {{-- Top Statistics Summary Row --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Card 1: Jatah Kuota Suporter --}}
        <div class="p-5 rounded-3xl bg-smeck-dark-900 border border-emerald-500/30 shadow-xl relative overflow-hidden">
            <div class="flex items-center justify-between text-xs text-white/50 mb-1">
                <span>Jatah Kuota dari PSMS</span>
                <i class="fa-solid fa-futbol text-emerald-400"></i>
            </div>
            <div class="text-3xl font-display font-black text-white mt-1">
                {{ number_format($totalQuotaSupporter) }}
                <span class="text-xs font-normal text-white/40">Tiket</span>
            </div>
            <div class="text-[11px] text-emerald-400 mt-1 font-medium">
                Sisa {{ number_format($totalRemainingQuota) }} tiket siap dipesan anggota
            </div>
        </div>

        {{-- Card 2: Tiket Dipesan Suporter --}}
        <div class="p-5 rounded-3xl bg-smeck-dark-900 border border-white/10 shadow-xl">
            <div class="flex items-center justify-between text-xs text-white/50 mb-1">
                <span>Tiket Dipesan Anggota</span>
                <i class="fa-solid fa-ticket-simple text-smeck-gold"></i>
            </div>
            <div class="text-3xl font-display font-black text-smeck-gold mt-1">
                {{ number_format($totalSoldTickets) }}
                <span class="text-xs font-normal text-white/40">Lembar</span>
            </div>
            <div class="text-[11px] text-white/50 mt-1 font-medium">
                Otomatis memotong kuota suporter
            </div>
        </div>

        {{-- Card 3: Gelang Sudah Ditukarkan di Stadion --}}
        <div class="p-5 rounded-3xl bg-smeck-dark-900 border border-white/10 shadow-xl">
            <div class="flex items-center justify-between text-xs text-white/50 mb-1">
                <span>Gelang Ditukar (Check-in)</span>
                <i class="fa-solid fa-person-circle-check text-cyan-400"></i>
            </div>
            <div class="text-3xl font-display font-black text-cyan-400 mt-1">
                {{ number_format($totalWristbandChecked) }}
                <span class="text-xs font-normal text-white/40">Suporter</span>
            </div>
            <div class="text-[11px] text-white/50 mt-1 font-medium">
                Gelang resmi tribun diserahkan di gate
            </div>
        </div>

        {{-- Card 4: Belum Ditukar Gelang --}}
        @php
            $pendingWristband = max(0, $totalSoldTickets - $totalWristbandChecked);
        @endphp
        <div class="p-5 rounded-3xl bg-smeck-dark-900 border border-white/10 shadow-xl">
            <div class="flex items-center justify-between text-xs text-white/50 mb-1">
                <span>Menunggu Tukar Gelang</span>
                <i class="fa-solid fa-clock-rotate-left text-amber-400"></i>
            </div>
            <div class="text-3xl font-display font-black text-amber-400 mt-1">
                {{ number_format($pendingWristband) }}
                <span class="text-xs font-normal text-white/40">Suporter</span>
            </div>
            <div class="text-[11px] text-white/50 mt-1 font-medium">
                Perlu diverifikasi panitia di stadion
            </div>
        </div>
    </div>

    {{-- SECTION 1: POSKO GATE PENUKARAN GELANG TRIBUN (WRISTBAND SUPORTER CHECK-IN) --}}
    <div class="p-6 sm:p-8 rounded-3xl bg-smeck-dark-900 border border-emerald-500/30 shadow-2xl space-y-6 relative overflow-hidden">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-white/10">
            <div>
                <div class="inline-flex items-center gap-2 px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 text-[10px] font-bold uppercase tracking-wider mb-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Posko Gate Stadion Teladan
                </div>
                <h3 class="font-display font-black text-xl text-white">
                    Verifikasi Bukti Tiket & Penyerahan Gelang Tribun
                </h3>
                <p class="text-xs text-white/50">Suporter menunjukkan bukti pemesanan atau KTA, panitia memverifikasi dan menyerahkan gelang tribun.</p>
            </div>

            <div class="flex items-center gap-2">
                <button type="button" onclick="openAddMatchModal()" class="px-4 py-2.5 rounded-xl bg-red-600 hover:bg-red-500 text-white font-bold text-xs shadow-lg transition-colors flex items-center gap-1.5">
                    <i class="fa-solid fa-plus"></i>
                    <span>Tambah Jadwal Laga PSMS</span>
                </button>
            </div>
        </div>

        {{-- Search & Filter Form for Ticket Orders --}}
        <form method="GET" action="{{ route('superadmin.tickets') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3">
            <div class="lg:col-span-5">
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-white/30 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama suporter, NIA, atau kode booking e-tiket..." class="w-full pl-9 pr-4 py-2 bg-white/5 border border-white/10 rounded-xl text-xs text-white placeholder-white/30 focus:border-emerald-500 focus:outline-none">
                </div>
            </div>

            <div class="lg:col-span-4">
                <select name="match_id" onchange="this.form.submit()" class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none">
                    <option value="" class="bg-smeck-dark-900">Semua Pertandingan PSMS</option>
                    @foreach($matches as $m)
                        <option value="{{ $m->id }}" {{ request('match_id') == $m->id ? 'selected' : '' }} class="bg-smeck-dark-900">
                            {{ $m->match_title }} (Sisa {{ $m->remaining_quota }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="lg:col-span-3 flex items-center gap-2">
                <select name="checkin_status" onchange="this.form.submit()" class="flex-1 px-3 py-2 bg-white/5 border border-white/10 rounded-xl text-xs text-white focus:border-emerald-500 focus:outline-none">
                    <option value="" class="bg-smeck-dark-900">Semua Status Gelang</option>
                    <option value="unchecked" {{ request('checkin_status') === 'unchecked' ? 'selected' : '' }} class="bg-smeck-dark-900">Belum Ditukar Gelang</option>
                    <option value="checked" {{ request('checkin_status') === 'checked' ? 'selected' : '' }} class="bg-smeck-dark-900">Gelang Sudah Diserahkan</option>
                </select>

                @if(request()->hasAny(['search', 'match_id', 'checkin_status']))
                    <a href="{{ route('superadmin.tickets') }}" class="p-2 rounded-xl bg-white/5 hover:bg-white/10 text-white/60 hover:text-white text-xs">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>

        {{-- Tabel Pembeli Tiket & Penukaran Gelang --}}
        <div class="rounded-2xl border border-white/10 overflow-hidden bg-black/30">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-white/5 border-b border-white/10 text-white/40 uppercase tracking-wider text-[11px]">
                            <th class="py-3.5 px-4">Kode Booking E-Tiket</th>
                            <th class="py-3.5 px-4">Suporter & NIA</th>
                            <th class="py-3.5 px-4">Pertandingan PSMS</th>
                            <th class="py-3.5 px-4">Nominal</th>
                            <th class="py-3.5 px-4 text-center">Status Gelang Tribun</th>
                            <th class="py-3.5 px-4 text-right">Aksi Panitia di Gate</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($ticketOrders as $order)
                            @php
                                $bookingCode = $order->seat_number ?? ('SMCK-TKT-' . str_pad($order->id, 5, '0', STR_PAD_LEFT));
                            @endphp
                            <tr class="hover:bg-white/[0.02] transition-colors">
                                <td class="py-3.5 px-4">
                                    <div class="font-mono font-bold text-smeck-gold text-sm tracking-wide">
                                        <i class="fa-solid fa-qrcode mr-1 text-[11px]"></i>{{ $bookingCode }}
                                    </div>
                                    <div class="text-[10px] text-white/40 mt-0.5">
                                        {{ $order->created_at->format('d M Y, H:i') }} WIB
                                    </div>
                                </td>

                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-white text-sm">{{ $order->user?->name ?? 'Suporter' }}</div>
                                    <div class="font-mono text-[10px] text-smeck-green-400 mt-0.5">
                                        NIA: {{ $order->user?->profile?->nia ?? 'Anggota Terdaftar' }}
                                    </div>
                                    <div class="text-[10px] text-white/50">
                                        Basis: {{ $order->chapter?->name ?? 'DPP Pusat' }}
                                    </div>
                                </td>

                                <td class="py-3.5 px-4">
                                    <div class="font-semibold text-white">{{ $order->matchTicket?->match_title ?? 'Laga PSMS Medan' }}</div>
                                    <div class="text-[10px] text-white/50 mt-0.5">
                                        {{ $order->matchTicket?->stadium ?? 'Stadion Teladan' }}
                                    </div>
                                </td>

                                <td class="py-3.5 px-4">
                                    <div class="font-mono font-bold text-smeck-green-400">
                                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                    </div>
                                    <span class="inline-block mt-0.5 px-2 py-0.2 rounded text-[9px] font-bold uppercase bg-emerald-500/20 text-emerald-300">
                                        LUNAS
                                    </span>
                                </td>

                                <td class="py-3.5 px-4 text-center">
                                    @if($order->is_checked_in)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-smeck-green-500/20 text-smeck-green-300 border border-smeck-green-500/30">
                                            <i class="fa-solid fa-circle-check text-smeck-green-400"></i>
                                            <span>GELANG SUDAH DISERAHKAN</span>
                                        </span>
                                        <div class="text-[9px] text-white/40 mt-1 font-mono">
                                            Tukar: {{ $order->checked_in_at ? $order->checked_in_at->format('H:i') : '-' }} WIB
                                        </div>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30">
                                            <i class="fa-solid fa-clock text-amber-400"></i>
                                            <span>BELUM DITUKAR GELANG</span>
                                        </span>
                                        <div class="text-[9px] text-white/40 mt-1">
                                            Tunjukkan bukti di posko
                                        </div>
                                    @endif
                                </td>

                                <td class="py-3.5 px-4 text-right">
                                    @if(!$order->is_checked_in)
                                        <form action="{{ route('superadmin.tickets.exchange', $order->id) }}" method="POST" onsubmit="return confirm('Serahkan gelang tribun kepada suporter {{ addslashes($order->user?->name) }} (Kode Tiket: {{ $bookingCode }})?')">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 rounded-xl bg-smeck-green-600 hover:bg-smeck-500 text-white font-bold text-xs shadow-lg shadow-smeck-green-700/30 transition-all flex items-center gap-1.5 ml-auto">
                                                <i class="fa-solid fa-hand-holding-hand"></i>
                                                <span>Serahkan Gelang</span>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('superadmin.tickets.undo', $order->id) }}" method="POST" onsubmit="return confirm('Batalkan penukaran gelang untuk {{ addslashes($order->user?->name) }}?')">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1 rounded-lg bg-white/5 hover:bg-white/10 text-white/40 hover:text-white text-[11px] font-semibold transition-colors ml-auto">
                                                Batalkan
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-white/40">Belum ada riwayat pemesanan tiket suporter.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($ticketOrders->hasPages())
                <div class="p-4 border-t border-white/5">
                    {{ $ticketOrders->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- SECTION 2: JADWAL PERTANDINGAN PSMS & JATAH KUOTA SUPORTER DARI PSMS --}}
    <div class="p-6 sm:p-8 rounded-3xl bg-smeck-dark-900 border border-white/10 shadow-xl space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="font-display font-bold text-lg text-white">Kalender Laga PSMS & Kuota Suporter</h3>
                <p class="text-xs text-white/40">Daftar pertandingan aktif, status penjualan, dan jatah tiket yang diberikan ke suporter</p>
            </div>
            <button type="button" onclick="openAddMatchModal()" class="text-xs text-red-400 font-bold hover:underline">
                + Tambah Pertandingan Baru
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($matches as $m)
                @php
                    $sold = $m->total_quota - $m->remaining_quota;
                    $pct = $m->total_quota > 0 ? round(($sold / $m->total_quota) * 100) : 0;
                @endphp
                <div class="p-5 rounded-2xl bg-white/[0.02] border border-white/5 space-y-3 flex flex-col justify-between hover:border-white/20 transition-all">
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $m->type === 'away' ? 'bg-amber-500/20 text-amber-300' : 'bg-smeck-green-500/20 text-smeck-green-300' }}">
                                {{ $m->type === 'away' ? 'Awaydays' : 'Kandang (Teladan)' }}
                            </span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $m->status === 'open' ? 'bg-emerald-500/20 text-emerald-300' : 'bg-red-500/20 text-red-300' }}">
                                {{ $m->status === 'open' ? 'Buka Penjualan' : 'Ditutup' }}
                            </span>
                        </div>

                        <h4 class="font-display font-bold text-white text-base leading-snug">
                            {{ $m->match_title }}
                        </h4>

                        <div class="text-[11px] text-white/60 space-y-1 mt-2">
                            <div><i class="fa-regular fa-calendar text-red-400 mr-1.5"></i>{{ $m->match_time ? $m->match_time->format('l, d M Y - H:i') : '-' }} WIB</div>
                            <div><i class="fa-solid fa-location-dot text-red-400 mr-1.5"></i>{{ $m->stadium }}</div>
                            <div><i class="fa-solid fa-tag text-smeck-gold mr-1.5"></i>Rp {{ number_format($m->price, 0, ',', '.') }} / tiket</div>
                        </div>

                        {{-- Quota Progress Bar --}}
                        <div class="pt-3">
                            <div class="flex items-center justify-between text-xs mb-1">
                                <span class="text-white/50">Terjual: <strong class="text-white">{{ $sold }}</strong></span>
                                <span class="text-smeck-green-400 font-bold">Sisa {{ $m->remaining_quota }} / {{ $m->total_quota }}</span>
                            </div>
                            <div class="w-full h-2 rounded-full bg-white/10 overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-smeck-green-600 to-smeck-gold rounded-full" style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-white/5 flex items-center justify-between">
                        <span class="text-[11px] text-white/40 font-mono">{{ $m->orders_count }} Transaksi</span>
                        <button type="button" onclick='openEditMatchModal(@json($m))' class="px-3 py-1.5 rounded-lg bg-white/10 hover:bg-white/20 text-white font-semibold text-xs transition-colors flex items-center gap-1">
                            <i class="fa-solid fa-pen-to-square"></i>
                            <span>Edit Laga / Kuota</span>
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-8 text-center text-white/40 text-xs">Belum ada pertandingan yang dijadwalkan.</div>
            @endforelse
        </div>
    </div>

    {{-- SECTION 3: ALOKASI KHUSUS ROMBONGAN BASIS CHAPTER (OPSIONAL) --}}
    <div class="p-6 sm:p-8 rounded-3xl bg-smeck-dark-900 border border-white/10 shadow-xl space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="font-display font-bold text-lg text-white">Alokasi Khusus Chapter Rombongan (Opsional)</h3>
                <p class="text-xs text-white/50">Gunakan jika korwil basis memesan kuota blok rombongan bus awaydays secara kolektif.</p>
            </div>
            <span class="text-xs text-white/40 font-mono">{{ $allocations->count() }} Alokasi Aktif</span>
        </div>

        <form action="{{ route('superadmin.tickets.allocate') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-3 pt-2">
            @csrf
            <div>
                <label class="block text-[11px] font-bold uppercase text-white/60 mb-1">Pertandingan</label>
                <select name="match_ticket_id" required class="w-full px-3 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-xs focus:border-red-400 focus:outline-none">
                    @foreach($matches as $m)
                        <option value="{{ $m->id }}" class="bg-smeck-dark-900">{{ $m->match_title }} (Sisa: {{ $m->remaining_quota }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[11px] font-bold uppercase text-white/60 mb-1">Chapter Tujuan</label>
                <select name="chapter_id" required class="w-full px-3 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-xs focus:border-red-400 focus:outline-none">
                    @foreach($chapters as $ch)
                        <option value="{{ $ch->id }}" class="bg-smeck-dark-900">{{ $ch->name }} ({{ $ch->region_code }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[11px] font-bold uppercase text-white/60 mb-1">Jumlah Jatah Lembar</label>
                <input type="number" name="allocated_quota" required min="5" placeholder="Contoh: 100" class="w-full px-3 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-xs focus:border-red-400 focus:outline-none font-mono">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full py-2.5 rounded-xl bg-red-600 hover:bg-red-500 text-white font-bold text-xs shadow-lg transition-colors flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-share-nodes"></i>
                    <span>Tetapkan Jatah Basis</span>
                </button>
            </div>
        </form>
    </div>

</div>

{{-- MODAL TAMBAH JADWAL LAGA PSMS --}}
<div id="modalAddMatch" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-md hidden flex items-center justify-center p-4">
    <div class="bg-smeck-dark-900 border border-white/10 rounded-3xl max-w-2xl w-full p-6 sm:p-8 shadow-2xl relative">
        <button onclick="closeAddMatchModal()" class="absolute top-6 right-6 text-white/40 hover:text-white text-lg">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <h3 class="font-display font-black text-xl text-white mb-1">Tambah Jadwal Laga PSMS Medan / Awaydays</h3>
        <p class="text-xs text-white/50 mb-6">Daftarkan jatah tiket dari PSMS Medan agar suporter dapat langsung memesan dari dashboard mereka.</p>

        <form action="{{ route('superadmin.tickets.match') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Judul Laga / Pertandingan <span class="text-red-400">*</span></label>
                    <input type="text" name="match_title" required placeholder="Contoh: PSMS Medan vs Sriwijaya FC" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-red-400 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Waktu Kick-Off <span class="text-red-400">*</span></label>
                    <input type="datetime-local" name="match_time" required class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-red-400 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Stadion / Venue <span class="text-red-400">*</span></label>
                    <input type="text" name="stadium" required value="Stadion Teladan Medan" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-red-400 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Tipe Laga <span class="text-red-400">*</span></label>
                    <select name="type" required class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-red-400 focus:outline-none">
                        <option value="home" class="bg-smeck-dark-900">Home (Kandang Teladan)</option>
                        <option value="away" class="bg-smeck-dark-900">Awaydays (Tur Luar Kota)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Harga Tiket Suporter (Rp) <span class="text-red-400">*</span></label>
                    <input type="number" name="price" required min="0" step="5000" placeholder="50000" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white font-mono text-sm focus:border-red-400 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Total Jatah Kuota dari PSMS (Lembar) <span class="text-red-400">*</span></label>
                    <input type="number" name="total_quota" required min="10" placeholder="Contoh: 1000" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white font-mono text-sm focus:border-red-400 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Info Transportasi / Bus (Jika Away)</label>
                    <input type="text" name="bus_transport_info" placeholder="Bus AC Pariwisata Seat 2-2" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-red-400 focus:outline-none">
                </div>
            </div>

            <div class="pt-4 flex items-center justify-end gap-2">
                <button type="button" onclick="closeAddMatchModal()" class="px-5 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 text-white font-semibold text-xs transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-red-600 hover:bg-red-500 text-white font-bold text-xs shadow-lg transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i>
                    <span>Simpan Jadwal Laga</span>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT JADWAL LAGA PSMS --}}
<div id="modalEditMatch" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-md hidden flex items-center justify-center p-4">
    <div class="bg-smeck-dark-900 border border-white/10 rounded-3xl max-w-2xl w-full p-6 sm:p-8 shadow-2xl relative">
        <button onclick="closeEditMatchModal()" class="absolute top-6 right-6 text-white/40 hover:text-white text-lg">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <h3 class="font-display font-black text-xl text-white mb-1">Edit Jadwal Laga & Kuota Suporter</h3>
        <p class="text-xs text-white/50 mb-6">Sesuaikan perubahan waktu kick-off, harga tiket, kuota jatah, atau status penjualan.</p>

        <form id="formEditMatch" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Judul Laga / Pertandingan <span class="text-red-400">*</span></label>
                    <input type="text" name="match_title" id="editMatchTitle" required class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-red-400 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Waktu Kick-Off <span class="text-red-400">*</span></label>
                    <input type="datetime-local" name="match_time" id="editMatchTime" required class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-red-400 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Stadion / Venue <span class="text-red-400">*</span></label>
                    <input type="text" name="stadium" id="editMatchStadium" required class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-red-400 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Tipe Laga <span class="text-red-400">*</span></label>
                    <select name="type" id="editMatchType" required class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-red-400 focus:outline-none">
                        <option value="home" class="bg-smeck-dark-900">Home (Kandang Teladan)</option>
                        <option value="away" class="bg-smeck-dark-900">Awaydays (Tur Luar Kota)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Harga Tiket Suporter (Rp) <span class="text-red-400">*</span></label>
                    <input type="number" name="price" id="editMatchPrice" required min="0" step="5000" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white font-mono text-sm focus:border-red-400 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Total Jatah Kuota dari PSMS <span class="text-red-400">*</span></label>
                    <input type="number" name="total_quota" id="editMatchQuota" required min="10" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white font-mono text-sm focus:border-red-400 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Status Penjualan Tiket <span class="text-red-400">*</span></label>
                    <select name="status" id="editMatchStatus" required class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-red-400 focus:outline-none">
                        <option value="open" class="bg-smeck-dark-900">Buka Penjualan (Open)</option>
                        <option value="closed" class="bg-smeck-dark-900">Tutup Penjualan (Closed)</option>
                        <option value="completed" class="bg-smeck-dark-900">Laga Selesai (Completed)</option>
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Info Transportasi / Bus (Jika Away)</label>
                    <input type="text" name="bus_transport_info" id="editMatchBus" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-red-400 focus:outline-none">
                </div>
            </div>

            <div class="pt-4 flex items-center justify-end gap-2">
                <button type="button" onclick="closeEditMatchModal()" class="px-5 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 text-white font-semibold text-xs transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-lg transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Simpan Perubahan Laga</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddMatchModal() {
    document.getElementById('modalAddMatch').classList.remove('hidden');
}
function closeAddMatchModal() {
    document.getElementById('modalAddMatch').classList.add('hidden');
}

function openEditMatchModal(match) {
    document.getElementById('formEditMatch').action = `/superadmin/tickets/match/${match.id}/update`;
    document.getElementById('editMatchTitle').value = match.match_title || '';
    if (match.match_time) {
        document.getElementById('editMatchTime').value = match.match_time.replace(' ', 'T').substring(0, 16);
    }
    document.getElementById('editMatchStadium').value = match.stadium || '';
    document.getElementById('editMatchType').value = match.type || 'home';
    document.getElementById('editMatchPrice').value = match.price || 50000;
    document.getElementById('editMatchQuota').value = match.total_quota || 1000;
    document.getElementById('editMatchStatus').value = match.status || 'open';
    document.getElementById('editMatchBus').value = match.bus_transport_info || '';

    document.getElementById('modalEditMatch').classList.remove('hidden');
}
function closeEditMatchModal() {
    document.getElementById('modalEditMatch').classList.add('hidden');
}
</script>
@endsection
