@extends('layouts.app')

@section('title', 'SMeCK Store')
@section('header_title', 'SMeCK Official Store')
@section('header_subtitle', 'Katalog merchandise resmi suporter PSMS Medan dengan harga diskon khusus pemegang KTA')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    
    {{-- Product Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @forelse($products as $p)
            <div class="p-5 rounded-3xl bg-smeck-dark-900 border border-white/10 hover:border-smeck-green-500/40 transition-all flex flex-col justify-between">
                <div>
                    {{-- Visual Merchandise Box --}}
                    <div class="w-full aspect-square rounded-2xl bg-gradient-to-br from-smeck-green-950 to-smeck-dark-800 border border-white/5 flex items-center justify-center p-6 text-center relative overflow-hidden group">
                        <div class="w-16 h-16 rounded-full bg-smeck-green-500/10 flex items-center justify-center text-smeck-green-400 text-3xl group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-shirt"></i>
                        </div>
                        <span class="absolute top-3 left-3 px-2 py-0.5 rounded text-[9px] font-bold bg-white/10 text-white uppercase">
                            {{ $p->category }}
                        </span>
                        <span class="absolute bottom-3 right-3 text-[10px] text-white/40 font-mono">
                            Stok: {{ $p->stock }}
                        </span>
                    </div>

                    <h4 class="font-display font-bold text-sm text-white mt-4 line-clamp-2">
                        {{ $p->name }}
                    </h4>
                    <p class="text-xs text-white/50 mt-1 line-clamp-2">
                        {{ $p->description }}
                    </p>
                </div>

                <div class="mt-4 pt-3 border-t border-white/10 flex items-center justify-between">
                    <div>
                        <div class="text-[10px] text-smeck-green-400 font-bold uppercase">Harga Member:</div>
                        <div class="text-sm font-bold font-mono text-smeck-gold">
                            Rp {{ number_format($p->member_price ?? $p->price, 0, ',', '.') }}
                        </div>
                        <div class="text-[10px] text-white/40 line-through">
                            Rp {{ number_format($p->price, 0, ',', '.') }}
                        </div>
                    </div>
                    <button type="button" onclick="alert('Pesanan diteruskan ke Admin Official Store SMeCK!')" class="p-2.5 rounded-xl bg-smeck-green-600 hover:bg-smeck-green-500 text-white text-xs transition-colors shadow">
                        <i class="fa-solid fa-cart-plus"></i>
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-4 text-center py-12 text-white/40">Katalog merchandise sedang diperbarui.</div>
        @endforelse
    </div>

</div>
@endsection
