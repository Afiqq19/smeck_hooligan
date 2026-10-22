@extends('layouts.app')

@section('title', 'Broadcast Pengumuman DPP')
@section('header_title', 'Broadcast Maklumat & Pengumuman Resmi DPP')
@section('header_subtitle', 'Kirim instruksi tribun, maklumat organisasi, dan rilis jadwal langsung ke dashboard seluruh anggota dan portal resmi')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">

    {{-- Top Statistics Summary --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="p-6 rounded-3xl bg-smeck-dark-900 border border-red-500/30 shadow-xl">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-white/50 uppercase">Total Maklumat Disiarkan</span>
                <div class="w-8 h-8 rounded-lg bg-red-500/20 text-red-400 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-bullhorn"></i>
                </div>
            </div>
            <div class="font-display font-black text-3xl text-white mt-2">{{ $totalBroadcasts ?? $recentBroadcasts->total() }}</div>
            <div class="text-[11px] text-red-400 mt-1 font-medium">Instruksi resmi DPP SMeCK Hooligan</div>
        </div>

        <div class="p-6 rounded-3xl bg-smeck-dark-900 border border-white/10 shadow-xl">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-white/50 uppercase">Jangkauan Notifikasi</span>
                <div class="w-8 h-8 rounded-lg bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
            <div class="font-display font-black text-3xl text-emerald-400 mt-2">Seluruh Anggota</div>
            <div class="text-[11px] text-white/50 mt-1 font-medium">Otomatis tampil di portal dashboard anggota</div>
        </div>

        <div class="p-6 rounded-3xl bg-smeck-dark-900 border border-white/10 shadow-xl">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-white/50 uppercase">Status Integrasi</span>
                <div class="w-8 h-8 rounded-lg bg-smeck-gold/20 text-smeck-gold flex items-center justify-center text-sm">
                    <i class="fa-solid fa-globe"></i>
                </div>
            </div>
            <div class="font-display font-black text-2xl text-smeck-gold mt-2">Web & Portal Member</div>
            <div class="text-[11px] text-white/50 mt-1 font-medium">Tersinkronisasi otomatis sebagai Berita Utama</div>
        </div>
    </div>

    {{-- Formulir Kirim Broadcast --}}
    <div class="p-6 sm:p-8 rounded-3xl bg-smeck-dark-900 border border-white/10 shadow-xl">
        <h3 class="font-display font-bold text-lg text-white mb-1.5 flex items-center gap-2">
            <i class="fa-solid fa-paper-plane text-red-500"></i>
            <span>Formulir Siaran Maklumat Baru DPP Pusat</span>
        </h3>
        <p class="text-xs text-white/50 mb-6">Pesan ini akan langsung disimpan ke database nasional, disiarkan ke portal anggota, dan diprioritaskan sebagai Maklumat DPP di web resmi.</p>

        <form action="{{ route('superadmin.broadcast.send') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Judul Maklumat / Instruksi Resmi <span class="text-red-400">*</span></label>
                    <input type="text" name="title" required placeholder="Contoh: Dresscode Hijau Total & Aturan Koreografi Derby Sumatera" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-red-400 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Target Penerima <span class="text-red-400">*</span></label>
                    <select name="target_chapter" required class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-red-400 focus:outline-none">
                        <option value="all" class="bg-smeck-dark-900">Seluruh Anggota Nasional (Semua Basis)</option>
                        <option value="sumut" class="bg-smeck-dark-900">Khusus Wilayah Sumatera Utara</option>
                        <option value="perantau" class="bg-smeck-dark-900">Khusus Chapter Perantauan / Luar Kota</option>
                    </select>
                </div>

                <div class="md:col-span-3">
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Isi Maklumat / Pesan Instruksi Lengkap <span class="text-red-400">*</span></label>
                    <textarea name="message" rows="6" required placeholder="Tuliskan isi instruksi, tata tertib tribun, atau maklumat resmi dari DPP SMeCK Hooligan..." class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-red-400 focus:outline-none font-sans"></textarea>
                </div>
            </div>

            <div class="pt-2 flex items-center justify-between">
                <div class="text-[11px] text-white/40 flex items-center gap-1.5">
                    <i class="fa-solid fa-circle-info text-smeck-gold"></i>
                    <span>Maklumat otomatis tayang dan berstatus featured unggulan.</span>
                </div>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-red-600 hover:bg-red-500 text-white font-bold text-xs shadow-lg transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-paper-plane"></i>
                    <span>Siarkan Maklumat Sekarang</span>
                </button>
            </div>
        </form>
    </div>

    {{-- Tabel Riwayat Broadcast Maklumat DPP --}}
    <div class="p-6 sm:p-8 rounded-3xl bg-smeck-dark-900 border border-white/10 shadow-xl">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="font-display font-bold text-lg text-white">Riwayat Maklumat & Siaran DPP</h3>
                <p class="text-xs text-white/40">Daftar maklumat resmi yang telah disiarkan ke portal suporter</p>
            </div>
            <span class="text-xs text-white/50 font-mono">{{ $recentBroadcasts->total() }} Maklumat Tercatat</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-white/5 border-b border-white/10 text-white/40 uppercase tracking-wider text-[11px]">
                        <th class="py-3.5 px-4">Judul Maklumat</th>
                        <th class="py-3.5 px-4">Kategori & Penulis</th>
                        <th class="py-3.5 px-4">Tanggal Siar</th>
                        <th class="py-3.5 px-4">Pembaca</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($recentBroadcasts as $bc)
                        <tr class="hover:bg-white/[0.02] transition-colors">
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-white text-sm">{{ $bc->title }}</div>
                                <div class="text-[11px] text-white/50 mt-0.5 line-clamp-1 max-w-md">{{ $bc->summary }}</div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-red-500/20 text-red-300">
                                    {{ $bc->category }}
                                </span>
                                <div class="text-[10px] text-white/40 mt-1">{{ $bc->author_name }}</div>
                            </td>
                            <td class="py-3.5 px-4 text-white/70 font-mono text-[11px]">
                                {{ $bc->published_at ? $bc->published_at->format('d M Y, H:i') : $bc->created_at->format('d M Y, H:i') }} WIB
                            </td>
                            <td class="py-3.5 px-4 font-mono font-bold text-cyan-400">
                                {{ number_format($bc->views_count) }} <span class="font-normal text-white/40 text-[10px]">views</span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-smeck-green-500/20 text-smeck-green-300 border border-smeck-green-500/30">
                                    Live
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('news.show', $bc->slug) }}" target="_blank" class="px-2.5 py-1 rounded-lg bg-white/5 hover:bg-white/15 text-white/70 hover:text-white font-semibold text-[11px] transition-colors inline-flex items-center gap-1">
                                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                        <span>Lihat</span>
                                    </a>
                                    <form action="{{ route('superadmin.news.delete', $bc->id) }}" method="POST" onsubmit="return confirm('Hapus maklumat ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1 rounded-lg bg-red-500/10 hover:bg-red-500 text-red-400 hover:text-white font-semibold text-[11px] transition-colors">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-white/40">Belum ada maklumat broadcast yang disiarkan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($recentBroadcasts->hasPages())
            <div class="p-4 border-t border-white/5">
                {{ $recentBroadcasts->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
