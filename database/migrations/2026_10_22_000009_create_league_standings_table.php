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
        Schema::create('league_standings', function (Blueprint $table) {
            $table->id();
            $table->string('competition_name')->default('Pegadaian Liga 2 Indonesia');
            $table->string('group_name')->default('Grup Barat / Wilayah 1');
            $table->integer('position')->default(1);
            $table->string('team_name');
            $table->string('team_code', 10)->nullable();
            $table->string('team_logo')->nullable();
            $table->integer('played')->default(0);
            $table->integer('won')->default(0);
            $table->integer('drawn')->default(0);
            $table->integer('lost')->default(0);
            $table->integer('goals_for')->default(0);
            $table->integer('goals_against')->default(0);
            $table->integer('goal_difference')->default(0);
            $table->integer('points')->default(0);
            $table->string('form', 50)->nullable()->comment('Contoh: W,D,W,L,W');
            $table->string('zone_status')->nullable()->comment('promotion_playoff, safe, relegation_playoff');
            $table->boolean('is_our_team')->default(false);
            $table->timestamp('last_synced_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('league_standings');
    }
};
