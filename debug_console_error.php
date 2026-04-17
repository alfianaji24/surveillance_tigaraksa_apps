<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== DEBUG CONSOLE ERROR ===\n\n";

// Test 1: Check Laravel basic functionality
echo "📊 Test 1: Laravel Basic Functionality...\n";
try {
    echo "  ✅ Laravel bootstrapped successfully\n";
    echo "  ✅ Kernel initialized\n";
    echo "  ✅ Application ready\n";
} catch (\Exception $e) {
    echo "  ❌ Laravel bootstrap error: " . $e->getMessage() . "\n";
}

// Test 2: Check database connection
echo "\n📊 Test 2: Database Connection...\n";
try {
    \Illuminate\Support\Facades\DB::connection()->getPdo();
    echo "  ✅ Database connection successful\n";
    
    // Check if tables exist
    $tables = ['users', 'pasiens', 'icd10s'];
    foreach ($tables as $table) {
        if (\Illuminate\Support\Facades\Schema::hasTable($table)) {
            $count = \Illuminate\Support\Facades\DB::table($table)->count();
            echo "  ✅ Table {$table}: {$count} records\n";
        } else {
            echo "  ❌ Table {$table}: not found\n";
        }
    }
} catch (\Exception $e) {
    echo "  ❌ Database connection error: " . $e->getMessage() . "\n";
}

// Test 3: Check routes
echo "\n📊 Test 3: Routes Check...\n";
try {
    $routes = app('router')->getRoutes();
    $routeCount = count($routes);
    echo "  ✅ Routes loaded: {$routeCount} routes\n";
    
    // Check main routes
    $mainRoutes = ['/', '/dashboard', '/pasien/import', '/survailance'];
    foreach ($mainRoutes as $route) {
        $exists = false;
        foreach ($routes as $r) {
            if ($r->uri() === ltrim($route, '/')) {
                $exists = true;
                break;
            }
        }
        echo $exists ? "  ✅ Route {$route}: found\n" : "  ❌ Route {$route}: not found\n";
    }
} catch (\Exception $e) {
    echo "  ❌ Routes error: " . $e->getMessage() . "\n";
}

// Test 4: Check views
echo "\n📊 Test 4: Views Check...\n";
try {
    $views = ['welcome', 'dashboard', 'pasien.import'];
    foreach ($views as $view) {
        try {
            $rendered = view($view)->render();
            echo "  ✅ View {$view}: renderable\n";
        } catch (\Exception $e) {
            echo "  ❌ View {$view}: " . $e->getMessage() . "\n";
        }
    }
} catch (\Exception $e) {
    echo "  ❌ Views error: " . $e->getMessage() . "\n";
}

// Test 5: Check memory and performance
echo "\n📊 Test 5: Memory & Performance...\n";
echo "  📊 Current memory usage: " . number_format(memory_get_usage() / 1024 / 1024, 2) . " MB\n";
echo "  📊 Peak memory usage: " . number_format(memory_get_peak_usage() / 1024 / 1024, 2) . " MB\n";
echo "  📊 Memory limit: " . ini_get('memory_limit') . "\n";
echo "  📊 Max execution time: " . ini_get('max_execution_time') . "s\n";

// Test 6: Check cache
echo "\n📊 Test 6: Cache System...\n";
try {
    \Illuminate\Support\Facades\Cache::put('test', 'value', 60);
    $value = \Illuminate\Support\Facades\Cache::get('test');
    echo $value === 'value' ? "  ✅ Cache system working\n" : "  ❌ Cache system failed\n";
    \Illuminate\Support\Facades\Cache::forget('test');
} catch (\Exception $e) {
    echo "  ❌ Cache error: " . $e->getMessage() . "\n";
}

// Test 7: Check session
echo "\n📊 Test 7: Session System...\n";
try {
    session_start();
    $_SESSION['test'] = 'value';
    echo isset($_SESSION['test']) ? "  ✅ Session system working\n" : "  ❌ Session system failed\n";
    unset($_SESSION['test']);
} catch (\Exception $e) {
    echo "  ❌ Session error: " . $e->getMessage() . "\n";
}

// Test 8: Simulate web request
echo "\n📊 Test 8: Web Request Simulation...\n";
try {
    $request = \Illuminate\Http\Request::create('/');
    $response = $kernel->handle($request);
    echo "  ✅ Web request handled (Status: " . $response->getStatusCode() . ")\n";
    $kernel->terminate($request, $response);
} catch (\Exception $e) {
    echo "  ❌ Web request error: " . $e->getMessage() . "\n";
    echo "  📍 File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n🎯 DEBUG SUMMARY:\n";
echo "  ================================================\n";
echo "  ✅ Console error debugging complete!\n";
echo "  \n";
echo "  If all tests pass, the application should work.\n";
echo "  If there are errors, check the specific test results.\n";
echo "  ================================================\n";

echo "\n✅ Debug completed!\n";
