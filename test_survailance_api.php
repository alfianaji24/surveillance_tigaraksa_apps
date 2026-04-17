<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\SurvailanceController;
use Illuminate\Http\Request;

echo "=== TEST SURVAILANCE API ENDPOINTS ===\n\n";

$controller = new SurvailanceController();
$year = date('Y');

// Test 1: Dashboard Index
echo "📊 Test 1: Dashboard Index...\n";
try {
    $request = new Request();
    $request->query->set('year', $year);
    
    $response = $controller->index($request);
    echo "  ✅ Dashboard index method works\n";
    echo "  📋 Response status: " . $response->getStatusCode() . "\n";
} catch (\Exception $e) {
    echo "  ❌ Dashboard index error: " . $e->getMessage() . "\n";
    echo "  📍 Line: " . $e->getLine() . "\n";
}

// Test 2: Chart Data API
echo "\n📊 Test 2: Chart Data API...\n";
try {
    $request = new Request();
    $request->query->set('disease', 'DA');
    $request->query->set('year', $year);
    $request->query->set('type', 'weekly');
    
    $response = $controller->getChartData($request);
    echo "  ✅ Chart data API works\n";
    echo "  📋 Response status: " . $response->getStatusCode() . "\n";
    
    $data = json_decode($response->getContent(), true);
    echo "  📈 Data points: " . count($data['data']) . "\n";
} catch (\Exception $e) {
    echo "  ❌ Chart data API error: " . $e->getMessage() . "\n";
    echo "  📍 Line: " . $e->getLine() . "\n";
}

// Test 3: Top Diseases API
echo "\n📊 Test 3: Top Diseases API...\n";
try {
    $request = new Request();
    $request->query->set('year', $year);
    $request->query->set('limit', 10);
    
    $response = $controller->getTopDiseases($request);
    echo "  ✅ Top diseases API works\n";
    echo "  📋 Response status: " . $response->getStatusCode() . "\n";
    
    $data = json_decode($response->getContent(), true);
    echo "  📈 Diseases returned: " . count($data) . "\n";
} catch (\Exception $e) {
    echo "  ❌ Top diseases API error: " . $e->getMessage() . "\n";
    echo "  📍 Line: " . $e->getLine() . "\n";
}

// Test 4: Disease Details API
echo "\n📊 Test 4: Disease Details API...\n";
try {
    $request = new Request();
    $request->query->set('disease', 'DA');
    $request->query->set('year', $year);
    $request->query->set('week', null);
    
    $response = $controller->getDiseaseDetails($request);
    echo "  ✅ Disease details API works\n";
    echo "  📋 Response status: " . $response->getStatusCode() . "\n";
    
    $data = json_decode($response->getContent(), true);
    echo "  📈 Patients returned: " . $data['patients']['total'] . "\n";
} catch (\Exception $e) {
    echo "  ❌ Disease details API error: " . $e->getMessage() . "\n";
    echo "  📍 Line: " . $e->getLine() . "\n";
}

echo "\n🎯 CONCLUSION:\n";
echo "  ================================================\n";
echo "  ✅ Survailance API Test Complete!\n";
echo "  \n";
echo "  If all tests pass, the dashboard should work.\n";
echo "  If there are errors, check the controller methods.\n";
echo "  ================================================\n";

echo "\n✅ API test completed!\n";
