<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Chants Tribun PSMS Medan
        Schema::create('chants', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category')->default('Chant Kebangsaan'); // Kebangsaan, Semangat, Provokasi Sportif, Anthem
            $table->text('lyrics');
            $table->string('audio_path')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('duration', 10)->nullable(); // e.g. 02:45
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });

        // 2. Tiket Pertandingan (Home Match) & Tur Awaydays
        Schema::create('match_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('match_title'); // e.g. PSMS Medan vs Sriwijaya FC
            $table->dateTime('match_time');
            $table->string('stadium')->default('Stadion Teladan Medan');
            $table->enum('type', ['home', 'away'])->default('home');
            $table->decimal('price', 10, 2);
            $table->integer('total_quota')->default(500);
            $table->integer('remaining_quota')->default(500);
            $table->string('away_destination')->nullable(); // e.g. Stadion Jakabaring Palembang
            $table->string('bus_transport_info')->nullable(); // e.g. Bus Pariwisata AC Seat 2-2
            $table->enum('status', ['open', 'closed', 'completed'])->default('open');
            $table->timestamps();
        });

        // 3. Alokasi Tiket ke Chapter oleh Super Admin
        Schema::create('ticket_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_ticket_id')->constrained('match_tickets')->cascadeOnDelete();
            $table->foreignId('chapter_id')->constrained('chapters')->cascadeOnDelete();
            $table->integer('allocated_quota')->default(0);
            $table->integer('distributed_quota')->default(0);
            $table->timestamps();
        });

        // 4. Pemesanan Tiket / Awayday oleh Member
        Schema::create('ticket_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_ticket_id')->constrained('match_tickets')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('chapter_id')->constrained('chapters')->cascadeOnDelete();
            $table->integer('seat_number')->nullable();
            $table->decimal('total_amount', 10, 2);
            $table->enum('payment_status', ['unpaid', 'paid', 'verified'])->default('unpaid');
            $table->string('payment_proof_path')->nullable();
            $table->timestamps();
        });

        // 5. Database Mitra SMeCK Partner (Diskon Tap NFC di Medan)
        Schema::create('smeck_partners', function (Blueprint $table) {
            $table->id();
            $table->string('business_name'); // e.g. "Warkop Sada Roha", "Distro Kinantan 1950"
            $table->string('category'); // Cafe, Distro, Warkop, Barbershop, Futsal
            $table->text('address');
            $table->string('google_maps_url')->nullable();
            $table->string('discount_rate'); // e.g. "Diskon 15% All Menu", "Free Kopi Tiap Matchday"
            $table->string('contact_person')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('logo_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 6. Dokumentasi Kegiatan Chapter (Kopdar, Nobar, Baksos)
        Schema::create('chapter_documentations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chapter_id')->constrained('chapters')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->enum('activity_type', ['kopdar', 'nobar', 'baksos', 'awaydays', 'lainnya'])->default('kopdar');
            $table->date('activity_date');
            $table->text('description');
            $table->string('photo_path')->nullable();
            $table->boolean('is_highlighted')->default(false);
            $table->timestamps();
        });

        // 7. SMeCK Store Merchandise
        Schema::create('store_products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category'); // Jersey, Syal, T-Shirt, Topi, Aksesoris
            $table->decimal('price', 10, 2);
            $table->decimal('member_price', 10, 2)->nullable(); // Khusus pemegang KTA
            $table->integer('stock')->default(0);
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_products');
        Schema::dropIfExists('chapter_documentations');
        Schema::dropIfExists('smeck_partners');
        Schema::dropIfExists('ticket_orders');
        Schema::dropIfExists('ticket_allocations');
        Schema::dropIfExists('match_tickets');
        Schema::dropIfExists('chants');
    }
};
