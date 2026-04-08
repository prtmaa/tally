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
        Schema::table('tujuan_produks', function (Blueprint $table) {
            $table->string('prod_date')->nullable()->after('produk_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tujuan_produks', function (Blueprint $table) {
            $table->dropColumn('prod_date');
        });
    }
};
