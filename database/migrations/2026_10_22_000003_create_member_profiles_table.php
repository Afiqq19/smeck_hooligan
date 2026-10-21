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
        Schema::create('member_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('nia')->unique()->nullable(); // Nomor Induk Anggota (e.g. SMCK-2026-0001)
            $table->string('nik_ktp', 16)->unique();
            $table->string('place_of_birth')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['L', 'P'])->default('L');
            $table->string('blood_type', 5)->nullable(); // A, B, AB, O
            $table->text('address')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_number')->nullable();
            $table->string('emergency_contact_relation')->nullable();
            $table->string('occupation')->nullable();
            $table->string('shirt_size', 10)->default('L');
            $table->string('ktp_photo_path')->nullable();
            $table->string('profile_photo_path')->nullable();
            $table->date('joined_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_profiles');
    }
};
