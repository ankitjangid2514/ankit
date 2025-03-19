<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHalfSangamBTable extends Migration
{
    public function up()
    {
        Schema::create('half_sangam_b', function (Blueprint $table) {
            $table->id();  // Primary key
            $table->foreignId('game_id')->constrained('games')->onDelete('cascade');  // Foreign key to games table
            $table->integer('close')->nullable();
            $table->integer('open_digit')->nullable();
            $table->integer('points')->nullable();
            $table->timestamps();  // Timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('half_sangam_b');
    }
}
