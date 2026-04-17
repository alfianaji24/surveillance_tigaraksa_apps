<?php

// Bootstrap Laravel
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\DiagnosaPKM;
use App\Imports\DiagnosaPKMImport;
use Maatwebsite\Excel\Facades\Excel;

echo "=== Testing Import Without Unique Validation ===\n";

// Clear existing data
DiagnosaPKM::query()->delete();
\Illuminate\Support\Facades\DB::statement('DELETE FROM sqlite_sequence WHERE name = \'diagnosa_p_k_m_s\'');

$filePath = 'C:\\Users\\Backtrack-5\\Documents\\Project\\SI-PTM\\public\\template_pkm\\Test_data.xlsx';

try {
    echo "📤 Starting Excel import (first 10 rows only)...\n";
    
    // Create a modified import class without unique validation
    $import = new class extends \App\Imports\DiagnosaPKMImport {
        public function rules(): array {
            return [
                'tanggal_kunjungan' => 'required',
                'poli' => 'required',
                'no_rekam_medik' => 'required', // Remove unique validation
                'nama_pasien' => 'required',
                'alamat' => 'required',
                'tanggal_lahir' => 'required',
                'jenis_kelamin' => 'required|in:L,P',
                'jenis_pasien' => 'required',
                'jenis_bayar' => 'required',
                'diagnosa' => 'required',
                'pemeriksa' => 'required',
                'status' => 'required',
                'nik' => 'nullable',
                'no_bpjs' => 'nullable',
                'anamnesa' => 'nullable',
                'rs_rujukan' => 'nullable',
            ];
        }
    };
    
    Excel::import($import, $filePath);
    
    echo "✅ Import completed successfully!\n\n";
    
} catch (\Exception $e) {
    echo "❌ Import failed: " . $e->getMessage() . "\n";
}

// Check results
echo "📊 Checking import results...\n";
$count = DiagnosaPKM::count();
echo "Total records imported: " . $count . "\n\n";

if ($count > 0) {
    echo "First 5 imported records:\n";
    echo "========================\n";
    
    $records = DiagnosaPKM::take(5)->get();
    foreach ($records as $index => $record) {
        echo "Record #" . ($index + 1) . ":\n";
        echo "  Nama: " . $record->nama_pasien . "\n";
        echo "  No. RM: " . $record->no_rekam_medik . "\n";
        echo "  Tanggal Kunjungan: " . $record->tanggal_kunjungan->format('d/m/Y') . "\n";
        echo "  Poli: " . $record->poli . "\n";
        echo "  Umur: " . $record->umur . " tahun\n";
        echo "  Jenis Kelamin: " . $record->jenis_kelamin . "\n";
        echo "  Jenis Pasien: " . $record->jenis_pasien . "\n";
        echo "  No. BPJS: " . ($record->no_bpjs ?: '-') . "\n";
        echo "  Jenis Bayar: " . $record->jenis_bayar . "\n";
        echo "  Diagnosa: " . $record->diagnosa . "\n";
        echo "  Kode ICD-10: " . $record->kode_icd_10 . "\n";
        echo "  Pemeriksa: " . $record->pemeriksa . "\n";
        echo "  Status: " . $record->status . "\n";
        echo "  RS Rujukan: " . ($record->rs_rujukan ?: '-') . "\n";
        echo "  ------------------------\n";
    }
    
    // Check for duplicates
    echo "\n🔍 Checking for duplicate No. Rekam Medik:\n";
    $duplicates = DiagnosaPKM::select('no_rekam_medik')
        ->selectRaw('COUNT(*) as count')
        ->groupBy('no_rekam_medik')
        ->havingRaw('COUNT(*) > 1')
        ->get();
    
    if ($duplicates->count() > 0) {
        echo "Found duplicates:\n";
        foreach ($duplicates as $dup) {
            echo "  - " . $dup->no_rekam_medik . " (appears " . $dup->count . " times)\n";
        }
    } else {
        echo "✅ No duplicates found\n";
    }
}

echo "\n=== Test Complete ===\n";
