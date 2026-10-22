@extends('layouts.guest')

@section('title', $article->title . ' — SMeCK Hooligan')
@section('meta_description', $article->summary ?? 'Rilis resmi SMeCK Hooligan suporter PSMS Medan.')

@section('content')
<article class="min-h-screen pt-24 pb-20 bg-smeck-dark-950 px-4 sm:px-6 lg:px-8">
    {{-- Glow background --}}
    <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-96 h-96 bg-smeck-green-600/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-4xl mx-auto relative z-10 space-y-8">

        {{-- Breadcrumb & Back --}}
        <div class="flex items-center justify-between gap-4 pt-4">
            <a href="{{ url('/') }}#card-berita" class="inline-flex items-center gap-2 text-xs text-white/60 hover:text-smeck-green-400 transition-colors">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Kembali ke Beranda</span>
            </a>

            <div class="flex items-center gap-2 text-xs">
                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                    @if($article->category === 'Maklumat DPP') bg-amber-500/20 text-amber-300 border border-amber-500/30
                    @elseif($article->category === 'Info Pertandingan') bg-emerald-500/20 text-emerald-300 border border-emerald-500/30
                    @elseif($article->category === 'Aksi Sosial') bg-blue-500/20 text-blue-300 border border-blue-500/30
                    @else bg-purple-500/20 text-purple-300 border border-purple-500/30 @endif">
                    {{ $article->category }}
                </span>
                @if($article->is_featured)
                    <span class="px-2.5 py-1 rounded-full bg-amber-500/20 text-amber-400 border border-amber-500/30 text-[10px] font-bold flex items-center gap-1">
                        <i class="fa-solid fa-star text-[9px]"></i> Headline
                    </span>
                @endif
            </div>
        </div>

        {{-- Main Article Card --}}
        <div class="p-6 sm:p-10 lg:p-12 rounded-3xl bg-smeck-dark-900 border border-white/10 shadow-2xl relative overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-smeck-green-500 via-smeck-gold to-smeck-green-400"></div>

            {{-- Title --}}
            <h1 class="font-display font-black text-2xl sm:text-3xl lg:text-4xl text-white leading-tight mb-4">
                {{ $article->title }}
            </h1>

            {{-- Metadata Row --}}
            <div class="flex flex-wrap items-center gap-4 text-xs text-white/50 pb-6 border-b border-white/10 mb-8">
                <div class="flex items-center gap-1.5 text-white/80">
                    <i class="fa-regular fa-user text-emerald-400"></i>
                    <span class="font-semibold">{{ $article->author_name }}</span>
                </div>
                <span>•</span>
                <div class="flex items-center gap-1.5">
                    <i class="fa-regular fa-calendar"></i>
                    <span>{{ $article->published_at ? $article->published_at->format('d F Y, H:i') : $article->created_at->format('d F Y') }} WIB</span>
                </div>
                <span>•</span>
                <div class="flex items-center gap-1.5 font-mono text-cyan-400">
                    <i class="fa-solid fa-eye text-[11px]"></i>
                    <span>{{ number_format($article->views_count) }} kali dibaca</span>
                </div>
            </div>

            {{-- Featured Image --}}
            @if($article->image_url)
                <div class="w-full rounded-2xl overflow-hidden mb-8 border border-white/10 shadow-xl bg-black/40">
                    <img src="{{ $article->image_url }}" alt="{{ $article->title }}" class="w-full max-h-[460px] object-cover">
                </div>
            @endif

            {{-- Summary Lead --}}
            @if($article->summary)
                <div class="p-4 sm:p-5 rounded-2xl bg-white/[0.03] border-l-4 border-smeck-green-500 text-white/90 text-sm sm:text-base font-medium leading-relaxed mb-8">
                    {{ $article->summary }}
                </div>
            @endif

            {{-- Article Body --}}
            <div class="prose prose-invert max-w-none text-white/80 leading-relaxed text-sm sm:text-base space-y-4">
                {!! nl2br(e($article->content)) !!}
            </div>

            {{-- Share to WhatsApp & Social Section --}}
            @php
                $shareText = urlencode("📢 *" . $article->title . "*\n\n" . ($article->summary ?? '') . "\n\nBaca selengkapnya di Portal Resmi SMeCK Hooligan:\n" . url()->current());
                $waShareUrl = "https://wa.me/?text={$shareText}";
            @endphp
            <div class="mt-12 pt-8 border-t border-white/10 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-xs text-white/60 text-center sm:text-left">
                    <div class="font-bold text-white text-sm">Bagikan Rilis Pers Ini:</div>
                    <span>Dukung perjuangan PSMS Medan dengan menyebarkan informasi resmi.</span>
                </div>

                <div class="flex items-center gap-3 w-full sm:w-auto">
                    {{-- 1-Click WhatsApp Share (100% Free) --}}
                    <a href="{{ $waShareUrl }}" target="_blank"
                        class="flex-1 sm:flex-none px-5 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs flex items-center justify-center gap-2 shadow-lg shadow-emerald-950/40 transition-all cursor-pointer">
                        <i class="fa-brands fa-whatsapp text-base"></i>
                        <span>Share ke WhatsApp</span>
                    </a>

                    <button onclick="navigator.clipboard.writeText(window.location.href); alert('Tautan berita berhasil disalin!');"
                        class="px-4 py-3 rounded-xl bg-white/5 hover:bg-white/10 text-white/80 text-xs font-bold flex items-center gap-2 transition-colors border border-white/10">
                        <i class="fa-regular fa-copy"></i>
                        <span>Salin Link</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Related News --}}
        @if(isset($relatedNews) && $relatedNews->isNotEmpty())
            <div class="pt-6">
                <h3 class="font-display font-bold text-lg text-white mb-4">Berita & Maklumat Lainnya</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($relatedNews as $rel)
                        <a href="{{ route('news.show', $rel->slug) }}" class="p-4 rounded-2xl bg-smeck-dark-900 border border-white/10 hover:border-smeck-green-500/40 transition-all block group">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-smeck-green-400 block mb-1">
                                {{ $rel->category }}
                            </span>
                            <h4 class="font-bold text-white text-xs group-hover:text-smeck-green-300 transition-colors line-clamp-2 mb-2">
                                {{ $rel->title }}
                            </h4>
                            <div class="text-[10px] text-white/40">
                                {{ $rel->published_at ? $rel->published_at->format('d M Y') : '-' }}
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</article>
@endsection
