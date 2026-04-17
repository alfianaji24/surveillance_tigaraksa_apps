<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Pasien;
use App\Http\Controllers\PasienController;

echo "=== Test Import Langsung ke Tabel Pasiens ===\n";
echo "File: C:\\Users\\Backtrack-5\\Documents\\Project\\SI-PTM\\public\\template_pkm\\Test_data.xlsx\n\n";

$filePath = 'C:\\Users\\Backtrack-5\\Documents\\Project\\SI-PTM\\public\\template_pkm\\Test_data.xlsx';

// Check if file exists
if (!file_exists($filePath)) {
    echo "❌ File tidak ditemukan: $filePath\n";
    exit(1);
}

echo "✅ File ditemukan\n";
echo "📁 File size: " . filesize($filePath) . " bytes\n\n";

try {
    // Create controller instance
    $controller = new PasienController();
    
    // Create mock request
    $request = new \Illuminate\Http\Request();
    $request->files->set('file', new \Illuminate\Http\UploadedFile(
        $filePath,
        'Test_data.xlsx',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        null,
        true
    ));
    
    echo "🚀 Memulai import ke tabel pasiens...\n";
    
    // Count existing records
    $beforeCount = Pasien::count();
    echo "📊 Jumlah pasien sebelum import: $beforeCount\n";
    
    // Call importPKM method
    $response = $controller->importPKM($request);
    
    // Get response data
    $responseData = json_decode($response->getContent(), true);
    
    if ($responseData['success']) {
        echo "✅ Import berhasil!\n";
        echo "📊 " . $responseData['message'] . "\n";
        echo "📈 Jumlah yang diimport: " . $responseData['imported'] . "\n";
        echo "⚠️ Jumlah yang dilewati: " . $responseData['skipped'] . "\n";
        
        if (!empty($responseData['errors'])) {
            echo "\n🔍 Detail Error (5 pertama):\n";
            foreach (array_slice($responseData['errors'], 0, 5) as $error) {
                echo "  - $error\n";
            }
            if (count($responseData['errors']) > 5) {
                echo "  - ...dan " . (count($responseData['errors']) - 5) . " error lainnya\n";
            }
        }
        
        // Count after import
        $afterCount = Pasien::count();
        $actualImported = $afterCount - $beforeCount;
        echo "\n📊 Jumlah pasien setelah import: $afterCount\n";
        echo "📈 Jumlah pasien yang bertambah: $actualImported\n";
        
        // Show sample data
        echo "\n📋 Sample data pasien yang diimport:\n";
        $sampleData = Pasien::latest()->take(5)->get();
        
        foreach ($sampleData as $index => $pasien) {
            echo "Pasien " . ($index + 1) . ":\n";
            echo "  - ID: " . $pasien->id . "\n";
            echo "  - Tanggal: " . $pasien->tanggal_kunjungan . "\n";
            echo "  - No RM: " . $pasien->no_rekam_medik . "\n";
            echo "  - Nama: " . $pasien->nama_pasien . "\n";
            echo "  - Poli: " . $pasien->poli . "\n";
            echo "  - Umur: " . $pasien->umur . "\n";
            echo "  - Jenis Kelamin: " . $pasien->jenis_kelamin . "\n";
            echo "  - Status: " . $pasien->status . "\n";
            echo "  - ICD-10 Codes: " . $pasien->icd10Codes->pluck('code')->implode(', ') . "\n";
            echo "  ----------------------------------------\n";
        }
        
    } else {
        echo "❌ Import gagal: " . $responseData['message'] . "\n";
    }
    
    echo "\n✅ Test import selesai!\n";
    
} catch (\Exception $e) {
    echo "❌ Error saat import: " . $e->getMessage() . "\n";
    echo "📍 Line: " . $e->getLine() . "\n";
    echo "📁 File: " . $e->getFile() . "\n";
    
    // Show stack trace for debugging
    echo "\n🔍 Stack trace:\n";
    echo $e->getTraceAsString() . "\n";
}
