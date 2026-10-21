@extends('layouts.app')

@section('title', 'Master Chapter')
@section('header_title', 'Master Basis & Chapter Wilayah')
@section('header_subtitle', 'Manajemen daftar cabang basis suporter SMeCK Hooligan resmi se-Indonesia')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">

    {{-- Form Tambah Chapter Baru --}}
    <div class="p-6 sm:p-8 rounded-3xl bg-smeck-dark-900 border border-white/10">
        <h3 class="font-display font-bold text-lg text-white mb-2">Tambah Basis / Chapter Baru</h3>
        <p class="text-xs text-white/50 mb-6">Daftarkan korwil dan basecamp basis wilayah baru untuk pembagian alokasi tiket & verifikasi anggota.</p>

        <form action="{{ route('superadmin.chapters.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Nama Chapter <span class="text-red-400">*</span></label>
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
    <div class="p-6 sm:p-8 rounded-3xl bg-smeck-dark-900 border border-white/10">
        <h3 class="font-display font-bold text-lg text-white mb-4">Daftar Basis / Chapter Resmi Aktif</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-white/10 text-white/40 uppercase">
                        <th class="py-3 px-4">Kode</th>
                        <th class="py-3 px-4">Nama Chapter</th>
                        <th class="py-3 px-4">Ketua Korwil</th>
                        <th class="py-3 px-4">Alamat Basecamp</th>
                        <th class="py-3 px-4">Jumlah Anggota</th>
                        <th class="py-3 px-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @foreach($chapters as $c)
                        <tr class="hover:bg-white/[0.02]">
                            <td class="py-3.5 px-4 font-mono font-bold text-red-400">{{ $c->region_code }}</td>
                            <td class="py-3.5 px-4 font-bold text-white">{{ $c->name }}</td>
                            <td class="py-3.5 px-4 text-white/80">{{ $c->leader_name }}</td>
                            <td class="py-3.5 px-4 text-white/60">{{ $c->basecamp_address }}</td>
                            <td class="py-3.5 px-4 font-bold text-smeck-green-400 font-mono">{{ $c->users_count }} Org</td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-smeck-green-500/20 text-smeck-green-300">
                                    Aktif
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
