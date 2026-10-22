@extends('layouts.app')

@section('title', 'Galeri Koreo & Dokumentasi')
@section('header_title', 'Galeri Koreo & Arsip Dokumentasi SMeCK')
@section('header_subtitle', 'Pusat arsip dokumentasi atraksi koreo tribun Stadion Teladan, nobar, awaydays, dan kegiatan baksos seluruh chapter')

@section('content')
<div class="space-y-8 max-w-7xl mx-auto">

    {{-- Form Unggah Dokumentasi Resmi --}}
    <div class="p-6 sm:p-8 rounded-3xl bg-smeck-dark-900 border border-white/10 shadow-xl">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="font-display font-bold text-lg text-white flex items-center gap-2">
                    <i class="fa-solid fa-camera-retro text-red-400"></i>
                    <span>Unggah Dokumentasi Kegiatan / Koreo Resmi</span>
                </h3>
                <p class="text-xs text-white/50">Arsipkan dokumentasi koreografi, aksi suporter, awaydays, atau bakti sosial ke database nasional.</p>
            </div>
        </div>

        <form action="{{ route('superadmin.documentations.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="lg:col-span-2">
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Judul Kegiatan / Koreo <span class="text-red-400">*</span></label>
                    <input type="text" name="title" required placeholder="Contoh: Koreo 3D Kinantan Juara - Derby Sumatera" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-xs focus:border-red-400 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Basis / Chapter Terkait <span class="text-red-400">*</span></label>
                    <select name="chapter_id" required class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-xs focus:border-red-400 focus:outline-none">
                        @foreach($chapters as $ch)
                            <option value="{{ $ch->id }}" class="bg-smeck-dark-900">{{ $ch->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Jenis Kegiatan <span class="text-red-400">*</span></label>
                    <select name="activity_type" required class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-xs focus:border-red-400 focus:outline-none">
                        <option value="awaydays" class="bg-smeck-dark-900">Awaydays (Tandang)</option>
                        <option value="kopdar" class="bg-smeck-dark-900">Kopdar / Silaturahmi</option>
                        <option value="nobar" class="bg-smeck-dark-900">Nonton Bareng (Nobar)</option>
                        <option value="baksos" class="bg-smeck-dark-900">Bakti Sosial (Baksos)</option>
                        <option value="lainnya" class="bg-smeck-dark-900">Koreo Tribun & Lainnya</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Tanggal Kegiatan <span class="text-red-400">*</span></label>
                    <input type="date" name="activity_date" required value="{{ date('Y-m-d') }}" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-xs focus:border-red-400 focus:outline-none">
                </div>
                <div class="lg:col-span-3">
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Deskripsi & Catatan Aksi <span class="text-red-400">*</span></label>
                    <textarea name="description" rows="2" required placeholder="Jelaskan detail koreo, total suporter hadir, atau pesan yang disampaikan..." class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-xs focus:border-red-400 focus:outline-none"></textarea>
                </div>
            </div>

            <div class="flex items-center justify-between pt-2">
                <label class="flex items-center gap-2 cursor-pointer text-xs text-white/70">
                    <input type="checkbox" name="is_highlighted" value="1" class="rounded bg-white/10 border-white/20 text-red-600 focus:ring-0">
                    <span>Jadikan Sorotan Utama (Featured Highlight)</span>
                </label>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-500 text-white font-bold text-xs shadow-lg transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                    <span>Arsipkan Dokumentasi</span>
                </button>
            </div>
        </form>
    </div>

    {{-- Grid Arsip Dokumentasi Nasional --}}
    <div>
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-display font-bold text-lg text-white">Arsip Dokumentasi Kegiatan Nasional</h3>
            <span class="text-xs text-white/50 font-mono">{{ $documentations->total() }} Arsip Tersimpan</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($documentations as $doc)
                <div class="rounded-3xl bg-smeck-dark-900 border border-white/10 overflow-hidden shadow-xl hover:border-red-500/30 transition-all flex flex-col justify-between">
                    {{-- Cover Banner --}}
                    <div class="h-44 relative bg-smeck-dark-950 overflow-hidden flex items-center justify-center">
                        <img src="{{ asset('images/auth-cover.jpg') }}" alt="{{ $doc->title }}" class="w-full h-full object-cover opacity-60">
                        <div class="absolute inset-0 bg-gradient-to-t from-smeck-dark-900 via-transparent to-transparent"></div>
                        
                        <div class="absolute top-3 left-3 flex items-center gap-1.5">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-red-600 text-white shadow-md">
                                {{ strtoupper($doc->activity_type) }}
                            </span>
                            @if($doc->is_highlighted)
                                <span class="px-2 py-1 rounded-full text-[10px] font-bold bg-amber-500/90 text-white shadow-md">
                                    <i class="fa-solid fa-star mr-1"></i> Highlight
                                </span>
                            @endif
                        </div>
                        <div class="absolute bottom-2 right-3 text-[10px] text-white/60 font-mono">
                            <i class="fa-regular fa-calendar mr-1"></i>{{ $doc->activity_date ? $doc->activity_date->format('d M Y') : '-' }}
                        </div>
                    </div>

                    {{-- Body Content --}}
                    <div class="p-5 flex-1 flex flex-col justify-between">
                        <div>
                            <div class="text-[11px] text-smeck-green-400 font-bold mb-1 flex items-center gap-1">
                                <i class="fa-solid fa-location-dot text-[10px]"></i>
                                <span>{{ $doc->chapter?->name ?? 'Chapter Pusat' }}</span>
                            </div>
                            <h4 class="font-display font-bold text-white text-base leading-snug mb-2">
                                {{ $doc->title }}
                            </h4>
                            <p class="text-xs text-white/60 line-clamp-3 leading-relaxed">
                                {{ $doc->description }}
                            </p>
                        </div>

                        <div class="pt-4 mt-4 border-t border-white/5 flex items-center justify-between text-[11px] text-white/40">
                            <span>Diunggah oleh: <strong class="text-white">{{ $doc->user?->name ?? 'Admin' }}</strong></span>
                            <span class="font-mono text-smeck-gold text-[10px]">{{ $doc->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center text-white/40 text-xs">
                    <i class="fa-solid fa-camera text-3xl mb-2 text-white/20 block"></i>
                    Belum ada dokumentasi yang diarsipkan. Gunakan formulir di atas untuk mengunggah dokumentasi perdana.
                </div>
            @endforelse
        </div>

        @if($documentations->hasPages())
            <div class="mt-6">
                {{ $documentations->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
