@extends('layouts.app')

@section('title', 'Detail Dokumentasi Klinis - ' . config('app.name'))

@section('page-title', 'Detail Dokumentasi Klinis')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-file-medical me-2"></i>
                        Detail Dokumentasi Klinis
                    </h4>
                    <div>
                        <span class="badge {{ $documentation->status_badge_class }} text-white">
                            {{ strtoupper($documentation->status) }}
                        </span>
                        @if($documentation->isSyncedWithSatuSehat())
                            <span class="badge bg-success text-white ms-2">
                                <i class="fas fa-check me-1"></i>SYNCED
                            </span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <!-- Patient Information -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="mb-3">
                                <i class="fas fa-user me-2"></i>Informasi Pasien
                            </h5>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>ID Pasien:</strong></td>
                                    <td>{{ $documentation->patient_id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Kunjungan:</strong></td>
                                    <td>{{ $documentation->encounter_date->format('d M Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Dokumenter:</strong></td>
                                    <td>{{ $documentation->documenter->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Dibuat:</strong></td>
                                    <td>{{ $documentation->created_at->format('d M Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Diagnosis Information -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="mb-3">
                                <i class="fas fa-stethoscope me-2"></i>Informasi Diagnosis
                            </h5>
                        </div>
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="text-primary">Diagnosis Utama</h6>
                                    <div class="d-flex align-items-center mb-3">
                                        <span class="badge bg-primary me-2">{{ $documentation->primary_diagnosis_code }}</span>
                                        <strong>{{ $documentation->primary_diagnosis_display }}</strong>
                                    </div>
                                    
                                    @if(!empty($documentation->secondary_diagnoses))
                                        <h6 class="text-secondary">Diagnosis Sekunder</h6>
                                        @foreach($documentation->secondary_diagnoses as $diagnosis)
                                            <div class="d-flex align-items-center mb-2">
                                                <span class="badge bg-secondary me-2">{{ $diagnosis['code'] }}</span>
                                                <span>{{ $diagnosis['display'] }}</span>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Clinical Notes -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="mb-3">
                                <i class="fas fa-notes-medical me-2"></i>Catatan Klinis
                            </h5>
                            <div class="card">
                                <div class="card-body">
                                    <div class="bg-light p-3 rounded" style="white-space: pre-wrap;">
                                        {{ $documentation->clinical_notes }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Satu Sehat Integration Status -->
                    @if($documentation->satu_sehat_encounter_id)
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="mb-3">
                                <i class="fas fa-link me-2"></i>Integrasi Satu Sehat
                            </h5>
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle fa-2x me-3"></i>
                                        <div>
                                            <strong>Terkirim ke Satu Sehat</strong>
                                            <br>
                                            <small>Encounter ID: {{ $documentation->satu_sehat_encounter_id }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Validation Information -->
                    @if($documentation->validated_at)
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="mb-3">
                                <i class="fas fa-certificate me-2"></i>Informasi Validasi
                            </h5>
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-user-check fa-2x me-3"></i>
                                        <div>
                                            <strong>Divalidasi oleh: {{ $documentation->validator->name }}</strong>
                                            <br>
                                            <small>{{ $documentation->validated_at->format('d M Y H:i') }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('cdi.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Kembali
                                </a>
                                <div>
                                    @if(in_array($documentation->status, ['draft', 'completed']))
                                        <a href="{{ route('cdi.edit', $documentation->id) }}" class="btn btn-warning me-2">
                                            <i class="fas fa-edit me-2"></i>Edit
                                        </a>
                                    @endif
                                    
                                    @if($documentation->status === 'completed' && auth()->user()->hasPermission('validate-cdi'))
                                        <button class="btn btn-success me-2" onclick="validateDocumentation({{ $documentation->id }})">
                                            <i class="fas fa-check me-2"></i>Validasi
                                        </button>
                                    @endif
                                    
                                    @if(!$documentation->isSyncedWithSatuSehat() && auth()->user()->hasPermission('sync-cdi-satu-sehat'))
                                        <button class="btn btn-primary me-2" onclick="syncWithSatuSehat({{ $documentation->id }})">
                                            <i class="fas fa-sync me-2"></i>Sync ke Satu Sehat
                                        </button>
                                    @endif
                                    
                                    @if(auth()->user()->hasPermission('delete-cdi'))
                                        <button class="btn btn-danger" onclick="deleteDocumentation({{ $documentation->id }})">
                                            <i class="fas fa-trash me-2"></i>Hapus
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function validateDocumentation(id) {
    if (confirm('Apakah Anda yakin ingin memvalidasi dokumentasi ini?')) {
        fetch(`/cdi/${id}/validate`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Gagal memvalidasi: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memvalidasi dokumentasi');
        });
    }
}

function syncWithSatuSehat(id) {
    if (confirm('Apakah Anda yakin ingin sinkronisasi dengan Satu Sehat?')) {
        fetch(`/cdi/${id}/sync`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Gagal sinkronisasi: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat sinkronisasi dengan Satu Sehat');
        });
    }
}

function deleteDocumentation(id) {
    if (confirm('Apakah Anda yakin ingin menghapus dokumentasi ini? Tindakan ini tidak dapat dibatalkan.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/cdi/${id}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
