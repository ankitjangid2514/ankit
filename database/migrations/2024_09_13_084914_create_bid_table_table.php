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
        Schema::create('bid_table', function (Blueprint $table) {
            $table->id();
            $table->integer('market_id');
            $table->integer('gtype_id');
            $table->timestamp('bid_date');
            $table->string('session');
            $table->integer('digit');
            $table->integer('amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bid_table');
    }
};
