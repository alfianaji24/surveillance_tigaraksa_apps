@extends('layouts.app')

@section('title', 'Import Data Pasien')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold">Import Data Pasien</h4>
                    <p class="text-muted mb-0">Upload data pasien dari template PKM</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('pasien.create') }}" class="btn btn-success">
                        <i class="fas fa-plus me-2"></i>Tambah Pasien
                    </a>
                    <a href="{{ route('pasien.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="importTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pkm-tab" data-bs-toggle="tab" data-bs-target="#pkm" type="button" role="tab" aria-controls="pkm" aria-selected="true">
                                <i class="fas fa-file-excel me-2"></i>Import File PKM
                            </button>
                        </li>
                        <!-- <li class="nav-item" role="presentation">
                            <button class="nav-link" id="manual-tab" data-bs-toggle="tab" data-bs-target="#manual" type="button" role="tab" aria-controls="manual" aria-selected="false">
                                <i class="fas fa-user-plus me-2"></i>Input Data Manual
                            </button>
                        </li> -->
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="importTabContent">
                        <!-- Tab 1: Import File PKM -->
                        <div class="tab-pane fade show active" id="pkm" role="tabpanel" aria-labelledby="pkm-tab">
                    <div id="pkmUploadStep">
<div class="alert alert-info border-0 shadow-sm p-4">
    
    <div class="d-flex align-items-center mb-3">
        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="min-width: 50px; height: 50px;">
            <i class="fas fa-file-excel fa-lg"></i>
        </div>
        <div>
            <h5 class="mb-0 text-dark"><strong>Panduan Import Data Pasien PKM</strong></h5>
            <p class="text-secondary mb-0">Pastikan file Excel Anda mengikuti urutan kolom di bawah ini untuk menghindari kegagalan sistem.</p>
        </div>
    </div>

    <hr>

    <p class="fw-bold mb-2 small text-uppercase text-primary tracking-wider">
        <i class="fas fa-eye me-2"></i>Contoh Tampilan Baris Pertama:
    </p>
    <div class="table-responsive mb-4">
        <table class="table table-sm table-bordered bg-white text-center mb-0" style="font-size: 0.8rem;">
            <thead class="table-dark">
                <tr>
                    <th width="5%">A</th>
                    <th width="12%">B</th>
                    <th width="10%">C</th>
                    <th width="12%">D</th>
                    <th width="12%">E</th>
                    <th width="15%">F</th>
                    <th class="bg-secondary text-white">... G s/d S</th>
                </tr>
            </thead>
            <tbody>
                <tr class="fw-bold bg-light text-dark">
                    <td>no</td>
                    <td>tanggal_kunjungan</td>
                    <td>poli</td>
                    <td>no_rekam_medik</td>
                    <td>nik</td>
                    <td>nama_pasien</td>
                    <td class="text-muted small">Lihat daftar di bawah</td>
                </tr>
                <tr class="text-muted">
                    <td>1</td>
                    <td>2026-03-23</td>
                    <td>Anak</td>
                    <td>0081***</td>
                    <td>360303***</td>
                    <td><span class="badge bg-secondary">DATA TERPROTEKSI</span></td>
                    <td>...</td>
                </tr>
            </tbody>
        </table>
    </div>

    <p class="fw-bold mb-2 small text-uppercase text-primary tracking-wider">
        <i class="fas fa-list-ol me-2"></i>Daftar Kolom Lengkap (Wajib Berurutan):
    </p>
    <div class="row g-2">
        <div class="col-md-4">
            <div class="d-grid gap-1">
                <div class="p-2 border rounded bg-white small d-flex align-items-center">
                    <b class="text-primary me-2" style="width: 15px;">A</b> <span>no</span>
                </div>
                <div class="p-2 border rounded bg-white small d-flex align-items-center">
                    <b class="text-primary me-2" style="width: 15px;">B</b> <span>tanggal_kunjungan</span>
                </div>
                <div class="p-2 border rounded bg-white small d-flex align-items-center">
                    <b class="text-primary me-2" style="width: 15px;">C</b> <span>poli</span>
                </div>
                <div class="p-2 border rounded bg-white small d-flex align-items-center">
                    <b class="text-primary me-2" style="width: 15px;">D</b> <span>no_rekam_medik</span>
                </div>
                <div class="p-2 border rounded bg-white small d-flex align-items-center">
                    <b class="text-primary me-2" style="width: 15px;">E</b> <span>nik</span>
                </div>
                <div class="p-2 border rounded bg-white small d-flex align-items-center">
                    <b class="text-primary me-2" style="width: 15px;">F</b> <span>nama_pasien</span>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="d-grid gap-1">
                <div class="p-2 border rounded bg-white small d-flex align-items-center">
                    <b class="text-primary me-2" style="width: 15px;">G</b> <span>alamat</span>
                </div>
                <div class="p-2 border rounded bg-white small d-flex align-items-center">
                    <b class="text-primary me-2" style="width: 15px;">H</b> <span>no_hp</span>
                </div>
                <div class="p-2 border rounded bg-white small d-flex align-items-center">
                    <b class="text-primary me-2" style="width: 15px;">I</b> <span>tanggal_lahir</span>
                </div>
                <div class="p-2 border rounded bg-white small d-flex align-items-center">
                    <b class="text-primary me-2" style="width: 15px;">J</b> <span>umur</span>
                </div>
                <div class="p-2 border rounded bg-white small d-flex align-items-center">
                    <b class="text-primary me-2" style="width: 15px;">K</b> <span>jenis_kelamin</span>
                </div>
                <div class="p-2 border rounded bg-white small d-flex align-items-center">
                    <b class="text-primary me-2" style="width: 15px;">L</b> <span>jenis_pasien</span>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="d-grid gap-1">
                <div class="p-2 border rounded bg-white small d-flex align-items-center">
                    <b class="text-primary me-2" style="width: 15px;">M</b> <span>no_bpjs</span>
                </div>
                <div class="p-2 border rounded bg-white small d-flex align-items-center">
                    <b class="text-primary me-2" style="width: 15px;">N</b> <span>jenis_bayar</span>
                </div>
                <div class="p-2 border rounded bg-white small d-flex align-items-center">
                    <b class="text-primary me-2" style="width: 15px;">O</b> <span>anamnesa</span>
                </div>
                <div class="p-2 border rounded bg-white small d-flex align-items-center">
                    <b class="text-primary me-2" style="width: 15px;">P</b> <span>diagnosa</span>
                </div>
                <div class="p-2 border rounded bg-white small d-flex align-items-center">
                    <b class="text-primary me-2" style="width: 15px;">Q</b> <span>pemeriksa</span>
                </div>
                <div class="p-2 border rounded bg-white small d-flex align-items-center">
                    <b class="text-primary me-2" style="width: 15px;">R</b> <span>status</span>
                </div>
                <div class="p-2 border rounded bg-white small d-flex align-items-center">
                    <b class="text-primary me-2" style="width: 15px;">S</b> <span>rs_rujukan</span>
                </div>
            </div>
        </div>
    </div>

    <hr>

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
        <div class="small">
            <span class="badge bg-warning text-dark me-2">PENTING</span>
            <span class="text-muted italic">Format Tanggal wajib <strong>YYYY-MM-DD</strong> dan NIK harus <strong>16 digit</strong>.</span>
        </div>
    </div>
</div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="pkmFile" class="form-label">Pilih File Excel/CSV PKM</label>
                                    <input type="file" class="form-control" id="pkmFile" accept=".csv,.xlsx,.xls">
                                    <div class="form-text">Format: .csv, .xlsx, .xls (Maks. 2MB) - Template PKM Excel atau CSV</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Quick Actions</label>
                                    <div class="btn-group w-100" role="group">
                                        <button type="button" class="btn btn-outline-info btn-sm" onclick="window.open('{{ route('pasien.download-template-pkm') }}', '_blank')">
                                            <i class="fas fa-download me-1"></i>Download Template
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                            <button type="button" class="btn btn-primary btn-lg" id="uploadPKMBtn">
                                <i class="fas fa-upload me-2"></i>Upload & Preview
                            </button>
                            <hr>
                        </div>

                    <!-- PKM Success Step -->
                    <div id="pkmSuccessStep" style="display: none;">
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Import Selesai!</strong>
                            <div id="pkmSuccessMessage"></div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-primary" id="pkmImportMoreBtn">
                                <i class="fas fa-plus me-2"></i>Import Lagi
                            </button>
                            <a href="{{ route('pasien.index') }}" class="btn btn-success">
                                <i class="fas fa-list me-2"></i>Lihat Data Pasien
                            </a>
                        </div>
                    </div>
                    </div>

                    <!-- Tab 2: Input Data Manual -->
                    <div class="tab-pane fade" id="manual" role="tabpanel" aria-labelledby="manual-tab">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Input Data Pasien Manual</strong><br>
                            Tambahkan data pasien satu per satu melalui form input manual.
                        </div>
                        
                        <div class="text-center py-4">
                            <a href="{{ route('pasien.create') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-plus me-2"></i>Buka Form Input Manual
                            </a>
                        </div>
                    </div>

                    <!-- Step 2: Preview Data -->
                    <div id="previewStep" style="display: none;">
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            Data berhasil dibaca! Silakan review data sebelum diproses.
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Nama Pasien</th>
                                        <th>No RM</th>
                                        <th>NIK</th>
                                        <th>Poli</th>
                                        <th>Tanggal Lahir</th>
                                        <th>Umur</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Jenis Pasien</th>
                                        <th>Anamnesa</th>
                                        <th>Diagnosa</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="previewTableBody">
                                    <!-- Data akan diisi via JavaScript -->
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary" id="backToUpload">
                                <i class="fas fa-arrow-left me-2"></i>Upload Ulang
                            </button>
                            <div>
                                <button type="button" class="btn btn-warning me-2" id="blastBtn">
                                    <i class="fas fa-rocket me-2"></i>Blast ke Form
                                </button>
                                <button type="button" class="btn btn-primary me-2" id="importDirectBtn">
                                    <i class="fas fa-database me-2"></i>Import Langsung ke DB
                                </button>
                                <button type="button" class="btn btn-success" id="saveAllBtn">
                                    <i class="fas fa-save me-2"></i>Simpan Semua Data
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Loading -->
                    <div id="loadingStep" style="display: none;">
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Sedang memproses data...</p>
                        </div>
                    </div>

                    <!-- Step 4: Success -->
                    <div id="successStep" style="display: none;">
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Success!</strong> <span id="successMessage"></span>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-primary" id="importMoreBtn">
                                <i class="fas fa-plus me-2"></i>Import Lagi
                            </button>
                            <a href="{{ route('pasien.index') }}" class="btn btn-success">
                                <i class="fas fa-list me-2"></i>Lihat Data Pasien
                            </a>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


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

            if (!confirm("Apakah Anda yakin ingin mengimport data PKM langsung ke database?\n\nData akan disimpan di tabel pasiens dan dapat langsung digunakan. No Rekam Medik boleh duplicate untuk multiple visits.")) {
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

            fetch("{{ route('pasien.import-pkm') }}", {
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
</script>
@endsection
