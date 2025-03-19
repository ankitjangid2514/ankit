<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFullSangamTable extends Migration
{
    public function up()
    {
        Schema::create('full_sangam', function (Blueprint $table) {
            $table->id();  // Primary key
            $table->foreignId('game_id')->constrained('games')->onDelete('cascade');  // Foreign key to games table
            $table->integer('open')->nullable();
            $table->integer('close')->nullable();
            $table->integer('points')->nullable();
            $table->timestamps();  // Timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('full_sangam');
    }
}
