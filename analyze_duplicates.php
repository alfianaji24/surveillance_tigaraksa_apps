<?php

// Bootstrap Laravel
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use PhpOffice\PhpSpreadsheet\IOFactory;

echo "=== ANALISIS DUPLIKASI NO. REKAM MEDIK ===\n";

$filePath = 'C:\\Users\\Backtrack-5\\Documents\\Project\\SI-PTM\\public\\template_pkm\\Test_data.xlsx';

try {
    $spreadsheet = IOFactory::load($filePath);
    $worksheet = $spreadsheet->getActiveSheet();
    
    $totalRows = $worksheet->getHighestRow() - 1; // Exclude header
    echo "📊 Total rows in Excel: $totalRows\n\n";
    
    // Collect all no_rekam_medik
    $allRekamMedik = [];
    $duplicates = [];
    
    for ($row = 2; $row <= $worksheet->getHighestRow(); $row++) {
        $noRm = $worksheet->getCell('D' . $row)->getValue(); // Column D = no_rekam_medik
        $nama = $worksheet->getCell('F' . $row)->getValue(); // Column F = nama_pasien
        
        if ($noRm) {
            if (!isset($allRekamMedik[$noRm])) {
                $allRekamMedik[$noRm] = [];
            }
            $allRekamMedik[$noRm][] = [
                'row' => $row,
                'nama' => $nama
            ];
        }
    }
    
    // Find duplicates
    foreach ($allRekamMedik as $noRm => $records) {
        if (count($records) > 1) {
            $duplicates[$noRm] = $records;
        }
    }
    
    echo "📈 Analysis Results:\n";
    echo "Total unique No. Rekam Medik: " . count($allRekamMedik) . "\n";
    echo "Total duplicates: " . count($duplicates) . "\n\n";
    
    if (!empty($duplicates)) {
        echo "❌ Found Duplicate No. Rekam Medik:\n";
        echo "================================\n";
        
        foreach ($duplicates as $noRm => $records) {
            echo "No. RM: $noRm (appears " . count($records) . " times)\n";
            foreach ($records as $record) {
                echo "  - Row " . $record['row'] . ": " . $record['nama'] . "\n";
            }
            echo "\n";
        }
        
        echo "💡 Solutions:\n";
        echo "1. Clean data in Excel (remove duplicates)\n";
        echo "2. Import only unique records\n";
        echo "3. Add suffix to duplicates (RM001-1, RM001-2)\n";
        
    } else {
        echo "✅ No duplicates found!\n";
    }
    
    echo "\n📊 Summary:\n";
    echo "- Total rows: $totalRows\n";
    echo "- Unique No. RM: " . count($allRekamMedik) . "\n";
    echo "- Duplicates: " . count($duplicates) . "\n";
    echo "- Can import unique: " . (count($allRekamMedik) - count($duplicates)) . " records\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== ANALISIS SELESAI ===\n";
