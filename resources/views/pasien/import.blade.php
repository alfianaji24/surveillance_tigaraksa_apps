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
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="manual-tab" data-bs-toggle="tab" data-bs-target="#klinik" type="button" role="tab" aria-controls="klinik" aria-selected="false">
                                <i class="fas fa-user-plus me-2"></i>Input Data Klinik
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
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Import Data PKM dari Template (Excel/CSV Format)</strong><br>
                                Upload file Excel template PKM untuk mengimport data pasien langsung ke database.
                                <br><br>
                                <strong>Format Kolom Template PKM:</strong><br>
                                A: No | B: Tanggal Kunjungan | C: Poli | D: No Rekam Medik | E: NIK<br>
                                F: Nama Pasien | G: Alamat | H: No HP | I: Tanggal Lahir<br>
                                J: Umur | K: Jenis Kelamin | L: Jenis Pasien | M: No BPJS | N: Jenis Bayar<br>
                                O: Anamnesa | P: Diagnosa | Q: Pemeriksa | R: Status | S: RS Rujukan
                                <br><br>
                                <small><i class="fas fa-shield-alt"></i> <strong>Validasi Otomatis:</strong> Required fields, data format, dan structure validation</small>
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
document.addEventListener('DOMContentLoaded', function() {
    const uploadBtn = document.getElementById('uploadBtn');
    const backToUploadBtn = document.getElementById('backToUpload');
    const blastBtn = document.getElementById('blastBtn');
    const importDirectBtn = document.getElementById('importDirectBtn');
    const saveAllBtn = document.getElementById('saveAllBtn');
    const importMoreBtn = document.getElementById('importMoreBtn');
    const importPKMBtn = document.getElementById('importPKMBtn');
    const excelFile = document.getElementById('excelFile');
    const pkmFile = document.getElementById('pkmFile');
    const uploadPKMBtn = document.getElementById('uploadPKMBtn');
    const pkmImportMoreBtn = document.getElementById('pkmImportMoreBtn');
    
    let excelData = [];
    let processedData = [];

    // Upload Excel
    uploadBtn.addEventListener('click', function() {
        const file = excelFile.files[0];
        if (!file) {
            alert('Silakan pilih file CSV terlebih dahulu');
            return;
        }

        const formData = new FormData();
        formData.append('excel_file', file);

        showStep('loadingStep');

        fetch('{{ route("pasien.import-excel") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                excelData = data.data;
                displayPreview(data.data);
                showStep('previewStep');
            } else {
                alert('Error: ' + data.message);
                showStep('uploadStep');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat upload file');
            showStep('uploadStep');
        });
    });

    // Back to Upload
    backToUploadBtn.addEventListener('click', function() {
        showStep('uploadStep');
        excelFile.value = '';
    });

    // Blast Data to Form
    blastBtn.addEventListener('click', function() {
        if (excelData.length === 0) {
            alert('Tidak ada data untuk di-blast');
            return;
        }

        showStep('loadingStep');

        fetch('{{ route("pasien.blast-data") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(excelData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Simpan data ke sessionStorage untuk form create
                sessionStorage.setItem('blastData', JSON.stringify(data.data[0]));
                
                // Redirect ke form create
                window.location.href = '{{ route("pasien.create") }}';
            } else {
                alert('Error: ' + data.message);
                showStep('previewStep');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat blast data');
            showStep('previewStep');
        });
    });

    // Import Langsung ke Database
    importDirectBtn.addEventListener('click', function() {
        if (excelData.length === 0) {
            alert('Tidak ada data untuk diimport');
            return;
        }

        if (!confirm(`Apakah Anda yakin ingin mengimport ${excelData.length} data langsung ke database?\n\nData yang sudah ada tidak akan ditimpa.`)) {
            return;
        }

        showStep('loadingStep');

        const formData = new FormData();
        const file = excelFile.files[0];
        formData.append('excel_file', file);

        fetch('{{ route("pasien.import-to-database") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Tampilkan hasil import
                document.getElementById('successMessage').innerHTML = `
                    <strong>Import Selesai!</strong><br>
                    ✅ ${data.imported} data berhasil disimpan<br>
                    ⚠️ ${data.skipped} data dilewati<br>
                    <br>
                    ${data.errors.length > 0 ? '<strong>Detail Error:</strong><br>' + data.errors.slice(0, 5).join('<br>') + (data.errors.length > 5 ? '<br>...dan ' + (data.errors.length - 5) + ' error lainnya' : '') : 'Semua data berhasil diimport tanpa error!'}
                `;
                showStep('successStep');
            } else {
                alert('Error: ' + data.message);
                showStep('previewStep');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat import data');
            showStep('previewStep');
        });
    });

    // Save All Data
    saveAllBtn.addEventListener('click', function() {
        if (excelData.length === 0) {
            alert('Tidak ada data untuk disimpan');
            return;
        }

        if (!confirm(`Apakah Anda yakin ingin menyimpan ${excelData.length} data pasien?`)) {
            return;
        }

        showStep('loadingStep');

        fetch('{{ route("pasien.blast-data") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(excelData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                processedData = data.data;
                saveAllPatients(data.data);
            } else {
                alert('Error: ' + data.message);
                showStep('previewStep');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memproses data');
            showStep('previewStep');
        });
    });

    // Import More
    importMoreBtn.addEventListener('click', function() {
        showStep('uploadStep');
        excelFile.value = '';
    });

    // Import PKM Langsung ke Database
    importPKMBtn.addEventListener('click', function() {
        const file = pkmFile.files[0];
        if (!file) {
            alert('Silakan pilih file Excel/CSV PKM terlebih dahulu');
            return;
        }

        if (!confirm('Apakah Anda yakin ingin mengimport data PKM langsung ke database?\n\nData akan disimpan di tabel pasiens dan dapat langsung digunakan. No Rekam Medik boleh duplicate untuk multiple visits.')) {
            return;
        }

        document.getElementById('pkmUploadStep').style.display = 'none';
        document.getElementById('pkmSuccessStep').style.display = 'none';

        const formData = new FormData();
        formData.append('file', file);

        fetch('{{ route("pasien.import-pkm") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('pkmSuccessMessage').textContent = data.message;
                document.getElementById('pkmSuccessStep').style.display = 'block';
            } else {
                alert('Error: ' + data.message);
                document.getElementById('pkmUploadStep').style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat import data PKM');
            document.getElementById('pkmUploadStep').style.display = 'block';
        });
    });

    // PKM Import More
    pkmImportMoreBtn.addEventListener('click', function() {
        pkmFile.value = '';
        document.getElementById('pkmUploadStep').style.display = 'block';
        document.getElementById('pkmSuccessStep').style.display = 'none';
    });

    function saveAllPatients(data) {
        let successCount = 0;
        let errorCount = 0;
        let promises = [];

        data.forEach(patientData => {
            const promise = fetch('{{ route("pasien.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(patientData)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    successCount++;
                } else {
                    errorCount++;
                }
            })
            .catch(error => {
                errorCount++;
            });

            promises.push(promise);
        });

        Promise.all(promises).then(() => {
            document.getElementById('successMessage').textContent = 
                `${successCount} data berhasil disimpan, ${errorCount} data gagal.`;
            showStep('successStep');
        });
    }

    function showStep(stepId) {
        document.getElementById('uploadStep').style.display = 'none';
        document.getElementById('previewStep').style.display = 'none';
        document.getElementById('loadingStep').style.display = 'none';
        document.getElementById('successStep').style.display = 'none';
        document.getElementById(stepId).style.display = 'block';
    }

    function displayPreview(data) {
        const tbody = document.getElementById('previewTableBody');
        tbody.innerHTML = '';

        data.forEach((row, index) => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${index + 1}</td>
                <td>${row.nama_pasien || '-'}</td>
                <td>${row.no_rekam_medik || '-'}</td>
                <td>${row.nik || '-'}</td>
                <td>${row.poli || '-'}</td>
                <td>${row.tanggal_lahir || '-'}</td>
                <td>-</td>
                <td>${row.jenis_kelamin || '-'}</td>
                <td>${row.jenis_pasien || '-'}</td>
                <td>${(row.anamnesa || '').substring(0, 50)}${row.anamnesa && row.anamnesa.length > 50 ? '...' : ''}</td>
                <td>${row.diagnosa_icd10 || '-'}</td>
                <td>${row.status || '-'}</td>
            `;
            tbody.appendChild(tr);
        });
    }

    // Template help function
    function showTemplateHelp() {
        const helpModal = `
            <div class="modal fade" id="templateHelpModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-question-circle me-2"></i>Panduan Template PKM
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <h6><i class="fas fa-file-excel me-2"></i>Template Structure</h6>
                            <p>Template PKM memiliki 17 kolom (A-Q) dengan format sebagai berikut:</p>
                            
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Kolom</th>
                                            <th>Field</th>
                                            <th>Required</th>
                                            <th>Format</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr><td>A</td><td>Tanggal Kunjungan</td><td>✓</td><td>YYYY-MM-DD</td></tr>
                                        <tr><td>B</td><td>Poli</td><td>✓</td><td>Text</td></tr>
                                        <tr><td>C</td><td>No Rekam Medik</td><td>✓</td><td>Text/Number</td></tr>
                                        <tr><td>D</td><td>NIK</td><td></td><td>16 Digit</td></tr>
                                        <tr><td>E</td><td>Nama Pasien</td><td>✓</td><td>Text</td></tr>
                                        <tr><td>F</td><td>Alamat</td><td></td><td>Text</td></tr>
                                        <tr><td>G</td><td>No HP</td><td></td><td>Phone Number</td></tr>
                                        <tr><td>H</td><td>Tanggal Lahir</td><td></td><td>YYYY-MM-DD</td></tr>
                                        <tr><td>I</td><td>Jenis Kelamin</td><td></td><td>L/P</td></tr>
                                        <tr><td>J</td><td>Jenis Pasien</td><td></td><td>Text</td></tr>
                                        <tr><td>K</td><td>No BPJS</td><td></td><td>Number</td></tr>
                                        <tr><td>L</td><td>Jenis Bayar</td><td></td><td>Text</td></tr>
                                        <tr><td>M</td><td>Anamnesa</td><td></td><td>Text</td></tr>
                                        <tr><td>N</td><td>Diagnosa ICD10</td><td></td><td>Code</td></tr>
                                        <tr><td>O</td><td>Pemeriksa</td><td></td><td>Text</td></tr>
                                        <tr><td>P</td><td>Status</td><td></td><td>Text</td></tr>
                                        <tr><td>Q</td><td>RS Rujukan</td><td></td><td>Text</td></tr>
                                    </tbody>
                                </table>
                            </div>
                            
                            <hr>
                            
                            <h6><i class="fas fa-lightbulb me-2"></i>Tips & Best Practices</h6>
                            <ul>
                                <li><strong>Required Fields:</strong> Nama Pasien dan No Rekam Medik harus diisi</li>
                                <li><strong>Date Format:</strong> Gunakan format YYYY-MM-DD untuk tanggal</li>
                                <li><strong>Header Row:</strong> Baris pertama adalah header, data dimulai dari baris ke-2</li>
                                <li><strong>Empty Rows:</strong> Sistem akan otomatis berhenti jika menemukan 5 baris kosong berturut-turut</li>
                                <li><strong>Validation:</strong> Sistem akan memvalidasi struktur template dan required fields</li>
                            </ul>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="button" class="btn btn-primary" onclick="window.open('/template_pkm/template_pkm.xlsx', '_blank')">
                                <i class="fas fa-download me-1"></i>Download Template
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Remove existing modal if any
        const existingModal = document.getElementById('templateHelpModal');
        if (existingModal) {
            existingModal.remove();
        }
        
        // Add modal to body and show
        document.body.insertAdjacentHTML('beforeend', helpModal);
        const modal = new bootstrap.Modal(document.getElementById('templateHelpModal'));
        modal.show();
    }
});
</script>
@endsection
