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
        Schema::create('diagnosa_p_k_m_s', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_kunjungan'); // B
            $table->string('poli'); // C
            $table->string('no_rekam_medik')->unique(); // D
            $table->string('nik')->nullable(); // E
            $table->string('nama_pasien'); // F
            $table->text('alamat'); // G
            $table->date('tanggal_lahir'); // H
            $table->integer('umur')->nullable(); // I (auto generate)
            $table->string('jenis_kelamin'); // J
            $table->string('jenis_pasien'); // K
            $table->string('no_bpjs')->nullable(); // L (terisi jika jenis_pasien = BPJS)
            $table->string('jenis_bayar'); // M
            $table->text('anamnesa')->nullable(); // N
            $table->text('diagnosa'); // O (ambil kalimat depan untuk kode ICD-10)
            $table->string('pemeriksa'); // P
            $table->string('status'); // Q
            $table->string('rs_rujukan')->nullable(); // R (terisi jika status = Rujuk)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diagnosa_p_k_m_s');
    }
};
