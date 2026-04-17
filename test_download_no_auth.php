<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== TEST DOWNLOAD WITHOUT AUTH ===\n\n";

// Test 1: Check route without auth
echo "📊 Test 1: Route Check (No Auth)...\n";
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
        
        // Check if auth middleware is removed
        $hasAuth = in_array('auth', $middleware);
        echo "  🔓 Auth required: " . ($hasAuth ? "❌ Yes" : "✅ No") . "\n";
    } else {
        echo "  ❌ Route not found\n";
    }
    
} catch (\Exception $e) {
    echo "  ❌ Route check error: " . $e->getMessage() . "\n";
}

// Test 2: Test web request without authentication
echo "\n📊 Test 2: Web Request (No Auth)...\n";
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

// Test 3: Direct controller test
echo "\n📊 Test 3: Direct Controller Test...\n";
try {
    $controller = new \App\Http\Controllers\PasienController();
    
    // Test without authentication
    $response = $controller->downloadTemplatePKM();
    
    echo "  ✅ Controller method works without auth\n";
    echo "  📄 Response type: " . get_class($response) . "\n";
    
    if (method_exists($response, 'getFile')) {
        $file = $response->getFile();
        echo "  📁 File: " . $file->getFilename() . "\n";
        echo "  📊 Size: " . number_format($file->getSize()) . " bytes\n";
    }
    
} catch (\Exception $e) {
    echo "  ❌ Controller error: " . $e->getMessage() . "\n";
}

echo "\n🎯 TEST SUMMARY:\n";
echo "  ================================================\n";
echo "  ✅ Download without auth test complete!\n";
echo "  \n";
echo "  URL: http://127.0.0.1:8000/pasien/download-template-pkm\n";
echo "  Status: Should work without login now\n";
echo "  ================================================\n";

echo "\n✅ Test completed!\n";
