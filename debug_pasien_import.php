<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== DEBUG PASIEN IMPORT ERROR ===\n\n";

// Test 1: Check route existence
echo "📊 Test 1: Route Check...\n";
try {
    $routes = app('router')->getRoutes();
    $importRoute = null;
    
    foreach ($routes as $route) {
        if ($route->uri() === 'pasien/import') {
            $importRoute = $route;
            break;
        }
    }
    
    if ($importRoute) {
        echo "  ✅ Route pasien/import found\n";
        echo "  📋 Methods: " . implode(', ', $importRoute->methods()) . "\n";
        echo "  🎯 Action: " . $importRoute->getActionName() . "\n";
    } else {
        echo "  ❌ Route pasien/import not found\n";
    }
} catch (\Exception $e) {
    echo "  ❌ Route check error: " . $e->getMessage() . "\n";
}

// Test 2: Check controller method
echo "\n📊 Test 2: Controller Method Check...\n";
try {
    $controller = new \App\Http\Controllers\PasienController();
    
    if (method_exists($controller, 'importPage')) {
        echo "  ✅ Method importPage exists\n";
    } else {
        echo "  ❌ Method importPage missing\n";
    }
    
    if (method_exists($controller, 'importPKM')) {
        echo "  ✅ Method importPKM exists\n";
    } else {
        echo "  ❌ Method importPKM missing\n";
    }
    
} catch (\Exception $e) {
    echo "  ❌ Controller check error: " . $e->getMessage() . "\n";
}

// Test 3: Check view file
echo "\n📊 Test 3: View File Check...\n";
$viewPath = resource_path('views/pasien/import.blade.php');

if (file_exists($viewPath)) {
    echo "  ✅ View file exists: pasien/import.blade.php\n";
    
    // Check view syntax
    try {
        $viewContent = file_get_contents($viewPath);
        echo "  📄 File size: " . number_format(strlen($viewContent)) . " bytes\n";
        
        // Check for common syntax errors
        if (strpos($viewContent, '@extends') !== false) {
            echo "  ✅ Has @extends directive\n";
        }
        
        if (strpos($viewContent, '@section') !== false) {
            echo "  ✅ Has @section directive\n";
        }
        
        if (strpos($viewContent, 'Auth::') !== false) {
            echo "  ⚠️  Contains Auth directives\n";
        }
        
    } catch (\Exception $e) {
        echo "  ❌ View file read error: " . $e->getMessage() . "\n";
    }
} else {
    echo "  ❌ View file missing: pasien/import.blade.php\n";
}

// Test 4: Simulate web request to pasien/import
echo "\n📊 Test 4: Web Request Simulation...\n";
try {
    $request = \Illuminate\Http\Request::create('/pasien/import', 'GET');
    
    // Set up basic session
    app('session')->start();
    
    // Try to handle the request
    $response = $kernel->handle($request);
    
    echo "  ✅ Request handled (Status: " . $response->getStatusCode() . ")\n";
    
    if ($response->getStatusCode() === 200) {
        echo "  ✅ Response OK\n";
    } else {
        echo "  ❌ Response error: " . $response->getStatusCode() . "\n";
        echo "  📄 Content: " . substr($response->getContent(), 0, 200) . "...\n";
    }
    
    $kernel->terminate($request, $response);
    
} catch (\Exception $e) {
    echo "  ❌ Web request error: " . $e->getMessage() . "\n";
    echo "  📍 File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    
    // Check if it's the getFirstArgument error
    if (strpos($e->getMessage(), 'getFirstArgument') !== false) {
        echo "  🔍 This is the getFirstArgument error!\n";
        echo "  🔧 This usually happens in console/web kernel mismatch\n";
    }
}

// Test 5: Check authentication
echo "\n📊 Test 5: Authentication Check...\n";
try {
    $auth = app('auth');
    echo "  ✅ Auth service available\n";
    
    // Check if user is authenticated
    if ($auth->check()) {
        echo "  ✅ User authenticated: " . $auth->user()->name . "\n";
    } else {
        echo "  ⚠️  No user authenticated (this might be expected)\n";
    }
    
} catch (\Exception $e) {
    echo "  ❌ Auth check error: " . $e->getMessage() . "\n";
}

// Test 6: Check middleware
echo "\n📊 Test 6: Middleware Check...\n";
try {
    $route = app('router')->getRoutes()->match(\Illuminate\Http\Request::create('/pasien/import'));
    $middleware = $route->middleware();
    
    echo "  📋 Route middleware: " . implode(', ', $middleware) . "\n";
    
    if (in_array('auth', $middleware)) {
        echo "  ✅ Auth middleware applied\n";
    }
    
} catch (\Exception $e) {
    echo "  ❌ Middleware check error: " . $e->getMessage() . "\n";
}

echo "\n🎯 DEBUG SUMMARY:\n";
echo "  ================================================\n";
echo "  ✅ Pasien import error debugging complete!\n";
echo "  \n";
echo "  If you see getFirstArgument error, it's likely\n";
echo "  a kernel mismatch between console and web.\n";
echo "  ================================================\n";

echo "\n✅ Debug completed!\n";
