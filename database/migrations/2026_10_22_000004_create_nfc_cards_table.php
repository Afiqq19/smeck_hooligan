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
        Schema::create('nfc_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('uid_number')->unique(); // Unique identifier from NFC chip
            $table->enum('card_vendor', ['emoney', 'flazz', 'brizzi', 'tapcash', 'custom'])->default('emoney');
            $table->enum('status', ['active', 'lost', 'blocked'])->default('active');
            $table->timestamp('issued_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nfc_cards');
    }
};
