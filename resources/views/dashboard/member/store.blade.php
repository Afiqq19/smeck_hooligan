@extends('layouts.app')

@section('title', 'SMeCK Store')
@section('header_title', 'SMeCK Official Store')
@section('header_subtitle', 'Katalog merchandise resmi suporter PSMS Medan dengan harga diskon khusus pemegang KTA')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    
    {{-- Header Banner: Keuntungan KTA --}}
    <div class="p-6 sm:p-8 rounded-3xl bg-gradient-to-r from-emerald-950 via-smeck-dark-900 to-black border border-smeck-green-500/30 shadow-2xl flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-amber-500/20 text-amber-400 flex items-center justify-center text-3xl shrink-0">
                <i class="fa-solid fa-tags"></i>
            </div>
            <div>
                <span class="px-3 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-500/20 text-amber-300 border border-amber-500/30">
                    Benefit Pemegang KTA
                </span>
                <h2 class="font-display font-black text-xl sm:text-2xl text-white mt-1">Diskon Eksklusif Member Resmi</h2>
                <p class="text-xs text-white/60 mt-0.5 max-w-lg">
                    Tunjukkan KTA atau order online untuk mendapatkan potongan harga spesial pada setiap pembelian jersey, syal, dan merchandise resmi.
                </p>
            </div>
        </div>
        <div class="text-right shrink-0">
            <span class="text-xs font-mono text-white/50 block">Admin Store WhatsApp:</span>
            <span class="font-mono font-bold text-smeck-green-400 text-sm">0812-6000-1950</span>
        </div>
    </div>

    {{-- Product Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @forelse($products as $p)
            @php
                $memberPrice = $p->member_price ?? $p->price;
                $savings = max(0, $p->price - $memberPrice);
                $prodJson = json_encode([
                    'id' => $p->id,
                    'name' => $p->name,
                    'category' => $p->category,
                    'price' => $p->price,
                    'member_price' => $memberPrice,
                    'stock' => $p->stock,
                    'description' => $p->description,
                ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
            @endphp
            <div class="p-5 rounded-3xl bg-smeck-dark-900 border border-white/10 hover:border-smeck-green-500/40 transition-all flex flex-col justify-between shadow-xl">
                <div>
                    {{-- Visual Merchandise Box --}}
                    <div class="w-full aspect-square rounded-2xl bg-gradient-to-br from-smeck-green-950 to-smeck-dark-800 border border-white/5 flex items-center justify-center p-6 text-center relative overflow-hidden group">
                        <div class="w-16 h-16 rounded-full bg-smeck-green-500/10 flex items-center justify-center text-smeck-green-400 text-3xl group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-shirt"></i>
                        </div>
                        <span class="absolute top-3 left-3 px-2 py-0.5 rounded text-[9px] font-bold bg-white/10 text-white uppercase tracking-wider">
                            {{ $p->category }}
                        </span>
                        @if($savings > 0)
                            <span class="absolute top-3 right-3 px-2 py-0.5 rounded text-[9px] font-bold bg-amber-500 text-black uppercase tracking-wider">
                                Hemat Rp {{ number_format($savings, 0, ',', '.') }}
                            </span>
                        @endif
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
                        <div class="text-[10px] text-smeck-green-400 font-bold uppercase">Harga Member KTA:</div>
                        <div class="text-base font-bold font-mono text-smeck-gold">
                            Rp {{ number_format($memberPrice, 0, ',', '.') }}
                        </div>
                        <div class="text-[11px] text-white/40 line-through">
                            Rp {{ number_format($p->price, 0, ',', '.') }}
                        </div>
                    </div>
                    <button type="button" onclick='openOrderStoreModal({!! $prodJson !!})' class="px-3.5 py-2 rounded-xl bg-smeck-green-600 hover:bg-smeck-green-500 text-white text-xs font-bold transition-all shadow flex items-center gap-1.5">
                        <i class="fa-brands fa-whatsapp text-sm"></i>
                        <span>Pesan</span>
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-4 text-center py-12 text-white/40 rounded-3xl bg-smeck-dark-900 border border-white/10">
                <i class="fa-solid fa-store-slash text-3xl mb-2 block text-white/20"></i>
                Katalog merchandise sedang diperbarui oleh Pengurus Pusat.
            </div>
        @endforelse
    </div>

</div>

{{-- ========================================================================= --}}
{{-- MODAL PESAN MERCHANDISE RESMI VIA WHATSAPP STORE --}}
{{-- ========================================================================= --}}
<div id="modalOrderStore" class="fixed inset-0 z-50 bg-black/90 backdrop-blur-md hidden flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-smeck-dark-900 border border-white/10 rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl relative my-8">
        <button onclick="closeOrderStoreModal()" class="absolute top-6 right-6 text-white/40 hover:text-white text-lg">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-xl">
                <i class="fa-brands fa-whatsapp"></i>
            </div>
            <div>
                <h3 class="font-display font-black text-lg text-white">Pesan via WhatsApp Store</h3>
                <p class="text-xs text-white/50">Diskon khusus langsung diverifikasi otomatis via NIA.</p>
            </div>
        </div>

        <div class="space-y-4 text-xs">
            <div class="p-4 rounded-2xl bg-white/[0.03] border border-white/5">
                <div id="modalProdCategory" class="text-[10px] text-smeck-green-400 font-bold uppercase tracking-wider">JERSEY</div>
                <div id="modalProdName" class="font-bold text-white text-base mt-0.5">Nama Produk</div>
                <div class="mt-2 flex items-baseline gap-2">
                    <span id="modalProdMemberPrice" class="font-mono font-bold text-lg text-smeck-gold">Rp 0</span>
                    <span id="modalProdNormalPrice" class="text-white/40 line-through text-xs">Rp 0</span>
                </div>
            </div>

            {{-- Pilih Ukuran --}}
            <div>
                <label class="block font-bold uppercase text-white/60 mb-1.5">Pilih Ukuran Kaos / Jersey <span class="text-red-400">*</span></label>
                <select id="selectProdSize" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-smeck-green-400 focus:outline-none">
                    <option value="S" class="bg-smeck-dark-900">S (Small)</option>
                    <option value="M" class="bg-smeck-dark-900">M (Medium)</option>
                    <option value="L" class="bg-smeck-dark-900" selected>L (Large) - Standar</option>
                    <option value="XL" class="bg-smeck-dark-900">XL (Extra Large)</option>
                    <option value="XXL" class="bg-smeck-dark-900">XXL (Double Extra Large)</option>
                    <option value="3XL" class="bg-smeck-dark-900">3XL (Jumbo)</option>
                    <option value="All Size" class="bg-smeck-dark-900">All Size (Syal / Topi / Aksesoris)</option>
                </select>
            </div>

            {{-- Jumlah Pesanan --}}
            <div>
                <label class="block font-bold uppercase text-white/60 mb-1.5">Jumlah (Pcs) <span class="text-red-400">*</span></label>
                <input type="number" id="inputProdQty" min="1" max="10" value="1" onchange="updateTotalOrderPrice()" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white font-mono text-sm focus:border-smeck-green-400 focus:outline-none">
            </div>

            {{-- Total Biaya --}}
            <div class="p-3.5 rounded-2xl bg-smeck-green-950/40 border border-smeck-green-500/20 flex items-center justify-between">
                <span class="text-white/60 font-medium">Perkiraan Total Bayar:</span>
                <span id="modalTotalOrder" class="font-mono font-black text-base text-smeck-gold">Rp 0</span>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end gap-2.5">
            <button type="button" onclick="closeOrderStoreModal()" class="px-4 py-2 rounded-xl bg-white/5 hover:bg-white/10 text-white text-xs font-semibold">
                Batal
            </button>
            <button type="button" onclick="launchWhatsAppOrder()" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-lg shadow-emerald-700/30 transition-all flex items-center gap-1.5">
                <i class="fa-brands fa-whatsapp text-sm"></i>
                <span>Lanjutkan ke WhatsApp</span>
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let currentStoreProduct = null;
    const currentMemberName = "{{ auth()->user()->name }}";
    const currentMemberNia = "{{ auth()->user()->profile?->nia ?? 'SMCK-1950' }}";
    const currentMemberChapter = "{{ auth()->user()->chapter?->name ?? 'Medan' }}";

    function openOrderStoreModal(data) {
        currentStoreProduct = data;
        document.getElementById('modalProdName').innerText = data.name;
        document.getElementById('modalProdCategory').innerText = data.category;
        document.getElementById('modalProdMemberPrice').innerText = 'Rp ' + Number(data.member_price).toLocaleString('id-ID');
        document.getElementById('modalProdNormalPrice').innerText = 'Rp ' + Number(data.price).toLocaleString('id-ID');
        document.getElementById('inputProdQty').value = 1;
        updateTotalOrderPrice();
        document.getElementById('modalOrderStore').classList.remove('hidden');
    }

    function closeOrderStoreModal() {
        document.getElementById('modalOrderStore').classList.add('hidden');
    }

    function updateTotalOrderPrice() {
        if (!currentStoreProduct) return;
        const qty = parseInt(document.getElementById('inputProdQty').value) || 1;
        const total = currentStoreProduct.member_price * qty;
        document.getElementById('modalTotalOrder').innerText = 'Rp ' + Number(total).toLocaleString('id-ID');
    }

    function launchWhatsAppOrder() {
        if (!currentStoreProduct) return;
        const size = document.getElementById('selectProdSize').value;
        const qty = document.getElementById('inputProdQty').value;
        const total = currentStoreProduct.member_price * qty;

        const message = `Halo Admin Official Store SMeCK Hooligan! 👋\n\nSaya ingin memesan merchandise resmi suporter PSMS Medan:\n\n` +
            `📦 *Nama Produk:* ${currentStoreProduct.name}\n` +
            `👕 *Ukuran:* ${size}\n` +
            `🔢 *Jumlah:* ${qty} Pcs\n` +
            `💰 *Total Biaya Member:* Rp ${Number(total).toLocaleString('id-ID')}\n\n` +
            `*Data Identitas Pemesan (Member):*\n` +
            `👤 Nama: ${currentMemberName}\n` +
            `🪪 NIA Resmi: ${currentMemberNia}\n` +
            `📍 Basis: ${currentMemberChapter}\n\n` +
            `Mohon info ketersediaan stok & nomor rekening untuk pembayaran. Salam Sada Roha! 🟢⚪`;

        const waUrl = `https://wa.me/6281260001950?text=${encodeURIComponent(message)}`;
        window.open(waUrl, '_blank');
        closeOrderStoreModal();
    }
</script>
@endpush
@endsection

