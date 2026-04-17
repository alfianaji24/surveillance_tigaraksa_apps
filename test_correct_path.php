<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== TEST CORRECT TEMPLATE PATH ===\n\n";

// Test 1: Check if form_pkm.xlsx exists in template_upload_form
echo "📊 Test 1: Template File Check...\n";
$templatePath = public_path('template_upload_form/form_pkm.xlsx');

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

// Test 2: Test controller method with correct path
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

// Test 3: Compare old vs new path
echo "\n📊 Test 3: Path Comparison...\n";
$oldPath = public_path('template_pkm/form_pkm.xlsx');
$newPath = public_path('template_upload_form/form_pkm.xlsx');

echo "  📁 Old path: {$oldPath}\n";
echo "  📁 New path: {$newPath}\n";

echo "  Old path exists: " . (file_exists($oldPath) ? "✅" : "❌") . "\n";
echo "  New path exists: " . (file_exists($newPath) ? "✅" : "❌") . "\n";

echo "\n🎯 TEST SUMMARY:\n";
echo "  ================================================\n";
echo "  ✅ Correct path test complete!\n";
echo "  \n";
echo "  File: template_upload_form/form_pkm.xlsx\n";
echo "  URL: http://127.0.0.1:8000/pasien/download-template-pkm\n";
echo "  ================================================\n";

echo "\n✅ Test completed!\n";
