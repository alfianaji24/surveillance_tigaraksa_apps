<?php

require_once 'vendor/autoload.php';

use App\Models\DiagnosaPKM;
use App\Imports\DiagnosaPKMImport;

// Test data array
$testData = [
    [
        'b' => '17/04/2026',
        'c' => 'Poli Umum',
        'd' => 'RM001',
        'e' => '1234567890123456',
        'f' => 'John Doe',
        'g' => 'Jl. Merdeka No. 123 Jakarta',
        'h' => '15/01/1990',
        'j' => 'L',
        'k' => 'Umum',
        'm' => 'Tunai',
        'o' => 'J11.0 Flu biasa',
        'p' => 'Dr. Smith',
        'q' => 'Sehat',
    ]
];

echo "Testing DiagnosaPKM Import...\n";
echo "ZipArchive Status: " . (class_exists('ZipArchive') ? 'AVAILABLE' : 'NOT AVAILABLE') . "\n\n";

try {
    $import = new DiagnosaPKMImport();
    
    foreach ($testData as $row) {
        echo "Processing row: " . json_encode($row) . "\n";
        
        $model = $import->model($row);
        
        if ($model) {
            echo "Model created successfully!\n";
            echo "Nama: " . $model->nama_pasien . "\n";
            echo "No RM: " . $model->no_rekam_medik . "\n";
            echo "Umur: " . $model->umur . "\n";
            echo "Kode ICD-10: " . $model->kode_icd_10 . "\n";
            
            // Save to database
            $model->save();
            echo "Data saved to database!\n";
        } else {
            echo "Model creation failed!\n";
        }
        echo "---\n";
    }
    
    echo "Test completed successfully!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\nChecking database...\n";
$data = DiagnosaPKM::all();
echo "Total records: " . $data->count() . "\n";
foreach ($data as $record) {
    echo "- " . $record->nama_pasien . " (RM: " . $record->no_rekam_medik . ")\n";
}
