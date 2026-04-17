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
        Schema::create('penyakit_data', function (Blueprint $table) {
            $table->id();
            $table->string('nama_penyakit');
            $table->integer('minggu_ke');
            $table->integer('jumlah_kasus');
            $table->integer('tahun');
            $table->timestamps();
            
            $table->index(['nama_penyakit', 'tahun', 'minggu_ke']);
            $table->index('tahun');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyakit_data');
    }
};
