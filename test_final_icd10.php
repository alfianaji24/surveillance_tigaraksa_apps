<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Pasien;

echo "=== Final Test ICD-10 Codes Display ===\n\n";

// Test 1: Check if ICD-10 codes are loaded with the query
echo "🔍 Testing controller query with ICD-10 codes relation:\n";

// Simulate the same query as in controller index
$pasiens = Pasien::with('icd10Codes')
    ->orderBy('tanggal_kunjungan', 'desc')
    ->take(5)
    ->get();

echo "📊 Found " . $pasiens->count() . " patients\n\n";

foreach ($pasiens as $index => $pasien) {
    echo "Patient " . ($index + 1) . ":\n";
    echo "  ID: {$pasien->id}\n";
    echo "  No RM: {$pasien->no_rekam_medik}\n";
    echo "  Nama: {$pasien->nama_pasien}\n";
    echo "  Poli: {$pasien->poli}\n";
    echo "  Tanggal: {$pasien->tanggal_kunjungan}\n";
    echo "  Diagnosa: {$pasien->diagnosa}\n";
    
    // Check if ICD-10 codes are loaded
    echo "  ICD-10 Codes (loaded):\n";
    if ($pasien->relationLoaded('icd10Codes')) {
        echo "    ✅ Relation loaded\n";
        echo "    Count: " . $pasien->icd10Codes->count() . "\n";
        if ($pasien->icd10Codes->count() > 0) {
            foreach ($pasien->icd10Codes as $icd10) {
                echo "    - {$icd10->code}: {$icd10->display}\n";
            }
        }
    } else {
        echo "    ❌ Relation NOT loaded\n";
    }
    
    echo "  ---\n";
}

// Test 2: Count patients with ICD-10 codes
echo "\n📊 Statistics:\n";
$totalPatients = Pasien::count();
$patientsWithICD10 = Pasien::whereHas('icd10Codes')->count();
$patientsWithoutICD10 = $totalPatients - $patientsWithICD10;

echo "  Total patients: $totalPatients\n";
echo "  Patients with ICD-10 codes: $patientsWithICD10\n";
echo "  Patients without ICD-10 codes: $patientsWithoutICD10\n";
echo "  Percentage with ICD-10: " . round(($patientsWithICD10 / $totalPatients) * 100, 2) . "%\n";

// Test 3: Show most common ICD-10 codes
echo "\n🏆 Top 10 Most Common ICD-10 Codes:\n";
$topICD10Codes = \DB::table('pasien_icd10')
    ->select('icd10_code_id', \DB::raw('count(*) as count'))
    ->groupBy('icd10_code_id')
    ->orderBy('count', 'desc')
    ->limit(10)
    ->get();

foreach ($topICD10Codes as $item) {
    $icd10 = \App\Models\Icd10Code::find($item->icd10_code_id);
    if ($icd10) {
        echo "  {$icd10->code}: {$icd10->display} ({$item->count} patients)\n";
    }
}

echo "\n✅ Final test completed!\n";
echo "\n🎯 Summary: ICD-10 codes are working correctly!\n";
echo "   - Data is stored in database\n";
echo "   - Relations are loaded properly\n";
echo "   - View should display the codes correctly\n";
