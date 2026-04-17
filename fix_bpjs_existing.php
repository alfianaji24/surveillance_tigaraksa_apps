<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Pasien;

echo "=== Fix Existing BPJS Data ===\n\n";

// Read the original Excel file to get the BPJS data
$excelFilePath = 'C:\\Users\\Backtrack-5\\Documents\\Project\\SI-PTM\\public\\template_pkm\\Test_data.xlsx';

echo "📖 Reading original Excel file for BPJS data...\n";

try {
    $dataArray = \Maatwebsite\Excel\Facades\Excel::toArray([], $excelFilePath);
    
    if (!empty($dataArray) && !empty($dataArray[0])) {
        echo "✅ Excel file loaded successfully\n";
        echo "📊 Total rows: " . count($dataArray[0]) . "\n\n";
        
        $updatedCount = 0;
        
        // Process each row from Excel
        for ($i = 1; $i < count($dataArray[0]); $i++) { // Skip header row
            $row = $dataArray[0][$i];
            
            if (count($row) >= 17) {
                $noRekamMedik = $row[3] ?? ''; // Column D
                $jenisPasien = $row[10] ?? ''; // Column K
                $noBpjs = $row[11] ?? ''; // Column L
                $jenisBayar = $row[12] ?? ''; // Column M
                
                if (!empty($noRekamMedik)) {
                    // Find the patient in database
                    $pasien = Pasien::where('no_rekam_medik', $noRekamMedik)->first();
                    
                    if ($pasien) {
                        $updated = false;
                        
                        // Update jenis_pasien if different
                        if (!empty($jenisPasien) && $pasien->jenis_pasien !== $jenisPasien) {
                            $pasien->jenis_pasien = $jenisPasien;
                            $updated = true;
                        }
                        
                        // Update no_bpjs - include 00000000000 if present
                        if (!empty($noBpjs) && $pasien->no_bpjs !== $noBpjs) {
                            $pasien->no_bpjs = $noBpjs;
                            $updated = true;
                        }
                        
                        // Update jenis_bayar if different
                        if (!empty($jenisBayar) && $pasien->jenis_bayar !== $jenisBayar) {
                            $pasien->jenis_bayar = $jenisBayar;
                            $updated = true;
                        }
                        
                        if ($updated) {
                            $pasien->save();
                            $updatedCount++;
                            
                            echo "✅ Updated: {$pasien->nama_pasien} (RM: {$noRekamMedik})\n";
                            echo "   Jenis Pasien: '{$pasien->jenis_pasien}'\n";
                            echo "   No BPJS: '{$pasien->no_bpjs}'\n";
                            echo "   Jenis Bayar: '{$pasien->jenis_bayar}'\n";
                            echo "   ---\n";
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
        echo "📊 Patients updated: $updatedCount\n";
        
        // Show final statistics
        $totalPatients = Pasien::count();
        $bpjsPatients = Pasien::where('jenis_pasien', 'like', '%bpjs%')->count();
        $patientsWithNoBpjs = Pasien::whereNotNull('no_bpjs')->count();
        $patientsWithJenisBayar = Pasien::whereNotNull('jenis_bayar')->where('jenis_bayar', '!=', '')->count();
        
        echo "\n📈 Final Statistics:\n";
        echo "  Total patients: $totalPatients\n";
        echo "  BPJS patients: $bpjsPatients\n";
        echo "  Patients with No BPJS: $patientsWithNoBpjs\n";
        echo "  Patients with Jenis Bayar: $patientsWithJenisBayar\n";
        
        // Show sample BPJS patients
        echo "\n📋 Sample BPJS patients after fix:\n";
        $sampleBpjsPatients = Pasien::where('jenis_pasien', 'like', '%bpjs%')
            ->limit(5)
            ->get();
            
        foreach ($sampleBpjsPatients as $pasien) {
            echo "  {$pasien->nama_pasien} (RM: {$pasien->no_rekam_medik})\n";
            echo "    No BPJS: '{$pasien->no_bpjs}'\n";
            echo "    Jenis Bayar: '{$pasien->jenis_bayar}'\n";
            echo "    ---\n";
        }
        
    } else {
        echo "❌ No data found in Excel file\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "📍 Line: " . $e->getLine() . "\n";
}

echo "\n✅ Fix process completed!\n";
