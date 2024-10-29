<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('reserve_guests', function (Blueprint $table) {
            $table->unsignedBigInteger('reserveId');
            $table->unsignedBigInteger('guestId');
            $table->primary(['reserveId', 'guestId']);
            $table->foreign('reserveId')
                ->references('id')
                ->on('reserves')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('guestId')
                ->references('id')
                ->on('guests')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reserve_guests');
    }
};
