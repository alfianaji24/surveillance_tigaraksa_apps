<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== DEBUG UPLOAD EXCEL ISSUE ===\n\n";

// Test 1: Check if file exists in Downloads
echo "📊 Test 1: File Existence Check...\n";
$filePath = 'C:\Users\Backtrack-5\Downloads\Diagnosa.xlsx';

if (file_exists($filePath)) {
    $fileSize = filesize($filePath);
    $fileModTime = date('Y-m-d H:i:s', filemtime($filePath));
    echo "  ✅ File exists: Diagnosa.xlsx\n";
    echo "  📁 Path: {$filePath}\n";
    echo "  📊 Size: " . number_format($fileSize) . " bytes\n";
    echo "  🕒 Modified: {$fileModTime}\n";
    
    // Check file extension
    $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    echo "  📄 Extension: {$extension}\n";
    
    if (in_array($extension, ['xlsx', 'xls', 'csv'])) {
        echo "  ✅ File extension is supported\n";
    } else {
        echo "  ❌ File extension not supported\n";
    }
} else {
    echo "  ❌ File not found: {$filePath}\n";
}

// Test 2: Check upload permissions and temp directory
echo "\n📊 Test 2: Upload Environment Check...\n";
echo "  📁 Upload tmp dir: " . sys_get_temp_dir() . "\n";
echo "  📊 Upload max filesize: " . ini_get('upload_max_filesize') . "\n";
echo "  📊 Post max size: " . ini_get('post_max_size') . "\n";
echo "  📊 Memory limit: " . ini_get('memory_limit') . "\n";
echo "  📊 Max execution time: " . ini_get('max_execution_time') . "s\n";

// Check if temp directory is writable
$tempDir = sys_get_temp_dir();
if (is_writable($tempDir)) {
    echo "  ✅ Temp directory is writable\n";
} else {
    echo "  ❌ Temp directory is not writable\n";
}

// Test 3: Test Excel file reading directly
echo "\n📊 Test 3: Excel File Reading Test...\n";
if (file_exists($filePath)) {
    try {
        // Test with simple file reading
        $fileHandle = fopen($filePath, 'rb');
        if ($fileHandle) {
            $header = fread($fileHandle, 1024);
            fclose($fileHandle);
            echo "  ✅ File can be opened in binary mode\n";
            echo "  📄 Header preview: " . substr(bin2hex($header), 0, 32) . "...\n";
            
            // Check if it's a valid Excel file (xlsx starts with PK)
            if (substr($header, 0, 2) === 'PK') {
                echo "  ✅ Valid XLSX file signature (PK)\n";
            } else {
                echo "  ⚠️  Not a standard XLSX file signature\n";
            }
        } else {
            echo "  ❌ Cannot open file\n";
        }
    } catch (\Exception $e) {
        echo "  ❌ File reading error: " . $e->getMessage() . "\n";
    }
}

// Test 4: Check Laravel Excel processing
echo "\n📊 Test 4: Laravel Excel Processing Test...\n";
if (file_exists($filePath)) {
    try {
        // Simulate file upload
        $file = new \Illuminate\Http\UploadedFile(
            $filePath,
            'Diagnosa.xlsx',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            null,
            true
        );
        
        echo "  ✅ UploadedFile object created successfully\n";
        echo "  📊 Original name: " . $file->getClientOriginalName() . "\n";
        echo "  📊 MIME type: " . $file->getMimeType() . "\n";
        echo "  📊 Extension: " . $file->getClientOriginalExtension() . "\n";
        echo "  📊 Size: " . $file->getSize() . " bytes\n";
        
        // Test validation
        $validator = \Illuminate\Support\Facades\Validator::make(
            ['file' => $file],
            ['file' => 'required|mimes:xlsx,xls,csv|max:10240']
        );
        
        if ($validator->fails()) {
            echo "  ❌ Validation failed:\n";
            foreach ($validator->errors()->all() as $error) {
                echo "    - {$error}\n";
            }
        } else {
            echo "  ✅ File validation passed\n";
        }
        
    } catch (\Exception $e) {
        echo "  ❌ Laravel file processing error: " . $e->getMessage() . "\n";
    }
}

// Test 5: Check importPKM method with this file
echo "\n📊 Test 5: Import PKM Method Test...\n";
if (file_exists($filePath)) {
    try {
        $controller = new \App\Http\Controllers\PasienController();
        
        // Create mock request
        $request = new \Illuminate\Http\Request();
        $uploadedFile = new \Illuminate\Http\UploadedFile(
            $filePath,
            'Diagnosa.xlsx',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            null,
            true
        );
        
        $request->files->set('file', $uploadedFile);
        
        echo "  ✅ Mock request created\n";
        echo "  🔄 Testing importPKM method...\n";
        
        // Test the method
        $result = $controller->importPKM($request);
        
        echo "  ✅ importPKM method executed\n";
        echo "  📊 Response: " . $result->getContent() . "\n";
        
    } catch (\Exception $e) {
        echo "  ❌ importPKM method error: " . $e->getMessage() . "\n";
        echo "  📍 Stack trace: " . $e->getTraceAsString() . "\n";
    }
}

// Test 6: Check recent Laravel logs for errors
echo "\n📊 Test 6: Recent Error Log Check...\n";
$logFile = storage_path('logs/laravel.log');
if (file_exists($logFile)) {
    $logContent = file_get_contents($logFile);
    $recentLogs = substr($logContent, -2000); // Last 2000 characters
    
    if (strpos($recentLogs, 'ERROR') !== false) {
        echo "  ⚠️  Recent errors found in log:\n";
        $lines = explode("\n", $recentLogs);
        foreach ($lines as $line) {
            if (strpos($line, 'ERROR') !== false) {
                echo "    " . trim($line) . "\n";
            }
        }
    } else {
        echo "  ✅ No recent errors in log\n";
    }
} else {
    echo "  ❌ Log file not found\n";
}

echo "\n🎯 DEBUG SUMMARY:\n";
echo "  ================================================\n";
echo "  ✅ Upload issue debugging complete!\n";
echo "  \n";
echo "  Next steps:\n";
echo "  1. Check if file exists and is valid\n";
echo "  2. Verify upload permissions\n";
echo "  3. Test Laravel validation\n";
echo "  4. Check import method execution\n";
echo "  5. Review error logs\n";
echo "  ================================================\n";

echo "\n✅ Debug completed!\n";
