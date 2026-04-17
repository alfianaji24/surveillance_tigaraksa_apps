<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== FINAL DOWNLOAD TEMPLATE TEST ===\n\n";

// Test 1: Check route location
echo "📊 Test 1: Route Location Check...\n";
try {
    $routes = app('router')->getRoutes();
    $downloadRoute = null;
    
    foreach ($routes as $route) {
        if ($route->uri() === 'pasien/download-template-pkm') {
            $downloadRoute = $route;
            break;
        }
    }
    
    if ($downloadRoute) {
        echo "  ✅ Route found: " . $downloadRoute->uri() . "\n";
        echo "  📋 Methods: " . implode(', ', $downloadRoute->methods()) . "\n";
        echo "  🎯 Action: " . $downloadRoute->getActionName() . "\n";
        
        $middleware = $downloadRoute->middleware();
        echo "  🔒 Middleware: " . implode(', ', $middleware) . "\n";
        
        // Check if auth middleware is present
        $hasAuth = in_array('auth', $middleware);
        echo "  🔓 Auth required: " . ($hasAuth ? "❌ Yes" : "✅ No") . "\n";
        
        // Check if web middleware is present (good for file downloads)
        $hasWeb = in_array('web', $middleware);
        echo "  🌐 Web middleware: " . ($hasWeb ? "✅ Yes" : "❌ No") . "\n";
        
    } else {
        echo "  ❌ Route not found\n";
    }
    
} catch (\Exception $e) {
    echo "  ❌ Route check error: " . $e->getMessage() . "\n";
}

// Test 2: Test web request without authentication
echo "\n📊 Test 2: Web Request Test (No Auth)...\n";
try {
    // Clear any existing session
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_destroy();
    }
    
    // Start fresh session
    app('session')->start();
    
    // Make sure user is NOT logged in
    \Illuminate\Support\Facades\Auth::logout();
    echo "  🔓 User logged out\n";
    
    // Create request
    $request = \Illuminate\Http\Request::create('/pasien/download-template-pkm', 'GET');
    
    // Handle request
    $response = $kernel->handle($request);
    
    echo "  ✅ Request handled (Status: " . $response->getStatusCode() . ")\n";
    
    if ($response->getStatusCode() === 200) {
        echo "  ✅ Download SUCCESS!\n";
        echo "  📄 Content-Type: " . $response->headers->get('Content-Type') . "\n";
        echo "  📊 Content-Length: " . $response->headers->get('Content-Length') . " bytes\n";
        echo "  📋 Content-Disposition: " . $response->headers->get('Content-Disposition') . "\n";
        
        // Check if it's a file download
        $contentDisposition = $response->headers->get('Content-Disposition');
        if ($contentDisposition && strpos($contentDisposition, 'attachment') !== false) {
            echo "  ✅ File download confirmed\n";
            
            // Extract filename
            if (preg_match('/filename="([^"]+)"/', $contentDisposition, $matches)) {
                echo "  📁 Filename: " . $matches[1] . "\n";
            }
        }
        
        // Check content type
        $contentType = $response->headers->get('Content-Type');
        if ($contentType && strpos($contentType, 'excel') !== false) {
            echo "  ✅ Excel file detected\n";
        } elseif ($contentType && strpos($contentType, 'spreadsheet') !== false) {
            echo "  ✅ Spreadsheet file detected\n";
        }
        
    } else {
        echo "  ❌ Response error: " . $response->getStatusCode() . "\n";
        echo "  📄 Content: " . substr($response->getContent(), 0, 200) . "...\n";
    }
    
    $kernel->terminate($request, $response);
    
} catch (\Exception $e) {
    echo "  ❌ Web request error: " . $e->getMessage() . "\n";
    echo "  📍 File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

// Test 3: Verify file exists and is accessible
echo "\n📊 Test 3: File Verification...\n";
$templatePath = public_path('template_upload_form/form_pkm.xlsx');

if (file_exists($templatePath)) {
    $fileSize = filesize($templatePath);
    $isReadable = is_readable($templatePath);
    
    echo "  ✅ File exists: form_pkm.xlsx\n";
    echo "  📁 Path: {$templatePath}\n";
    echo "  📊 Size: " . number_format($fileSize) . " bytes\n";
    echo "  👁️  Readable: " . ($isReadable ? "✅" : "❌") . "\n";
    
    // Check file extension
    $extension = strtolower(pathinfo($templatePath, PATHINFO_EXTENSION));
    echo "  📄 Extension: {$extension}\n";
    
    if ($extension === 'xlsx') {
        echo "  ✅ Excel file format confirmed\n";
    }
    
} else {
    echo "  ❌ File NOT found: {$templatePath}\n";
}

echo "\n🎯 FINAL TEST SUMMARY:\n";
echo "  ================================================\n";
echo "  ✅ Final download test complete!\n";
echo "  \n";
echo "  Status: Download template should work without login\n";
echo "  URL: http://127.0.0.1:8000/pasien/download-template-pkm\n";
echo "  File: template_upload_form/form_pkm.xlsx\n";
echo "  \n";
echo "  If this test shows SUCCESS, the download should work!\n";
echo "  ================================================\n";

echo "\n✅ Final test completed!\n";
