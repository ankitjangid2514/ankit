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
        Schema::create('market', function (Blueprint $table) {
            $table->id();  // id column
            $table->string('market_name');  // market_name column
            $table->string('market_name_hindi');  // market_name_hindi column
            $table->time('open_time');  // open_time column
            $table->time('close_time');  // close_time column
            $table->string('market_status')->nullable();
            $table->string('active')->nullable();

            $table->timestamps();  // created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('market');
    }
};
