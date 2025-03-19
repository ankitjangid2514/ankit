<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithdrawalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdrawal', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('user_id'); // Foreign key to users table
            $table->decimal('amount', 15, 2); // Amount withdrawn
            $table->string('payout');
            $table->integer('number');
            $table->string('status')->default('pending'); // Withdrawal status (e.g., pending, completed, failed)
            $table->timestamp('withdrawal_date')->nullable(); // Date of withdrawal
            $table->timestamps(); // created_at and updated_at fields

            // Foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Index for foreign key
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('withdrawal');
    }
}

