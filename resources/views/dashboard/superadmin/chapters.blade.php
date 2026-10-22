@extends('layouts.app')

@section('title', 'Master Chapter')
@section('header_title', 'Master Basis & Chapter Wilayah')
@section('header_subtitle', 'Manajemen cabang basis suporter SMeCK Hooligan resmi se-Indonesia, koordinator wilayah, dan basecamp')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">

    {{-- Top Statistics Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="p-6 rounded-3xl bg-smeck-dark-900 border border-white/10 shadow-xl">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-white/50 uppercase">Basis / Chapter Resmi</span>
                <div class="w-8 h-8 rounded-lg bg-blue-500/20 text-blue-400 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-sitemap"></i>
                </div>
            </div>
            <div class="font-display font-black text-3xl text-white mt-2">{{ $totalChapters }}</div>
            <div class="text-[11px] text-blue-400 mt-1 font-medium">Sumatera Utara & Perantauan Nasional</div>
        </div>

        <div class="p-6 rounded-3xl bg-smeck-dark-900 border border-white/10 shadow-xl">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-white/50 uppercase">Anggota Berbasis</span>
                <div class="w-8 h-8 rounded-lg bg-smeck-green-500/20 text-smeck-green-400 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
            <div class="font-display font-black text-3xl text-smeck-green-400 mt-2">{{ $totalChapterMembers }}</div>
            <div class="text-[11px] text-white/50 mt-1 font-medium">Suporter terdata di bawah korwil basis</div>
        </div>

        <div class="p-6 rounded-3xl bg-smeck-dark-900 border border-white/10 shadow-xl">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-white/50 uppercase">Rata-rata Kekuatan Basis</span>
                <div class="w-8 h-8 rounded-lg bg-smeck-gold/20 text-smeck-gold flex items-center justify-center text-sm">
                    <i class="fa-solid fa-chart-pie"></i>
                </div>
            </div>
            <div class="font-display font-black text-3xl text-smeck-gold mt-2">{{ $avgMembers }}</div>
            <div class="text-[11px] text-white/50 mt-1 font-medium">Rata-rata suporter per chapter</div>
        </div>
    </div>

    {{-- Form Tambah Chapter Baru --}}
    <div class="p-6 sm:p-8 rounded-3xl bg-smeck-dark-900 border border-white/10 shadow-xl">
        <h3 class="font-display font-bold text-lg text-white mb-1.5 flex items-center gap-2">
            <i class="fa-solid fa-circle-plus text-red-500"></i>
            <span>Daftarkan Basis / Chapter Baru</span>
        </h3>
        <p class="text-xs text-white/50 mb-6">Daftarkan korwil dan basecamp basis wilayah baru untuk pembagian alokasi tiket, verifikasi anggota, dan jalur koordinasi DPP.</p>

        <form action="{{ route('superadmin.chapters.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Nama Chapter / Basis <span class="text-red-400">*</span></label>
                    <input type="text" name="name" required placeholder="Contoh: Chapter Medan Sunggal" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-red-400 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Kode Wilayah Unik <span class="text-red-400">*</span></label>
                    <input type="text" name="region_code" required placeholder="Contoh: MDS-08" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white font-mono uppercase text-sm focus:border-red-400 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Nama Ketua / Korwil <span class="text-red-400">*</span></label>
                    <input type="text" name="leader_name" required placeholder="Contoh: Bang Ucok Harahap" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-red-400 focus:outline-none">
                </div>
                <div class="md:col-span-3">
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Alamat Basecamp Chapter <span class="text-red-400">*</span></label>
                    <input type="text" name="basecamp_address" required placeholder="Contoh: Jl. TB Simatupang No. 34, Medan Sunggal" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-red-400 focus:outline-none">
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-red-600 hover:bg-red-500 text-white font-bold text-xs shadow-lg transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i>
                    <span>Simpan Chapter Baru</span>
                </button>
            </div>
        </form>
    </div>

    {{-- Tabel Master Chapters --}}
    <div class="p-6 sm:p-8 rounded-3xl bg-smeck-dark-900 border border-white/10 shadow-xl">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="font-display font-bold text-lg text-white">Daftar Basis / Chapter Resmi Aktif</h3>
                <p class="text-xs text-white/40">Seluruh cabang suporter SMeCK Hooligan yang terverifikasi di DPP</p>
            </div>
            <span class="text-xs text-white/50 font-mono">{{ $totalChapters }} Basis Terdaftar</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-white/5 border-b border-white/10 text-white/40 uppercase tracking-wider text-[11px]">
                        <th class="py-3.5 px-4">Kode</th>
                        <th class="py-3.5 px-4">Nama Chapter</th>
                        <th class="py-3.5 px-4">Ketua Korwil</th>
                        <th class="py-3.5 px-4">Alamat Basecamp</th>
                        <th class="py-3.5 px-4">Kekuatan Anggota</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4 text-right">Direktori</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($chapters as $c)
                        <tr class="hover:bg-white/[0.02] transition-colors">
                            <td class="py-3.5 px-4 font-mono font-bold text-red-400">{{ $c->region_code }}</td>
                            <td class="py-3.5 px-4 font-bold text-white text-sm">{{ $c->name }}</td>
                            <td class="py-3.5 px-4 text-white/80 font-medium">
                                <i class="fa-solid fa-user-shield text-amber-400 mr-1 text-[11px]"></i>
                                {{ $c->leader_name }}
                            </td>
                            <td class="py-3.5 px-4 text-white/60">
                                <i class="fa-solid fa-location-dot text-white/30 mr-1 text-[10px]"></i>
                                {{ $c->basecamp_address }}
                            </td>
                            <td class="py-3.5 px-4 font-bold text-smeck-green-400 font-mono text-sm">
                                {{ $c->users_count }} Anggota
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-smeck-green-500/20 text-smeck-green-300 border border-smeck-green-500/30">
                                    Aktif
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <a href="{{ route('superadmin.members', ['chapter_id' => $c->id]) }}" class="px-3 py-1.5 rounded-lg bg-white/5 hover:bg-white/15 text-white/80 hover:text-white font-semibold text-[11px] transition-colors inline-flex items-center gap-1.5">
                                    <span>Lihat Anggota</span>
                                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-white/40">Belum ada basis chapter yang didaftarkan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
