<?php

// Bootstrap Laravel
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\DiagnosaPKM;
use App\Imports\DiagnosaPKMImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

echo "=== Testing Real Excel File Import ===\n";
echo "File: C:\\Users\\Backtrack-5\\Documents\\Project\\SI-PTM\\public\\template_pkm\\Test_data.xlsx\n\n";

// Check if file exists
$filePath = 'C:\\Users\\Backtrack-5\\Documents\\Project\\SI-PTM\\public\\template_pkm\\Test_data.xlsx';
if (!file_exists($filePath)) {
    echo "❌ File not found: " . $filePath . "\n";
    exit(1);
}

echo "✅ File found!\n";
echo "File size: " . filesize($filePath) . " bytes\n\n";

// Clear existing data for clean test
DiagnosaPKM::query()->delete();
echo "🗑️  Cleared existing data\n\n";

// Also reset auto-increment
\Illuminate\Support\Facades\DB::statement('DELETE FROM sqlite_sequence WHERE name = \'diagnosa_p_k_m_s\'');
echo "🔄 Reset auto-increment\n\n";

try {
    echo "📤 Starting Excel import...\n";
    
    $import = new DiagnosaPKMImport();
    Excel::import($import, $filePath);
    
    echo "✅ Import completed successfully!\n\n";
    
} catch (\Exception $e) {
    echo "❌ Import failed: " . $e->getMessage() . "\n";
    echo "   File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "   Stack trace:\n" . $e->getTraceAsString() . "\n\n";
}

// Check results
echo "📊 Checking import results...\n";
try {
    $count = DiagnosaPKM::count();
    echo "Total records imported: " . $count . "\n\n";
    
    if ($count > 0) {
        $records = DiagnosaPKM::all();
        echo "Imported data:\n";
        echo "================\n";
        
        foreach ($records as $index => $record) {
            echo "Record #" . ($index + 1) . ":\n";
            echo "  Nama: " . $record->nama_pasien . "\n";
            echo "  No. RM: " . $record->no_rekam_medik . "\n";
            echo "  Tanggal Kunjungan: " . $record->tanggal_kunjungan->format('d/m/Y') . "\n";
            echo "  Poli: " . $record->poli . "\n";
            echo "  NIK: " . ($record->nik ?: '-') . "\n";
            echo "  Alamat: " . $record->alamat . "\n";
            echo "  Tanggal Lahir: " . $record->tanggal_lahir->format('d/m/Y') . "\n";
            echo "  Umur: " . $record->umur . " tahun\n";
            echo "  Jenis Kelamin: " . $record->jenis_kelamin . "\n";
            echo "  Jenis Pasien: " . $record->jenis_pasien . "\n";
            echo "  No. BPJS: " . ($record->no_bpjs ?: '-') . "\n";
            echo "  Jenis Bayar: " . $record->jenis_bayar . "\n";
            echo "  Anamnesa: " . ($record->anamnesa ?: '-') . "\n";
            echo "  Diagnosa: " . $record->diagnosa . "\n";
            echo "  Kode ICD-10: " . $record->kode_icd_10 . "\n";
            echo "  Pemeriksa: " . $record->pemeriksa . "\n";
            echo "  Status: " . $record->status . "\n";
            echo "  RS Rujukan: " . ($record->rs_rujukan ?: '-') . "\n";
            echo "  Created: " . $record->created_at->format('d/m/Y H:i:s') . "\n";
            echo "  ------------------------\n";
        }
    } else {
        echo "❌ No data was imported!\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Database check failed: " . $e->getMessage() . "\n";
}

echo "\n=== Test Complete ===\n";
