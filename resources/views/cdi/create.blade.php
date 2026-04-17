@extends('layouts.app')

@section('title', 'Buat Dokumentasi Klinis - ' . config('app.name'))

@section('page-title', 'Buat Dokumentasi Klinis Baru')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-plus me-2"></i>
                        Buat Dokumentasi Klinis Baru
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('cdi.store') }}">
                        @csrf
                        
                        <!-- Patient Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="mb-3">
                                    <i class="fas fa-user me-2"></i>Informasi Pasien
                                </h5>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="patient_id" class="form-label">ID Pasien <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="patient_id" 
                                           name="patient_id" 
                                           required
                                           placeholder="Masukkan ID Pasien">
                                    <div class="form-text">ID Pasien dari sistem Satu Sehat</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="encounter_date" class="form-label">Tanggal Kunjungan <span class="text-danger">*</span></label>
                                    <input type="datetime-local" 
                                           class="form-control" 
                                           id="encounter_date" 
                                           name="encounter_date" 
                                           required
                                           value="{{ now()->format('Y-m-d\TH:i') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Primary Diagnosis -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="mb-3">
                                    <i class="fas fa-stethoscope me-2"></i>Diagnosis Utama
                                </h5>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label">Kode ICD-10 <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" 
                                               class="form-control" 
                                               id="primary_diagnosis_code" 
                                               name="primary_diagnosis[code]" 
                                               required
                                               placeholder="Cari kode ICD-10..."
                                               readonly>
                                        <button type="button" 
                                                class="btn btn-outline-primary" 
                                                onclick="showICD10SearchModal('primary')">
                                            <i class="fas fa-search"></i> Cari
                                        </button>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Nama Diagnosis</label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="primary_diagnosis_display" 
                                           name="primary_diagnosis[display]" 
                                           readonly
                                           placeholder="Nama diagnosis akan muncul otomatis">
                                </div>
                            </div>
                        </div>

                        <!-- Secondary Diagnoses -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="mb-3">
                                    <i class="fas fa-list me-2"></i>Diagnosis Sekunder (Opsional)
                                </h5>
                            </div>
                            <div class="col-12">
                                <div id="secondary-diagnoses-container">
                                    <!-- Secondary diagnoses will be added here dynamically -->
                                </div>
                                <button type="button" 
                                        class="btn btn-outline-success btn-sm mt-2" 
                                        onclick="addSecondaryDiagnosis()">
                                    <i class="fas fa-plus"></i> Tambah Diagnosis Sekunder
                                </button>
                            </div>
                        </div>

                        <!-- Clinical Notes -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="mb-3">
                                    <i class="fas fa-notes-medical me-2"></i>Catatan Klinis
                                </h5>
                                <div class="mb-3">
                                    <label for="clinical_notes" class="form-label">Catatan Klinis <span class="text-danger">*</span></label>
                                    <textarea class="form-control" 
                                              id="clinical_notes" 
                                              name="clinical_notes" 
                                              rows="8" 
                                              required
                                              placeholder="Masukkan catatan klinis pasien..."></textarea>
                                    <div class="form-text">
                                        <strong>Template yang bisa digunakan:</strong>
                                        <ul class="mb-0">
                                            <li><a href="#" onclick="insertTemplate('subjective')">Anamnesis (Subjective)</a></li>
                                            <li><a href="#" onclick="insertTemplate('objective')">Pemeriksaan Fisik (Objective)</a></li>
                                            <li><a href="#" onclick="insertTemplate('assessment')">Assessment</a></li>
                                            <li><a href="#" onclick="insertTemplate('plan')">Rencana Tindakan (Plan)</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Documenter Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="mb-3">
                                    <i class="fas fa-user-md me-2"></i>Informasi Pendokumentasi
                                </h5>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="documenter_id" class="form-label">ID Dokter <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="documenter_id" 
                                           name="documenter_id" 
                                           required
                                           value="{{ Auth::user()->id }}"
                                           readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nama Dokter</label>
                                    <input type="text" 
                                           class="form-control" 
                                           value="{{ Auth::user()->name }}"
                                           readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('cdi.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>Kembali
                                    </a>
                                    <div>
                                        <button type="button" 
                                                class="btn btn-outline-primary me-2" 
                                                onclick="saveAsDraft()">
                                            <i class="fas fa-save me-2"></i>Simpan Draft
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-check me-2"></i>Simpan Dokumentasi
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ICD-10 Search Modal -->
<div class="modal fade" id="icd10SearchModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cari Kode ICD-10</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <div class="input-group">
                        <input type="text" 
                               id="modal-icd10-search" 
                               class="form-control" 
                               placeholder="Masukkan nama penyakit atau kode ICD-10..."
                               autocomplete="off">
                        <button class="btn btn-primary" onclick="searchICD10InModal()">
                            <i class="fas fa-search"></i> Cari
                        </button>
                    </div>
                </div>
                <div id="modal-icd10-results">
                    <!-- Results will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.icd10-search-result {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 12px;
    margin-bottom: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.icd10-search-result:hover {
    background-color: #f8f9fa;
    border-color: #007bff;
}

.icd10-code-badge {
    background-color: #007bff;
    color: white;
    padding: 2px 8px;
    border-radius: 4px;
    font-weight: bold;
    font-size: 0.9em;
}

.secondary-diagnosis-item {
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 10px;
}

.template-link {
    color: #007bff;
    text-decoration: none;
    cursor: pointer;
}

.template-link:hover {
    text-decoration: underline;
}
</style>
@endpush

@push('scripts')
<script>
let currentDiagnosisField = null;
let secondaryDiagnosisCount = 0;

function showICD10SearchModal(field) {
    currentDiagnosisField = field;
    const modal = new bootstrap.Modal(document.getElementById('icd10SearchModal'));
    document.getElementById('modal-icd10-search').value = '';
    document.getElementById('modal-icd10-results').innerHTML = '';
    document.getElementById('modal-icd10-search').focus();
    modal.show();
}

document.getElementById('modal-icd10-search').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        searchICD10InModal();
    }
});

function searchICD10InModal() {
    const query = document.getElementById('modal-icd10-search').value.trim();
    const resultsDiv = document.getElementById('modal-icd10-results');
    
    if (!query) {
        alert('Silakan masukkan kata kunci pencarian');
        return;
    }
    
    resultsDiv.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Mencari...</div>';
    
    fetch(`/cdi/search-icd10?query=${encodeURIComponent(query)}&limit=20`)
        .then(response => response.json())
        .then(data => {
            displayModalICD10Results(data);
        })
        .catch(error => {
            console.error('Error:', error);
            resultsDiv.innerHTML = '<div class="alert alert-danger">Terjadi kesalahan saat mencari data ICD-10</div>';
        });
}

function displayModalICD10Results(data) {
    const resultsDiv = document.getElementById('modal-icd10-results');
    
    if (!data || data.length === 0) {
        resultsDiv.innerHTML = '<div class="alert alert-info">Tidak ditemukan hasil untuk pencarian ini</div>';
        return;
    }
    
    let html = '';
    data.forEach(item => {
        html += `
            <div class="icd10-search-result" onclick="selectICD10Code('${item.code}', '${item.display}')">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="icd10-code-badge">${item.code}</span>
                        <strong class="ms-2">${item.display}</strong>
                    </div>
                </div>
                ${item.definition ? `<div class="text-muted small mt-2">${item.definition}</div>` : ''}
            </div>
        `;
    });
    
    resultsDiv.innerHTML = html;
}

function selectICD10Code(code, display) {
    if (currentDiagnosisField === 'primary') {
        document.getElementById('primary_diagnosis_code').value = code;
        document.getElementById('primary_diagnosis_display').value = display;
    } else if (currentDiagnosisField && currentDiagnosisField.startsWith('secondary_')) {
        const index = currentDiagnosisField.split('_')[1];
        document.getElementById(`secondary_diagnosis_${index}_code`).value = code;
        document.getElementById(`secondary_diagnosis_${index}_display`).value = display;
    }
    
    // Close modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('icd10SearchModal'));
    modal.hide();
}

function addSecondaryDiagnosis() {
    secondaryDiagnosisCount++;
    const container = document.getElementById('secondary-diagnoses-container');
    
    const div = document.createElement('div');
    div.className = 'secondary-diagnosis-item';
    div.innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="mb-0">Diagnosis Sekunder #${secondaryDiagnosisCount}</h6>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeSecondaryDiagnosis(this)">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Kode ICD-10</label>
                    <div class="input-group">
                        <input type="text" 
                               class="form-control" 
                               id="secondary_diagnosis_${secondaryDiagnosisCount}_code" 
                               name="secondary_diagnoses[${secondaryDiagnosisCount}][code]" 
                               placeholder="Cari kode ICD-10..."
                               readonly>
                        <button type="button" 
                                class="btn btn-outline-primary" 
                                onclick="showICD10SearchModal('secondary_${secondaryDiagnosisCount}')">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Nama Diagnosis</label>
                    <input type="text" 
                           class="form-control" 
                           id="secondary_diagnosis_${secondaryDiagnosisCount}_display" 
                           name="secondary_diagnoses[${secondaryDiagnosisCount}][display]" 
                           readonly
                           placeholder="Nama diagnosis akan muncul otomatis">
                </div>
            </div>
        </div>
    `;
    
    container.appendChild(div);
}

function removeSecondaryDiagnosis(button) {
    button.closest('.secondary-diagnosis-item').remove();
}

function insertTemplate(type) {
    const textarea = document.getElementById('clinical_notes');
    const templates = {
        subjective: 'ANAMNESIS (SUBJEKTIF)\n\nKeluhan Utama:\n- \n\nRiwayat Penyakit Sekarang:\n- \n\nRiwayat Penyakit Dahulu:\n- \n\nRiwayat Penyakit Keluarga:\n- \n\nRiwayat Sosial:\n- \n',
        objective: 'PEMERIKSAAN FISIK (OBJEKTIF)\n\nKeadaan Umum: \n- Kesadaran: Compos Mentis\n- Tanda Vital: TD: / mmHg, N: /menit, RR: /menit, S: °C\n- Status Gizi: \n\nKepala:\n- Mata: \n- Telinga: \n- Hidung: \n- Tenggorokan: \n\nLeher: \n\nThoraks:\n- Jantung: \n- Paru: \n\nAbdomen: \n\nEkstremitas: \n\nNeurologis: \n',
        assessment: 'ASSESSMENT\n\nDiagnosis Utama:\n- \n\nDiagnosis Banding:\n- \n\nKomplikasi:\n- \n',
        plan: 'PLAN (RENCANA TINDAKAN)\n\nTerapi:\n- \n\nPemeriksaan Penunjang:\n- \n\nKonsultasi:\n- \n\nEdukasi Pasien:\n- \n\nKontrol: \n- '
    };
    
    const currentValue = textarea.value;
    const template = templates[type] || '';
    
    if (currentValue) {
        textarea.value = currentValue + '\n\n' + template;
    } else {
        textarea.value = template;
    }
    
    textarea.focus();
}

function saveAsDraft() {
    // Change form action to save as draft
    const form = document.querySelector('form');
    const currentAction = form.action;
    form.action = '/cdi/save-draft';
    
    // Submit form
    form.submit();
    
    // Restore original action (in case of validation error)
    setTimeout(() => {
        form.action = currentAction;
    }, 1000);
}

// Auto-save functionality
let autoSaveTimer;
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const inputs = form.querySelectorAll('input, textarea, select');
    
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            clearTimeout(autoSaveTimer);
            autoSaveTimer = setTimeout(() => {
                console.log('Auto-saving draft...');
                // Implement auto-save logic here
            }, 30000); // Auto-save after 30 seconds of inactivity
        });
    });
});
</script>
@endpush
