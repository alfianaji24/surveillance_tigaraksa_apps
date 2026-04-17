<?php

// Bootstrap Laravel
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\DiagnosaPKM;
use PhpOffice\PhpSpreadsheet\IOFactory;

echo "=== IMPORT DATA UNIK SAJA (Tanpa Duplikat) ===\n";

$filePath = 'C:\\Users\\Backtrack-5\\Documents\\Project\\SI-PTM\\public\\template_pkm\\Test_data.xlsx';

// Clear existing data
DiagnosaPKM::query()->delete();
\Illuminate\Support\Facades\DB::statement('DELETE FROM sqlite_sequence WHERE name = \'diagnosa_p_k_m_s\'');
echo "🗑️  Cleared existing data\n\n";

try {
    $spreadsheet = IOFactory::load($filePath);
    $worksheet = $spreadsheet->getActiveSheet();
    
    echo "📤 Processing unique records only...\n";
    
    $importedCount = 0;
    $skippedCount = 0;
    $processedRMs = [];
    
    for ($row = 2; $row <= $worksheet->getHighestRow(); $row++) {
        // Get data from Excel
        $rowData = [];
        $headers = [
            'a' => 'no',
            'b' => 'tanggal_kunjungan',
            'c' => 'poli',
            'd' => 'no_rekam_medik',
            'e' => 'nik',
            'f' => 'nama_pasien',
            'g' => 'alamat',
            'h' => 'tanggal_lahir',
            'i' => 'umur',
            'j' => 'jenis_kelamin',
            'k' => 'jenis_pasien',
            'l' => 'no_bpjs',
            'm' => 'jenis_bayar',
            'n' => 'anamnesa',
            'o' => 'diagnosa',
            'p' => 'pemeriksa',
            'q' => 'status',
            'r' => 'rs_rujukan'
        ];
        
        foreach ($headers as $col => $header) {
            $cellValue = $worksheet->getCell(strtoupper($col) . $row)->getValue();
            if ($header && $cellValue !== null) {
                $rowData[$header] = $cellValue;
            }
        }
        
        $noRm = $rowData['no_rekam_medik'] ?? '';
        
        // Skip if no RM or already processed
        if (empty($noRm) || in_array($noRm, $processedRMs)) {
            $skippedCount++;
            continue;
        }
        
        // Mark as processed
        $processedRMs[] = $noRm;
        
        // Create and save model
        try {
            $model = new DiagnosaPKM([
                'tanggal_kunjungan' => convertDate($rowData['tanggal_kunjungan'] ?? null),
                'poli' => $rowData['poli'] ?? null,
                'no_rekam_medik' => $noRm,
                'nik' => $rowData['nik'] ?? null,
                'nama_pasien' => $rowData['nama_pasien'] ?? null,
                'alamat' => $rowData['alamat'] ?? null,
                'tanggal_lahir' => convertDate($rowData['tanggal_lahir'] ?? null),
                'umur' => calculateUmur($rowData['tanggal_lahir'] ?? null),
                'jenis_kelamin' => $rowData['jenis_kelamin'] ?? null,
                'jenis_pasien' => $rowData['jenis_pasien'] ?? null,
                'no_bpjs' => (strtolower($rowData['jenis_pasien'] ?? '') === 'bpjs') ? ($rowData['no_bpjs'] ?? null) : null,
                'jenis_bayar' => $rowData['jenis_bayar'] ?? null,
                'anamnesa' => $rowData['anamnesa'] ?? null,
                'diagnosa' => $rowData['diagnosa'] ?? null,
                'pemeriksa' => $rowData['pemeriksa'] ?? null,
                'status' => $rowData['status'] ?? null,
                'rs_rujukan' => (strtolower($rowData['status'] ?? '') === 'dirujuk') ? ($rowData['rs_rujukan'] ?? null) : null,
            ]);
            
            $model->save();
            $importedCount++;
            
            // Progress indicator
            if ($importedCount % 100 === 0) {
                echo "✅ Imported $importedCount records...\n";
            }
            
        } catch (\Exception $e) {
            echo "❌ Error importing row $row: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n🎉 Import completed!\n";
    echo "📊 Results:\n";
    echo "  - Total rows processed: " . ($worksheet->getHighestRow() - 1) . "\n";
    echo "  - Unique records imported: $importedCount\n";
    echo "  - Duplicates skipped: $skippedCount\n";
    
    // Show statistics
    if ($importedCount > 0) {
        echo "\n📈 Database Statistics:\n";
        
        $poliStats = DiagnosaPKM::select('poli', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
            ->groupBy('poli')
            ->orderBy('count', 'desc')
            ->get();
        
        echo "By Poli:\n";
        foreach ($poliStats as $stat) {
            echo "  - " . $stat->poli . ": " . $stat->count . " records\n";
        }
        
        $statusStats = DiagnosaPKM::select('status', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
            ->groupBy('status')
            ->orderBy('count', 'desc')
            ->get();
        
        echo "\nBy Status:\n";
        foreach ($statusStats as $stat) {
            echo "  - " . $stat->status . ": " . $stat->count . " records\n";
        }
        
        echo "\n🌐 View all data at: http://127.0.0.1:8000/diagnosa-pkm\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

// Helper functions
function convertDate($dateValue) {
    if (empty($dateValue)) return null;
    
    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateValue)) {
        return $dateValue;
    }
    
    try {
        return \Carbon\Carbon::parse($dateValue)->format('Y-m-d');
    } catch (\Exception $e) {
        return null;
    }
}

function calculateUmur($tanggalLahir) {
    if (empty($tanggalLahir)) return null;
    
    try {
        $date = convertDate($tanggalLahir);
        if ($date) {
            return \Carbon\Carbon::parse($date)->age;
        }
    } catch (\Exception $e) {
        return null;
    }
    return null;
}

echo "\n=== IMPORT SELESAI ===\n";
