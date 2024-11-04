<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('method')->nullable()->after('value');
            $table->boolean('paid')->default(false)->after('method');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('method');
            $table->dropColumn('paid');
        });
    }
};
