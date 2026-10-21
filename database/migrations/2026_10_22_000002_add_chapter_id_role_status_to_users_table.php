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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('chapter_id')->nullable()->constrained('chapters')->nullOnDelete();
            $table->enum('role', ['super_admin', 'admin_chapter', 'member'])->default('member');
            $table->enum('status', ['active', 'pending_approval', 'suspended'])->default('pending_approval');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['chapter_id']);
            $table->dropColumn(['chapter_id', 'role', 'status']);
        });
    }
};
