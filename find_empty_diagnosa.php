<?php

// Bootstrap Laravel
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use PhpOffice\PhpSpreadsheet\IOFactory;

echo "=== MENCARI BARIS DENGAN DIAGNOSA KOSONG ===\n";

$filePath = 'C:\\Users\\Backtrack-5\\Documents\\Project\\SI-PTM\\public\\template_pkm\\Test_data.xlsx';

try {
    $spreadsheet = IOFactory::load($filePath);
    $worksheet = $spreadsheet->getActiveSheet();
    
    $emptyDiagnosaRows = [];
    $totalRows = 0;
    
    for ($row = 2; $row <= $worksheet->getHighestRow(); $row++) {
        $totalRows++;
        
        // Get data
        $noRm = $worksheet->getCell('D' . $row)->getValue();
        $nama = $worksheet->getCell('F' . $row)->getValue();
        $diagnosa = $worksheet->getCell('O' . $row)->getValue(); // Column O = diagnosa
        $poli = $worksheet->getCell('C' . $row)->getValue();
        $tanggal = $worksheet->getCell('B' . $row)->getValue();
        
        // Check if diagnosa is empty or null
        if (empty($diagnosa) || trim($diagnosa) === '') {
            $emptyDiagnosaRows[] = [
                'row' => $row,
                'no_rm' => $noRm,
                'nama' => $nama,
                'poli' => $poli,
                'tanggal' => $tanggal,
                'diagnosa' => $diagnosa
            ];
            
            echo "❌ Row $row: $nama (RM: $noRm) - Diagnosa KOSONG\n";
            
            // Stop after finding 5 examples
            if (count($emptyDiagnosaRows) >= 5) {
                break;
            }
        }
    }
    
    echo "\n📊 Summary:\n";
    echo "Total rows checked: $totalRows\n";
    echo "Rows with empty diagnosa: " . count($emptyDiagnosaRows) . "\n";
    
    if (!empty($emptyDiagnosaRows)) {
        echo "\n🔍 First 5 rows with empty diagnosa:\n";
        foreach ($emptyDiagnosaRows as $row) {
            echo "Row {$row['row']}: {$row['nama']} (RM: {$row['no_rm']})\n";
            echo "  - Poli: {$row['poli']}\n";
            echo "  - Tanggal: {$row['tanggal']}\n";
            echo "  - Diagnosa: '" . $row['diagnosa'] . "'\n\n";
        }
        
        echo "💡 Solutions:\n";
        echo "1. Fill empty diagnosa in Excel file\n";
        echo "2. Make diagnosa field optional in validation\n";
        echo "3. Skip rows with empty diagnosa during import\n";
    } else {
        echo "✅ No empty diagnosa found!\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== ANALISIS SELESAI ===\n";
