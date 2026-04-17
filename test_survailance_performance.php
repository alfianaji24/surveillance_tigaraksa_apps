<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\SurvailanceController;
use Illuminate\Http\Request;

echo "=== TEST SURVAILANCE PERFORMANCE ===\n\n";

$controller = new SurvailanceController();
$year = date('Y');

// Test 1: Chart Data API Performance
echo "📊 Test 1: Chart Data API Performance...\n";
$startTime = microtime(true);

try {
    $request = new Request();
    $request->query->set('disease', 'DA');
    $request->query->set('year', $year);
    $request->query->set('type', 'weekly');
    
    $response = $controller->getChartData($request);
    $endTime = microtime(true);
    
    $executionTime = ($endTime - $startTime) * 1000; // Convert to milliseconds
    echo "  ✅ Chart data API works\n";
    echo "  ⏱️  Execution time: " . number_format($executionTime, 2) . " ms\n";
    
    $data = json_decode($response->getContent(), true);
    echo "  📈 Data points: " . count($data['data']) . "\n";
    
    // Test cache performance
    $startTime2 = microtime(true);
    $response2 = $controller->getChartData($request);
    $endTime2 = microtime(true);
    
    $executionTime2 = ($endTime2 - $startTime2) * 1000;
    echo "  🚀 Cached execution time: " . number_format($executionTime2, 2) . " ms\n";
    echo "  📈 Performance improvement: " . number_format(($executionTime - $executionTime2) / $executionTime * 100, 1) . "%\n";
    
} catch (\Exception $e) {
    echo "  ❌ Chart data API error: " . $e->getMessage() . "\n";
}

// Test 2: Multiple Chart Data Requests
echo "\n📊 Test 2: Multiple Chart Data Requests...\n";
$diseases = ['DA', 'ISPA', 'CM', 'DT', 'MK'];
$totalTime = 0;

foreach ($diseases as $disease) {
    $startTime = microtime(true);
    
    try {
        $request = new Request();
        $request->query->set('disease', $disease);
        $request->query->set('year', $year);
        $request->query->set('type', 'weekly');
        
        $response = $controller->getChartData($request);
        $endTime = microtime(true);
        
        $executionTime = ($endTime - $startTime) * 1000;
        $totalTime += $executionTime;
        
        echo "  📈 {$disease}: " . number_format($executionTime, 2) . " ms\n";
        
    } catch (\Exception $e) {
        echo "  ❌ {$disease}: " . $e->getMessage() . "\n";
    }
}

echo "  📊 Average time: " . number_format($totalTime / count($diseases), 2) . " ms\n";

// Test 3: Cache Hit Rate
echo "\n📊 Test 3: Cache Hit Rate...\n";
$cacheHits = 0;
$cacheMisses = 0;

for ($i = 0; $i < 10; $i++) {
    $startTime = microtime(true);
    
    try {
        $request = new Request();
        $request->query->set('disease', 'DA');
        $request->query->set('year', $year);
        $request->query->set('type', 'weekly');
        
        $response = $controller->getChartData($request);
        $endTime = microtime(true);
        
        $executionTime = ($endTime - $startTime) * 1000;
        
        if ($executionTime < 10) { // Less than 10ms indicates cache hit
            $cacheHits++;
        } else {
            $cacheMisses++;
        }
        
    } catch (\Exception $e) {
        echo "  ❌ Request {$i}: " . $e->getMessage() . "\n";
    }
}

echo "  🎯 Cache hits: {$cacheHits}/10\n";
echo "  📊 Cache hit rate: " . number_format($cacheHits / 10 * 100, 1) . "%\n";

echo "\n🎯 PERFORMANCE OPTIMIZATION RESULTS:\n";
echo "  ================================================\n";
echo "  ✅ Optimizations Applied:\n";
echo "  - Server-side caching (30 min for stats, 1 hr for API)\n";
echo "  - Client-side caching with JavaScript\n";
echo "  - Debouncing to prevent excessive API calls\n";
echo "  - Faster chart animations (300ms)\n";
echo "  - Optimized chart rendering\n";
echo "  - Loading states and user feedback\n";
echo "  \n";
echo "  Expected Performance:\n";
echo "  - First load: ~200-500ms\n";
echo "  - Cached load: ~5-20ms\n";
echo "  - Cache hit rate: >80%\n";
echo "  - Smooth chart interactions\n";
echo "  ================================================\n";

echo "\n✅ Performance test completed!\n";
