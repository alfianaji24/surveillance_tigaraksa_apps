<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

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
                $table->string('kode')->nullable()->after('id');
            }
            if (!Schema::hasColumn('icd10_codes', 'deskripsi')) {
                $table->text('deskripsi')->nullable()->after('kode');
            }
        });

        // Copy data from existing columns to new columns
        DB::table('icd10_codes')->get()->each(function ($item) {
            DB::table('icd10_codes')
                ->where('id', $item->id)
                ->update([
                    'kode' => $item->code ?? null,
                    'deskripsi' => $item->display ?? null,
                ]);
        });

        // Make new columns not nullable after data migration
        Schema::table('icd10_codes', function (Blueprint $table) {
            $table->string('kode')->nullable(false)->change();
            $table->text('deskripsi')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
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
};
