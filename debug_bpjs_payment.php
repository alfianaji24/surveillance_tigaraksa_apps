<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Pasien;

echo "=== Debug BPJS and Payment Data Issue ===\n\n";

// 1. Check sample patients for BPJS and payment data
echo "📊 Sample 5 patients with BPJS data:\n";
$bpjsPatients = Pasien::where('jenis_pasien', 'bpjs')
    ->orWhere('jenis_pasien', 'BPJS')
    ->limit(5)
    ->get();

foreach ($bpjsPatients as $pasien) {
    echo "Patient: {$pasien->nama_pasien} (RM: {$pasien->no_rekam_medik})\n";
    echo "  Jenis Pasien: '{$pasien->jenis_pasien}'\n";
    echo "  No BPJS: '{$pasien->no_bpjs}'\n";
    echo "  Jenis Bayar: '{$pasien->jenis_bayar}'\n";
    echo "  ---\n";
}

// 2. Check Excel structure for BPJS and payment columns
echo "\n🔍 Checking Excel structure for BPJS and payment data:\n";
$excelFilePath = 'C:\\Users\\Backtrack-5\\Documents\\Project\\SI-PTM\\public\\template_pkm\\Test_data.xlsx';

try {
    $dataArray = \Maatwebsite\Excel\Facades\Excel::toArray([], $excelFilePath);
    
    if (!empty($dataArray) && !empty($dataArray[0])) {
        echo "✅ Excel file loaded\n";
        echo "📋 Headers (Row 1):\n";
        $headers = $dataArray[0][0];
        foreach ($headers as $index => $header) {
            $colLetter = chr(65 + $index);
            echo "  $colLetter: '$header'\n";
        }
        
        echo "\n📋 Sample data for BPJS patients (first 5):\n";
        $bpjsCount = 0;
        for ($i = 1; $i < count($dataArray[0]) && $bpjsCount < 5; $i++) {
            $row = $dataArray[0][$i];
            $jenisPasien = $row[10] ?? ''; // Column K
            $noBpjs = $row[11] ?? ''; // Column L
            $jenisBayar = $row[12] ?? ''; // Column M
            
            if (stripos($jenisPasien, 'bpjs') !== false) {
                echo "  Row " . ($i + 1) . ":\n";
                echo "    Jenis Pasien: '$jenisPasien'\n";
                echo "    No BPJS: '$noBpjs'\n";
                echo "    Jenis Bayar: '$jenisBayar'\n";
                echo "    ---\n";
                $bpjsCount++;
            }
        }
    }
} catch (\Exception $e) {
    echo "❌ Error reading Excel: " . $e->getMessage() . "\n";
}

// 3. Check current mapping in controller
echo "\n🔍 Current mapping in controller:\n";
echo "Based on the debug, the mapping should be:\n";
echo "  Column K (index 10): jenis_pasien\n";
echo "  Column L (index 11): no_bpjs\n";
echo "  Column M (index 12): jenis_bayar\n";

// 4. Statistics
echo "\n📊 Current database statistics:\n";
$totalPatients = Pasien::count();
$bpjsPatients = Pasien::where('jenis_pasien', 'like', '%bpjs%')->count();
$patientsWithNoBpjs = Pasien::whereNotNull('no_bpjs')->where('no_bpjs', '!=', '')->count();
$patientsWithJenisBayar = Pasien::whereNotNull('jenis_bayar')->where('jenis_bayar', '!=', '')->count();

echo "  Total patients: $totalPatients\n";
echo "  BPJS patients: $bpjsPatients\n";
echo "  Patients with No BPJS: $patientsWithNoBpjs\n";
echo "  Patients with Jenis Bayar: $patientsWithJenisBayar\n";

echo "\n✅ Debug completed!\n";
