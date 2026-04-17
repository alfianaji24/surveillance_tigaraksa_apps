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
        Schema::create('pasiens', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_kunjungan');
            $table->string('poli');
            $table->string('no_rekam_medik');
            $table->string('nik', 16)->nullable();
            $table->string('nama_pasien');
            $table->text('alamat');
            $table->date('tanggal_lahir');
            $table->string('umur');
            $table->string('jenis_kelamin'); // L/P
            $table->string('jenis_pasien'); // BPJS/Umum
            $table->string('jenis_bayar'); // PBI/NONPBI/Tunai
            $table->text('anamnesa');
            $table->string('diagnosa');
            $table->string('pemeriksa');
            $table->string('status'); // Dilayani/Dirujuk/Lain-Lain
            $table->string('rs_rujukan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pasiens');
    }
};
