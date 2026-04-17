<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Pasien;

echo "=== Analyze Missing Data ===\n\n";

// Read the original Excel file
$excelFilePath = 'C:\\Users\\Backtrack-5\\Documents\\Project\\SI-PTM\\public\\template_pkm\\Test_data.xlsx';

echo "📖 Reading original Excel file...\n";

try {
    $dataArray = \Maatwebsite\Excel\Facades\Excel::toArray([], $excelFilePath);
    
    if (!empty($dataArray) && !empty($dataArray[0])) {
        echo "✅ Excel file loaded successfully\n";
        echo "📊 Total rows in Excel: " . count($dataArray[0]) . " (including header)\n";
        echo "📊 Data rows: " . (count($dataArray[0]) - 1) . "\n\n";
        
        $controller = new \App\Http\Controllers\PasienController();
        $reflection = new ReflectionClass($controller);
        $mapRowMethod = $reflection->getMethod('mapRowToData');
        $mapRowMethod->setAccessible(true);
        
        $duplicateRows = [];
        $emptyRows = [];
        $errorRows = [];
        $validRows = [];
        $allNoRekamMedik = [];
        
        // Analyze each row
        for ($i = 1; $i < count($dataArray[0]); $i++) { // Skip header
            $row = $dataArray[0][$i];
            
            if (count($row) >= 17) {
                // Map row data
                $mappedData = $mapRowMethod->invoke($controller, $row);
                
                $noRekamMedik = $mappedData['no_rekam_medik'] ?? '';
                $namaPasien = $mappedData['nama_pasien'] ?? '';
                
                // Track all No Rekam Medik
                if (!empty($noRekamMedik)) {
                    $allNoRekamMedik[] = $noRekamMedik;
                }
                
                // Check for empty required fields
                if (empty($namaPasien) || empty($noRekamMedik)) {
                    $emptyRows[] = [
                        'row' => $i + 1,
                        'no_rekam_medik' => $noRekamMedik,
                        'nama_pasien' => $namaPasien,
                        'reason' => 'Empty required fields'
                    ];
                    continue;
                }
                
                // Check for duplicates in Excel itself
                if (count(array_keys($allNoRekamMedik, $noRekamMedik)) > 1) {
                    $duplicateRows[] = [
                        'row' => $i + 1,
                        'no_rekam_medik' => $noRekamMedik,
                        'nama_pasien' => $namaPasien,
                        'reason' => 'Duplicate in Excel'
                    ];
                    continue;
                }
                
                $validRows[] = [
                    'row' => $i + 1,
                    'no_rekam_medik' => $noRekamMedik,
                    'nama_pasien' => $namaPasien
                ];
                
            } else {
                $errorRows[] = [
                    'row' => $i + 1,
                    'reason' => 'Insufficient columns (only ' . count($row) . ' columns)'
                ];
            }
        }
        
        echo "📊 Analysis Results:\n";
        echo "  Valid rows: " . count($validRows) . "\n";
        echo "  Empty required fields: " . count($emptyRows) . "\n";
        echo "  Duplicates in Excel: " . count($duplicateRows) . "\n";
        echo "  Error rows: " . count($errorRows) . "\n";
        echo "  Total analyzed: " . (count($validRows) + count($emptyRows) + count($duplicateRows) + count($errorRows)) . "\n\n";
        
        // Check which duplicates are actually in database
        echo "🔍 Checking duplicates against database...\n";
        $dbNoRekamMedik = Pasien::pluck('no_rekam_medik')->toArray();
        $actualDuplicates = [];
        
        foreach ($validRows as $row) {
            if (in_array($row['no_rekam_medik'], $dbNoRekamMedik)) {
                $actualDuplicates[] = $row;
            }
        }
        
        echo "  Duplicates already in database: " . count($actualDuplicates) . "\n\n";
        
        // Show details
        if (!empty($emptyRows)) {
            echo "📋 Empty Required Fields (first 5):\n";
            foreach (array_slice($emptyRows, 0, 5) as $row) {
                echo "  Row {$row['row']}: No RM '{$row['no_rekam_medik']}', Nama: '{$row['nama_pasien']}'\n";
            }
            if (count($emptyRows) > 5) {
                echo "  ...and " . (count($emptyRows) - 5) . " more\n";
            }
            echo "\n";
        }
        
        if (!empty($duplicateRows)) {
            echo "📋 Duplicates in Excel (first 5):\n";
            foreach (array_slice($duplicateRows, 0, 5) as $row) {
                echo "  Row {$row['row']}: No RM '{$row['no_rekam_medik']}', Nama: '{$row['nama_pasien']}'\n";
            }
            if (count($duplicateRows) > 5) {
                echo "  ...and " . (count($duplicateRows) - 5) . " more\n";
            }
            echo "\n";
        }
        
        if (!empty($actualDuplicates)) {
            echo "📋 Actual Duplicates (already in database) (first 10):\n";
            foreach (array_slice($actualDuplicates, 0, 10) as $row) {
                echo "  Row {$row['row']}: No RM '{$row['no_rekam_medik']}', Nama: '{$row['nama_pasien']}'\n";
            }
            if (count($actualDuplicates) > 10) {
                echo "  ...and " . (count($actualDuplicates) - 10) . " more\n";
            }
            echo "\n";
        }
        
        if (!empty($errorRows)) {
            echo "📋 Error Rows:\n";
            foreach ($errorRows as $row) {
                echo "  Row {$row['row']}: {$row['reason']}\n";
            }
            echo "\n";
        }
        
        // Summary
        $expectedImport = count($validRows) - count($actualDuplicates);
        $actualImport = Pasien::count();
        
        echo "📈 Summary:\n";
        echo "  Expected to import: $expectedImport\n";
        echo "  Actually imported: $actualImport\n";
        echo "  Difference: " . ($expectedImport - $actualImport) . "\n";
        
        if ($expectedImport == $actualImport) {
            echo "  ✅ Import count is correct!\n";
        } else {
            echo "  ⚠️ There's a discrepancy in import count\n";
        }
        
    } else {
        echo "❌ No data found in Excel file\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "📍 Line: " . $e->getLine() . "\n";
}

echo "\n✅ Analysis completed!\n";
