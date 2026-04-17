@extends('layouts.app')

@section('title', 'Sinkronisasi Data Pasien')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4>
                    <i class="fas fa-sync-alt me-2"></i>
                    Sinkronisasi Data Pasien
                </h4>
                <a href="{{ route('pasien.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">{{ $totalDiagnosaPkm }}</h4>
                            <p class="card-text">Total Data Diagnosa PKM</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-file-medical fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">{{ $uniquePatients }}</h4>
                            <p class="card-text">Pasien Unik</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x"></i>
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
                            <h4 class="card-title">{{ $syncedPatients }}</h4>
                            <p class="card-text">Sudah Disinkronkan</p>
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
                            <h4 class="card-title">{{ $totalPasien }}</h4>
                            <p class="card-text">Total Bank Data</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-database fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sync Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-cogs me-2"></i>
                        Aksi Sinkronisasi
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-grid gap-2">
                                <button class="btn btn-success" onclick="syncAll('new_only')">
                                    <i class="fas fa-plus-circle me-2"></i>
                                    Sync Pasien Baru Saja
                                </button>
                                <small class="text-muted">Menambahkan pasien yang belum ada di bank data</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-grid gap-2">
                                <button class="btn btn-warning" onclick="syncAll('update_existing')">
                                    <i class="fas fa-sync me-2"></i>
                                    Update Data Yang Ada
                                </button>
                                <small class="text-muted">Memperbarui data pasien yang sudah ada dengan kunjungan terakhir</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Patients Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-list me-2"></i>
                            Data Pasien dari Diagnosa PKM
                        </h5>
                        <div class="d-flex gap-2">
                            <input type="text" class="form-control" id="searchInput" placeholder="Cari nama atau No. RM..." style="width: 250px;">
                            <button class="btn btn-outline-primary" onclick="loadPatients()">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No. RM</th>
                                    <th>Nama Pasien</th>
                                    <th>Poli</th>
                                    <th>Total Kunjungan</th>
                                    <th>Kunjungan Terakhir</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="patientsTableBody">
                                <tr>
                                    <td colspan="7" class="text-center">
                                        <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                                        Memuat data...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <nav aria-label="Page navigation" class="mt-3">
                        <ul class="pagination justify-content-center" id="pagination">
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Progress Modal -->
<div class="modal fade" id="progressModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-sync fa-spin me-2"></i>
                    Sedang Sinkronisasi...
                </h5>
            </div>
            <div class="modal-body">
                <div class="progress mb-3">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 100%"></div>
                </div>
                <p id="progressMessage">Memproses data...</p>
            </div>
        </div>
    </div>
</div>

<!-- Result Modal -->
<div class="modal fade" id="resultModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hasil Sinkronisasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="resultBody">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentPage = 1;

$(document).ready(function() {
    loadPatients();
    
    // Search on enter
    $('#searchInput').on('keypress', function(e) {
        if (e.which === 13) {
            loadPatients();
        }
    });
});

function loadPatients(page = 1) {
    const search = $('#searchInput').val();
    
    $.get(`{{ route('pasien.sync.patients') }}?page=${page}&search=${search}`)
        .done(function(data) {
            displayPatients(data);
            currentPage = page;
        })
        .fail(function() {
            $('#patientsTableBody').html(`
                <tr>
                    <td colspan="7" class="text-center text-danger">
                        Gagal memuat data
                    </td>
                </tr>
            `);
        });
}

function displayPatients(data) {
    let html = '';
    
    if (data.data.length === 0) {
        html = `
            <tr>
                <td colspan="7" class="text-center text-muted">
                    Tidak ada data pasien
                </td>
            </tr>
        `;
    } else {
        data.data.forEach(function(patient) {
            const statusBadge = patient.is_synced 
                ? '<span class="badge bg-success">Sudah Disinkronkan</span>'
                : '<span class="badge bg-warning">Belum Disinkronkan</span>';
            
            const actionButtons = patient.is_synced
                ? `<button class="btn btn-sm btn-danger" onclick="deleteSynced('${patient.no_rekam_medik}')">
                    <i class="fas fa-trash"></i>
                   </button>`
                : `<button class="btn btn-sm btn-primary" onclick="syncSingle('${patient.no_rekam_medik}')">
                    <i class="fas fa-sync"></i>
                   </button>`;
            
            html += `
                <tr>
                    <td>${patient.no_rekam_medik}</td>
                    <td>${patient.nama_pasien}</td>
                    <td>${patient.poli}</td>
                    <td>${patient.total_visits}</td>
                    <td>${formatDate(patient.latest_visit)}</td>
                    <td>${statusBadge}</td>
                    <td>${actionButtons}</td>
                </tr>
            `;
        });
    }
    
    $('#patientsTableBody').html(html);
    displayPagination(data);
}

function displayPagination(data) {
    let html = '';
    
    if (data.prev_page_url) {
        html += `<li class="page-item">
            <a class="page-link" href="#" onclick="loadPatients(${data.current_page - 1})">Previous</a>
        </li>`;
    }
    
    for (let i = 1; i <= data.last_page; i++) {
        const active = i === data.current_page ? 'active' : '';
        html += `<li class="page-item ${active}">
            <a class="page-link" href="#" onclick="loadPatients(${i})">${i}</a>
        </li>`;
    }
    
    if (data.next_page_url) {
        html += `<li class="page-item">
            <a class="page-link" href="#" onclick="loadPatients(${data.current_page + 1})">Next</a>
        </li>`;
    }
    
    $('#pagination').html(html);
}

function syncAll(type) {
    $('#progressModal').modal('show');
    
    $.post(`{{ route('pasien.sync.all') }}`, { sync_type: type })
        .done(function(response) {
            $('#progressModal').modal('hide');
            showResult(response);
            loadPatients(currentPage);
        })
        .fail(function(xhr) {
            $('#progressModal').modal('hide');
            const error = xhr.responseJSON ? xhr.responseJSON.message : 'Terjadi kesalahan';
            showResult({ success: false, message: error });
        });
}

function syncSingle(noRekamMedik) {
    if (!confirm('Sinkronkan pasien ini ke bank data?')) return;
    
    $.post(`{{ route('pasien.sync.single', noRekamMedik) }}`)
        .done(function(response) {
            showResult(response);
            loadPatients(currentPage);
        })
        .fail(function(xhr) {
            const error = xhr.responseJSON ? xhr.responseJSON.message : 'Terjadi kesalahan';
            showResult({ success: false, message: error });
        });
}

function deleteSynced(noRekamMedik) {
    if (!confirm('Hapus data pasien dari bank data?')) return;
    
    $.ajax({
        url: `{{ route('pasien.sync.delete', ':noRekamMedik') }}`.replace(':noRekamMedik', noRekamMedik),
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })
    .done(function(response) {
        showResult(response);
        loadPatients(currentPage);
    })
    .fail(function(xhr) {
        const error = xhr.responseJSON ? xhr.responseJSON.message : 'Terjadi kesalahan';
        showResult({ success: false, message: error });
    });
}

function showResult(response) {
    const alertClass = response.success ? 'alert-success' : 'alert-danger';
    const icon = response.success ? 'fa-check-circle' : 'fa-exclamation-triangle';
    
    let html = `
        <div class="alert ${alertClass}" role="alert">
            <i class="fas ${icon} me-2"></i>
            ${response.message}
        </div>
    `;
    
    if (response.data) {
        html += `
            <div class="mt-3">
                <strong>Detail Pasien:</strong><br>
                Nama: ${response.data.nama_pasien}<br>
                No. RM: ${response.data.no_rekam_medik}<br>
                Poli: ${response.data.poli}
            </div>
        `;
    }
    
    if (response.errors && response.errors.length > 0) {
        html += `
            <div class="mt-3">
                <strong>Errors:</strong><br>
                <div class="text-danger" style="max-height: 200px; overflow-y: auto;">
                    ${response.errors.join('<br>')}
                </div>
            </div>
        `;
    }
    
    $('#resultBody').html(html);
    $('#resultModal').modal('show');
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
}
</script>
@endpush
