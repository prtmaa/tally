<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tujuan_produks', function (Blueprint $table) {
            $table->string('note')->nullable()->after('produk_id');
        });
    }

    public function down(): void
    {
        Schema::table('tujuan_produks', function (Blueprint $table) {
            $table->dropColumn('note');
        });
    }
};
