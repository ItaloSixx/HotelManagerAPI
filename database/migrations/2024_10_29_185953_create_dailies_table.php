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
        Schema::create('dailies', function (Blueprint $table) {
            $table->unsignedBigInteger('reserveId');
            $table->unsignedBigInteger('ghestId');
            $table->primary(['reserveId', 'ghestId']);
            $table->foreign('reserveId')
                    ->references('id')
                    ->on('reserves')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
            $table->foreign('ghestId')
                    ->references('id')
                    ->on('ghests')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dailies');
    }
};
