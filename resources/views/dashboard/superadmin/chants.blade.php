@extends('layouts.app')

@section('title', 'Pustaka Chant Tribun')
@section('header_title', 'Pustaka Chant & Anthem SMeCK Hooligan')
@section('header_subtitle', 'Kelola koleksi lagu kebanggaan suporter, lirik chant pembakar semangat di tribun Teladan, dan status featured landing page')

@section('content')
<div class="space-y-8 max-w-7xl mx-auto">

    {{-- Form Tambah Chant Baru --}}
    <div class="p-6 sm:p-8 rounded-3xl bg-smeck-dark-900 border border-white/10 shadow-xl">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="font-display font-bold text-lg text-white flex items-center gap-2">
                    <i class="fa-solid fa-music text-red-400"></i>
                    <span>Tambah Lagu / Chant Suporter Baru</span>
                </h3>
                <p class="text-xs text-white/50">Daftarkan chant resmi SMeCK Hooligan agar dapat dihafal suporter dan ditampilkan di Landing Page.</p>
            </div>
        </div>

        <form action="{{ route('superadmin.chants.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="lg:col-span-2">
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Judul Lagu / Chant <span class="text-red-400">*</span></label>
                    <input type="text" name="title" required placeholder="Contoh: Kami Datang Bersama Kinantan" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-xs focus:border-red-400 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Kategori Chant <span class="text-red-400">*</span></label>
                    <select name="category" required class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-xs focus:border-red-400 focus:outline-none">
                        <option value="Anthem Kebanggaan" class="bg-smeck-dark-900">Anthem Kebanggaan</option>
                        <option value="Pembakar Semangat" class="bg-smeck-dark-900">Pembakar Semangat</option>
                        <option value="Chant Kebangsaan" class="bg-smeck-dark-900">Chant Kebangsaan</option>
                        <option value="Chant Awaydays" class="bg-smeck-dark-900">Chant Awaydays</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Durasi Lagu (Estimasi)</label>
                    <input type="text" name="duration" placeholder="Contoh: 03:15" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-xs focus:border-red-400 focus:outline-none">
                </div>
                <div class="lg:col-span-2">
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Link YouTube / Audio (Opsional)</label>
                    <input type="url" name="youtube_url" placeholder="https://www.youtube.com/watch?v=..." class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-xs focus:border-red-400 focus:outline-none">
                </div>
                <div class="lg:col-span-4">
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Lirik Lagu Chant <span class="text-red-400">*</span></label>
                    <textarea name="lyrics" rows="4" required placeholder="Tuliskan lirik lengkap baris per baris..." class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-xs font-mono focus:border-red-400 focus:outline-none"></textarea>
                </div>
            </div>

            <div class="flex items-center justify-between pt-2">
                <label class="flex items-center gap-2 cursor-pointer text-xs text-white/70">
                    <input type="checkbox" name="is_featured" value="1" class="rounded bg-white/10 border-white/20 text-red-600 focus:ring-0">
                    <span>Tampilkan sebagai Chant Pilihan di Landing Page</span>
                </label>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-500 text-white font-bold text-xs shadow-lg transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i>
                    <span>Simpan Chant Baru</span>
                </button>
            </div>
        </form>
    </div>

    {{-- Daftar Chant Tersimpan --}}
    <div class="rounded-3xl bg-smeck-dark-900 border border-white/10 overflow-hidden shadow-2xl">
        <div class="p-6 border-b border-white/10 flex items-center justify-between">
            <div>
                <h3 class="font-display font-bold text-lg text-white">Koleksi Pustaka Chant</h3>
                <p class="text-xs text-white/40">Seluruh lagu suporter tersimpan di database</p>
            </div>
            <span class="text-xs text-white/50 font-mono">{{ $chants->total() }} Lagu</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-white/5 border-b border-white/10 text-white/40 uppercase tracking-wider text-[11px]">
                        <th class="py-4 px-5">Judul Lagu</th>
                        <th class="py-4 px-5">Kategori</th>
                        <th class="py-4 px-5">Cuplikan Lirik</th>
                        <th class="py-4 px-5">Durasi & Media</th>
                        <th class="py-4 px-5 text-center">Featured Landing</th>
                        <th class="py-4 px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($chants as $ch)
                        <tr class="hover:bg-white/[0.02] transition-colors">
                            <td class="py-4 px-5 font-bold text-white text-sm">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg bg-red-500/20 text-red-400 flex items-center justify-center text-xs">
                                        <i class="fa-solid fa-compact-disc"></i>
                                    </div>
                                    <span>{{ $ch->title }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-5">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-white/10 text-white/80">
                                    {{ $ch->category }}
                                </span>
                            </td>
                            <td class="py-4 px-5 max-w-xs truncate text-white/60 font-mono text-[11px]">
                                {{ Str::limit($ch->lyrics, 60) }}
                            </td>
                            <td class="py-4 px-5">
                                <div class="text-white/80 font-mono text-[11px]">{{ $ch->duration ?? '02:30' }}</div>
                                @if($ch->youtube_url)
                                    <a href="{{ $ch->youtube_url }}" target="_blank" class="text-red-400 hover:underline text-[10px] flex items-center gap-1 mt-0.5">
                                        <i class="fa-brands fa-youtube"></i> Tonton YouTube
                                    </a>
                                @endif
                            </td>
                            <td class="py-4 px-5 text-center">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $ch->is_featured ? 'bg-smeck-green-500/20 text-smeck-green-400' : 'bg-white/10 text-white/40' }}">
                                    {{ $ch->is_featured ? 'FEATURED' : 'STANDAR' }}
                                </span>
                            </td>
                            <td class="py-4 px-5 text-right">
                                <form action="{{ route('superadmin.chants.featured', $ch->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-2.5 py-1 rounded-lg {{ $ch->is_featured ? 'bg-amber-500/10 hover:bg-amber-500 text-amber-400 hover:text-white' : 'bg-smeck-green-500/10 hover:bg-smeck-green-500 text-smeck-green-400 hover:text-white' }} font-semibold text-[11px] transition-colors">
                                        {{ $ch->is_featured ? 'Hapus Featured' : 'Jadikan Featured' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-white/40 text-xs">
                                Belum ada chant yang didaftarkan. Gunakan form di atas untuk menambahkan chant.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($chants->hasPages())
            <div class="p-4 border-t border-white/5">
                {{ $chants->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
