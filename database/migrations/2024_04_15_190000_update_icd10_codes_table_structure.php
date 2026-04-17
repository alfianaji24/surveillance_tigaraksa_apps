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
            // Add new columns if they don't exist
            if (!Schema::hasColumn('icd10_codes', 'kode')) {
                $table->string('kode')->after('id');
            }
            if (!Schema::hasColumn('icd10_codes', 'deskripsi')) {
                $table->text('deskripsi')->after('kode');
            }
        });

        // Copy data from old columns to new columns if needed
        if (Schema::hasColumn('icd10_codes', 'code') && Schema::hasColumn('icd10_codes', 'kode')) {
            DB::statement('UPDATE icd10_codes SET kode = code WHERE kode IS NULL');
        }
        if (Schema::hasColumn('icd10_codes', 'display') && Schema::hasColumn('icd10_codes', 'deskripsi')) {
            DB::statement('UPDATE icd10_codes SET deskripsi = display WHERE deskripsi IS NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('icd10_codes', function (Blueprint $table) {
            $table->dropColumn(['kode', 'deskripsi']);
        });
    }
};
