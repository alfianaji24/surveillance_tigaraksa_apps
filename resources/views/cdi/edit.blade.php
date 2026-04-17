@extends('layouts.app')

@section('title', 'Edit Dokumentasi Klinis - ' . config('app.name'))

@section('page-title', 'Edit Dokumentasi Klinis')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-edit me-2"></i>
                        Edit Dokumentasi Klinis
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('cdi.update', $documentation->id) }}">
                        @csrf
                        @method('PUT')
                        
                        <!-- Patient Information (Read-only) -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="mb-3">
                                    <i class="fas fa-user me-2"></i>Informasi Pasien
                                </h5>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="patient_id" class="form-label">ID Pasien</label>
                                    <input type="text" 
                                           class="form-control bg-light" 
                                           id="patient_id" 
                                           name="patient_id" 
                                           value="{{ $documentation->patient_id }}"
                                           readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="encounter_date" class="form-label">Tanggal Kunjungan</label>
                                    <input type="datetime-local" 
                                           class="form-control bg-light" 
                                           id="encounter_date" 
                                           name="encounter_date" 
                                           value="{{ $documentation->encounter_date->format('Y-m-d\TH:i') }}"
                                           readonly>
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
                                               value="{{ $documentation->primary_diagnosis['code'] }}"
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
                                           value="{{ $documentation->primary_diagnosis['display'] }}"
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
                                    @if(!empty($documentation->secondary_diagnoses))
                                        @foreach($documentation->secondary_diagnoses as $index => $diagnosis)
                                            <div class="secondary-diagnosis-item">
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <h6 class="mb-0">Diagnosis Sekunder #{{ $index + 1 }}</h6>
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
                                                                       id="secondary_diagnosis_{{ $index }}_code" 
                                                                       name="secondary_diagnoses[{{ $index }}][code]" 
                                                                       value="{{ $diagnosis['code'] }}"
                                                                       placeholder="Cari kode ICD-10..."
                                                                       readonly>
                                                                <button type="button" 
                                                                        class="btn btn-outline-primary" 
                                                                        onclick="showICD10SearchModal('secondary_{{ $index }}')">
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
                                                                   id="secondary_diagnosis_{{ $index }}_display" 
                                                                   name="secondary_diagnoses[{{ $index }}][display]" 
                                                                   value="{{ $diagnosis['display'] }}"
                                                                   readonly
                                                                   placeholder="Nama diagnosis akan muncul otomatis">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
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
                                              placeholder="Masukkan catatan klinis pasien...">{{ $documentation->clinical_notes }}</textarea>
                                    <div class="form-text">
                                        <strong>Template yang bisa digunakan:</strong>
                                        <ul class="mb-0">
                                            <li><a href="#" class="template-link" onclick="insertTemplate('subjective')">Anamnesis (Subjective)</a></li>
                                            <li><a href="#" class="template-link" onclick="insertTemplate('objective')">Pemeriksaan Fisik (Objective)</a></li>
                                            <li><a href="#" class="template-link" onclick="insertTemplate('assessment')">Assessment</a></li>
                                            <li><a href="#" class="template-link" onclick="insertTemplate('plan')">Rencana Tindakan (Plan)</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="mb-3">
                                    <i class="fas fa-info-circle me-2"></i>Informasi Status
                                </h5>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Status Saat Ini:</strong> {{ strtoupper($documentation->status) }}
                                    @if($documentation->isSyncedWithSatuSehat())
                                        <br><strong>Status Satu Sehat:</strong> Tersinkronisasi
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('cdi.show', $documentation->id) }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>Kembali
                                    </a>
                                    <div>
                                        <button type="button" 
                                                class="btn btn-outline-warning me-2" 
                                                onclick="saveAsDraft()">
                                            <i class="fas fa-save me-2"></i>Simpan Draft
                                        </button>
                                        <button type="submit" class="btn btn-warning">
                                            <i class="fas fa-check me-2"></i>Update Dokumentasi
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
let secondaryDiagnosisCount = {{ count($documentation->secondary_diagnoses ?? []) }};

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
            <h6 class="mb-0">Diagnosis Sekunder #${secondaryDiagnosisCount + 1}</h6>
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
