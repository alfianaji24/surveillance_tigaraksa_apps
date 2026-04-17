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
        Schema::create('clinical_documentations', function (Blueprint $table) {
            $table->id();
            $table->string('patient_id');
            $table->string('encounter_id')->nullable();
            $table->dateTime('encounter_date');
            $table->json('primary_diagnosis');
            $table->json('secondary_diagnoses')->nullable();
            $table->longText('clinical_notes');
            $table->string('documenter_id');
            $table->string('status')->default('draft'); // draft, completed, validated
            $table->string('satu_sehat_encounter_id')->nullable();
            $table->json('satu_sehat_response')->nullable();
            $table->timestamp('validated_at')->nullable();
            $table->string('validated_by')->nullable();
            $table->timestamps();
            
            $table->index(['patient_id', 'status']);
            $table->index(['documenter_id', 'encounter_date']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinical_documentations');
    }
};
