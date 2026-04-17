<?php

// Bootstrap Laravel
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\DiagnosaPKM;
use App\Imports\DiagnosaPKMImport;
use Maatwebsite\Excel\Facades\Excel;

echo "=== IMPORT SEMUA DATA EXCEL (1,688 RECORDS) ===\n";
echo "✅ Duplikat No. Rekam Medik DIPERBOLEHKAN\n\n";

$filePath = 'C:\\Users\\Backtrack-5\\Documents\\Project\\SI-PTM\\public\\template_pkm\\Test_data.xlsx';

// Clear existing data
DiagnosaPKM::query()->delete();
\Illuminate\Support\Facades\DB::statement('DELETE FROM sqlite_sequence WHERE name = \'diagnosa_p_k_m_s\'');
echo "🗑️  Cleared existing data\n\n";

echo "📤 Starting FULL Excel import (1,688 records)...\n";
echo "⏱️  This may take a while...\n\n";

$startTime = microtime(true);

try {
    $import = new DiagnosaPKMImport();
    Excel::import($import, $filePath);
    
    $endTime = microtime(true);
    $executionTime = round($endTime - $startTime, 2);
    
    echo "✅ Import completed successfully!\n";
    echo "⏱️  Execution time: $executionTime seconds\n\n";
    
} catch (\Exception $e) {
    echo "❌ Import failed: " . $e->getMessage() . "\n";
    echo "📍 Error location: " . $e->getFile() . ":" . $e->getLine() . "\n";
    
    // Show what we managed to import before failure
    $count = DiagnosaPKM::count();
    echo "📊 Records imported before failure: $count\n";
}

// Check final results
echo "📊 Final Results:\n";
$totalRecords = DiagnosaPKM::count();
echo "Total records in database: $totalRecords\n\n";

if ($totalRecords > 0) {
    echo "📈 Import Statistics:\n";
    
    // Count by poli
    $poliStats = DiagnosaPKM::select('poli', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
        ->groupBy('poli')
        ->orderBy('count', 'desc')
        ->get();
    
    echo "By Poli:\n";
    foreach ($poliStats as $stat) {
        echo "  - " . $stat->poli . ": " . $stat->count . " records\n";
    }
    
    // Count by status
    $statusStats = DiagnosaPKM::select('status', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
        ->groupBy('status')
        ->orderBy('count', 'desc')
        ->get();
    
    echo "\nBy Status:\n";
    foreach ($statusStats as $stat) {
        echo "  - " . $stat->status . ": " . $stat->count . " records\n";
    }
    
    // Show duplicate analysis
    echo "\n🔍 Duplicate Analysis:\n";
    $duplicateStats = DiagnosaPKM::select('no_rekam_medik', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
        ->groupBy('no_rekam_medik')
        ->havingRaw('count(*) > 1')
        ->orderBy('count', 'desc')
        ->get();
    
    echo "Patients with multiple visits: " . $duplicateStats->count() . "\n";
    if ($duplicateStats->count() > 0) {
        echo "Top 5 most frequent visitors:\n";
        foreach ($duplicateStats->take(5) as $stat) {
            $patient = DiagnosaPKM::where('no_rekam_medik', $stat->no_rekam_medik)->first();
            echo "  - " . $patient->nama_pasien . " (RM: " . $stat->no_rekam_medik . "): " . $stat->count . " visits\n";
        }
    }
    
    // Show date range
    $dateRange = DiagnosaPKM::selectRaw('MIN(tanggal_kunjungan) as min_date, MAX(tanggal_kunjungan) as max_date')->first();
    echo "\n📅 Date Range:\n";
    echo "  - From: " . \Carbon\Carbon::parse($dateRange->min_date)->format('d/m/Y') . "\n";
    echo "  - To: " . \Carbon\Carbon::parse($dateRange->max_date)->format('d/m/Y') . "\n";
    
    // Show first and last records
    echo "\n📋 Sample Records:\n";
    echo "First record: " . DiagnosaPKM::oldest()->first()->nama_pasien . " (RM: " . DiagnosaPKM::oldest()->first()->no_rekam_medik . ") on " . DiagnosaPKM::oldest()->first()->tanggal_kunjungan->format('d/m/Y') . "\n";
    echo "Last record: " . DiagnosaPKM::latest()->first()->nama_pasien . " (RM: " . DiagnosaPKM::latest()->first()->no_rekam_medik . ") on " . DiagnosaPKM::latest()->first()->tanggal_kunjungan->format('d/m/Y') . "\n";
    
    echo "\n🌐 View all data at: http://127.0.0.1:8000/diagnosa-pkm\n";
    echo "📊 Total patients: " . DiagnosaPKM::distinct('no_rekam_medik')->count('no_rekam_medik') . "\n";
    echo "📋 Total visits: $totalRecords\n";
}

echo "\n=== IMPORT COMPLETE ===\n";
