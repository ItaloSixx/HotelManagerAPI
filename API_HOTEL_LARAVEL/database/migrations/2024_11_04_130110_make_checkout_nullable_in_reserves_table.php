<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('reserves', function (Blueprint $table) {
            $table->date('checkOut')->nullable()->change();
        });
    }


    public function down(): void
    {
        Schema::table('reserves', function (Blueprint $table) {
            $table->date('checkOut')->nullable(false)->change();
        });
    }
};
