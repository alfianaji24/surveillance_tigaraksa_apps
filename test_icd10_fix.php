<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Pasien;
use App\Http\Controllers\PasienController;

echo "=== Test ICD-10 Codes Fix ===\n\n";

// Test 1: Test processICD10Codes function directly
echo "🧪 Test processICD10Codes function:\n";
$controller = new PasienController();

// Use reflection to access private method
$reflection = new ReflectionClass($controller);
$method = $reflection->getMethod('processICD10Codes');
$method->setAccessible(true);

$testCases = [
    'K30 Dyspepsia',
    'M54.5 Low back pain',
    'R50.9 Fever, unspecified',
    'K08.3 Retained dental root',
    'E14.7 Unspecified diabetes mellitus with multiple complications; I50.0 Congestive heart failure',
    'Z09.8 Follow-up exam after other treatment for other conditions; I25.9 Chronic ischaemic heart disease, unspecified'
];

foreach ($testCases as $testCase) {
    $result = $method->invoke($controller, $testCase);
    echo "  Input: '$testCase'\n";
    echo "  Result IDs: " . implode(', ', $result) . "\n";
    echo "  Count: " . count($result) . "\n";
    echo "  ---\n";
}

// Test 2: Import 1 sample record to test full process
echo "\n🚀 Test import 1 sample record:\n";

// Create a small test data
$testData = [
    [
        'tanggal_kunjungan' => '2026-04-17',
        'poli' => 'Umum',
        'no_rekam_medik' => 'TEST001',
        'nik' => '1234567890123456',
        'nama_pasien' => 'TEST PATIENT',
        'alamat' => 'Test Address',
        'no_hp' => '08123456789',
        'tanggal_lahir' => '1990-01-01',
        'umur' => '36 Thn',
        'jenis_kelamin' => 'L',
        'jenis_pasien' => 'BPJS',
        'no_bpjs' => '1234567890',
        'jenis_bayar' => 'PBI',
        'anamnesa' => 'Test anamnesa',
        'diagnosa_icd10' => 'K30 Dyspepsia',
        'pemeriksa' => 'Dr. Test',
        'status' => 'Dilayani',
        'rs_rujukan' => ''
    ]
];

try {
    // Delete existing test record if any
    Pasien::where('no_rekam_medik', 'TEST001')->delete();
    
    // Process the test data
    foreach ($testData as $index => $rowData) {
        $processedData = $controller->processPKMRowData($rowData);
        $pasien = Pasien::create($processedData);
        
        // Process ICD10 codes
        if (!empty($rowData['diagnosa_icd10'])) {
            $icd10Ids = $method->invoke($controller, $rowData['diagnosa_icd10']);
            if (!empty($icd10Ids)) {
                $pasien->icd10Codes()->attach($icd10Ids);
                echo "  ✅ ICD-10 codes attached: " . implode(', ', $icd10Ids) . "\n";
            } else {
                echo "  ❌ No ICD-10 codes found\n";
            }
        }
        
        echo "  ✅ Test patient created with ID: {$pasien->id}\n";
        
        // Verify the ICD-10 codes were saved
        $savedPasien = Pasien::with('icd10Codes')->find($pasien->id);
        echo "  📊 Saved ICD-10 codes: " . $savedPasien->icd10Codes->pluck('code')->implode(', ') . "\n";
        echo "  📊 Count: " . $savedPasien->icd10Codes->count() . "\n";
    }
    
    echo "\n✅ Test import successful!\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "📍 Line: " . $e->getLine() . "\n";
}

// Test 3: Check existing patients for ICD-10 codes
echo "\n📊 Check existing patients for ICD-10 codes:\n";
$existingPatients = Pasien::with('icd10Codes')->where('icd10Codes', '>', 0)->limit(5)->get();

if ($existingPatients->count() > 0) {
    foreach ($existingPatients as $pasien) {
        echo "  Patient: {$pasien->nama_pasien} (RM: {$pasien->no_rekam_medik})\n";
        echo "    ICD-10: " . $pasien->icd10Codes->pluck('code')->implode(', ') . "\n";
    }
} else {
    echo "  ❌ No existing patients found with ICD-10 codes\n";
}

echo "\n✅ Test selesai!\n";
