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
        Schema::table('diagnosa_p_k_m_s', function (Blueprint $table) {
            $table->dropUnique('diagnosa_p_k_m_s_no_rekam_medik_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('diagnosa_p_k_m_s', function (Blueprint $table) {
            //
        });
    }
};
