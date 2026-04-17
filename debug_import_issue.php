<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Pasien;
use App\Http\Controllers\PasienController;

echo "=== Debug Import ICD-10 Issue ===\n\n";

// Check patients that should have ICD-10 but don't
echo "🔍 Checking patients with diagnosa but no ICD-10 codes:\n";

$patientsWithDiagnosa = Pasien::whereNotNull('diagnosa')
    ->where('diagnosa', '!=', '')
    ->whereDoesntHave('icd10Codes')
    ->limit(10)
    ->get();

foreach ($patientsWithDiagnosa as $pasien) {
    echo "Patient: {$pasien->nama_pasien} (RM: {$pasien->no_rekam_medik})\n";
    echo "  Diagnosa field: '{$pasien->diagnosa}'\n";
    echo "  ICD-10 count: " . $pasien->icd10Codes->count() . "\n";
    echo "  ---\n";
}

// Test the processICD10Codes function with actual diagnosa data
echo "\n🧪 Testing processICD10Codes with real data:\n";
$controller = new PasienController();
$reflection = new ReflectionClass($controller);
$method = $reflection->getMethod('processICD10Codes');
$method->setAccessible(true);

$realDiagnosaSamples = [
    'K30 Dyspepsia',
    'M54.5 Low back pain',
    'R50.9 Fever, unspecified',
    'K08.3 Retained dental root',
    'E14.7 Unspecified diabetes mellitus with multiple complications; I50.0 Congestive heart failure'
];

foreach ($realDiagnosaSamples as $diagnosa) {
    $result = $method->invoke($controller, $diagnosa);
    echo "  Diagnosa: '$diagnosa'\n";
    echo "  Result: " . implode(', ', $result) . " (Count: " . count($result) . ")\n";
    echo "  ---\n";
}

// Check if the issue is in the import process
echo "\n🔍 Checking import process issue:\n";
echo "Let's manually process one patient to see what happens:\n";

// Get a patient that has diagnosa but no ICD-10
$testPatient = Pasien::whereNotNull('diagnosa')
    ->where('diagnosa', '!=', '')
    ->whereDoesntHave('icd10Codes')
    ->first();

if ($testPatient) {
    echo "Test patient: {$testPatient->nama_pasien}\n";
    echo "  Diagnosa: '{$testPatient->diagnosa}'\n";
    
    // Try to process ICD-10 codes manually
    $icd10Ids = $method->invoke($controller, $testPatient->diagnosa);
    echo "  Processed ICD-10 IDs: " . implode(', ', $icd10Ids) . "\n";
    
    if (!empty($icd10Ids)) {
        echo "  Attaching ICD-10 codes...\n";
        $testPatient->icd10Codes()->attach($icd10Ids);
        echo "  ✅ ICD-10 codes attached successfully!\n";
        
        // Verify
        $updatedPatient = Pasien::with('icd10Codes')->find($testPatient->id);
        echo "  New ICD-10 codes: " . $updatedPatient->icd10Codes->pluck('code')->implode(', ') . "\n";
    } else {
        echo "  ❌ No ICD-10 codes found in diagnosa\n";
    }
} else {
    echo "  No test patient found\n";
}

echo "\n✅ Debug completed!\n";
