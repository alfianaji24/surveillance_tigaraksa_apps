<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== DEBUG MOBILE MENU ===\n\n";

// Test 1: Check navbar component
echo "📊 Test 1: Navbar Component Check...\n";
$navbarPath = resource_path('views/components/navbar.blade.php');

if (file_exists($navbarPath)) {
    $navbarContent = file_get_contents($navbarPath);
    
    // Check for mobile menu button
    if (strpos($navbarContent, 'mobileMenuButton') !== false) {
        echo "  ✅ mobileMenuButton ID found\n";
    } else {
        echo "  ❌ mobileMenuButton ID missing\n";
    }
    
    // Check for lg:hidden class
    if (strpos($navbarContent, 'lg:hidden') !== false) {
        echo "  ✅ lg:hidden class found\n";
    } else {
        echo "  ❌ lg:hidden class missing\n";
    }
    
} else {
    echo "  ❌ Navbar component not found\n";
}

// Test 2: Check sidebar component
echo "\n📊 Test 2: Sidebar Component Check...\n";
$sidebarPath = resource_path('views/components/sidebar.blade.php');

if (file_exists($sidebarPath)) {
    $sidebarContent = file_get_contents($sidebarPath);
    
    // Check for sidebar ID
    if (strpos($sidebarContent, 'id="sidebar"') !== false) {
        echo "  ✅ sidebar ID found\n";
    } else {
        echo "  ❌ sidebar ID missing\n";
    }
    
    // Check for responsive classes
    if (strpos($sidebarContent, 'fixed lg:relative') !== false) {
        echo "  ✅ responsive classes found\n";
    } else {
        echo "  ❌ responsive classes missing\n";
    }
    
    // Check for transform classes
    if (strpos($sidebarContent, '-translate-x-full') !== false) {
        echo "  ✅ transform classes found\n";
    } else {
        echo "  ❌ transform classes missing\n";
    }
    
} else {
    echo "  ❌ Sidebar component not found\n";
}

// Test 3: Check layout script
echo "\n📊 Test 3: Layout Script Check...\n";
$layoutPath = resource_path('views/layouts/app.blade.php');

if (file_exists($layoutPath)) {
    $layoutContent = file_get_contents($layoutPath);
    
    // Check for overlay
    if (strpos($layoutContent, 'mobileMenuOverlay') !== false) {
        echo "  ✅ mobileMenuOverlay found\n";
    } else {
        echo "  ❌ mobileMenuOverlay missing\n";
    }
    
    // Check for script
    if (strpos($layoutContent, 'mobileMenuButton.addEventListener') !== false) {
        echo "  ✅ JavaScript event listener found\n";
    } else {
        echo "  ❌ JavaScript event listener missing\n";
    }
    
    // Check for toggle function
    if (strpos($layoutContent, 'toggle(\'-translate-x-full\')') !== false) {
        echo "  ✅ toggle function found\n";
    } else {
        echo "  ❌ toggle function missing\n";
    }
    
} else {
    echo "  ❌ Layout file not found\n";
}

echo "\n🎯 DEBUG SUMMARY:\n";
echo "  ================================================\n";
echo "  ✅ Mobile menu debugging complete!\n";
echo "  \n";
echo "  If any items are missing, that's the issue.\n";
echo "  ================================================\n";

echo "\n✅ Debug completed!\n";
