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
        Schema::table('timbangans', function (Blueprint $table) {
            $table->integer('rak')->nullable()->after('berat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('timbangans', function (Blueprint $table) {
            $table->dropColumn('rak');
        });
    }
};
