<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Pasien;

echo "=== IMPORT ALL DATA (ALLOWING DUPLICATES) ===\n\n";

// Step 1: Clear all data
echo "🗑️ Step 1: Deleting all existing data...\n";
try {
    $countBefore = Pasien::count();
    echo "📊 Current pasien count: $countBefore\n";
    
    // Delete all relationships first
    \DB::table('pasien_icd10')->truncate();
    
    // Delete all pasiens
    Pasien::truncate();
    
    $countAfter = Pasien::count();
    echo "✅ All data deleted!\n";
    echo "📊 Pasien count after deletion: $countAfter\n\n";
    
} catch (\Exception $e) {
    echo "❌ Error deleting data: " . $e->getMessage() . "\n";
    exit(1);
}

// Step 2: Import all data without duplicate check
echo "📥 Step 2: Importing all 1688 data rows...\n";
$excelFilePath = 'C:\\Users\\Backtrack-5\\Documents\\Project\\SI-PTM\\public\\template_pkm\\Test_data.xlsx';

if (!file_exists($excelFilePath)) {
    echo "❌ Excel file not found: $excelFilePath\n";
    exit(1);
}

try {
    // Read Excel file
    $dataArray = \Maatwebsite\Excel\Facades\Excel::toArray([], $excelFilePath);
    
    if (empty($dataArray) || empty($dataArray[0])) {
        echo "❌ No data found in Excel file\n";
        exit(1);
    }
    
    echo "✅ Excel file loaded successfully\n";
    echo "📊 Total rows to process: " . (count($dataArray[0]) - 1) . "\n\n";
    
    $controller = new \App\Http\Controllers\PasienController();
    $reflection = new ReflectionClass($controller);
    
    // Get methods
    $mapRowMethod = $reflection->getMethod('mapRowToData');
    $mapRowMethod->setAccessible(true);
    
    $processICD10Method = $reflection->getMethod('processICD10Codes');
    $processICD10Method->setAccessible(true);
    
    $processPKMRowMethod = $reflection->getMethod('processPKMRowData');
    $processPKMRowMethod->setAccessible(true);
    
    $importedCount = 0;
    $skippedCount = 0;
    $errors = [];
    
    // Process each row
    for ($i = 1; $i < count($dataArray[0]); $i++) { // Skip header
        $row = $dataArray[0][$i];
        
        if (count($row) >= 17) {
            try {
                // Map row data
                $mappedData = $mapRowMethod->invoke($controller, $row);
                
                // Validate required fields
                if (empty($mappedData['nama_pasien']) || empty($mappedData['no_rekam_medik'])) {
                    $skippedCount++;
                    $errors[] = "Baris " . ($i + 1) . ": Nama pasien dan No Rekam Medik wajib diisi";
                    continue;
                }
                
                // Process data
                $processedData = $processPKMRowMethod->invoke($controller, $mappedData);
                
                // Save pasien (allowing duplicates)
                $pasien = Pasien::create($processedData);
                
                // Process ICD-10 codes
                if (!empty($mappedData['diagnosa_icd10'])) {
                    $icd10Ids = $processICD10Method->invoke($controller, $mappedData['diagnosa_icd10']);
                    if (!empty($icd10Ids)) {
                        $pasien->icd10Codes()->attach($icd10Ids);
                    }
                }
                
                $importedCount++;
                
                // Show progress every 100 records
                if ($importedCount % 100 == 0) {
                    echo "📊 Imported: $importedCount records...\n";
                }
                
            } catch (\Exception $e) {
                $skippedCount++;
                $errors[] = "Baris " . ($i + 1) . ": " . $e->getMessage();
            }
        }
    }
    
    echo "\n🎉 Import completed!\n";
    echo "📊 Successfully imported: $importedCount records\n";
    echo "⚠️ Skipped: $skippedCount records\n";
    
    if (!empty($errors)) {
        echo "\n🔍 First 5 errors:\n";
        foreach (array_slice($errors, 0, 5) as $error) {
            echo "  - $error\n";
        }
        if (count($errors) > 5) {
            echo "  - ...and " . (count($errors) - 5) . " more errors\n";
        }
    }
    
    // Final statistics
    $finalCount = Pasien::count();
    $bpjsCount = Pasien::where('jenis_pasien', 'like', '%bpjs%')->count();
    $withICD10 = Pasien::whereHas('icd10Codes')->count();
    $withNoBpjs = Pasien::whereNotNull('no_bpjs')->count();
    
    echo "\n📈 Final Statistics:\n";
    echo "  Total pasiens: $finalCount\n";
    echo "  BPJS patients: $bpjsCount\n";
    echo "  With ICD-10 codes: $withICD10\n";
    echo "  With No BPJS: $withNoBpjs\n";
    
    // Check for duplicates
    $duplicateAnalysis = \DB::table('pasiens')
        ->select('no_rekam_medik', \DB::raw('count(*) as count'))
        ->groupBy('no_rekam_medik')
        ->having('count', '>', 1)
        ->get();
    
    echo "  No Rekam Medik duplicates: " . $duplicateAnalysis->count() . "\n";
    
    if ($duplicateAnalysis->count() > 0) {
        echo "\n📋 Sample duplicates (first 5):\n";
        foreach ($duplicateAnalysis->take(5) as $dup) {
            echo "  No RM '{$dup->no_rekam_medik}': {$dup->count} visits\n";
        }
    }
    
    // Sample data
    echo "\n📋 Sample imported data:\n";
    $samplePasiens = Pasien::with('icd10Codes')->latest()->take(3)->get();
    foreach ($samplePasiens as $pasien) {
        echo "  {$pasien->nama_pasien} (RM: {$pasien->no_rekam_medik})\n";
        echo "    Poli: {$pasien->poli}, Jenis: {$pasien->jenis_pasien}\n";
        echo "    No BPJS: '{$pasien->no_bpjs}', Bayar: {$pasien->jenis_bayar}\n";
        echo "    ICD-10: " . $pasien->icd10Codes->pluck('code')->implode(', ') . "\n";
        echo "    ---\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Error during import: " . $e->getMessage() . "\n";
    echo "📍 Line: " . $e->getLine() . "\n";
    echo "📁 File: " . $e->getFile() . "\n";
}

echo "\n✅ Import all data process completed!\n";
echo "\n🎯 RESULT: All data imported as bank data kunjungan pasien\n";
echo "   - Pasien bisa datang berkali-kali\n";
echo "   - No Rekam Medik boleh duplicate\n";
echo "   - Setiap baris = satu kunjungan\n";
