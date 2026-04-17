<?php

// Bootstrap Laravel
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\DiagnosaPKM;

echo "=== Debug Row by Row Import ===\n";

$filePath = 'C:\\Users\\Backtrack-5\\Documents\\Project\\SI-PTM\\public\\template_pkm\\Test_data.xlsx';

// Clear existing data
DiagnosaPKM::query()->delete();
\Illuminate\Support\Facades\DB::statement('DELETE FROM sqlite_sequence WHERE name = \'diagnosa_p_k_m_s\'');

try {
    $spreadsheet = IOFactory::load($filePath);
    $worksheet = $spreadsheet->getActiveSheet();
    
    echo "Processing first 10 rows...\n\n";
    
    $import = new \App\Imports\DiagnosaPKMImport();
    
    for ($row = 2; $row <= min(11, $worksheet->getHighestRow()); $row++) {
        echo "=== Row $row ===\n";
        
        // Get row data
        $rowData = [];
        for ($col = 'A'; $col <= 'R'; $col++) {
            $cellValue = $worksheet->getCell($col . $row)->getValue();
            $rowData[strtolower($col)] = $cellValue;
        }
        
        // Also add header names
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
        
        echo "Data: " . json_encode($rowData, JSON_PRETTY_PRINT) . "\n";
        
        // Test validation
        try {
            $rules = $import->rules();
            $validator = \Illuminate\Support\Facades\Validator::make($rowData, $rules, $import->customValidationMessages());
            
            if ($validator->fails()) {
                echo "❌ Validation failed:\n";
                foreach ($validator->errors()->all() as $error) {
                    echo "  - $error\n";
                }
            } else {
                echo "✅ Validation passed\n";
                
                // Try to create model
                $model = $import->model($rowData);
                if ($model) {
                    $model->save();
                    echo "✅ Model created and saved: " . $model->nama_pasien . " (RM: " . $model->no_rekam_medik . ")\n";
                } else {
                    echo "❌ Model creation failed (empty row?)\n";
                }
            }
        } catch (\Exception $e) {
            echo "❌ Exception: " . $e->getMessage() . "\n";
        }
        
        echo "\n";
    }
    
    // Check final results
    $count = DiagnosaPKM::count();
    echo "=== Final Results ===\n";
    echo "Total records imported: $count\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== Debug Complete ===\n";
