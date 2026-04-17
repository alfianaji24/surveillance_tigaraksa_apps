<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\DiagnosaPKM;
use App\Imports\DiagnosaPKMImport;
use App\Imports\DiagnosaPKMCsvImport;
use Maatwebsite\Excel\Facades\Excel;

echo "=== Test Import Data PKM ===\n";
echo "File: C:\\Users\\Backtrack-5\\Documents\\Project\\SI-PTM\\public\\template_pkm\\Test_data.xlsx\n\n";

$filePath = 'C:\\Users\\Backtrack-5\\Documents\\Project\\SI-PTM\\public\\template_pkm\\Test_data.xlsx';

// Check if file exists
if (!file_exists($filePath)) {
    echo "❌ File tidak ditemukan: $filePath\n";
    exit(1);
}

echo "✅ File ditemukan\n";
echo "📁 File size: " . filesize($filePath) . " bytes\n";
echo "📅 File modified: " . date('Y-m-d H:i:s', filemtime($filePath)) . "\n\n";

// Check ZipArchive availability
echo "🔍 Checking ZipArchive availability...\n";
if (class_exists('ZipArchive')) {
    echo "✅ ZipArchive tersedia\n";
} else {
    echo "❌ ZipArchive tidak tersedia\n";
}

try {
    echo "\n🚀 Memulai import...\n";
    
    // Get file info
    $fileInfo = pathinfo($filePath);
    $extension = strtolower($fileInfo['extension']);
    echo "📄 Extension: $extension\n";
    
    // Count existing records
    $beforeCount = DiagnosaPKM::count();
    echo "📊 Jumlah record sebelum import: $beforeCount\n";
    
    // Import using appropriate class
    if ($extension === 'csv') {
        echo "📥 Menggunakan DiagnosaPKMCsvImport...\n";
        Excel::import(new DiagnosaPKMCsvImport, $filePath);
    } else {
        echo "📥 Menggunakan DiagnosaPKMImport...\n";
        Excel::import(new DiagnosaPKMImport, $filePath);
    }
    
    // Count after import
    $afterCount = DiagnosaPKM::count();
    $importedCount = $afterCount - $beforeCount;
    
    echo "✅ Import berhasil!\n";
    echo "📊 Jumlah record setelah import: $afterCount\n";
    echo "📈 Jumlah record yang diimport: $importedCount\n\n";
    
    // Show sample data
    echo "📋 Sample data yang diimport:\n";
    $sampleData = DiagnosaPKM::latest()->take(5)->get();
    
    foreach ($sampleData as $index => $data) {
        echo "Record " . ($index + 1) . ":\n";
        echo "  - Tanggal: " . $data->tanggal_kunjungan . "\n";
        echo "  - No RM: " . $data->no_rekam_medik . "\n";
        echo "  - Nama: " . $data->nama_pasien . "\n";
        echo "  - Poli: " . $data->poli . "\n";
        echo "  - Diagnosa: " . $data->diagnosa . "\n";
        echo "  - ICD-10: " . $data->kode_icd_10 . "\n";
        echo "  - Umur: " . $data->umur . "\n";
        echo "  ----------------------------------------\n";
    }
    
    // Check connection with Pasien
    echo "\n🔗 Mengecek koneksi dengan data pasien...\n";
    $connectedPatients = DiagnosaPKM::whereHas('pasien')->count();
    echo "📊 Data PKM yang terhubung dengan pasien: $connectedPatients\n";
    
    $unconnectedPatients = DiagnosaPKM::whereDoesntHave('pasien')->count();
    echo "📊 Data PKM yang belum terhubung dengan pasien: $unconnectedPatients\n";
    
    echo "\n✅ Test import selesai!\n";
    
} catch (\Exception $e) {
    echo "❌ Error saat import: " . $e->getMessage() . "\n";
    echo "📍 Line: " . $e->getLine() . "\n";
    echo "📁 File: " . $e->getFile() . "\n";
    
    // Show stack trace for debugging
    echo "\n🔍 Stack trace:\n";
    echo $e->getTraceAsString() . "\n";
}
