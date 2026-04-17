<?php

// Bootstrap Laravel
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Pasien;
use App\Models\DiagnosaPKM;
use App\Models\Icd10Code;
use Carbon\Carbon;

echo "=== TESTING PASIEN SYNC INTEGRATION ===\n\n";

// Get statistics
$totalDiagnosaPkm = DiagnosaPKM::count();
$totalPasien = Pasien::count();
$uniquePatients = DiagnosaPKM::distinct('no_rekam_medik')->count('no_rekam_medik');
$syncedPatients = Pasien::whereIn('no_rekam_medik', function($query) {
    $query->select('no_rekam_medik')->from('diagnosa_p_k_m_s');
})->count();

echo "📊 Current Statistics:\n";
echo "  - Total DiagnosaPKM records: $totalDiagnosaPkm\n";
echo "  - Unique patients in DiagnosaPKM: $uniquePatients\n";
echo "  - Total patients in bank data: $totalPasien\n";
echo "  - Synced patients: $syncedPatients\n";
echo "  - Patients to sync: " . ($uniquePatients - $syncedPatients) . "\n\n";

// Test sync first 5 patients
echo "🔄 Testing sync for first 5 patients...\n";

$uniquePatientsList = DiagnosaPKM::select('no_rekam_medik')
    ->distinct()
    ->take(5)
    ->pluck('no_rekam_medik');

$syncedCount = 0;
$updatedCount = 0;

foreach ($uniquePatientsList as $noRekamMedik) {
    echo "\nProcessing RM: $noRekamMedik\n";
    
    // Get latest visit
    $latestVisit = DiagnosaPKM::where('no_rekam_medik', $noRekamMedik)
        ->latest('tanggal_kunjungan')
        ->first();

    if (!$latestVisit) {
        echo "  ❌ No visit found\n";
        continue;
    }

    echo "  📋 Latest visit: " . $latestVisit->nama_pasien . " on " . $latestVisit->tanggal_kunjungan->format('d/m/Y') . "\n";

    // Check if patient already exists
    $existingPasien = Pasien::where('no_rekam_medik', $noRekamMedik)->first();

    if ($existingPasien) {
        echo "  🔄 Updating existing patient\n";
        // Update existing patient
        $existingPasien->update([
            'tanggal_kunjungan' => $latestVisit->tanggal_kunjungan,
            'poli' => $latestVisit->poli,
            'nik' => $latestVisit->nik,
            'no_bpjs' => $latestVisit->no_bpjs,
            'nama_pasien' => $latestVisit->nama_pasien,
            'alamat' => $latestVisit->alamat,
            'tanggal_lahir' => $latestVisit->tanggal_lahir,
            'umur' => $latestVisit->umur . ' tahun',
            'jenis_kelamin' => $latestVisit->jenis_kelamin,
            'jenis_pasien' => $latestVisit->jenis_pasien,
            'jenis_bayar' => $latestVisit->jenis_bayar,
            'anamnesa' => $latestVisit->anamnesa,
            'diagnosa' => $latestVisit->diagnosa,
            'pemeriksa' => $latestVisit->pemeriksa,
            'status' => $latestVisit->status,
            'rs_rujukan' => $latestVisit->rs_rujukan
        ]);

        // Update ICD10 codes
        $existingPasien->icd10Codes()->detach();
        if ($latestVisit->kode_icd_10) {
            $icd10 = Icd10Code::where('code', $latestVisit->kode_icd_10)->first();
            if ($icd10) {
                $existingPasien->icd10Codes()->attach($icd10->id);
                echo "  🏷️  ICD-10 code: " . $icd10->code . " - " . $icd10->display . "\n";
            }
        }

        $updatedCount++;
        echo "  ✅ Patient updated successfully\n";

    } else {
        echo "  ➕ Creating new patient\n";
        // Find poli by name
        $poli = \App\Models\Poli::where('nama', 'like', '%' . $latestVisit->poli . '%')->first();
        $poliId = $poli ? $poli->id : null;

        // Create new patient
        $pasien = Pasien::create([
            'tanggal_kunjungan' => $latestVisit->tanggal_kunjungan,
            'poli' => $latestVisit->poli,
            'poli_id' => $poliId,
            'no_rekam_medik' => $latestVisit->no_rekam_medik,
            'nik' => $latestVisit->nik,
            'no_bpjs' => $latestVisit->no_bpjs,
            'nama_pasien' => $latestVisit->nama_pasien,
            'alamat' => $latestVisit->alamat,
            'no_hp' => null,
            'tanggal_lahir' => $latestVisit->tanggal_lahir,
            'umur' => $latestVisit->umur . ' tahun',
            'jenis_kelamin' => $latestVisit->jenis_kelamin,
            'jenis_pasien' => $latestVisit->jenis_pasien,
            'jenis_bayar' => $latestVisit->jenis_bayar,
            'anamnesa' => $latestVisit->anamnesa,
            'diagnosa' => $latestVisit->diagnosa,
            'pemeriksa' => $latestVisit->pemeriksa,
            'status' => $latestVisit->status,
            'status_active' => true,
            'rs_rujukan' => $latestVisit->rs_rujukan
        ]);

        // Process ICD10 codes
        if ($latestVisit->kode_icd_10) {
            $icd10 = Icd10Code::where('code', $latestVisit->kode_icd_10)->first();
            if ($icd10) {
                $pasien->icd10Codes()->attach($icd10->id);
                echo "  🏷️  ICD-10 code: " . $icd10->code . " - " . $icd10->display . "\n";
            }
        }

        $syncedCount++;
        echo "  ✅ Patient created successfully\n";
    }

    // Test relationship
    $totalVisits = DiagnosaPKM::where('no_rekam_medik', $noRekamMedik)->count();
    echo "  📈 Total visits: $totalVisits\n";
}

echo "\n🎉 Test Results:\n";
echo "  - New patients synced: $syncedCount\n";
echo "  - Existing patients updated: $updatedCount\n";

// Final statistics
$newTotalPasien = Pasien::count();
$newSyncedPatients = Pasien::whereIn('no_rekam_medik', function($query) {
    $query->select('no_rekam_medik')->from('diagnosa_p_k_m_s');
})->count();

echo "\n📊 Updated Statistics:\n";
echo "  - Total patients in bank data: $newTotalPasien\n";
echo "  - Synced patients: $newSyncedPatients\n";

// Test relationship queries
echo "\n🔍 Testing Relationships:\n";

$testPatient = Pasien::with('diagnosaPkm', 'icd10Codes')->first();
if ($testPatient) {
    echo "  Patient: " . $testPatient->nama_pasien . " (RM: " . $testPatient->no_rekam_medik . ")\n";
    echo "  Total visits: " . $testPatient->total_visits . "\n";
    echo "  ICD-10 codes: " . $testPatient->icd10Codes->pluck('code')->implode(', ') . "\n";
    echo "  Latest visit: " . $testPatient->diagnosaPkm()->latest('tanggal_kunjungan')->first()->tanggal_kunjungan->format('d/m/Y') . "\n";
}

echo "\n🌐 Access Points:\n";
echo "  - Pasien Bank Data: http://127.0.0.1:8000/pasien\n";
echo "  - Sync Dashboard: http://127.0.0.1:8000/pasien/sync\n";
echo "  - Diagnosa PKM: http://127.0.0.1:8000/diagnosa-pkm\n";

echo "\n=== TEST COMPLETE ===\n";
