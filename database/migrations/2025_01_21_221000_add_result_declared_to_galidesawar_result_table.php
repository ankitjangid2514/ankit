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
        Schema::table('galidesawar_result', function (Blueprint $table) {
            $table->boolean('result_declared')->default(false)->after('digit');
        });
    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('galidesawar_result', function (Blueprint $table) {
            $table->dropColumn('result_declared');
        });
    
    }
};
