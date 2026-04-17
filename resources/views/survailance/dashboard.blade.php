@extends('layouts.app')

@section('title', 'Dashboard Survailance')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold">Dashboard Survailance Penyakit</h4>
                    <p class="text-muted mb-0">Monitoring dan analisis data penyakit berdasarkan ICD-10</p>
                </div>
                <div>
                    <a href="{{ route('pasien.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('survailance.dashboard') }}">
                        <div class="row align-items-end">
                            <div class="col-md-3">
                                <label for="year" class="form-label">Tahun</label>
                                <select name="year" id="year" class="form-select">
                                    @foreach($availableYears as $availableYear)
                                        <option value="{{ $availableYear }}" {{ $availableYear == $year ? 'selected' : '' }}>
                                            {{ $availableYear }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="week" class="form-label">Minggu (Opsional)</label>
                                <select name="week" id="week" class="form-select">
                                    <option value="">Semua Minggu</option>
                                    @for($i = 1; $i <= 52; $i++)
                                        <option value="{{ $i }}" {{ $week == $i ? 'selected' : '' }}>
                                            Minggu {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter me-2"></i>Filter
                                </button>
                                <a href="{{ route('survailance.dashboard') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-refresh me-2"></i>Reset
                                </a>
                            </div>
                            <div class="col-md-3">
                                <div class="text-end">
                                    <small class="text-muted">
                                        Total Kasus: <strong>{{ $totalCases }}</strong>
                                        @if($week)
                                            <br>Minggu {{ $week }}, {{ $year }}
                                        @else
                                            <br>Tahun {{ $year }}
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Statistik Penyakit
                        @if($week)
                            - Minggu {{ $week }}, {{ $year }}
                        @else
                            - Tahun {{ $year }}
                        @endif
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama Penyakit</th>
                                    <th>Kategori</th>
                                    <th>Kode ICD-10</th>
                                    <th class="text-center">Jumlah Kasus</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($statistics as $stat)
                                <tr>
                                    <td><strong>{{ $stat['kode'] }}</strong></td>
                                    <td>{{ $stat['nama_penyakit'] }}</td>
                                    <td><span class="badge bg-info">{{ $stat['kategori'] }}</span></td>
                                    <td>
                                        <small>
                                            @foreach($stat['icd10_codes'] as $code)
                                                <span class="badge bg-secondary me-1">{{ $code }}</span>
                                            @endforeach
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $stat['jumlah_kasus'] > 0 ? 'danger' : 'secondary' }} fs-6">
                                            {{ $stat['jumlah_kasus'] }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <!-- <button class="btn btn-sm btn-outline-primary" onclick="showDiseaseChart('{{ $stat['kode'] }}')" title="Lihat Grafik">
                                            <i class="fas fa-chart-line"></i>
                                        </button> -->
                                        <button class="btn btn-sm btn-outline-info" onclick="showDiseaseDetails('{{ $stat['kode'] }}')" title="Detail Pasien">
                                            <i class="fas fa-list"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-warning" onclick="editDisease('{{ $stat['kode'] }}')" title="Edit Penyakit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-pie me-2"></i>Top 10 Penyakit Terbanyak
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="topDiseasesChart" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Distribusi Kategori Penyakit
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="categoryChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Weekly/Monthly Trend Chart - Optimized -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-line me-2"></i>Tren Penyakit - {{ $year }}
                        <small class="text-muted ms-2">(Optimized)</small>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <select id="diseaseSelect" class="form-select">
                                <option value="">Pilih Penyakit</option>
                                @foreach($diseases as $disease)
                                    <option value="{{ $disease->kode }}">{{ $disease->nama_penyakit }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-primary active" id="weeklyBtn">Mingguan</button>
                                <button type="button" class="btn btn-outline-primary" id="monthlyBtn">Bulanan</button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <div class="spinner-border spinner-border-sm me-2 d-none" id="trendLoading" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <small class="text-muted" id="trendStatus">Pilih penyakit untuk melihat tren</small>
                            </div>
                        </div>
                    </div>
                    <div class="chart-container" style="position: relative; height: 400px;">
                        <canvas id="trendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Disease Chart Modal -->
<div class="modal fade" id="diseaseChartModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Grafik Detail Penyakit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <canvas id="diseaseDetailChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Disease Details Modal -->
<div class="modal fade" id="diseaseDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Pasien</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="diseaseDetailsContent">
                    <!-- Content will be loaded via AJAX -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Disease Modal -->
<div class="modal fade" id="editDiseaseModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Penyakit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editDiseaseForm">
                    <input type="hidden" id="editDiseaseId">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editDiseaseKode" class="form-label">Kode Penyakit</label>
                                <input type="text" class="form-control" id="editDiseaseKode" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editDiseaseNama" class="form-label">Nama Penyakit</label>
                                <input type="text" class="form-control" id="editDiseaseNama" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editDiseaseKategori" class="form-label">Kategori</label>
                                <select class="form-select" id="editDiseaseKategori" required>
                                    <option value="">Pilih Kategori</option>
                                    <option value="Gastrointestinal">Gastrointestinal</option>
                                    <option value="Parasitic">Parasitic</option>
                                    <option value="Viral">Viral</option>
                                    <option value="Respiratory">Respiratory</option>
                                    <option value="Bacterial">Bacterial</option>
                                    <option value="Neurological">Neurological</option>
                                    <option value="Zoonotic">Zoonotic</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editDiseaseStatus" class="form-label">Status</label>
                                <select class="form-select" id="editDiseaseStatus" required>
                                    <option value="1">Aktif</option>
                                    <option value="0">Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="editDiseaseIcd10" class="form-label">Kode ICD-10</label>
                        <textarea class="form-control" id="editDiseaseIcd10" rows="3" placeholder="Masukkan kode ICD-10, dipisahkan dengan koma (contoh: A09, K59.1)" required></textarea>
                        <small class="text-muted">Masukkan kode ICD-10 dipisahkan dengan koma</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="saveDisease()">Simpan Perubahan</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize charts
    initializeTopDiseasesChart();
    initializeCategoryChart();
    initializeTrendChart();
    
    // Event listeners with debouncing
    document.getElementById('diseaseSelect').addEventListener('change', debouncedUpdateTrendChart);
    document.getElementById('weeklyBtn').addEventListener('click', function() {
        this.classList.add('active');
        document.getElementById('monthlyBtn').classList.remove('active');
        debouncedUpdateTrendChart();
    });
    document.getElementById('monthlyBtn').addEventListener('click', function() {
        this.classList.add('active');
        document.getElementById('weeklyBtn').classList.remove('active');
        debouncedUpdateTrendChart();
    });
});

function initializeTopDiseasesChart() {
    fetch(`/survailance/top-diseases?year={{ $year }}&limit=10`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            const ctx = document.getElementById('topDiseasesChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: data.map(d => d.nama_penyakit),
                    datasets: [{
                        data: data.map(d => d.jumlah_kasus),
                        backgroundColor: [
                            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
                            '#FF9F40', '#FF6384', '#C9CBCF', '#4BC0C0', '#FF6384'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        })
        .catch(error => {
            console.error('Error loading top diseases chart:', error);
            const chartContainer = document.getElementById('topDiseasesChart').parentElement;
            chartContainer.innerHTML = '<div class="alert alert-warning">Error loading top diseases data</div>';
        });
}

function initializeCategoryChart() {
    const statistics = @json($statistics);
    const categoryData = {};
    
    statistics.forEach(stat => {
        if (!categoryData[stat.kategori]) {
            categoryData[stat.kategori] = 0;
        }
        categoryData[stat.kategori] += stat.jumlah_kasus;
    });
    
    const ctx = document.getElementById('categoryChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: Object.keys(categoryData),
            datasets: [{
                label: 'Jumlah Kasus',
                data: Object.values(categoryData),
                backgroundColor: '#36A2EB'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

function initializeTrendChart() {
    const ctx = document.getElementById('trendChart').getContext('2d');
    window.trendChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: []
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                duration: 300 // Faster animation
            },
            interaction: {
                intersect: false,
                mode: 'index'
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltip: {
                    enabled: true,
                    mode: 'index',
                    intersect: false
                }
            }
        }
    });
    
    // Cache for trend data
    window.trendDataCache = {};
}

// Debounce function to prevent excessive API calls
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Optimized updateTrendChart with caching
function updateTrendChart() {
    const diseaseKode = document.getElementById('diseaseSelect').value;
    const type = document.getElementById('weeklyBtn').classList.contains('active') ? 'weekly' : 'monthly';
    
    if (!diseaseKode) {
        window.trendChart.data.labels = [];
        window.trendChart.data.datasets = [];
        window.trendChart.update();
        document.getElementById('trendStatus').textContent = 'Pilih penyakit untuk melihat tren';
        return;
    }
    
    // Show loading state
    document.getElementById('trendLoading').classList.remove('d-none');
    document.getElementById('trendStatus').textContent = 'Memuat data...';
    
    // Check cache first
    const cacheKey = `${diseaseKode}_${type}_{{ $year }}`;
    if (window.trendDataCache[cacheKey]) {
        const cachedData = window.trendDataCache[cacheKey];
        updateChartWithData(cachedData);
        document.getElementById('trendLoading').classList.add('d-none');
        document.getElementById('trendStatus').textContent = 'Data dari cache';
        return;
    }
    
    fetch(`/survailance/chart-data?disease=${diseaseKode}&year={{ $year }}&type=${type}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Cache the data
            window.trendDataCache[cacheKey] = data;
            updateChartWithData(data);
            document.getElementById('trendLoading').classList.add('d-none');
            document.getElementById('trendStatus').textContent = `Data ${data.disease} (${type})`;
        })
        .catch(error => {
            console.error('Error updating trend chart:', error);
            document.getElementById('trendLoading').classList.add('d-none');
            document.getElementById('trendStatus').textContent = 'Error: ' + error.message;
        });
}

// Helper function to update chart with data
function updateChartWithData(data) {
    window.trendChart.data.labels = data.labels;
    window.trendChart.data.datasets = [{
        label: data.disease,
        data: data.data,
        borderColor: '#FF6384',
        backgroundColor: 'rgba(255, 99, 132, 0.2)',
        tension: 0.1,
        borderWidth: 2,
        pointRadius: 3,
        pointHoverRadius: 5
    }];
    window.trendChart.update('none'); // Update without animation for better performance
}

// Create debounced version of updateTrendChart
const debouncedUpdateTrendChart = debounce(updateTrendChart, 300);

function editDisease(diseaseKode) {
    // Get disease data from the statistics
    const statistics = @json($statistics);
    const disease = statistics.find(stat => stat.kode === diseaseKode);
    
    if (!disease) {
        alert('Data penyakit tidak ditemukan');
        return;
    }
    
    // Populate form fields
    document.getElementById('editDiseaseId').value = disease.kode;
    document.getElementById('editDiseaseKode').value = disease.kode;
    document.getElementById('editDiseaseNama').value = disease.nama_penyakit;
    document.getElementById('editDiseaseKategori').value = disease.kategori;
    document.getElementById('editDiseaseIcd10').value = disease.icd10_codes.join(', ');
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('editDiseaseModal'));
    modal.show();
}

function saveDisease() {
    const diseaseId = document.getElementById('editDiseaseId').value;
    const nama = document.getElementById('editDiseaseNama').value;
    const kategori = document.getElementById('editDiseaseKategori').value;
    const status = document.getElementById('editDiseaseStatus').value;
    const icd10Codes = document.getElementById('editDiseaseIcd10').value
        .split(',')
        .map(code => code.trim().toUpperCase())
        .filter(code => code.length > 0);
    
    if (!nama || !kategori || icd10Codes.length === 0) {
        alert('Mohon lengkapi semua field yang diperlukan');
        return;
    }
    
    // Show loading state
    const saveBtn = event.target;
    const originalText = saveBtn.textContent;
    saveBtn.textContent = 'Menyimpan...';
    saveBtn.disabled = true;
    
    fetch(`/survailance/update-disease`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            kode: diseaseId,
            nama_penyakit: nama,
            kategori: kategori,
            aktif: status === '1',
            icd10_codes: icd10Codes
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Close modal
            bootstrap.Modal.getInstance(document.getElementById('editDiseaseModal')).hide();
            
            // Show success message
            const successDiv = document.createElement('div');
            successDiv.className = 'alert alert-success alert-dismissible fade show';
            successDiv.innerHTML = `
                <strong>Success!</strong> Data penyakit berhasil diperbarui.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            // Insert at the top of the page
            const container = document.querySelector('.container-fluid');
            container.insertBefore(successDiv, container.firstChild);
            
            // Remove after 5 seconds
            setTimeout(() => {
                if (successDiv.parentNode) {
                    successDiv.parentNode.removeChild(successDiv);
                }
            }, 5000);
            
            // Reload page to show updated data
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            throw new Error(data.message || 'Gagal memperbarui data');
        }
    })
    .catch(error => {
        console.error('Error saving disease:', error);
        alert('Error: ' + error.message);
        
        // Reset button
        saveBtn.textContent = originalText;
        saveBtn.disabled = false;
    });
}

function showDiseaseChart(diseaseKode) {
    const type = document.getElementById('weeklyBtn').classList.contains('active') ? 'weekly' : 'monthly';
    
    fetch(`/survailance/chart-data?disease=${diseaseKode}&year={{ $year }}&type=${type}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            const modal = new bootstrap.Modal(document.getElementById('diseaseChartModal'));
            const ctx = document.getElementById('diseaseDetailChart').getContext('2d');
            
            // Destroy existing chart if it exists
            if (window.diseaseDetailChart) {
                window.diseaseDetailChart.destroy();
            }
            
            window.diseaseDetailChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: data.disease,
                        data: data.data,
                        borderColor: '#36A2EB',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
            
            modal.show();
        })
        .catch(error => {
            console.error('Error loading disease chart:', error);
            alert('Error loading disease chart: ' + error.message);
        });
}

function showDiseaseDetails(diseaseKode) {
    const week = {{ $week ? $week : 'null' }};
    
    fetch(`/survailance/disease-details?disease=${diseaseKode}&year={{ $year }}&week=${week}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            const modal = new bootstrap.Modal(document.getElementById('diseaseDetailsModal'));
            const content = document.getElementById('diseaseDetailsContent');
            
            let html = `
                <div class="mb-3">
                    <h6>${data.disease.nama_penyakit}</h6>
                    <p class="text-muted">Kode ICD-10: ${data.disease.icd10_codes.join(', ')}</p>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Tanggal Kunjungan</th>
                                <th>Nama Pasien</th>
                                <th>No RM</th>
                                <th>ICD-10</th>
                                <th>Poli</th>
                            </tr>
                        </thead>
                        <tbody>
            `;
            
            data.patients.data.forEach(patient => {
                html += `
                    <tr>
                        <td>${patient.tanggal_kunjungan}</td>
                        <td>${patient.nama_pasien}</td>
                        <td>${patient.no_rekam_medik}</td>
                        <td>
                            ${patient.icd10_codes.map(icd => `<span class="badge bg-secondary me-1">${icd.code}</span>`).join('')}
                        </td>
                        <td>${patient.poli}</td>
                    </tr>
                `;
            });
            
            html += `
                        </tbody>
                    </table>
                </div>
            `;
            
            content.innerHTML = html;
            modal.show();
        })
        .catch(error => {
            console.error('Error loading disease details:', error);
            alert('Error loading disease details: ' + error.message);
        });
}
</script>
@endpush
