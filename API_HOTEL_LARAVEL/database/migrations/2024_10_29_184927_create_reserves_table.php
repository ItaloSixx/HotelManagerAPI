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
        Schema::create('reserves', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hotelCode');
            $table->unsignedBigInteger('roomCode');
            $table->date('checkIn');
            $table->date('checkOut');
            $table->decimal('total', 10,2);
            $table->decimal('discounts', 10,2)->nullable();
            $table->decimal('additional_charges', 10,2)->nullable();
            $table->foreign('hotelCode')
                    ->references('id')
                    ->on('hotels')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
            $table->foreign('roomCode')
                    ->references('id')
                    ->on('rooms')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reserves');
    }
};
