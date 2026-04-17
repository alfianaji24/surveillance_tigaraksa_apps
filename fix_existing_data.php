<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Pasien;
use App\Http\Controllers\PasienController;

echo "=== Fix Existing Data with ICD-10 Codes ===\n\n";

// Read the original Excel file to get the diagnosa data
$excelFilePath = 'C:\\Users\\Backtrack-5\\Documents\\Project\\SI-PTM\\public\\template_pkm\\Test_data.xlsx';

echo "📖 Reading original Excel file...\n";

try {
    $dataArray = \Maatwebsite\Excel\Facades\Excel::toArray([], $excelFilePath);
    
    if (!empty($dataArray) && !empty($dataArray[0])) {
        echo "✅ Excel file loaded successfully\n";
        echo "📊 Total rows: " . count($dataArray[0]) . "\n\n";
        
        $controller = new PasienController();
        $reflection = new ReflectionClass($controller);
        
        // Get the processICD10Codes method
        $processICD10Method = $reflection->getMethod('processICD10Codes');
        $processICD10Method->setAccessible(true);
        
        // Get the mapRowToData method
        $mapRowMethod = $reflection->getMethod('mapRowToData');
        $mapRowMethod->setAccessible(true);
        
        $updatedCount = 0;
        $icd10AttachedCount = 0;
        
        // Process each row from Excel
        for ($i = 1; $i < count($dataArray[0]); $i++) { // Skip header row
            $row = $dataArray[0][$i];
            
            if (count($row) >= 17) {
                // Map the row data
                $mappedData = $mapRowMethod->invoke($controller, $row);
                
                $noRekamMedik = $mappedData['no_rekam_medik'] ?? '';
                $diagnosaText = $mappedData['diagnosa_icd10'] ?? '';
                
                if (!empty($noRekamMedik) && !empty($diagnosaText)) {
                    // Find the patient in database
                    $pasien = Pasien::where('no_rekam_medik', $noRekamMedik)->first();
                    
                    if ($pasien) {
                        // Update diagnosa field if empty
                        if (empty($pasien->diagnosa)) {
                            $pasien->diagnosa = $diagnosaText;
                            $pasien->save();
                            $updatedCount++;
                        }
                        
                        // Process and attach ICD-10 codes
                        $icd10Ids = $processICD10Method->invoke($controller, $diagnosaText);
                        
                        if (!empty($icd10Ids)) {
                            // Check if already attached to avoid duplicates
                            $existingCodes = $pasien->icd10Codes->pluck('id')->toArray();
                            $newCodes = array_diff($icd10Ids, $existingCodes);
                            
                            if (!empty($newCodes)) {
                                $pasien->icd10Codes()->attach($newCodes);
                                $icd10AttachedCount++;
                                
                                echo "✅ Updated: {$pasien->nama_pasien} (RM: {$noRekamMedik})\n";
                                echo "   Diagnosa: {$diagnosaText}\n";
                                echo "   ICD-10 Codes: " . $pasien->icd10Codes->pluck('code')->implode(', ') . "\n";
                                echo "   ---\n";
                            }
                        }
                    }
                }
            }
            
            // Show progress every 100 records
            if ($i % 100 == 0) {
                echo "📊 Processed $i rows...\n";
            }
        }
        
        echo "\n🎉 Update completed!\n";
        echo "📊 Patients updated with diagnosa: $updatedCount\n";
        echo "📊 Patients with ICD-10 codes attached: $icd10AttachedCount\n";
        
        // Show final statistics
        $totalPatients = Pasien::count();
        $patientsWithICD10 = Pasien::whereHas('icd10Codes')->count();
        $patientsWithDiagnosa = Pasien::whereNotNull('diagnosa')->where('diagnosa', '!=', '')->count();
        
        echo "\n📈 Final Statistics:\n";
        echo "  Total patients: $totalPatients\n";
        echo "  Patients with diagnosa text: $patientsWithDiagnosa\n";
        echo "  Patients with ICD-10 codes: $patientsWithICD10\n";
        echo "  Percentage with ICD-10: " . round(($patientsWithICD10 / $totalPatients) * 100, 2) . "%\n";
        
    } else {
        echo "❌ No data found in Excel file\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "📍 Line: " . $e->getLine() . "\n";
}

echo "\n✅ Fix process completed!\n";
