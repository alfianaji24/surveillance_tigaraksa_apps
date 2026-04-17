<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Pasien;
use App\Models\Icd10Code;

echo "=== Debug ICD-10 Codes Issue ===\n\n";

// 1. Cek sample data pasien yang sudah diimport
echo "📊 Sample 5 pasien terakhir:\n";
$samplePasiens = Pasien::latest()->take(5)->get();

foreach ($samplePasiens as $index => $pasien) {
    echo "Pasien " . ($index + 1) . " (ID: {$pasien->id}):\n";
    echo "  - No RM: {$pasien->no_rekam_medik}\n";
    echo "  - Nama: {$pasien->nama_pasien}\n";
    echo "  - ICD-10 Codes: " . $pasien->icd10Codes->pluck('code')->implode(', ') . "\n";
    echo "  - Jumlah ICD-10: " . $pasien->icd10Codes->count() . "\n";
    echo "  ---\n";
}

// 2. Cek data ICD-10 yang tersedia di database
echo "\n🔍 Cek 10 ICD-10 codes pertama di database:\n";
$icd10Codes = Icd10Code::take(10)->get();
foreach ($icd10Codes as $icd10) {
    echo "  - {$icd10->code}: {$icd10->display}\n";
}

// 3. Test proses ICD-10 dari diagnosa string
echo "\n🧪 Test proses ICD-10 codes:\n";
$testDiagnosaStrings = [
    'K30 Dyspepsia',
    'M54.5 Low back pain',
    'R50.9 Fever, unspecified',
    'K08.3 Retained dental root',
    'E14.7 Unspecified diabetes mellitus with multiple complications; I50.0 Congestive heart failure'
];

foreach ($testDiagnosaStrings as $diagnosa) {
    echo "  Diagnosa: '$diagnosa'\n";
    
    // Extract ICD-10 codes (same logic as in controller)
    $icd10Ids = [];
    if (!empty($diagnosa)) {
        $diagnosaCodes = explode(',', $diagnosa);
        foreach ($diagnosaCodes as $code) {
            $code = trim(strtoupper($code));
            
            // Extract first word/code from diagnosa text
            $words = explode(' ', trim($code));
            $extractedCode = $words[0] ?? '';
            
            if (!empty($extractedCode)) {
                $icd10 = Icd10Code::where('code', $extractedCode)->first();
                if ($icd10) {
                    $icd10Ids[] = $icd10->id;
                    echo "    ✅ Found: {$extractedCode} -> ID: {$icd10->id}\n";
                } else {
                    echo "    ❌ Not found: {$extractedCode}\n";
                }
            }
        }
    }
    
    echo "    Result IDs: " . implode(', ', $icd10Ids) . "\n";
    echo "  ---\n";
}

// 4. Cek apakah ada ICD-10 codes yang cocok dengan data Excel
echo "\n🔍 Cek ICD-10 codes dari data Excel:\n";
$excelFilePath = 'C:\\Users\\Backtrack-5\\Documents\\Project\\SI-PTM\\public\\template_pkm\\Test_data.xlsx';

try {
    $dataArray = \Maatwebsite\Excel\Facades\Excel::toArray([], $excelFilePath);
    
    if (!empty($dataArray) && !empty($dataArray[0])) {
        $uniqueDiagnosaCodes = [];
        
        for ($i = 1; $i <= min(10, count($dataArray[0]) - 1); $i++) {
            $row = $dataArray[0][$i];
            if (!empty($row[14])) { // Column O = diagnosa
                $diagnosa = $row[14];
                echo "  Row " . ($i + 1) . " Diagnosa: '$diagnosa'\n";
                
                // Extract ICD-10 code
                $words = explode(' ', trim($diagnosa));
                $extractedCode = $words[0] ?? '';
                
                if (!empty($extractedCode)) {
                    $uniqueDiagnosaCodes[] = $extractedCode;
                    
                    $icd10 = Icd10Code::where('code', $extractedCode)->first();
                    if ($icd10) {
                        echo "    ✅ Found in DB: {$extractedCode} -> {$icd10->display}\n";
                    } else {
                        echo "    ❌ Not found in DB: {$extractedCode}\n";
                    }
                }
            }
        }
        
        echo "\n📊 Unique ICD-10 codes found in Excel (first 10 rows):\n";
        $uniqueDiagnosaCodes = array_unique($uniqueDiagnosaCodes);
        foreach ($uniqueDiagnosaCodes as $code) {
            echo "  - $code\n";
        }
    }
} catch (\Exception $e) {
    echo "❌ Error reading Excel: " . $e->getMessage() . "\n";
}

echo "\n✅ Debug selesai!\n";
