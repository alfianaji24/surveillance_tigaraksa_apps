<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== TEST PASIEN IMPORT PAGE ===\n\n";

// Test 1: Check if user can access import page
echo "📊 Test 1: Import Page Access...\n";
try {
    // Create a mock authenticated user
    $user = \App\Models\User::first();
    if ($user) {
        echo "  ✅ Found user: " . $user->name . "\n";
        
        // Authenticate the user
        \Illuminate\Support\Facades\Auth::login($user);
        
        if (\Illuminate\Support\Facades\Auth::check()) {
            echo "  ✅ User authenticated successfully\n";
            
            // Test controller method
            $controller = new \App\Http\Controllers\PasienController();
            $response = $controller->importPage();
            
            echo "  ✅ Controller method executed\n";
            echo "  📄 Response type: " . get_class($response) . "\n";
            
            if (method_exists($response, 'getContent')) {
                $content = $response->getContent();
                echo "  📊 Content length: " . number_format(strlen($content)) . " bytes\n";
                
                if (strpos($content, 'Import Data Pasien') !== false) {
                    echo "  ✅ Page contains expected title\n";
                } else {
                    echo "  ⚠️  Page might not contain expected content\n";
                }
            }
            
        } else {
            echo "  ❌ User authentication failed\n";
        }
        
    } else {
        echo "  ❌ No user found in database\n";
    }
    
} catch (\Exception $e) {
    echo "  ❌ Test error: " . $e->getMessage() . "\n";
    echo "  📍 File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

// Test 2: Check permissions
echo "\n📊 Test 2: Permission Check...\n";
try {
    $user = \App\Models\User::first();
    if ($user) {
        \Illuminate\Support\Facades\Auth::login($user);
        
        // Check if user has read-pasien permission
        if ($user->hasPermission('read-pasien')) {
            echo "  ✅ User has read-pasien permission\n";
        } else {
            echo "  ❌ User lacks read-pasien permission\n";
        }
        
        // Check if user has create-pasien permission
        if ($user->hasPermission('create-pasien')) {
            echo "  ✅ User has create-pasien permission\n";
        } else {
            echo "  ❌ User lacks create-pasien permission\n";
        }
        
        // Show user roles
        $roles = $user->roles->pluck('name')->toArray();
        echo "  📋 User roles: " . implode(', ', $roles) . "\n";
    }
    
} catch (\Exception $e) {
    echo "  ❌ Permission check error: " . $e->getMessage() . "\n";
}

// Test 3: Check route accessibility
echo "\n📊 Test 3: Route Accessibility...\n";
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
        echo "  ✅ Route found: " . $importRoute->uri() . "\n";
        echo "  📋 Methods: " . implode(', ', $importRoute->methods()) . "\n";
        echo "  🎯 Action: " . $importRoute->getActionName() . "\n";
        
        $middleware = $importRoute->middleware();
        echo "  🔒 Middleware: " . implode(', ', $middleware) . "\n";
    }
    
} catch (\Exception $e) {
    echo "  ❌ Route check error: " . $e->getMessage() . "\n";
}

echo "\n🎯 TEST SUMMARY:\n";
echo "  ================================================\n";
echo "  ✅ Import page test complete!\n";
echo "  \n";
echo "  If all tests pass, the import page should work.\n";
echo "  If there are permission issues, check user roles.\n";
echo "  ================================================\n";

echo "\n✅ Test completed!\n";
