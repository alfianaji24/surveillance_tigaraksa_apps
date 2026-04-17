<?php

// Bootstrap Laravel
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use PhpOffice\PhpSpreadsheet\IOFactory;

echo "=== Debug Excel File Structure ===\n";
$filePath = 'C:\\Users\\Backtrack-5\\Documents\\Project\\SI-PTM\\public\\template_pkm\\Test_data.xlsx';

try {
    $spreadsheet = IOFactory::load($filePath);
    $worksheet = $spreadsheet->getActiveSheet();
    
    echo "Worksheet name: " . $worksheet->getTitle() . "\n";
    echo "Highest row: " . $worksheet->getHighestRow() . "\n";
    echo "Highest column: " . $worksheet->getHighestColumn() . "\n\n";
    
    // Read headers (first row)
    echo "Headers (Row 1):\n";
    $headers = [];
    for ($col = 'A'; $col <= 'R'; $col++) {
        $cellValue = $worksheet->getCell($col . '1')->getValue();
        $headers[$col] = $cellValue;
        echo "  $col: " . ($cellValue ?? 'EMPTY') . "\n";
    }
    
    echo "\nFirst few data rows:\n";
    for ($row = 2; $row <= min(5, $worksheet->getHighestRow()); $row++) {
        echo "Row $row:\n";
        $rowData = [];
        for ($col = 'A'; $col <= 'R'; $col++) {
            $cellValue = $worksheet->getCell($col . $row)->getValue();
            $rowData[$col] = $cellValue;
            echo "  $col: " . ($cellValue ?? 'EMPTY') . "\n";
        }
        echo "\n";
    }
    
    // Test with actual import class mapping
    echo "=== Testing Import Mapping ===\n";
    $import = new \App\Imports\DiagnosaPKMImport();
    
    for ($row = 2; $row <= min(3, $worksheet->getHighestRow()); $row++) {
        echo "Testing Row $row:\n";
        
        // Convert to array format that import expects
        $rowArray = [];
        foreach ($headers as $col => $header) {
            if ($header) {
                $rowArray[strtolower($header)] = $worksheet->getCell($col . $row)->getValue();
            }
        }
        
        // Also add column letters as fallback
        for ($col = 'A'; $col <= 'R'; $col++) {
            $rowArray[strtolower($col)] = $worksheet->getCell($col . $row)->getValue();
        }
        
        echo "Row data: " . json_encode($rowArray, JSON_PRETTY_PRINT) . "\n";
        
        try {
            $model = $import->model($rowArray);
            if ($model) {
                echo "✅ Model created for row $row\n";
            } else {
                echo "❌ Model creation failed for row $row (empty row?)\n";
            }
        } catch (\Exception $e) {
            echo "❌ Error creating model for row $row: " . $e->getMessage() . "\n";
        }
        echo "---\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Error reading Excel file: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== Debug Complete ===\n";
