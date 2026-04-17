<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== DEBUG PKM UPLOAD BUTTON ===\n\n";

// Test 1: Check view file for button and JavaScript
echo "📊 Test 1: View File Analysis...\n";
$viewPath = resource_path('views/pasien/import.blade.php');

if (file_exists($viewPath)) {
    $viewContent = file_get_contents($viewPath);
    
    // Check for button ID
    if (strpos($viewContent, 'id="uploadPKMBtn"') !== false) {
        echo "  ✅ Button ID 'uploadPKMBtn' found in HTML\n";
    } else {
        echo "  ❌ Button ID 'uploadPKMBtn' not found in HTML\n";
    }
    
    // Check for event listener
    if (strpos($viewContent, "uploadPKMBtn.addEventListener") !== false) {
        echo "  ✅ Event listener for uploadPKMBtn found\n";
    } else {
        echo "  ❌ Event listener for uploadPKMBtn missing\n";
    }
    
    // Check for pkmFile ID
    if (strpos($viewContent, 'id="pkmFile"') !== false) {
        echo "  ✅ File input ID 'pkmFile' found\n";
    } else {
        echo "  ❌ File input ID 'pkmFile' not found\n";
    }
    
    // Check for route pasien.import-pkm
    if (strpos($viewContent, 'pasien.import-pkm') !== false) {
        echo "  ✅ Route 'pasien.import-pkm' found in JavaScript\n";
    } else {
        echo "  ❌ Route 'pasien.import-pkm' missing in JavaScript\n";
    }
    
    // Check for syntax errors in JavaScript
    $scriptStart = strpos($viewContent, '<script>');
    $scriptEnd = strpos($viewContent, '</script>');
    
    if ($scriptStart !== false && $scriptEnd !== false) {
        $scriptContent = substr($viewContent, $scriptStart, $scriptEnd - $scriptStart);
        echo "  📄 JavaScript section found (" . strlen($scriptContent) . " bytes)\n";
        
        // Check for common JavaScript errors
        if (strpos($scriptContent, 'document.getElementById(\'uploadPKMBtn\')') !== false) {
            echo "  ✅ getElementById for uploadPKMBtn found\n";
        } else {
            echo "  ❌ getElementById for uploadPKMBtn missing\n";
        }
        
        if (strpos($scriptContent, 'pkmFile.files[0]') !== false) {
            echo "  ✅ File access for pkmFile found\n";
        } else {
            echo "  ❌ File access for pkmFile missing\n";
        }
    }
    
} else {
    echo "  ❌ View file not found\n";
}

// Test 2: Check route exists
echo "\n📊 Test 2: Route Check...\n";
try {
    $routes = app('router')->getRoutes();
    $pkmRoute = null;
    
    foreach ($routes as $route) {
        if ($route->uri() === 'pasien/import-pkm') {
            $pkmRoute = $route;
            break;
        }
    }
    
    if ($pkmRoute) {
        echo "  ✅ Route pasien/import-pkm found\n";
        echo "  📋 Methods: " . implode(', ', $pkmRoute->methods()) . "\n";
        echo "  🎯 Action: " . $pkmRoute->getActionName() . "\n";
        
        $middleware = $pkmRoute->middleware();
        echo "  🔒 Middleware: " . implode(', ', $middleware) . "\n";
    } else {
        echo "  ❌ Route pasien/import-pkm not found\n";
    }
    
} catch (\Exception $e) {
    echo "  ❌ Route check error: " . $e->getMessage() . "\n";
}

// Test 3: Check controller method
echo "\n📊 Test 3: Controller Method Check...\n";
try {
    $controller = new \App\Http\Controllers\PasienController();
    
    if (method_exists($controller, 'importPKM')) {
        echo "  ✅ Method importPKM exists\n";
        
        // Check method signature
        $reflection = new ReflectionMethod($controller, 'importPKM');
        $params = $reflection->getParameters();
        echo "  📋 Parameters: ";
        foreach ($params as $param) {
            echo $param->getType() ? $param->getType()->getName() : 'mixed';
            echo ' $' . $param->getName();
            if ($param->isDefaultValueAvailable()) {
                echo ' = ' . json_encode($param->getDefaultValue());
            }
            echo ', ';
        }
        echo "\n";
        
    } else {
        echo "  ❌ Method importPKM missing\n";
    }
    
} catch (\Exception $e) {
    echo "  ❌ Controller check error: " . $e->getMessage() . "\n";
}

// Test 4: Check permissions
echo "\n📊 Test 4: Permission Check...\n";
try {
    $user = \App\Models\User::first();
    if ($user) {
        \Illuminate\Support\Facades\Auth::login($user);
        
        // Check importPKM permission
        if ($user->hasPermission('create-pasien')) {
            echo "  ✅ User has create-pasien permission (required for importPKM)\n";
        } else {
            echo "  ❌ User lacks create-pasien permission\n";
        }
        
        echo "  📋 User roles: " . implode(', ', $user->roles->pluck('name')->toArray()) . "\n";
    }
    
} catch (\Exception $e) {
    echo "  ❌ Permission check error: " . $e->getMessage() . "\n";
}

// Test 5: Create simple HTML test
echo "\n📊 Test 5: HTML Structure Check...\n";
$htmlTest = '
<!DOCTYPE html>
<html>
<head>
    <title>PKM Button Test</title>
</head>
<body>
    <input type="file" id="pkmFile" accept=".csv,.xlsx,.xls">
    <button type="button" id="uploadPKMBtn">Upload & Preview</button>
    
    <script>
        console.log("Testing PKM button...");
        
        const uploadPKMBtn = document.getElementById("uploadPKMBtn");
        const pkmFile = document.getElementById("pkmFile");
        
        console.log("uploadPKMBtn:", uploadPKMBtn);
        console.log("pkmFile:", pkmFile);
        
        if (uploadPKMBtn) {
            uploadPKMBtn.addEventListener("click", function() {
                console.log("Button clicked!");
                alert("Button is working!");
            });
            console.log("Event listener added");
        } else {
            console.error("Button not found!");
        }
    </script>
</body>
</html>';

file_put_contents(public_path('test_pkm_button.html'), $htmlTest);
echo "  ✅ Test HTML file created: public/test_pkm_button.html\n";
echo "  🌐 Access: http://127.0.0.1:8000/test_pkm_button.html\n";

echo "\n🎯 DEBUG SUMMARY:\n";
echo "  ================================================\n";
echo "  ✅ PKM button debugging complete!\n";
echo "  \n";
echo "  Check the test HTML file to verify basic functionality.\n";
echo "  If basic test works, the issue is in the main view.\n";
echo "  ================================================\n";

echo "\n✅ Debug completed!\n";
