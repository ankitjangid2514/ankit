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
        Schema::create('game_rates', function (Blueprint $table) {
            $table->id();
            // Bid and Win columns
            $table->decimal('single_digit_bid', 8, 2)->nullable();
            $table->decimal('single_digit_win', 8, 2)->nullable();
            $table->decimal('jodi_digit_bid', 8, 2)->nullable();
            $table->decimal('jodi_digit_win', 8, 2)->nullable();
            $table->decimal('single_panna_bid', 8, 2)->nullable();
            $table->decimal('single_panna_win', 8, 2)->nullable();
            $table->decimal('double_panna_bid', 8, 2)->nullable();
            $table->decimal('double_panna_win', 8, 2)->nullable();
            $table->decimal('tripple_panna_bid', 8, 2)->nullable();
            $table->decimal('tripple_panna_win', 8, 2)->nullable();
            $table->decimal('half_sangam_bid', 8, 2)->nullable();
            $table->decimal('half_sangam_win', 8, 2)->nullable();
            $table->decimal('full_sangam_bid', 8, 2)->nullable();
            $table->decimal('full_sangam_win', 8, 2)->nullable();

            // Timestamps for tracking creation and updates
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_rates');
    }
};
