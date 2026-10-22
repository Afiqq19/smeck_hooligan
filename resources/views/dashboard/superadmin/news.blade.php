@extends('layouts.app')

@section('title', 'Manajemen Berita & Maklumat Resmi')
@section('header_title', 'Manajemen Berita & Maklumat Resmi')
@section('header_subtitle', 'Publikasikan rilis pers resmi suporter, kabar PSMS Medan, dan himbauan komando tribun ke landing page')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm flex items-center justify-between">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-circle-check text-lg"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-400/60 hover:text-emerald-400">&times;</button>
        </div>
    @endif

    {{-- Top Metrics Row --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Card 1: Total Berita --}}
        <div class="p-5 rounded-2xl bg-smeck-dark-900 border border-emerald-500/30 relative overflow-hidden">
            <div class="absolute -right-4 -bottom-4 w-20 h-20 bg-emerald-500/10 rounded-full blur-xl pointer-events-none"></div>
            <div class="flex items-center justify-between text-xs text-white/50 mb-1">
                <span>Total Rilis Artikel</span>
                <i class="fa-solid fa-newspaper text-emerald-400"></i>
            </div>
            <div class="text-3xl font-display font-black text-white flex items-baseline gap-2">
                <span>{{ $totalArticles }}</span>
                <span class="text-xs font-normal text-white/40">Artikel</span>
            </div>
            <div class="text-[11px] text-emerald-400/80 mt-1">Portal Berita SMeCK Resmi</div>
        </div>

        {{-- Card 2: Terbit Aktif --}}
        <div class="p-5 rounded-2xl bg-smeck-dark-900 border border-white/10">
            <div class="flex items-center justify-between text-xs text-white/50 mb-1">
                <span>Status Terbit (Live)</span>
                <i class="fa-solid fa-globe text-smeck-gold"></i>
            </div>
            <div class="text-3xl font-display font-black text-emerald-400 flex items-baseline gap-2">
                <span>{{ $publishedCount }}</span>
                <span class="text-xs font-normal text-white/40">Tayang di Web</span>
            </div>
            <div class="text-[11px] text-white/50 mt-1">Dapat dibaca oleh suporter</div>
        </div>

        {{-- Card 3: Berita Unggulan --}}
        <div class="p-5 rounded-2xl bg-smeck-dark-900 border border-white/10">
            <div class="flex items-center justify-between text-xs text-white/50 mb-1">
                <span>Berita Utama (Featured)</span>
                <i class="fa-solid fa-star text-amber-400"></i>
            </div>
            <div class="text-3xl font-display font-black text-amber-400 flex items-baseline gap-2">
                <span>{{ $featuredCount }}</span>
                <span class="text-xs font-normal text-white/40">Headline</span>
            </div>
            <div class="text-[11px] text-white/50 mt-1">Diprioritaskan di Landing Page</div>
        </div>

        {{-- Card 4: Total Pembaca --}}
        <div class="p-5 rounded-2xl bg-smeck-dark-900 border border-white/10">
            <div class="flex items-center justify-between text-xs text-white/50 mb-1">
                <span>Total Pembaca (Views)</span>
                <i class="fa-solid fa-eye text-cyan-400"></i>
            </div>
            <div class="text-3xl font-display font-black text-cyan-400 flex items-baseline gap-2">
                <span>{{ number_format($totalViews) }}</span>
                <span class="text-xs font-normal text-white/40">Kali Dibaca</span>
            </div>
            <div class="text-[11px] text-white/50 mt-1">Jangkauan suporter & publik</div>
        </div>
    </div>

    {{-- Action Bar & Filters --}}
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
        <form method="GET" action="{{ route('superadmin.news') }}" class="flex flex-wrap items-center gap-2.5 flex-1 max-w-2xl">
            <div class="relative flex-1 min-w-[200px]">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-white/30 text-xs"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul, topik, atau penulis..."
                    class="w-full pl-9 pr-3 py-2 bg-smeck-dark-900 border border-white/10 rounded-xl text-xs text-white placeholder-white/40 focus:outline-none focus:border-emerald-500">
            </div>

            <select name="category" onchange="this.form.submit()"
                class="px-3 py-2 bg-smeck-dark-900 border border-white/10 rounded-xl text-xs text-white/80 focus:outline-none focus:border-emerald-500">
                <option value="">Semua Kategori</option>
                <option value="Maklumat DPP" {{ request('category') == 'Maklumat DPP' ? 'selected' : '' }}>Maklumat Resmi</option>
                <option value="Info Pertandingan" {{ request('category') == 'Info Pertandingan' ? 'selected' : '' }}>Info Pertandingan</option>
                <option value="Aksi Sosial" {{ request('category') == 'Aksi Sosial' ? 'selected' : '' }}>Aksi Sosial</option>
                <option value="Liputan Basis" {{ request('category') == 'Liputan Basis' ? 'selected' : '' }}>Liputan Basis</option>
            </select>

            @if(request('search') || request('category'))
                <a href="{{ route('superadmin.news') }}" class="px-3 py-2 rounded-xl bg-white/5 hover:bg-white/10 text-white/60 text-xs flex items-center gap-1">
                    <i class="fa-solid fa-xmark"></i> Reset
                </a>
            @endif
        </form>

        <button onclick="openCreateModal()" class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-emerald-600 to-emerald-500 hover:from-emerald-500 hover:to-emerald-400 text-white font-bold text-xs flex items-center justify-center gap-2 shadow-lg shadow-emerald-950/40 cursor-pointer">
            <i class="fa-solid fa-plus"></i>
            <span>Tulis Berita / Maklumat Baru</span>
        </button>
    </div>

    {{-- Articles List Table --}}
    <div class="p-6 sm:p-8 rounded-3xl bg-smeck-dark-900 border border-white/10 shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-white/10 text-white/40 uppercase tracking-wider text-[11px]">
                        <th class="py-3 px-3">Artikel & Gambar</th>
                        <th class="py-3 px-3">Kategori</th>
                        <th class="py-3 px-3">Penulis</th>
                        <th class="py-3 px-3 text-center">Pembaca</th>
                        <th class="py-3 px-3 text-center">Featured</th>
                        <th class="py-3 px-3 text-center">Status</th>
                        <th class="py-3 px-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($articles as $item)
                        <tr class="hover:bg-white/[0.02] transition-colors">
                            {{-- Info & Thumbnail --}}
                            <td class="py-4 px-3">
                                <div class="flex items-start gap-3">
                                    <div class="w-16 h-12 rounded-lg overflow-hidden bg-black/40 border border-white/10 shrink-0">
                                        <img src="{{ $item->image_url ?? 'https://images.unsplash.com/photo-1508098682722-e99c43a406b2?auto=format&fit=crop&w=300&q=80' }}"
                                            alt="{{ $item->title }}" class="w-full h-full object-cover">
                                    </div>
                                    <div class="min-w-0 max-w-md">
                                        <div class="font-bold text-white text-sm hover:text-emerald-400 transition-colors line-clamp-1">
                                            {{ $item->title }}
                                        </div>
                                        <div class="text-[11px] text-white/50 line-clamp-1 mt-0.5">
                                            {{ $item->summary }}
                                        </div>
                                        <div class="text-[10px] text-white/30 font-mono mt-1">
                                            {{ $item->published_at ? $item->published_at->format('d M Y, H:i') : '-' }} • Slug: /berita/{{ $item->slug }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Kategori --}}
                            <td class="py-4 px-3">
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider
                                    @if($item->category === 'Maklumat DPP') bg-amber-500/20 text-amber-300 border border-amber-500/30
                                    @elseif($item->category === 'Info Pertandingan') bg-emerald-500/20 text-emerald-300 border border-emerald-500/30
                                    @elseif($item->category === 'Aksi Sosial') bg-blue-500/20 text-blue-300 border border-blue-500/30
                                    @else bg-purple-500/20 text-purple-300 border border-purple-500/30 @endif">
                                    {{ $item->category === 'Maklumat DPP' ? 'Maklumat Resmi' : $item->category }}
                                </span>
                            </td>

                            {{-- Penulis --}}
                            <td class="py-4 px-3 text-white/80">
                                <div class="font-semibold text-xs">{{ $item->author_name }}</div>
                            </td>

                            {{-- Views Count --}}
                            <td class="py-4 px-3 text-center">
                                <span class="font-mono text-cyan-400 font-bold text-xs flex items-center justify-center gap-1">
                                    <i class="fa-solid fa-eye text-[10px]"></i>
                                    {{ number_format($item->views_count) }}
                                </span>
                            </td>

                            {{-- Featured Toggle --}}
                            <td class="py-4 px-3 text-center">
                                <form method="POST" action="{{ route('superadmin.news.featured', $item->id) }}">
                                    @csrf
                                    <button type="submit" title="Klik untuk ubah status headline"
                                        class="w-7 h-7 rounded-lg flex items-center justify-center mx-auto transition-all cursor-pointer {{ $item->is_featured ? 'bg-amber-500/20 text-amber-400 border border-amber-500/30 shadow-md shadow-amber-500/10' : 'bg-white/5 text-white/30 hover:text-white/60' }}">
                                        <i class="fa-solid fa-star text-xs"></i>
                                    </button>
                                </form>
                            </td>

                            {{-- Status Published --}}
                            <td class="py-4 px-3 text-center">
                                <form method="POST" action="{{ route('superadmin.news.toggle', $item->id) }}">
                                    @csrf
                                    <button type="submit" class="px-2.5 py-1 rounded-full text-[10px] font-bold border transition-colors cursor-pointer {{ $item->is_published ? 'bg-emerald-500/15 border-emerald-500/30 text-emerald-300 hover:bg-emerald-500/25' : 'bg-white/5 border-white/10 text-white/40 hover:bg-white/10' }}">
                                        {{ $item->is_published ? 'Tayang' : 'Draft' }}
                                    </button>
                                </form>
                            </td>

                            {{-- Aksi --}}
                            <td class="py-4 px-3 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('news.show', $item->slug) }}" target="_blank" title="Lihat di Web"
                                        class="w-7 h-7 rounded-lg bg-white/5 hover:bg-white/10 text-white/70 hover:text-white flex items-center justify-center transition-colors">
                                        <i class="fa-solid fa-arrow-up-right-from-square text-[11px]"></i>
                                    </a>

                                    <button type="button" onclick='openEditModal(@json($item))' title="Edit Berita"
                                        class="w-7 h-7 rounded-lg bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 flex items-center justify-center transition-colors cursor-pointer">
                                        <i class="fa-solid fa-pen-to-square text-[11px]"></i>
                                    </button>

                                    <form method="POST" action="{{ route('superadmin.news.delete', $item->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus berita ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus Berita"
                                            class="w-7 h-7 rounded-lg bg-red-500/10 hover:bg-red-500/20 text-red-400 flex items-center justify-center transition-colors cursor-pointer">
                                            <i class="fa-solid fa-trash-can text-[11px]"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-white/40">
                                <i class="fa-solid fa-newspaper text-3xl mb-2 opacity-50 block"></i>
                                Belum ada artikel atau rilis pers yang ditambahkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $articles->links() }}
        </div>
    </div>
</div>

{{-- MODAL CREATE / EDIT NEWS --}}
<div id="modalNewsForm" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm hidden items-center justify-center p-4 overflow-y-auto">
    <div class="relative w-full max-w-2xl bg-smeck-dark-900 border border-white/15 rounded-3xl p-6 sm:p-8 shadow-2xl my-8">
        <div class="flex items-center justify-between pb-4 border-b border-white/10 mb-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-pen-nib"></i>
                </div>
                <div>
                    <h3 id="modalTitle" class="font-display font-bold text-lg text-white">Tulis Berita / Maklumat Baru</h3>
                    <p class="text-xs text-white/50">Rilis pers akan langsung dapat dibaca oleh ribuan suporter di landing page.</p>
                </div>
            </div>
            <button onclick="closeNewsModal()" class="w-8 h-8 rounded-lg bg-white/5 hover:bg-white/10 text-white/60 hover:text-white flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form method="POST" action="{{ route('superadmin.news.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <input type="hidden" name="id" id="newsId">

            {{-- Judul Berita --}}
            <div>
                <label class="block text-xs font-semibold text-white/70 mb-1.5">Judul Berita / Maklumat <span class="text-emerald-400">*</span></label>
                <input type="text" name="title" id="newsTitleInput" required placeholder="Contoh: Maklumat Resmi SMeCK Jelang Laga Derby"
                    class="w-full px-3.5 py-2.5 bg-black/40 border border-white/10 rounded-xl text-xs text-white placeholder-white/40 focus:outline-none focus:border-emerald-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Kategori --}}
                <div>
                    <label class="block text-xs font-semibold text-white/70 mb-1.5">Kategori Rilis <span class="text-emerald-400">*</span></label>
                    <select name="category" id="newsCategoryInput" required
                        class="w-full px-3.5 py-2.5 bg-black/40 border border-white/10 rounded-xl text-xs text-white focus:outline-none focus:border-emerald-500">
                        <option value="Maklumat DPP">Maklumat Resmi</option>
                        <option value="Info Pertandingan">Info Pertandingan</option>
                        <option value="Aksi Sosial">Aksi Sosial</option>
                        <option value="Liputan Basis">Liputan Basis</option>
                        <option value="Warta PSMS">Warta PSMS</option>
                    </select>
                </div>

                {{-- Penulis --}}
                <div>
                    <label class="block text-xs font-semibold text-white/70 mb-1.5">Nama Penulis / Rilis Oleh <span class="text-emerald-400">*</span></label>
                    <input type="text" name="author_name" id="newsAuthorInput" value="Humas SMeCK Hooligan" required
                        class="w-full px-3.5 py-2.5 bg-black/40 border border-white/10 rounded-xl text-xs text-white placeholder-white/40 focus:outline-none focus:border-emerald-500">
                </div>
            </div>

            {{-- Ringkasan --}}
            <div>
                <label class="block text-xs font-semibold text-white/70 mb-1.5">Ringkasan Singkat (Lead Paragraph)</label>
                <textarea name="summary" id="newsSummaryInput" rows="2" placeholder="Satu atau dua kalimat pembuka untuk preview di kartu berita..."
                    class="w-full px-3.5 py-2.5 bg-black/40 border border-white/10 rounded-xl text-xs text-white placeholder-white/40 focus:outline-none focus:border-emerald-500"></textarea>
            </div>

            {{-- Isi Berita Lengkap --}}
            <div>
                <label class="block text-xs font-semibold text-white/70 mb-1.5">Isi Lengkap Berita / Maklumat <span class="text-emerald-400">*</span></label>
                <textarea name="content" id="newsContentInput" rows="7" required placeholder="Tuliskan isi berita, pengumuman, atau arahan komando di sini..."
                    class="w-full px-3.5 py-2.5 bg-black/40 border border-white/10 rounded-xl text-xs text-white placeholder-white/40 focus:outline-none focus:border-emerald-500 font-mono"></textarea>
            </div>

            {{-- Gambar Header --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-white/70 mb-1.5">URL Gambar (Opsional)</label>
                    <input type="url" name="image_url" id="newsImageUrlInput" placeholder="https://example.com/foto.jpg"
                        class="w-full px-3.5 py-2.5 bg-black/40 border border-white/10 rounded-xl text-xs text-white placeholder-white/40 focus:outline-none focus:border-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-white/70 mb-1.5">Atau Upload Gambar File</label>
                    <input type="file" name="image_file" accept="image/*"
                        class="w-full px-3 py-2 bg-black/40 border border-white/10 rounded-xl text-xs text-white/70 file:mr-3 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-emerald-500/20 file:text-emerald-300">
                </div>
            </div>

            {{-- Checklist Options --}}
            <div class="pt-2 flex flex-wrap items-center gap-6 border-t border-white/10">
                <label class="flex items-center gap-2 cursor-pointer text-xs text-white/80">
                    <input type="checkbox" name="is_featured" id="newsFeaturedInput" value="1" class="rounded text-emerald-500 focus:ring-emerald-500">
                    <span>Tandai Sebagai Berita Utama (Featured Headline)</span>
                </label>

                <label class="flex items-center gap-2 cursor-pointer text-xs text-white/80">
                    <input type="checkbox" name="is_published" id="newsPublishedInput" value="1" checked class="rounded text-emerald-500 focus:ring-emerald-500">
                    <span>Langsung Terbitkan (Publish Live)</span>
                </label>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-white/10">
                <button type="button" onclick="closeNewsModal()" class="px-4 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 text-white/70 text-xs font-bold transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs flex items-center gap-2 shadow-lg shadow-emerald-950/40">
                    <i class="fa-solid fa-paper-plane text-xs"></i>
                    <span>Simpan & Tayangkan</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openCreateModal() {
        document.getElementById('modalTitle').innerText = 'Tulis Berita / Maklumat Baru';
        document.getElementById('newsId').value = '';
        document.getElementById('newsTitleInput').value = '';
        document.getElementById('newsCategoryInput').value = 'Maklumat DPP';
        document.getElementById('newsAuthorInput').value = 'Humas SMeCK Hooligan';
        document.getElementById('newsSummaryInput').value = '';
        document.getElementById('newsContentInput').value = '';
        document.getElementById('newsImageUrlInput').value = '';
        document.getElementById('newsFeaturedInput').checked = false;
        document.getElementById('newsPublishedInput').checked = true;

        const modal = document.getElementById('modalNewsForm');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function openEditModal(item) {
        document.getElementById('modalTitle').innerText = 'Edit Berita: ' + item.title;
        document.getElementById('newsId').value = item.id;
        document.getElementById('newsTitleInput').value = item.title;
        document.getElementById('newsCategoryInput').value = item.category;
        document.getElementById('newsAuthorInput').value = item.author_name;
        document.getElementById('newsSummaryInput').value = item.summary || '';
        document.getElementById('newsContentInput').value = item.content;
        document.getElementById('newsImageUrlInput').value = item.image_url || '';
        document.getElementById('newsFeaturedInput').checked = item.is_featured ? true : false;
        document.getElementById('newsPublishedInput').checked = item.is_published ? true : false;

        const modal = document.getElementById('modalNewsForm');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeNewsModal() {
        const modal = document.getElementById('modalNewsForm');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
@endsection
