<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('survailance_penyakits', function (Blueprint $table) {
            $table->id();
            $table->string('kode');
            $table->string('nama_penyakit');
            $table->json('icd10_codes');
            $table->string('kategori');
            $table->boolean('aktif')->default(true);
            $table->timestamps();
            
            $table->unique('kode');
        });
    }

    public function down()
    {
        Schema::dropIfExists('survailance_penyakits');
    }
};
