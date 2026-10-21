@extends('layouts.app')

@section('title', 'Distribusi Tiket')
@section('header_title', 'Manajemen Distribusi Tiket Stadion')
@section('header_subtitle', 'Alokasikan jatah tiket fisik & barcode pertandingan kandang Stadion Teladan ke seluruh basis chapter')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">

    {{-- Section 1: Tambah Jadwal Laga PSMS Medan / Awaydays --}}
    <div class="p-6 sm:p-8 rounded-3xl bg-smeck-dark-900 border border-white/10">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="font-display font-bold text-lg text-white flex items-center gap-2">
                    <i class="fa-solid fa-futbol text-red-400"></i>
                    <span>Tambah Jadwal Pertandingan PSMS Medan / Awaydays</span>
                </h3>
                <p class="text-xs text-white/50">Daftarkan laga baru agar kuota tiket dapat dialokasikan ke chapter dan dipesan oleh anggota.</p>
            </div>
        </div>

        <form action="{{ route('superadmin.tickets.match') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="lg:col-span-2">
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Judul Laga / Pertandingan <span class="text-red-400">*</span></label>
                    <input type="text" name="match_title" required placeholder="Contoh: PSMS Medan vs Sriwijaya FC" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-xs focus:border-red-400 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Waktu Kick-Off <span class="text-red-400">*</span></label>
                    <input type="datetime-local" name="match_time" required class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-xs focus:border-red-400 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Stadion / Venue <span class="text-red-400">*</span></label>
                    <input type="text" name="stadium" required value="Stadion Teladan Medan" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-xs focus:border-red-400 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Tipe Laga <span class="text-red-400">*</span></label>
                    <select name="type" required class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-xs focus:border-red-400 focus:outline-none">
                        <option value="home" class="bg-smeck-dark-900">Home (Kandang Teladan)</option>
                        <option value="away" class="bg-smeck-dark-900">Awaydays (Tandang Luar Kota)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Harga Tiket (Rp) <span class="text-red-400">*</span></label>
                    <input type="number" name="price" required min="0" step="5000" placeholder="50000" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white font-mono text-xs focus:border-red-400 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Total Kuota Tiket <span class="text-red-400">*</span></label>
                    <input type="number" name="total_quota" required min="10" placeholder="1000" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white font-mono text-xs focus:border-red-400 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Info Transportasi / Bus (Away)</label>
                    <input type="text" name="bus_transport_info" placeholder="Bus Pariwisata Seat 2-2 AC" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-xs focus:border-red-400 focus:outline-none">
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-500 text-white font-bold text-xs shadow-lg transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i>
                    <span>Tambah Jadwal Laga PSMS</span>
                </button>
            </div>
        </form>
    </div>

    {{-- Form Alokasi Tiket --}}
    <div class="p-6 sm:p-8 rounded-3xl bg-smeck-dark-900 border border-white/10">
        <h3 class="font-display font-bold text-lg text-white mb-2">Alokasi Jatah Tiket ke Chapter</h3>
        <p class="text-xs text-white/50 mb-6">Tentukan kuota tiket resmi yang diberikan ke masing-masing pengurus basis wilayah.</p>

        <form action="{{ route('superadmin.tickets.allocate') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Pertandingan <span class="text-red-400">*</span></label>
                    <select name="match_ticket_id" required class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-red-400 focus:outline-none">
                        @foreach($matches as $m)
                            <option value="{{ $m->id }}" class="bg-smeck-dark-900">{{ $m->match_title }} (Sisa: {{ $m->remaining_quota }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Basis / Chapter Tujuan <span class="text-red-400">*</span></label>
                    <select name="chapter_id" required class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-red-400 focus:outline-none">
                        @foreach($chapters as $ch)
                            <option value="{{ $ch->id }}" class="bg-smeck-dark-900">{{ $ch->name }} ({{ $ch->region_code }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Jumlah Kuota Lembar <span class="text-red-400">*</span></label>
                    <input type="number" name="allocated_quota" required min="10" step="10" placeholder="Contoh: 150" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-red-400 focus:outline-none">
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-red-600 hover:bg-red-500 text-white font-bold text-xs shadow-lg transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-share-nodes"></i>
                    <span>Tetapkan Alokasi Kuota</span>
                </button>
            </div>
        </form>
    </div>

    {{-- Tabel Alokasi Terpasang --}}
    <div class="p-6 sm:p-8 rounded-3xl bg-smeck-dark-900 border border-white/10">
        <h3 class="font-display font-bold text-lg text-white mb-4">Distribusi Tiket Berjalan per Chapter</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-white/10 text-white/40 uppercase">
                        <th class="py-3 px-4">Pertandingan</th>
                        <th class="py-3 px-4">Chapter Penerima</th>
                        <th class="py-3 px-4">Kuota Diberikan</th>
                        <th class="py-3 px-4">Terdistribusi ke Member</th>
                        <th class="py-3 px-4">Sisa Kuota</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($allocations as $alc)
                        <tr class="hover:bg-white/[0.02]">
                            <td class="py-3.5 px-4 font-bold text-white">{{ $alc->matchTicket->match_title }}</td>
                            <td class="py-3.5 px-4 text-white/80">{{ $alc->chapter->name }} ({{ $alc->chapter->region_code }})</td>
                            <td class="py-3.5 px-4 font-mono font-bold text-white">{{ $alc->allocated_quota }} Lembar</td>
                            <td class="py-3.5 px-4 font-mono font-bold text-smeck-green-400">{{ $alc->distributed_quota }} Lembar</td>
                            <td class="py-3.5 px-4 font-mono font-bold text-smeck-gold">{{ $alc->allocated_quota - $alc->distributed_quota }} Lembar</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-white/40">Belum ada alokasi tiket yang dibuat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
