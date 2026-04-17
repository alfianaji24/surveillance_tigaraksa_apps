<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== TEST DOWNLOAD TEMPLATE FIX ===\n\n";

// Test 1: Check if form_pkm.xlsx file exists
echo "📊 Test 1: Template File Check...\n";
$templatePath = public_path('template_pkm/form_pkm.xlsx');

if (file_exists($templatePath)) {
    $fileSize = filesize($templatePath);
    $fileModTime = date('Y-m-d H:i:s', filemtime($templatePath));
    echo "  ✅ Template file exists: form_pkm.xlsx\n";
    echo "  📁 Path: {$templatePath}\n";
    echo "  📊 Size: " . number_format($fileSize) . " bytes\n";
    echo "  🕒 Modified: {$fileModTime}\n";
} else {
    echo "  ❌ Template file not found: {$templatePath}\n";
}

// Test 2: Check controller method
echo "\n📊 Test 2: Controller Method Check...\n";
try {
    $controller = new \App\Http\Controllers\PasienController();
    
    if (method_exists($controller, 'downloadTemplatePKM')) {
        echo "  ✅ Method downloadTemplatePKM exists\n";
        
        // Test the method
        $response = $controller->downloadTemplatePKM();
        
        echo "  ✅ Method executed successfully\n";
        echo "  📄 Response type: " . get_class($response) . "\n";
        
        if (method_exists($response, 'getFile')) {
            $file = $response->getFile();
            echo "  📁 Download file: " . $file->getFilename() . "\n";
            echo "  📊 File size: " . number_format($file->getSize()) . " bytes\n";
        }
        
        if (method_exists($response, 'headers')) {
            $contentType = $response->headers->get('Content-Type');
            $contentDisposition = $response->headers->get('Content-Disposition');
            echo "  📋 Content-Type: {$contentType}\n";
            echo "  📋 Content-Disposition: {$contentDisposition}\n";
        }
        
    } else {
        echo "  ❌ Method downloadTemplatePKM missing\n";
    }
    
} catch (\Exception $e) {
    echo "  ❌ Controller method error: " . $e->getMessage() . "\n";
    echo "  📍 File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

// Test 3: Check file content
echo "\n📊 Test 3: File Content Check...\n";
if (file_exists($templatePath)) {
    $content = file_get_contents($templatePath);
    $lines = explode("\n", $content);
    
    echo "  📄 Total lines: " . count($lines) . "\n";
    echo "  📋 Header: " . $lines[0] . "\n";
    echo "  📋 Sample row: " . $lines[1] . "\n";
    
    // Check required columns
    $headers = str_getcsv($lines[0]);
    $requiredColumns = ['No', 'Tanggal Kunjungan', 'Poli', 'No Rekam Medik', 'NIK', 'Nama Pasien'];
    
    echo "  🔍 Column check:\n";
    foreach ($requiredColumns as $col) {
        $found = in_array($col, $headers);
        echo "    " . ($found ? "✅" : "❌") . " {$col}\n";
    }
}

echo "\n🎯 TEST SUMMARY:\n";
echo "  ================================================\n";
echo "  ✅ Download template fix test complete!\n";
echo "  \n";
echo "  File: form_pkm.xlsx\n";
echo "  URL: http://127.0.0.1:8000/pasien/download-template-pkm\n";
echo "  ================================================\n";

echo "\n✅ Test completed!\n";
