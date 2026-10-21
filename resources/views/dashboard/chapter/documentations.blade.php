@extends('layouts.app')

@section('title', 'Dokumentasi Kegiatan')
@section('header_title', 'Laporan Dokumentasi Kegiatan Chapter')
@section('header_subtitle', 'Upload rekam jejak kegiatan kopdar, nobar, bakti sosial, dan awaydays sebagai laporan resmi ke Pusat')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    
    {{-- Form Upload Dokumentasi --}}
    <div class="p-6 sm:p-8 rounded-3xl bg-smeck-dark-900 border border-white/10">
        <h3 class="font-display font-bold text-lg text-white mb-2">Formulir Laporan Kegiatan Baru</h3>
        <p class="text-xs text-white/50 mb-6">Laporkan kegiatan rutin chapter untuk penilaian keaktifan basis oleh Pengurus Pusat.</p>

        <form action="{{ route('chapter.documentations.store') }}" method="POST" class="space-y-5">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase text-white/60 mb-2">Judul Kegiatan <span class="text-red-400">*</span></label>
                    <input type="text" name="title" required placeholder="Contoh: Kopdar Akbar & Doa Bersama Jelang Derby" class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-amber-400 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-2">Jenis Kegiatan <span class="text-red-400">*</span></label>
                    <select name="activity_type" required class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-amber-400 focus:outline-none">
                        <option value="kopdar" class="bg-smeck-dark-900">Kopdar Rutin</option>
                        <option value="nobar" class="bg-smeck-dark-900">Nonton Bareng (Nobar)</option>
                        <option value="baksos" class="bg-smeck-dark-900">Bakti Sosial / Peduli</option>
                        <option value="awaydays" class="bg-smeck-dark-900">Tour Tandang (Awaydays)</option>
                        <option value="lainnya" class="bg-smeck-dark-900">Lainnya</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-2">Tanggal Kegiatan <span class="text-red-400">*</span></label>
                    <input type="date" name="activity_date" required value="{{ date('Y-m-d') }}" class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-amber-400 focus:outline-none">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase text-white/60 mb-2">Ringkasan & Hasil Kegiatan <span class="text-red-400">*</span></label>
                    <textarea name="description" rows="2" required placeholder="Deskripsikan jumlah kehadiran anggota, lokasi, dan jalannya acara..." class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-amber-400 focus:outline-none"></textarea>
                </div>
            </div>

            <button type="submit" class="px-6 py-3 rounded-xl bg-amber-600 hover:bg-amber-500 text-white font-bold text-xs shadow-lg transition-all flex items-center gap-2">
                <i class="fa-solid fa-cloud-arrow-up"></i>
                <span>Unggah Laporan ke Pusat</span>
            </button>
        </form>
    </div>

    {{-- Activities Feed --}}
    <div class="space-y-4">
        <h3 class="font-display font-bold text-lg text-white">Arsip Dokumentasi Terunggah</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($docs as $doc)
                <div class="p-6 rounded-3xl bg-smeck-dark-900 border border-white/10 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-amber-500/20 text-amber-300">
                            {{ strtoupper($doc->activity_type) }}
                        </span>
                        <span class="text-xs text-white/40 font-mono">{{ $doc->activity_date->format('d M Y') }}</span>
                    </div>
                    <h4 class="font-display font-bold text-base text-white">{{ $doc->title }}</h4>
                    <p class="text-xs text-white/60 leading-relaxed">{{ $doc->description }}</p>
                    <div class="text-[11px] text-smeck-green-400 pt-2 border-t border-white/10 flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>Tercatat Resmi di Database DPP Pusat</span>
                    </div>
                </div>
            @empty
                <div class="col-span-2 text-center py-8 text-white/40">Belum ada dokumentasi yang diunggah oleh chapter ini.</div>
            @endforelse
        </div>
    </div>

</div>
@endsection
