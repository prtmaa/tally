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
        Schema::create('timbangan_bahans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tanggal_bahan_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('bahan_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->integer('pcs')->nullable();
            $table->decimal('berat', 10, 2)->nullable();
            $table->integer('urutan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timbangan_bahans');
    }
};
