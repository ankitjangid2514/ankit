<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGtypeTable extends Migration
{
    public function up()
    {
        Schema::create('gtype', function (Blueprint $table) {
            $table->id(); // Creates an auto-incrementing BIGINT column named 'id'
            $table->string('gtype'); // Creates a VARCHAR column named 'gtype'
            $table->timestamps(); // Optional: adds created_at and updated_at columns
        });
    }

    public function down()
    {
        Schema::dropIfExists('gtype');
    }
}
