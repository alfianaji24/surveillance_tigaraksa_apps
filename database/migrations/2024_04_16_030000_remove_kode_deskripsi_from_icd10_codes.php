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
        Schema::table('icd10_codes', function (Blueprint $table) {
            if (Schema::hasColumn('icd10_codes', 'kode')) {
                $table->dropColumn('kode');
            }
            if (Schema::hasColumn('icd10_codes', 'deskripsi')) {
                $table->dropColumn('deskripsi');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('icd10_codes', function (Blueprint $table) {
            $table->string('kode')->nullable()->after('id');
            $table->text('deskripsi')->nullable()->after('kode');
        });
    }
};
