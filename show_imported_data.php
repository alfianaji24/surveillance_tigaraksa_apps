<?php

// Bootstrap Laravel
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\DiagnosaPKM;

echo "=== DATA HASIL IMPORT EXCEL ===\n\n";

$totalRecords = DiagnosaPKM::count();
echo "📊 Total records in database: $totalRecords\n\n";

if ($totalRecords > 0) {
    echo "📋 Latest 10 imported records:\n";
    echo "==============================\n";
    
    $records = DiagnosaPKM::latest()->take(10)->get();
    
    foreach ($records as $index => $record) {
        echo "Record #" . ($index + 1) . ":\n";
        echo "  👤 Nama: " . $record->nama_pasien . "\n";
        echo "  🏥 No. RM: " . $record->no_rekam_medik . "\n";
        echo "  📅 Tanggal Kunjungan: " . $record->tanggal_kunjungan->format('d/m/Y') . "\n";
        echo "  🏢 Poli: " . $record->poli . "\n";
        echo "  🆔 NIK: " . ($record->nik ?: '-') . "\n";
        echo "  📍 Alamat: " . $record->alamat . "\n";
        echo "  🎂 Tanggal Lahir: " . $record->tanggal_lahir->format('d/m/Y') . "\n";
        echo "  👶 Umur: " . $record->umur . " tahun\n";
        echo "  ⚧ Jenis Kelamin: " . $record->jenis_kelamin . "\n";
        echo "  🏥 Jenis Pasien: " . $record->jenis_pasien . "\n";
        echo "  💳 No. BPJS: " . ($record->no_bpjs ?: '-') . "\n";
        echo "  💰 Jenis Bayar: " . $record->jenis_bayar . "\n";
        echo "  📝 Anamnesa: " . ($record->anamnesa ?: '-') . "\n";
        echo "  🔬 Diagnosa: " . $record->diagnosa . "\n";
        echo "  🏷️  Kode ICD-10: " . $record->kode_icd_10 . "\n";
        echo "  👨‍⚕️  Pemeriksa: " . $record->pemeriksa . "\n";
        echo "  📊 Status: " . $record->status . "\n";
        echo "  🏥 RS Rujukan: " . ($record->rs_rujukan ?: '-') . "\n";
        echo "  ⏰ Created: " . $record->created_at->format('d/m/Y H:i:s') . "\n";
        echo "  ----------------------------------------\n";
    }
    
    echo "\n🌐 Cara melihat data di browser:\n";
    echo "1. Buka: http://127.0.0.1:8000/diagnosa-pkm/index\n";
    echo "2. Atau: http://127.0.0.1:8000/diagnosa-pkm\n";
    echo "3. Data akan tampil dalam bentuk tabel yang rapi\n";
    
} else {
    echo "❌ Tidak ada data di database\n";
    echo "📝 Pastikan import sudah berhasil dijalankan\n";
}

echo "\n=== SELESAI ===\n";
