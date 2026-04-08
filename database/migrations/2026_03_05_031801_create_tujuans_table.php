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
        Schema::create('tujuans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tanggal_kiriman_id')
                ->constrained('tanggal_kirimans')
                ->cascadeOnDelete();

            $table->string('prod_date_1')->nullable();
            $table->string('prod_date_2')->nullable();
            $table->string('nama_tujuan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tujuans');
    }
};
