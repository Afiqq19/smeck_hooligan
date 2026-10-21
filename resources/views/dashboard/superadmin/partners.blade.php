@extends('layouts.app')

@section('title', 'SMeCK Partner')
@section('header_title', 'Database Mitra SMeCK Partner')
@section('header_subtitle', 'Jaringan kafe, warkop, barbershop, dan distro di Medan yang memberikan diskon khusus pemegang kartu KTA NFC aktif')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">

    {{-- Form Tambah Merchant Partner --}}
    <div class="p-6 sm:p-8 rounded-3xl bg-smeck-dark-900 border border-white/10">
        <h3 class="font-display font-bold text-lg text-white mb-2">Registrasi Mitra Merchant Baru</h3>
        <p class="text-xs text-white/50 mb-6">Tambahkan merchant di Kota Medan & sekitarnya yang bekerja sama dengan SMeCK Hooligan.</p>

        <form action="{{ route('superadmin.partners.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Nama Usaha / Merchant <span class="text-red-400">*</span></label>
                    <input type="text" name="business_name" required placeholder="Contoh: Warkop Sada Roha Teladan" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-red-400 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Kategori Usaha <span class="text-red-400">*</span></label>
                    <input type="text" name="category" required placeholder="Contoh: Warkop / Kafe / Distro" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-red-400 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Bentuk Promo / Diskon NFC <span class="text-red-400">*</span></label>
                    <input type="text" name="discount_rate" required placeholder="Contoh: Diskon 20% All Item / Free Kopi" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-red-400 focus:outline-none">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Alamat Lengkap di Medan <span class="text-red-400">*</span></label>
                    <input type="text" name="address" required placeholder="Contoh: Jl. Gedung Arca No. 8, Medan Kota" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-red-400 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Kontak / WhatsApp Pemilik</label>
                    <input type="text" name="phone_number" placeholder="Contoh: 081260112233" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-red-400 focus:outline-none">
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-red-600 hover:bg-red-500 text-white font-bold text-xs shadow-lg transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i>
                    <span>Daftarkan Mitra Merchant</span>
                </button>
            </div>
        </form>
    </div>

    {{-- Grid Mitra Terdaftar --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($partners as $partner)
            <div class="p-6 rounded-3xl bg-smeck-dark-900 border border-white/10 space-y-4 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-smeck-green-500/20 text-smeck-green-300">
                            {{ $partner->category }}
                        </span>
                        <span class="text-[10px] text-smeck-gold font-bold">
                            <i class="fa-solid fa-wifi rotate-90 mr-1"></i> Tap NFC
                        </span>
                    </div>

                    <h4 class="font-display font-black text-lg text-white">
                        {{ $partner->business_name }}
                    </h4>

                    <div class="mt-2 p-3 rounded-2xl bg-smeck-green-500/10 border border-smeck-green-500/20 text-xs font-bold text-smeck-green-300">
                        <i class="fa-solid fa-tags mr-1"></i> {{ $partner->discount_rate }}
                    </div>

                    <p class="text-xs text-white/50 mt-3 flex items-start gap-2">
                        <i class="fa-solid fa-location-dot text-red-400 shrink-0 mt-0.5"></i>
                        <span>{{ $partner->address }}</span>
                    </p>
                </div>

                <div class="pt-3 border-t border-white/10 flex items-center justify-between text-xs text-white/40">
                    <span>PJ: {{ $partner->contact_person ?? 'Owner' }}</span>
                    <span class="font-mono text-smeck-green-400">{{ $partner->phone_number ?? '-' }}</span>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-12 text-white/40">Belum ada mitra SMeCK Partner yang terdaftar.</div>
        @endforelse
    </div>

</div>
@endsection
