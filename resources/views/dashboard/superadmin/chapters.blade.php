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
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-right">Aksi & Direktori</th>
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
                            <td class="py-3.5 px-4 text-center">
                                @if($c->is_active ?? true)
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-smeck-green-500/20 text-smeck-green-300 border border-smeck-green-500/30">
                                        Aktif
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-red-500/20 text-red-400 border border-red-500/30">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    {{-- Tombol Edit Chapter --}}
                                    <button type="button" onclick='openEditChapterModal(@json($c))' class="px-2.5 py-1.5 rounded-xl bg-amber-500/10 hover:bg-amber-500 text-amber-400 hover:text-white border border-amber-500/20 font-semibold text-[11px] transition-colors inline-flex items-center gap-1" title="Edit Data Chapter">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                        <span>Edit</span>
                                    </button>

                                    {{-- Tombol Lihat Anggota --}}
                                    <a href="{{ route('superadmin.members', ['chapter_id' => $c->id]) }}" class="px-2.5 py-1.5 rounded-xl bg-white/5 hover:bg-white/15 text-white/80 hover:text-white font-semibold text-[11px] transition-colors inline-flex items-center gap-1" title="Lihat Anggota Basis Ini">
                                        <i class="fa-solid fa-users text-[10px]"></i>
                                        <span>Anggota</span>
                                    </a>

                                    {{-- Tombol Hapus Chapter --}}
                                    <form action="{{ route('superadmin.chapters.delete', $c->id) }}" method="POST" onsubmit="return confirm('Hapus atau nonaktifkan Basis / Chapter {{ addslashes($c->name) }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-xl bg-red-500/10 hover:bg-red-500 text-red-400 hover:text-white border border-red-500/20 transition-colors" title="Hapus Chapter">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
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

{{-- MODAL EDIT BASIS / CHAPTER --}}
<div id="modalEditChapter" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-md hidden flex items-center justify-center p-4">
    <div class="bg-smeck-dark-900 border border-white/10 rounded-3xl max-w-xl w-full p-6 sm:p-8 shadow-2xl relative">
        <button onclick="closeEditChapterModal()" class="absolute top-6 right-6 text-white/40 hover:text-white text-lg">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <h3 class="font-display font-black text-xl text-white mb-1">Edit Basis / Chapter Wilayah</h3>
        <p class="text-xs text-white/50 mb-6">Perbarui nama chapter, korwil pengurus, kode wilayah, atau alamat basecamp.</p>

        <form id="formEditChapter" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Nama Chapter / Basis <span class="text-red-400">*</span></label>
                <input type="text" name="name" id="editChapterName" required class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-red-400 focus:outline-none">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Kode Wilayah Unik <span class="text-red-400">*</span></label>
                    <input type="text" name="region_code" id="editChapterCode" required class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white font-mono uppercase text-sm focus:border-red-400 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Nama Ketua / Korwil <span class="text-red-400">*</span></label>
                    <input type="text" name="leader_name" id="editChapterLeader" required class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-red-400 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Alamat Basecamp Chapter <span class="text-red-400">*</span></label>
                <input type="text" name="basecamp_address" id="editChapterAddress" required class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-red-400 focus:outline-none">
            </div>

            <div class="pt-2">
                <label class="flex items-center gap-2 cursor-pointer text-xs text-white/80">
                    <input type="checkbox" name="is_active" id="editChapterIsActive" value="1" class="rounded bg-white/10 border-white/20 text-red-600 focus:ring-0">
                    <span>Status Basis Aktif & Beroperasi</span>
                </label>
            </div>

            <div class="pt-4 flex items-center justify-end gap-2">
                <button type="button" onclick="closeEditChapterModal()" class="px-5 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 text-white font-semibold text-xs transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-red-600 hover:bg-red-500 text-white font-bold text-xs shadow-lg transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Simpan Perubahan Basis</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditChapterModal(chapter) {
    document.getElementById('formEditChapter').action = `/superadmin/chapters/${chapter.id}/update`;
    document.getElementById('editChapterName').value = chapter.name || '';
    document.getElementById('editChapterCode').value = chapter.region_code || '';
    document.getElementById('editChapterLeader').value = chapter.leader_name || '';
    document.getElementById('editChapterAddress').value = chapter.basecamp_address || '';
    document.getElementById('editChapterIsActive').checked = chapter.is_active !== false && chapter.is_active !== 0;

    document.getElementById('modalEditChapter').classList.remove('hidden');
}

function closeEditChapterModal() {
    document.getElementById('modalEditChapter').classList.add('hidden');
}
</script>
@endsection
