<?php

// Bootstrap Laravel
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\DiagnosaPKM;
use App\Imports\DiagnosaPKMImport;
use Illuminate\Support\Facades\Log;

echo "=== Testing Excel Import ===\n";
echo "ZipArchive Status: " . (class_exists('ZipArchive') ? 'AVAILABLE' : 'NOT AVAILABLE') . "\n\n";

// Test data
$testRow = [
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
];

try {
    echo "Testing import with sample data...\n";
    
    $import = new DiagnosaPKMImport();
    $model = $import->model($testRow);
    
    if ($model) {
        echo "✅ Model created successfully!\n";
        echo "   Nama: " . $model->nama_pasien . "\n";
        echo "   No RM: " . $model->no_rekam_medik . "\n";
        echo "   Tanggal Lahir: " . $model->tanggal_lahir . "\n";
        echo "   Umur: " . $model->umur . "\n";
        echo "   Poli: " . $model->poli . "\n";
        echo "   Diagnosa: " . $model->diagnosa . "\n";
        echo "   Kode ICD-10: " . $model->kode_icd_10 . "\n";
        
        // Save to database
        $model->save();
        echo "✅ Data saved to database!\n";
        
    } else {
        echo "❌ Model creation failed!\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "   File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n=== Checking Database ===\n";
try {
    $count = DiagnosaPKM::count();
    echo "Total records in database: " . $count . "\n";
    
    if ($count > 0) {
        $latest = DiagnosaPKM::latest()->first();
        echo "Latest record:\n";
        echo "  - " . $latest->nama_pasien . " (RM: " . $latest->no_rekam_medik . ")\n";
    }
} catch (\Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
}

echo "\n=== Test Complete ===\n";
