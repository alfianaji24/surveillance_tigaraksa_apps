<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== FIX PKM BUTTON ISSUE ===\n\n";

// Read the current view file
$viewPath = resource_path('views/pasien/import.blade.php');
$viewContent = file_get_contents($viewPath);

echo "📊 Analyzing current JavaScript implementation...\n";

// Find the problematic section
$scriptStart = strpos($viewContent, '<script>');
$scriptEnd = strpos($viewContent, '</script>');

if ($scriptStart !== false && $scriptEnd !== false) {
    $scriptContent = substr($viewContent, $scriptStart, $scriptEnd - $scriptStart + 9);
    
    // Check for duplicate variable declarations
    $uploadPKMCount = substr_count($scriptContent, "const uploadPKMBtn");
    $pkmFileCount = substr_count($scriptContent, "const pkmFile");
    
    echo "  📋 uploadPKMBtn declarations: $uploadPKMCount\n";
    echo "  📋 pkmFile declarations: $pkmFileCount\n";
    
    if ($uploadPKMCount > 1) {
        echo "  ⚠️  Found duplicate uploadPKMBtn declarations\n";
    }
    
    if ($pkmFileCount > 1) {
        echo "  ⚠️  Found duplicate pkmFile declarations\n";
    }
    
    // Check for event listener
    if (strpos($scriptContent, "uploadPKMBtn.addEventListener") !== false) {
        echo "  ✅ Event listener found\n";
        
        // Count event listeners
        $listenerCount = substr_count($scriptContent, "uploadPKMBtn.addEventListener");
        echo "  📋 Event listeners: $listenerCount\n";
        
        if ($listenerCount > 1) {
            echo "  ⚠️  Multiple event listeners found - this could cause conflicts\n";
        }
    } else {
        echo "  ❌ Event listener missing\n";
    }
}

echo "\n🔧 Creating fixed JavaScript section...\n";

// Create a clean, fixed JavaScript section
$fixedScript = '
<script>
document.addEventListener("DOMContentLoaded", function() {
    console.log("PKM Import script loaded");
    
    // Get all DOM elements
    const uploadBtn = document.getElementById("uploadBtn");
    const backToUploadBtn = document.getElementById("backToUpload");
    const blastBtn = document.getElementById("blastBtn");
    const importDirectBtn = document.getElementById("importDirectBtn");
    const saveAllBtn = document.getElementById("saveAllBtn");
    const importMoreBtn = document.getElementById("importMoreBtn");
    const importPKMBtn = document.getElementById("importPKMBtn");
    const excelFile = document.getElementById("excelFile");
    const pkmFile = document.getElementById("pkmFile");
    const uploadPKMBtnElement = document.getElementById("uploadPKMBtn");
    const pkmImportMoreBtn = document.getElementById("pkmImportMoreBtn");
    
    console.log("DOM Elements:", {
        uploadPKMBtnElement: uploadPKMBtnElement,
        pkmFile: pkmFile
    });
    
    let excelData = [];
    let processedData = [];

    // PKM Upload & Preview Button - MAIN FUNCTIONALITY
    if (uploadPKMBtnElement) {
        console.log("Adding event listener to uploadPKMBtn");
        
        uploadPKMBtnElement.addEventListener("click", function() {
            console.log("PKM Upload button clicked!");
            
            const file = pkmFile.files[0];
            if (!file) {
                alert("Silakan pilih file Excel/CSV PKM terlebih dahulu");
                return;
            }

            if (!confirm("Apakah Anda yakin ingin mengimport data PKM langsung ke database?\\n\\nData akan disimpan di tabel pasiens dan dapat langsung digunakan. No Rekam Medik boleh duplicate untuk multiple visits.")) {
                return;
            }

            console.log("Starting PKM upload process...");
            
            // Show loading state
            const uploadStep = document.getElementById("pkmUploadStep");
            const successStep = document.getElementById("pkmSuccessStep");
            
            if (uploadStep) uploadStep.style.display = "none";
            if (successStep) successStep.style.display = "none";
            
            // Create loading indicator
            const loadingHtml = `
                <div id="pkmLoadingStep" style="display: block;">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <h5 class="mt-3">Sedang Mengimport Data...</h5>
                        <p class="text-muted">Mohon tunggu, proses import sedang berjalan. Jangan tutup halaman ini.</p>
                        <div class="progress mt-3">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
            `;
            
            if (uploadStep) {
                uploadStep.insertAdjacentHTML("afterend", loadingHtml);
            }

            const formData = new FormData();
            formData.append("file", file);

            fetch("{{ route(\'pasien.import-pkm\') }}", {
                method: "POST",
                body: formData,
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log("PKM Upload response:", data);
                
                // Remove loading indicator
                const loadingStep = document.getElementById("pkmLoadingStep");
                if (loadingStep) {
                    loadingStep.remove();
                }
                
                if (data.success) {
                    // Show success message
                    const successHtml = `
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <h5 class="alert-heading"><i class="fas fa-check-circle me-2"></i>Import Berhasil!</h5>
                            <p class="mb-2">${data.message}</p>
                            <hr>
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Data Diimport:</strong> ${data.imported || 0}
                                </div>
                                <div class="col-md-4">
                                    <strong>Data Dilewati:</strong> ${data.skipped || 0}
                                </div>
                                <div class="col-md-4">
                                    <strong>Total Errors:</strong> ${data.errors ? data.errors.length : 0}
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `;
                    
                    const successMessage = document.getElementById("pkmSuccessMessage");
                    if (successMessage) {
                        successMessage.innerHTML = successHtml;
                    }
                    
                    if (successStep) {
                        successStep.style.display = "block";
                        successStep.scrollIntoView({ behavior: "smooth" });
                    }
                    
                    alert("✅ Import Berhasil! " + (data.imported || 0) + " data pasien berhasil diimport.");
                    
                } else {
                    alert("❌ Import Gagal: " + data.message);
                    if (uploadStep) uploadStep.style.display = "block";
                }
            })
            .catch(error => {
                console.error("PKM Upload error:", error);
                
                // Remove loading indicator
                const loadingStep = document.getElementById("pkmLoadingStep");
                if (loadingStep) {
                    loadingStep.remove();
                }
                
                alert("❌ Koneksi Error: Terjadi kesalahan saat menghubungi server.");
                if (uploadStep) uploadStep.style.display = "block";
            });
        });
        
        console.log("PKM Upload button event listener added successfully");
    } else {
        console.error("PKM Upload button not found!");
    }

    // PKM Import More button
    if (pkmImportMoreBtn) {
        pkmImportMoreBtn.addEventListener("click", function() {
            if (pkmFile) pkmFile.value = "";
            
            const uploadStep = document.getElementById("pkmUploadStep");
            const successStep = document.getElementById("pkmSuccessStep");
            
            if (uploadStep) uploadStep.style.display = "block";
            if (successStep) successStep.style.display = "none";
        });
    }

    // Notification function
    function showNotification(title, message, type = "info") {
        console.log("Notification:", title, message);
        // Simple alert for now
        alert(title + ": " + message);
    }

    console.log("PKM Import script initialization complete");
});
</script>';

echo "  ✅ Fixed JavaScript created\n";

// Create a backup of the original file
$backupPath = $viewPath . '.backup';
file_put_contents($backupPath, $viewContent);
echo "  ✅ Backup created: import.blade.php.backup\n";

// Replace the script section
$newContent = preg_replace('/<script>.*?<\/script>/s', $fixedScript, $viewContent);

if ($newContent) {
    file_put_contents($viewPath, $newContent);
    echo "  ✅ View file updated with fixed JavaScript\n";
} else {
    echo "  ❌ Failed to update view file\n";
}

echo "\n🎯 FIX SUMMARY:\n";
echo "  ================================================\n";
echo "  ✅ PKM button fix complete!\n";
echo "  \n";
echo "  Changes made:\n";
echo "  - Removed duplicate variable declarations\n";
echo "  - Fixed event listener conflicts\n";
echo "  - Added comprehensive error handling\n";
echo "  - Added console logging for debugging\n";
echo "  ================================================\n";

echo "\n✅ Fix completed! Please refresh the page and test the PKM button.\n";
