<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== DEBUG 404 DOWNLOAD TEMPLATE ===\n\n";

// Test 1: Check route registration
echo "📊 Test 1: Route Registration Check...\n";
try {
    $routes = app('router')->getRoutes();
    $downloadRoute = null;
    
    foreach ($routes as $route) {
        echo "  📍 Route: " . $route->uri() . " -> " . $route->getActionName() . "\n";
        if ($route->uri() === 'pasien/download-template-pkm') {
            $downloadRoute = $route;
            echo "  ✅ Found target route!\n";
        }
    }
    
    if ($downloadRoute) {
        echo "  ✅ Route pasien/download-template-pkm found\n";
        echo "  📋 Methods: " . implode(', ', $downloadRoute->methods()) . "\n";
        echo "  🎯 Action: " . $downloadRoute->getActionName() . "\n";
        
        $middleware = $downloadRoute->middleware();
        echo "  🔒 Middleware: " . implode(', ', $middleware) . "\n";
    } else {
        echo "  ❌ Route pasien/download-template-pkm NOT found!\n";
        echo "  🔍 Available pasien routes:\n";
        foreach ($routes as $route) {
            if (strpos($route->uri(), 'pasien') !== false) {
                echo "    - " . $route->uri() . "\n";
            }
        }
    }
    
} catch (\Exception $e) {
    echo "  ❌ Route check error: " . $e->getMessage() . "\n";
}

// Test 2: Check file permissions
echo "\n📊 Test 2: File Permissions Check...\n";
$templatePath = public_path('template_upload_form/form_pkm.xlsx');

if (file_exists($templatePath)) {
    $fileSize = filesize($templatePath);
    $isReadable = is_readable($templatePath);
    $filePerms = substr(sprintf('%o', fileperms($templatePath)), -4);
    
    echo "  ✅ File exists: {$templatePath}\n";
    echo "  📊 Size: " . number_format($fileSize) . " bytes\n";
    echo "  👁️  Readable: " . ($isReadable ? "✅" : "❌") . "\n";
    echo "  🔐 Permissions: {$filePerms}\n";
} else {
    echo "  ❌ File NOT found: {$templatePath}\n";
    
    // Check directory
    $dirPath = dirname($templatePath);
    if (is_dir($dirPath)) {
        echo "  📁 Directory exists: {$dirPath}\n";
        $files = scandir($dirPath);
        echo "  📋 Files in directory:\n";
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                echo "    - {$file}\n";
            }
        }
    } else {
        echo "  ❌ Directory NOT found: {$dirPath}\n";
    }
}

// Test 3: Test controller method directly
echo "\n📊 Test 3: Controller Method Direct Test...\n";
try {
    $controller = new \App\Http\Controllers\PasienController();
    
    if (method_exists($controller, 'downloadTemplatePKM')) {
        echo "  ✅ Method downloadTemplatePKM exists\n";
        
        // Test the method without authentication
        try {
            $response = $controller->downloadTemplatePKM();
            echo "  ✅ Method executed successfully\n";
            echo "  📄 Response type: " . get_class($response) . "\n";
            
            if (method_exists($response, 'getFile')) {
                $file = $response->getFile();
                echo "  📁 Download file: " . $file->getFilename() . "\n";
                echo "  📊 File size: " . number_format($file->getSize()) . " bytes\n";
            }
            
        } catch (\Exception $e) {
            echo "  ❌ Method execution error: " . $e->getMessage() . "\n";
            echo "  📍 File: " . $e->getFile() . ":" . $e->getLine() . "\n";
        }
        
    } else {
        echo "  ❌ Method downloadTemplatePKM missing\n";
    }
    
} catch (\Exception $e) {
    echo "  ❌ Controller instantiation error: " . $e->getMessage() . "\n";
}

// Test 4: Check authentication requirements
echo "\n📊 Test 4: Authentication Check...\n";
try {
    // Check if user is required
    $user = \App\Models\User::first();
    if ($user) {
        echo "  ✅ User found: " . $user->name . "\n";
        
        // Check permissions
        \Illuminate\Support\Facades\Auth::login($user);
        
        if ($user->hasPermission('create-pasien')) {
            echo "  ✅ User has create-pasien permission\n";
        } else {
            echo "  ❌ User lacks create-pasien permission\n";
        }
        
        // Test method with authenticated user
        try {
            $response = $controller->downloadTemplatePKM();
            echo "  ✅ Method works with authenticated user\n";
        } catch (\Exception $e) {
            echo "  ❌ Method fails with authenticated user: " . $e->getMessage() . "\n";
        }
        
    } else {
        echo "  ❌ No user found in database\n";
    }
    
} catch (\Exception $e) {
    echo "  ❌ Authentication check error: " . $e->getMessage() . "\n";
}

// Test 5: Simulate web request
echo "\n📊 Test 5: Web Request Simulation...\n";
try {
    // Clear any existing session
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_destroy();
    }
    
    // Start fresh session
    app('session')->start();
    
    // Authenticate user
    $user = \App\Models\User::first();
    if ($user) {
        \Illuminate\Support\Facades\Auth::login($user);
        echo "  ✅ User authenticated for web request\n";
    }
    
    // Create request
    $request = \Illuminate\Http\Request::create('/pasien/download-template-pkm', 'GET');
    
    // Handle request
    $response = $kernel->handle($request);
    
    echo "  ✅ Request handled (Status: " . $response->getStatusCode() . ")\n";
    
    if ($response->getStatusCode() === 200) {
        echo "  ✅ Download response OK\n";
        echo "  📄 Content-Type: " . $response->headers->get('Content-Type') . "\n";
        echo "  📊 Content-Length: " . $response->headers->get('Content-Length') . " bytes\n";
        echo "  📋 Content-Disposition: " . $response->headers->get('Content-Disposition') . "\n";
    } else {
        echo "  ❌ Response error: " . $response->getStatusCode() . "\n";
        echo "  📄 Content: " . substr($response->getContent(), 0, 200) . "...\n";
    }
    
    $kernel->terminate($request, $response);
    
} catch (\Exception $e) {
    echo "  ❌ Web request error: " . $e->getMessage() . "\n";
    echo "  📍 File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n🎯 DEBUG SUMMARY:\n";
echo "  ================================================\n";
echo "  ✅ 404 download debugging complete!\n";
echo "  \n";
echo "  Check the results above to identify the issue.\n";
echo "  Common causes:\n";
echo "  - Route not registered\n";
echo "  - File not found or not readable\n";
echo "  - Authentication/permission issues\n";
echo "  - Middleware blocking access\n";
echo "  ================================================\n";

echo "\n✅ Debug completed!\n";
