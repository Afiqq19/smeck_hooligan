@extends('layouts.app')

@section('title', 'Broadcast Pengumuman')
@section('header_title', 'Broadcast Pengumuman Resmi DPP')
@section('header_subtitle', 'Kirim instruksi tribun, maklumat organisasi, dan rilis jadwal langsung ke dashboard seluruh anggota')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div class="p-6 sm:p-8 rounded-3xl bg-smeck-dark-900 border border-white/10">
        <h3 class="font-display font-bold text-lg text-white mb-2">Formulir Siaran Broadcast DPP Pusat</h3>
        <p class="text-xs text-white/50 mb-6">Pesan ini akan muncul sebagai notifikasi prioritas di portal seluruh anggota aktif.</p>

        <form action="{{ route('superadmin.broadcast.send') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Judul Pengumuman / Instruksi <span class="text-red-400">*</span></label>
                <input type="text" name="title" required placeholder="Contoh: Dresscode Hijau Total & Aturan Koreografi Derby Sumatera" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-red-400 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Target Penerima <span class="text-red-400">*</span></label>
                <select name="target_chapter" required class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-red-400 focus:outline-none">
                    <option value="all" class="bg-smeck-dark-900">Seluruh Anggota Nasional (Semua Chapter)</option>
                    <option value="sumut" class="bg-smeck-dark-900">Khusus Chapter Wilayah Sumatera Utara</option>
                    <option value="perantau" class="bg-smeck-dark-900">Khusus Chapter Jabodetabek & Perantauan</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Isi Pesan / Maklumat Resmi <span class="text-red-400">*</span></label>
                <textarea name="message" rows="5" required placeholder="Tuliskan isi instruksi atau maklumat resmi dari Ketua Umum DPP SMeCK Hooligan..." class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-red-400 focus:outline-none"></textarea>
            </div>

            <div class="pt-2">
                <button type="submit" class="px-6 py-3 rounded-xl bg-red-600 hover:bg-red-500 text-white font-bold text-xs shadow-lg transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-paper-plane"></i>
                    <span>Siarkan Pengumuman Sekarang</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
