<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== TEST DOWNLOAD TEMPLATE PKM ===\n\n";

// Test 1: Check if template file exists
echo "📊 Test 1: Template File Check...\n";
$templatePath = public_path('template_pkm/Template_Data_Diagnosa_PKM.xlsx');

if (file_exists($templatePath)) {
    $fileSize = filesize($templatePath);
    $fileModTime = date('Y-m-d H:i:s', filemtime($templatePath));
    echo "  ✅ Template file exists: Template_Data_Diagnosa_PKM.xlsx\n";
    echo "  📁 Path: {$templatePath}\n";
    echo "  📊 Size: " . number_format($fileSize) . " bytes\n";
    echo "  🕒 Modified: {$fileModTime}\n";
} else {
    echo "  ❌ Template file not found: {$templatePath}\n";
}

// Test 2: Check route exists
echo "\n📊 Test 2: Route Check...\n";
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
        echo "  ✅ Route pasien/download-template-pkm found\n";
        echo "  📋 Methods: " . implode(', ', $downloadRoute->methods()) . "\n";
        echo "  🎯 Action: " . $downloadRoute->getActionName() . "\n";
        
        $middleware = $downloadRoute->middleware();
        echo "  🔒 Middleware: " . implode(', ', $middleware) . "\n";
    } else {
        echo "  ❌ Route pasien/download-template-pkm not found\n";
    }
    
} catch (\Exception $e) {
    echo "  ❌ Route check error: " . $e->getMessage() . "\n";
}

// Test 3: Check controller method
echo "\n📊 Test 3: Controller Method Check...\n";
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
            $headers = $response->headers->all();
            echo "  📋 Response headers:\n";
            foreach ($headers as $key => $values) {
                echo "    {$key}: " . implode(', ', $values) . "\n";
            }
        }
        
    } else {
        echo "  ❌ Method downloadTemplatePKM missing\n";
    }
    
} catch (\Exception $e) {
    echo "  ❌ Controller method error: " . $e->getMessage() . "\n";
    echo "  📍 File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

// Test 4: Check permissions
echo "\n📊 Test 4: Permission Check...\n";
try {
    $user = \App\Models\User::first();
    if ($user) {
        \Illuminate\Support\Facades\Auth::login($user);
        
        // Check if user has permission to access
        if ($user->hasPermission('create-pasien')) {
            echo "  ✅ User has create-pasien permission (required for download)\n";
        } else {
            echo "  ❌ User lacks create-pasien permission\n";
        }
        
        echo "  📋 User roles: " . implode(', ', $user->roles->pluck('name')->toArray()) . "\n";
    }
    
} catch (\Exception $e) {
    echo "  ❌ Permission check error: " . $e->getMessage() . "\n";
}

// Test 5: Simulate web request
echo "\n📊 Test 5: Web Request Simulation...\n";
try {
    $request = \Illuminate\Http\Request::create('/pasien/download-template-pkm', 'GET');
    
    // Set up basic session
    app('session')->start();
    
    // Try to handle the request
    $response = $kernel->handle($request);
    
    echo "  ✅ Request handled (Status: " . $response->getStatusCode() . ")\n";
    
    if ($response->getStatusCode() === 200) {
        echo "  ✅ Download response OK\n";
        echo "  📄 Content type: " . $response->headers->get('Content-Type') . "\n";
        echo "  📊 Content length: " . $response->headers->get('Content-Length') . " bytes\n";
    } else {
        echo "  ❌ Response error: " . $response->getStatusCode() . "\n";
    }
    
    $kernel->terminate($request, $response);
    
} catch (\Exception $e) {
    echo "  ❌ Web request error: " . $e->getMessage() . "\n";
    echo "  📍 File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n🎯 TEST SUMMARY:\n";
echo "  ================================================\n";
echo "  ✅ Download template test complete!\n";
echo "  \n";
echo "  If all tests pass, the download should work.\n";
echo "  Access: http://127.0.0.1:8000/pasien/download-template-pkm\n";
echo "  ================================================\n";

echo "\n✅ Test completed!\n";
