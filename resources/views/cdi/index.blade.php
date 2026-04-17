@extends('layouts.app')

@section('title', 'Clinical Documentation Improvement - ' . config('app.name'))

@section('page-title', 'Clinical Documentation Improvement')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-notes-medical me-2"></i>
                        Clinical Documentation Improvement (CDI)
                    </h4>
                </div>
                <div class="card-body">
                    <p class="text-muted">
                        Sistem dokumentasi klinis terintegrasi dengan Satu Sehat untuk pengkodean ICD-10 yang akurat dan efisien.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-md-3">
            <a href="{{ route('cdi.create') }}" class="btn btn-success w-100 mb-2">
                <i class="fas fa-plus me-2"></i>Dokumentasi Baru
            </a>
        </div>
        <div class="col-md-3">
            <button class="btn btn-info w-100 mb-2" onclick="showICD10Search()">
                <i class="fas fa-search me-2"></i>Cari ICD-10
            </button>
        </div>
        <div class="col-md-3">
            <button class="btn btn-warning w-100 mb-2" onclick="showDocumentationList()">
                <i class="fas fa-list me-2"></i>Daftar Dokumentasi
            </button>
        </div>
        <div class="col-md-3">
            <button class="btn btn-secondary w-100 mb-2" onclick="showAnalytics()">
                <i class="fas fa-chart-bar me-2"></i>Analitik
            </button>
        </div>
    </div>

    <!-- ICD-10 Search Section -->
    <div id="icd10-search-section" class="row mb-4" style="display: none;">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-search me-2"></i>
                        Pencarian Kode ICD-10
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="text" 
                                       id="icd10-search-input" 
                                       class="form-control" 
                                       placeholder="Masukkan nama penyakit atau kode ICD-10..."
                                       autocomplete="off">
                                <button class="btn btn-primary" onclick="searchICD10()">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select id="icd10-limit" class="form-select">
                                <option value="10">10 hasil</option>
                                <option value="20" selected>20 hasil</option>
                                <option value="50">50 hasil</option>
                            </select>
                        </div>
                    </div>
                    
                    <div id="icd10-search-results" class="mt-3" style="display: none;">
                        <!-- Results will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Documentation List Section -->
    <div id="documentation-list-section" class="row" style="display: none;">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        Daftar Dokumentasi Klinis
                    </h5>
                    <div class="d-flex gap-2">
                        <input type="text" 
                               class="form-control form-control-sm" 
                               placeholder="Cari dokumentasi..." 
                               style="width: 200px;">
                        <select class="form-select form-select-sm" style="width: 150px;">
                            <option>Semua Status</option>
                            <option>Draft</option>
                            <option>Selesai</option>
                            <option>Tervalidasi</option>
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Pasien</th>
                                    <th>Diagnosis Utama</th>
                                    <th>Status</th>
                                    <th>Dokumen</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($recentDocumentations->count() > 0)
                                    @foreach($recentDocumentations as $doc)
                                        <tr>
                                            <td>{{ $doc->encounter_date->format('d M Y') }}</td>
                                            <td>{{ $doc->patient_id }}</td>
                                            <td>
                                                <span class="badge bg-primary me-1">{{ $doc->primary_diagnosis_code }}</span>
                                                {{ $doc->primary_diagnosis_display }}
                                            </td>
                                            <td>
                                                <span class="badge {{ $doc->status_badge_class }} text-white">
                                                    {{ strtoupper($doc->status) }}
                                                </span>
                                                @if($doc->isSyncedWithSatuSehat())
                                                    <i class="fas fa-check-circle text-success ms-1" title="Synced with Satu Sehat"></i>
                                                @endif
                                            </td>
                                            <td>{{ $doc->documenter->name }}</td>
                                            <td>
                                                <a href="{{ route('cdi.show', $doc->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if(in_array($doc->status, ['draft', 'completed']))
                                                    <a href="{{ route('cdi.edit', $doc->id) }}" class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3"></i>
                                            <p>Belum ada dokumentasi klinis</p>
                                            <button class="btn btn-primary btn-sm" onclick="hideDocumentationList(); showICD10Search();">
                                                Buat Dokumentasi Baru
                                            </button>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Section -->
    <div id="analytics-section" class="row" style="display: none;">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Top 10 Diagnosis</h6>
                </div>
                <div class="card-body">
                    <canvas id="topDiagnosisChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Trend Dokumentasi</h6>
                </div>
                <div class="card-body">
                    <canvas id="documentationTrendChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0" id="total-documents">0</h4>
                            <p class="mb-0">Total Dokumentasi</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-file-medical fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0" id="completed-documents">0</h4>
                            <p class="mb-0">Selesai</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0" id="draft-documents">0</h4>
                            <p class="mb-0">Draft</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-edit fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0" id="icd10-codes">0</h4>
                            <p class="mb-0">Kode ICD-10</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-code fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ICD-10 Detail Modal -->
<div class="modal fade" id="icd10DetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail ICD-10</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="icd10-detail-content">
                <!-- Detail will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="useICD10Code()">
                    <i class="fas fa-plus me-2"></i>Gunakan Kode
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.icd10-result-item {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 10px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.icd10-result-item:hover {
    background-color: #f8f9fa;
    border-color: #007bff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.icd10-code {
    background-color: #007bff;
    color: white;
    padding: 2px 8px;
    border-radius: 4px;
    font-weight: bold;
    font-size: 0.9em;
}

.search-loading {
    text-align: center;
    padding: 20px;
}

.status-badge {
    font-size: 0.8em;
    padding: 4px 8px;
    border-radius: 12px;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let selectedICD10Code = null;

function showICD10Search() {
    hideAllSections();
    document.getElementById('icd10-search-section').style.display = 'block';
    document.getElementById('icd10-search-input').focus();
}

function showDocumentationList() {
    hideAllSections();
    document.getElementById('documentation-list-section').style.display = 'block';
}

function showAnalytics() {
    hideAllSections();
    document.getElementById('analytics-section').style.display = 'block';
    initCharts();
}

function hideAllSections() {
    document.getElementById('icd10-search-section').style.display = 'none';
    document.getElementById('documentation-list-section').style.display = 'none';
    document.getElementById('analytics-section').style.display = 'none';
}

function hideDocumentationList() {
    document.getElementById('documentation-list-section').style.display = 'none';
}

// ICD-10 Search
document.getElementById('icd10-search-input').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        searchICD10();
    }
});

function searchICD10() {
    const query = document.getElementById('icd10-search-input').value.trim();
    const limit = document.getElementById('icd10-limit').value;
    
    if (!query) {
        alert('Silakan masukkan kata kunci pencarian');
        return;
    }
    
    const resultsDiv = document.getElementById('icd10-search-results');
    resultsDiv.style.display = 'block';
    resultsDiv.innerHTML = '<div class="search-loading"><i class="fas fa-spinner fa-spin"></i> Mencari...</div>';
    
    fetch(`/cdi/search-icd10?query=${encodeURIComponent(query)}&limit=${limit}`)
        .then(response => response.json())
        .then(data => {
            displayICD10Results(data);
        })
        .catch(error => {
            console.error('Error:', error);
            resultsDiv.innerHTML = '<div class="alert alert-danger">Terjadi kesalahan saat mencari data ICD-10</div>';
        });
}

function displayICD10Results(data) {
    const resultsDiv = document.getElementById('icd10-search-results');
    
    if (!data || data.length === 0) {
        resultsDiv.innerHTML = '<div class="alert alert-info">Tidak ditemukan hasil untuk pencarian ini</div>';
        return;
    }
    
    let html = '<div class="mb-2"><strong>Ditemukan ' + data.length + ' hasil:</strong></div>';
    
    data.forEach(item => {
        html += `
            <div class="icd10-result-item" onclick="showICD10Detail('${item.code}')">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="icd10-code">${item.code}</span>
                        <strong class="ms-2">${item.display}</strong>
                    </div>
                    <button class="btn btn-sm btn-outline-primary" onclick="event.stopPropagation(); selectICD10Code('${item.code}', '${item.display}')">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                ${item.definition ? `<div class="text-muted small mt-2">${item.definition}</div>` : ''}
            </div>
        `;
    });
    
    resultsDiv.innerHTML = html;
}

function showICD10Detail(code) {
    const modal = new bootstrap.Modal(document.getElementById('icd10DetailModal'));
    const contentDiv = document.getElementById('icd10-detail-content');
    
    contentDiv.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Memuat detail...</div>';
    
    fetch(`/cdi/icd10-detail/${code}`)
        .then(response => response.json())
        .then(data => {
            selectedICD10Code = data;
            displayICD10Detail(data);
        })
        .catch(error => {
            console.error('Error:', error);
            contentDiv.innerHTML = '<div class="alert alert-danger">Gagal memuat detail ICD-10</div>';
        });
    
    modal.show();
}

function displayICD10Detail(data) {
    const contentDiv = document.getElementById('icd10-detail-content');
    
    let html = `
        <div class="row">
            <div class="col-md-4">
                <strong>Kode:</strong>
            </div>
            <div class="col-md-8">
                <span class="icd10-code">${data.code}</span>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-4">
                <strong>Nama Diagnosis:</strong>
            </div>
            <div class="col-md-8">
                ${data.display}
            </div>
        </div>
    `;
    
    if (data.definition) {
        html += `
            <div class="row mt-2">
                <div class="col-md-4">
                    <strong>Definisi:</strong>
                </div>
                <div class="col-md-8">
                    ${data.definition}
                </div>
            </div>
        `;
    }
    
    if (data.hierarchy) {
        html += `
            <div class="row mt-2">
                <div class="col-md-4">
                    <strong>Hierarki:</strong>
                </div>
                <div class="col-md-8">
                    <small class="text-muted">${data.hierarchy}</small>
                </div>
            </div>
        `;
    }
    
    if (data.inclusion && data.inclusion.length > 0) {
        html += `
            <div class="row mt-3">
                <div class="col-12">
                    <strong>Inklusi:</strong>
                    <ul class="small">
                        ${data.inclusion.map(item => `<li>${item}</li>`).join('')}
                    </ul>
                </div>
            </div>
        `;
    }
    
    if (data.exclusion && data.exclusion.length > 0) {
        html += `
            <div class="row mt-3">
                <div class="col-12">
                    <strong>Eksklusi:</strong>
                    <ul class="small">
                        ${data.exclusion.map(item => `<li>${item}</li>`).join('')}
                    </ul>
                </div>
            </div>
        `;
    }
    
    contentDiv.innerHTML = html;
}

function selectICD10Code(code, display) {
    selectedICD10Code = { code: code, display: display };
    useICD10Code();
}

function useICD10Code() {
    if (selectedICD10Code) {
        // This function can be customized based on where you want to use the selected code
        alert(`Kode ICD-10 ${selectedICD10Code.code} - ${selectedICD10Code.display} telah dipilih`);
        
        // Close modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('icd10DetailModal'));
        modal.hide();
        
        // You can redirect to create documentation page with pre-selected code
        // window.location.href = '/cdi/create?icd10_code=' + selectedICD10Code.code;
    }
}

function initCharts() {
    // Top Diagnosis Chart
    const ctx1 = document.getElementById('topDiagnosisChart').getContext('2d');
    new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: ['I10', 'E11', 'J45', 'I50', 'N18'],
            datasets: [{
                label: 'Jumlah Kasus',
                data: [45, 38, 32, 28, 25],
                backgroundColor: '#007bff'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
    
    // Documentation Trend Chart
    const ctx2 = document.getElementById('documentationTrendChart').getContext('2d');
    new Chart(ctx2, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
            datasets: [{
                label: 'Dokumentasi',
                data: [12, 19, 15, 25, 22, 30],
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
}

// Load statistics on page load
document.addEventListener('DOMContentLoaded', function() {
    // Load actual statistics from server
    document.getElementById('total-documents').textContent = '{{ $totalDocuments }}';
    document.getElementById('completed-documents').textContent = '{{ $completedDocuments }}';
    document.getElementById('draft-documents').textContent = '{{ $draftDocuments }}';
    document.getElementById('icd10-codes').textContent = '{{ $validatedDocuments }}';
});
</script>
@endpush
