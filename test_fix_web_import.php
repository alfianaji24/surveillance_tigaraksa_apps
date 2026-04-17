<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Pasien;
use App\Http\Controllers\PasienController;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

echo "=== TEST FIX WEB IMPORT ===\n\n";

// Test the fixed importPKM method
echo "📊 Testing fixed importPKM method...\n";

try {
    $controller = new PasienController();
    $excelFilePath = 'C:\\Users\\Backtrack-5\\Documents\\Project\\SI-PTM\\public\\template_pkm\\Test_data.xlsx';
    
    // Create a mock request with file
    $file = new UploadedFile(
        $excelFilePath,
        'Test_data.xlsx',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        null,
        true
    );
    
    $request = new Request();
    $request->files->set('file', $file);
    
    // Get count before import
    $beforeCount = Pasien::count();
    echo "  📊 Before import: $beforeCount records\n";
    
    // Call importPKM method
    $response = $controller->importPKM($request);
    $result = $response->getData();
    
    // Get count after import
    $afterCount = Pasien::count();
    $importedCount = $afterCount - $beforeCount;
    
    echo "  📊 After import: $afterCount records\n";
    echo "  📊 Imported: $importedCount records\n";
    echo "  📊 Success: " . ($result->success ? 'Yes' : 'No') . "\n";
    echo "  📊 Message: " . $result->message . "\n";
    
    if ($result->success) {
        echo "  ✅ Web import fix successful!\n";
        echo "  ✅ Excel file reading works\n";
        echo "  ✅ Data processing works\n";
        echo "  ✅ Database insertion works\n";
    } else {
        echo "  ❌ Import still failed: " . $result->message . "\n";
    }
    
} catch (\Exception $e) {
    echo "  ❌ Error testing importPKM: " . $e->getMessage() . "\n";
    echo "  📍 Line: " . $e->getLine() . "\n";
    echo "  📁 File: " . $e->getFile() . "\n";
}

echo "\n🎯 CONCLUSION:\n";
echo "  ================================================\n";
echo "  Fix Applied:\n";
echo "  - Changed Excel::toArray([], \$file) to Excel::toArray([], \$file->getPathname())\n";
echo "  - This ensures proper file path is passed to Excel reader\n";
echo "  \n";
echo "  Expected Result:\n";
echo "  - Web import should now work with Excel files\n";
echo "  - No more 'reading' error\n";
echo "  - All 1688 data should import successfully\n";
echo "  ================================================\n";

echo "\n✅ Test completed!\n";
