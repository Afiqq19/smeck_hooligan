<?php

namespace Database\Seeders;

use App\Models\Chant;
use App\Models\Chapter;
use App\Models\ChapterDocumentation;
use App\Models\EventAttendance;
use App\Models\MatchTicket;
use App\Models\MemberProfile;
use App\Models\NfcCard;
use App\Models\SmeckPartner;
use App\Models\StoreProduct;
use App\Models\TicketAllocation;
use App\Models\User;
use App\Models\VoluntaryDue;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Chapters
        $chapterMedanKota = Chapter::create([
            'name' => 'Chapter Medan Kota (Pusat)',
            'region_code' => 'MDK-01',
            'leader_name' => 'Bani Ginting',
            'basecamp_address' => 'Jl. Stadion Teladan No. 12, Medan Kota',
            'is_active' => true,
        ]);

        $chapterTembung = Chapter::create([
            'name' => 'Chapter Medan Tembung',
            'region_code' => 'MDT-02',
            'leader_name' => 'Rahmat Siregar',
            'basecamp_address' => 'Jl. Letda Sujono No. 88, Medan Tembung',
            'is_active' => true,
        ]);

        $chapterMarelan = Chapter::create([
            'name' => 'Chapter Medan Marelan',
            'region_code' => 'MDM-03',
            'leader_name' => 'Indra Nasution',
            'basecamp_address' => 'Jl. Marelan Raya Psr IV No. 24, Medan Marelan',
            'is_active' => true,
        ]);

        $chapterJohor = Chapter::create([
            'name' => 'Chapter Medan Johor',
            'region_code' => 'MDJ-04',
            'leader_name' => 'Dedi Simanjuntak',
            'basecamp_address' => 'Jl. Karya Wisata No. 15, Medan Johor',
            'is_active' => true,
        ]);

        $chapterJabodetabek = Chapter::create([
            'name' => 'Chapter Jabodetabek (Perantau)',
            'region_code' => 'JBD-07',
            'leader_name' => 'Ferry Pasaribu',
            'basecamp_address' => 'Jl. Tebet Raya No. 45, Jakarta Selatan',
            'is_active' => true,
        ]);

        // 2. Super Admin User
        $superAdmin = User::create([
            'name' => 'Bani Ginting (Ketua Umum)',
            'email' => 'admin@smeck.id',
            'password' => Hash::make('password'),
            'chapter_id' => $chapterMedanKota->id,
            'role' => 'super_admin',
            'status' => 'active',
        ]);

        MemberProfile::create([
            'user_id' => $superAdmin->id,
            'nia' => 'SMCK-2003-0001',
            'nik_ktp' => '1271011508850001',
            'place_of_birth' => 'Medan',
            'date_of_birth' => '1985-08-15',
            'gender' => 'L',
            'blood_type' => 'O',
            'address' => 'Jl. Gedung Arca No. 22, Medan',
            'phone_number' => '081260000001',
            'emergency_contact_name' => 'Dewi Sartika',
            'emergency_contact_number' => '081260000002',
            'emergency_contact_relation' => 'Istri',
            'occupation' => 'Wiraswasta',
            'shirt_size' => 'XL',
            'joined_date' => '2003-09-30',
        ]);

        // 3. Admin Chapter User
        $adminChapter = User::create([
            'name' => 'Rahmat Siregar (Korwil Tembung)',
            'email' => 'chapter@smeck.id',
            'password' => Hash::make('password'),
            'chapter_id' => $chapterTembung->id,
            'role' => 'admin_chapter',
            'status' => 'active',
        ]);

        MemberProfile::create([
            'user_id' => $adminChapter->id,
            'nia' => 'SMCK-2015-0245',
            'nik_ktp' => '1271042004920002',
            'place_of_birth' => 'Medan',
            'date_of_birth' => '1992-04-20',
            'gender' => 'L',
            'blood_type' => 'B',
            'address' => 'Jl. Letda Sujono Gg. Bersama, Medan',
            'phone_number' => '081370000002',
            'emergency_contact_name' => 'H. Siregar',
            'emergency_contact_number' => '081370000003',
            'emergency_contact_relation' => 'Ayah',
            'occupation' => 'Karyawan Swasta',
            'shirt_size' => 'L',
            'joined_date' => '2015-05-12',
        ]);

        // 4. Member User (Anggota Aktif dengan KTA NFC)
        $member = User::create([
            'name' => 'Muhammad Syafiq',
            'email' => 'member@smeck.id',
            'password' => Hash::make('password'),
            'chapter_id' => $chapterMedanKota->id,
            'role' => 'member',
            'status' => 'active',
        ]);

        MemberProfile::create([
            'user_id' => $member->id,
            'nia' => 'SMCK-2024-1950',
            'nik_ktp' => '1271081206980005',
            'place_of_birth' => 'Medan',
            'date_of_birth' => '1998-06-12',
            'gender' => 'L',
            'blood_type' => 'O',
            'address' => 'Jl. Halat Gg. Tabek Indah No. 4B, Medan Area',
            'phone_number' => '082276543210',
            'emergency_contact_name' => 'Zulfikar',
            'emergency_contact_number' => '081265432199',
            'emergency_contact_relation' => 'Saudara Kandung',
            'occupation' => 'Software Engineer',
            'shirt_size' => 'L',
            'joined_date' => '2024-01-10',
        ]);

        // Kartu NFC Fisik untuk Member
        NfcCard::create([
            'user_id' => $member->id,
            'uid_number' => '04:A2:8B:1A:F3:60:80',
            'card_vendor' => 'emoney',
            'status' => 'active',
            'issued_at' => now()->subMonths(3),
        ]);

        // 5. Sample Voluntary Dues
        VoluntaryDue::create([
            'user_id' => $member->id,
            'chapter_id' => $chapterMedanKota->id,
            'amount' => 50000,
            'status' => 'verified',
            'notes' => 'Iuran Sukarela Kas Bulanan SMeCK Medan Kota',
        ]);

        VoluntaryDue::create([
            'user_id' => $member->id,
            'chapter_id' => $chapterMedanKota->id,
            'amount' => 100000,
            'status' => 'verified',
            'notes' => 'Donasi Koreografi Banner Derby Sumatera',
        ]);

        // 6. Sample Event Attendance (Tap NFC)
        EventAttendance::create([
            'chapter_id' => $chapterMedanKota->id,
            'user_id' => $member->id,
            'event_name' => 'PSMS Medan vs Semen Padang (Liga 2)',
            'scanned_at' => now()->subDays(12),
        ]);

        EventAttendance::create([
            'chapter_id' => $chapterMedanKota->id,
            'user_id' => $member->id,
            'event_name' => 'Kopdar Akbar & Doa Bersama HUT SMeCK Hooligan',
            'scanned_at' => now()->subDays(5),
        ]);

        // 7. Chants Tribun SMeCK Hooligan (Prompt 6)
        Chant::create([
            'title' => 'Salam Sada Roha (Anthem SMeCK)',
            'category' => 'Anthem Kebangsaan',
            'lyrics' => "Satu hati satu jiwa, bersatu untuk PSMS\nTakkan pernah lelah kami bernyanyi di sini\nDari Teladan bergema suara lantang membahana\nSalam Sada Roha! Untukmu Ayam Kinantan!\n\nReff:\nPSMS Medan... Kaulah kebanggaan Kota Medan!\nWalau rintangan menghadang kami kan tetap bertahan!\nOoo ooo ooo... Salam Sada Roha!",
            'duration' => '03:15',
            'is_featured' => true,
        ]);

        Chant::create([
            'title' => 'Kinantan Pantang Menyerah',
            'category' => 'Semangat Bertanding',
            'lyrics' => "Ayo bangkit Ayam Kinantan!\nRebut kembali kejayaanmu!\nDi sini kami berdiri tegak,\nHanya untuk kemenanganmu!\n\nRibak Sude! Libas Lawanmu!\nMedan Takkan Pernah Menyerah!",
            'duration' => '02:40',
            'is_featured' => true,
        ]);

        Chant::create([
            'title' => 'Biar Pecah di Lapangan',
            'category' => 'Chant Tribun Panas',
            'lyrics' => "Biar pecah di lapangan, tapi persaudaraan tetap terjaga\nHijau putih lambang di dada, kehormatan tanah Deli tercinta!\nSMeCK Hooligan tak gentar, kami suporter berjiwa ksatria!",
            'duration' => '02:10',
            'is_featured' => false,
        ]);

        // 8. Match Tickets & Awayday (Prompt 6)
        $homeMatch = MatchTicket::create([
            'match_title' => 'PSMS Medan vs Sriwijaya FC',
            'match_time' => now()->addDays(6)->setTime(19, 0),
            'stadium' => 'Stadion Teladan Medan (Tribun Timur)',
            'type' => 'home',
            'price' => 35000,
            'total_quota' => 1200,
            'remaining_quota' => 840,
            'status' => 'open',
        ]);

        $awayTour = MatchTicket::create([
            'match_title' => 'TUR AWAYDAYS: Semen Padang vs PSMS Medan',
            'match_time' => now()->addDays(18)->setTime(15, 30),
            'stadium' => 'Stadion H. Agus Salim, Padang',
            'type' => 'away',
            'price' => 350000,
            'total_quota' => 100,
            'remaining_quota' => 32,
            'away_destination' => 'Padang, Sumatera Barat',
            'bus_transport_info' => '2 Bus Pariwisata AC Seat 2-2, Makan 4x, Tiket Masuk Stadion, Kaos Tour',
            'status' => 'open',
        ]);

        // Ticket Allocation
        TicketAllocation::create([
            'match_ticket_id' => $homeMatch->id,
            'chapter_id' => $chapterMedanKota->id,
            'allocated_quota' => 300,
            'distributed_quota' => 180,
        ]);

        TicketAllocation::create([
            'match_ticket_id' => $homeMatch->id,
            'chapter_id' => $chapterTembung->id,
            'allocated_quota' => 200,
            'distributed_quota' => 95,
        ]);

        // 9. SMeCK Partners (Diskon Tap NFC di Medan - Prompt 6)
        SmeckPartner::create([
            'business_name' => 'Warkop Sada Roha Teladan',
            'category' => 'Warkop / Cafe',
            'address' => 'Jl. Gedung Arca No. 8, Medan Kota (Depan Stadion)',
            'discount_rate' => 'Diskon 20% All Menu + Free Kopi Tiap Matchday PSMS',
            'contact_person' => 'Bang Ucok',
            'phone_number' => '081260112233',
            'is_active' => true,
        ]);

        SmeckPartner::create([
            'business_name' => 'Kinantan 1950 Apparel & Distro',
            'category' => 'Distro Clothing',
            'address' => 'Jl. Dr. Mansyur No. 44, Medan Baru',
            'discount_rate' => 'Diskon 15% Merchandise PSMS & SMeCK',
            'contact_person' => 'Bro Kevin',
            'phone_number' => '081375223344',
            'is_active' => true,
        ]);

        SmeckPartner::create([
            'business_name' => 'Champion Futsal Stadium Medan',
            'category' => 'Futsal & Sport',
            'address' => 'Jl. STM No. 10, Medan Amplas',
            'discount_rate' => 'Diskon Sewa Lapangan Rp 25.000/jam',
            'contact_person' => 'Bang Iwan',
            'phone_number' => '082166334455',
            'is_active' => true,
        ]);

        // 10. Chapter Documentations (Prompt 6)
        ChapterDocumentation::create([
            'chapter_id' => $chapterMedanKota->id,
            'user_id' => $superAdmin->id,
            'title' => 'Koreografi 3D Green Wall Derby Sumatera',
            'activity_type' => 'nobar',
            'activity_date' => now()->subDays(15),
            'description' => 'Aksi pengibaran giant flag dan koreografi kertas hijau-putih di Tribun Timur Stadion Teladan menyambut laga akbar.',
            'is_highlighted' => true,
        ]);

        ChapterDocumentation::create([
            'chapter_id' => $chapterTembung->id,
            'user_id' => $adminChapter->id,
            'title' => 'Bakti Sosial & Santunan Yatim Piatu Chapter Tembung',
            'activity_type' => 'baksos',
            'activity_date' => now()->subDays(25),
            'description' => 'Penyerahan 150 paket sembako dan santunan ke panti asuhan sebagai wujud kepedulian sosial SMeCK Hooligan.',
            'is_highlighted' => false,
        ]);

        // 11. SMeCK Store Products (Prompt 6)
        StoreProduct::create([
            'name' => 'Jersey Home SMeCK Hooligan "Sada Roha Edition"',
            'category' => 'Jersey Official',
            'price' => 175000,
            'member_price' => 145000,
            'stock' => 120,
            'description' => 'Bahan Dryfit Milano Premium anti UV dengan emblem 3D silikon dan detail corak khas Kota Medan.',
            'is_active' => true,
        ]);

        StoreProduct::create([
            'name' => 'Syal Rajut HD "1950 - Ayam Kinantan Pride"',
            'category' => 'Syal Tribun',
            'price' => 120000,
            'member_price' => 95000,
            'stock' => 75,
            'description' => 'Syal rajut 14 get jacquard premium dengan rumbai tebal dan warna hijau botol pekat.',
            'is_active' => true,
        ]);

        StoreProduct::create([
            'name' => 'T-Shirt "Ribak Sude" Vintage Washed Black',
            'category' => 'T-Shirt',
            'price' => 110000,
            'member_price' => 85000,
            'stock' => 200,
            'description' => 'Bahan Cotton Combed 24s heavy weight dengan sablon plastisol discharge vintage.',
            'is_active' => true,
        ]);

        StoreProduct::create([
            'name' => 'Stiker Pack Hologram SMeCK Hooligan (Isi 10)',
            'category' => 'Aksesoris',
            'price' => 25000,
            'member_price' => 20000,
            'stock' => 350,
            'description' => 'Stiker vinyl anti air tahan cuaca dengan efek pelangi hologram menyala.',
            'is_active' => true,
        ]);
    }
}
