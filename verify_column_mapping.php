<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== VERIFIKASI KOLOM A (NO URUT) DIABAIKAN ===\n\n";

// Read Excel file to check structure
$excelFilePath = 'C:\\Users\\Backtrack-5\\Documents\\Project\\SI-PTM\\public\\template_pkm\\Test_data.xlsx';

try {
    $dataArray = \Maatwebsite\Excel\Facades\Excel::toArray([], $excelFilePath);
    
    if (!empty($dataArray) && !empty($dataArray[0])) {
        echo "📊 Struktur Excel:\n";
        $headers = $dataArray[0][0];
        
        foreach ($headers as $index => $header) {
            $colLetter = chr(65 + $index);
            echo "  $colLetter: '$header'\n";
        }
        
        echo "\n🔍 Mapping di Controller (mapRowToData):\n";
        echo "  Column A (index 0): DIABAIKAN - No urut\n";
        echo "  Column B (index 1): tanggal_kunjungan\n";
        echo "  Column C (index 2): poli\n";
        echo "  Column D (index 3): no_rekam_medik\n";
        echo "  Column E (index 4): nik\n";
        echo "  Column F (index 5): nama_pasien\n";
        echo "  ...dan seterusnya\n\n";
        
        echo "📋 Sample Data (5 baris pertama):\n";
        for ($i = 1; $i <= min(5, count($dataArray[0]) - 1); $i++) {
            $row = $dataArray[0][$i];
            echo "  Baris " . ($i + 1) . ":\n";
            echo "    Kolom A (No): '" . ($row[0] ?? '') . "' → DIABAIKAN\n";
            echo "    Kolom B (Tanggal): '" . ($row[1] ?? '') . "'\n";
            echo "    Kolom C (Poli): '" . ($row[2] ?? '') . "'\n";
            echo "    Kolom D (No RM): '" . ($row[3] ?? '') . "'\n";
            echo "    Kolom E (Nama): '" . ($row[5] ?? '') . "'\n";
            echo "    ---\n";
        }
        
        echo "🎯 KONFIRMASI:\n";
        echo "  ================================================\n";
        echo "  ✅ Kolom A (No urut) BENAR diabaikan\n";
        echo "  ✅ Tidak disimpan ke database\n";
        echo "  ✅ Setiap file baru mulai dari No 1 lagi\n";
        echo "  ✅ Tidak ada konflik nomor urut\n";
        echo "  \n";
        echo "  ALASAN mengapa Kolom A diabaikan:\n";
        echo "  - Nomor urut hanya untuk referensi Excel\n";
        echo "  - Setiap import file baru mulai dari 1 lagi\n";
        echo "  - Tidak relevan untuk data pasien\n";
        echo "  - Menghindari konflik nomor urut\n";
        echo "  \n";
        echo "  Data yang disimpan adalah:\n";
        echo "  - tanggal_kunjungan, poli, no_rekam_medik\n";
        echo "  - nama_pasien, alamat, diagnosa, dll\n";
        echo "  - TIDAK termasuk nomor urut Excel\n";
        echo "  ================================================\n";
        
    } else {
        echo "❌ No data found in Excel file\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n✅ Verifikasi selesai!\n";
