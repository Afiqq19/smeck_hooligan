<?php

namespace Database\Seeders;

use App\Models\NewsArticle;
use Illuminate\Database\Seeder;

class NewsArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        NewsArticle::firstOrCreate(
            ['slug' => 'maklumat-resmi-dpp-smeck-hooligan-laga-derby'],
            [
                'title' => 'Maklumat Resmi DPP SMeCK Hooligan: Jaga Sportivitas dan Ketertiban Tribun Timur',
                'category' => 'Maklumat DPP',
                'summary' => 'Dewan Pimpinan Pusat SMeCK Hooligan menghimbau seluruh suporter untuk menjaga kondusivitas, tidak membawa flare ke dalam stadion, dan tetap satu komando.',
                'content' => "Kepada Seluruh Saudaraku SMeCK Hooligan di Seluruh Penjuru Nusantara,\n\nMenjelang pertandingan lanjutan Liga 2 musim 2024/2025, DPP SMeCK Hooligan menyampaikan maklumat penting demi menjaga marwah klub kebanggaan PSMS Medan:\n\n1. Seluruh anggota wajib mengenakan atribut kebesaran Hijau-Putih saat hadir di Stadion Teladan.\n2. Tetap tertib, jangan mudah terprovokasi, dan satu komando di bawah arahan Capo dan Korlap Tribun Timur.\n3. Dilarang keras menyalakan flare, smoke bomb, atau kembang api sebelum peluit akhir pertandingan selesai ditiup wasit guna menghindari denda komdis PSSI bagi klub.\n4. Hormati penonton umum, anak-anak, serta fasilitas stadion kebanggaan masyarakat Kota Medan.\n\nMari kita buktikan bahwa SMeCK Hooligan adalah suporter yang militan, loyal, namun tetap beradab dan terpelajar!\n\nSalam Sada Roha!\nDPP SMeCK Hooligan",
                'author_name' => 'Bani Ginting (Ketua Umum)',
                'image_url' => 'https://images.unsplash.com/photo-1508098682722-e99c43a406b2?auto=format&fit=crop&w=800&q=80',
                'is_featured' => true,
                'is_published' => true,
                'views_count' => 1420,
                'published_at' => now()->subDays(2),
            ]
        );

        NewsArticle::firstOrCreate(
            ['slug' => 'panduan-pemesanan-kuota-tiket-tribun-timur'],
            [
                'title' => 'Alokasi Tiket Khusus Anggota SMeCK Hooligan Dibuka Lewat Portal Resmi',
                'category' => 'Info Pertandingan',
                'summary' => 'Pengurus Chapter Wilayah kini dapat langsung memesan alokasi tiket bus rombongan dan tiket mandiri anggota dengan harga khusus diskon suporter.',
                'content' => "Kabar gembira untuk seluruh anggota aktif SMeCK Hooligan!\n\nPanitia Pelaksana bersama DPP SMeCK Hooligan telah menetapkan sistem tiket satu pintu melalui Web Portal Resmi. Anggota yang telah memiliki KTA Digital atau KTA Fisik NFC berhak mendapatkan prioritas kuota dan diskon khusus Tribun Timur.\n\nBagi pengurus Chapter (Korlap), kini tersedia fitur Check-In 1-Klik Rombongan Bus dan Cetak Manifest untuk mempermudah koordinasi rombongan laga away maupun home.\n\nPastikan KTA Anda sudah terverifikasi sebelum hari H pertandingan!",
                'author_name' => 'Biro Hubungan Antar Lembaga & Tiketing',
                'image_url' => 'https://images.unsplash.com/photo-1522778119026-d647f0596c20?auto=format&fit=crop&w=800&q=80',
                'is_featured' => true,
                'is_published' => true,
                'views_count' => 890,
                'published_at' => now()->subDays(4),
            ]
        );

        NewsArticle::firstOrCreate(
            ['slug' => 'smeck-peduli-salurkan-bantuan-korban-banjir-medan'],
            [
                'title' => 'SMeCK Peduli: Penyaluran Paket Sembako & Evakuasi Warga Terdampak Banjir',
                'category' => 'Aksi Sosial',
                'summary' => 'Kolaborasi antar chapter Medan Marelan, Tembung, dan Medan Kota turun langsung menyalurkan bantuan darurat kepada keluarga korban banjir di bantaran Sungai Deli.',
                'content' => "Solidaritas kami tak hanya bergemuruh di atas beton tribun, melainkan nyata terasa bagi masyarakat Kota Medan yang membutuhkan bantuan.\n\nMelalui divisi SMeCK Peduli, para anggota relawan telah mendistribusikan lebih dari 350 paket sembako, selimut, dan air bersih ke posko-posko penampungan darurat. Donasi ini dikumpulkan dari iuran sukarela para anggota dan kas donasi resmi organisasi.\n\nTerima kasih kepada seluruh anggota yang telah berpartisipasi menyisihkan rezeki. Semoga persaudaraan ini semakin erat dan penuh berkah!\n\nSalam Sada Roha!",
                'author_name' => 'Divisi Sosial DPP SMeCK',
                'image_url' => 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?auto=format&fit=crop&w=800&q=80',
                'is_featured' => false,
                'is_published' => true,
                'views_count' => 610,
                'published_at' => now()->subDays(7),
            ]
        );
    }
}
