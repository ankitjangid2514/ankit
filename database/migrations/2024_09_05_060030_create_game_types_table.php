<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGameTypesTable extends Migration
{
    public function up()
    {
        Schema::create('game_types', function (Blueprint $table) {
            $table->id();  // Primary key
            $table->foreignId('game_id')->constrained('games')->onDelete('cascade');  // Foreign key to games table
            $table->string('type');  // Type of game (e.g., 'singleAnk', 'jodi', etc.)
            $table->integer('number')->nullable();
            $table->integer('points')->nullable();
            $table->timestamps();  // Timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('game_types');
    }
}
