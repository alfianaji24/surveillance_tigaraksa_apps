@extends('layouts.app')

@section('title', 'Edit Kode ICD-10 - ' . config('app.name'))

@section('page-title', 'Edit Kode ICD-10')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center">
            <a href="{{ route('icd10.show', $code->id) }}" class="text-gray-600 hover:text-gray-900 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Edit Kode ICD-10</h2>
                <p class="text-gray-600">Perbarui informasi kode {{ $code->formatted_code }}</p>
            </div>
        </div>
    </div>

    <!-- Current Info Alert -->
    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-blue-700">
                    <strong>Informasi Saat Ini:</strong> 
                    <span class="ml-2 px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">{{ $code->formatted_code }}</span>
                    <span class="ml-2">{{ $code->display }}</span>
                </p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('icd10.update', $code->id) }}">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kode ICD-10 <span class="text-red-500">*</span></label>
                <input type="text" 
                       name="code" 
                       required
                       value="{{ $code->code }}"
                       placeholder="Contoh: A00, I10, E11"
                       maxlength="10"
                       style="text-transform: uppercase;"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                <p class="mt-1 text-sm text-gray-500">Masukkan kode ICD-10 (maksimal 10 karakter)</p>
                @error('code')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Penyakit <span class="text-red-500">*</span></label>
                <input type="text" 
                       name="display" 
                       required
                       value="{{ $code->display }}"
                       placeholder="Contoh: Kolera, Hipertensi esensial"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                <p class="mt-1 text-sm text-gray-500">Nama lengkap penyakit sesuai klasifikasi ICD-10</p>
                @error('display')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        
        
        <!-- Submit Buttons -->
        <div class="mt-8 flex justify-end space-x-3">
            <a href="{{ route('icd10.show', $code->id) }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                Batal
            </a>
            <button type="submit" class="px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                Perbarui Kode
            </button>
        </div>
    </form>
</div>

<script>
document.querySelector('input[name="code"]').addEventListener('input', function(e) {
    e.target.value = e.target.value.toUpperCase();
});
</script>
@endsection

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-eye me-2"></i>
                    Preview Perubahan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="previewContent">
                <!-- Preview content will be inserted here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-warning" onclick="submitForm()">
                    <i class="fas fa-save me-2"></i>Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Original data for comparison
const originalData = {
    code: '{{ $code->code }}',
    display: '{{ $code->display }}',
    definition: '{{ $code->definition }}',
    is_active: {{ $code->is_active ? 'true' : 'false' }}
};

// Auto-uppercase for code field
document.getElementById('code').addEventListener('input', function(e) {
    e.target.value = e.target.value.toUpperCase();
});

// Character counter for definition
document.getElementById('definition').addEventListener('input', function(e) {
    document.getElementById('charCount').textContent = e.target.value.length;
});

// Preview changes functionality
function previewChanges() {
    const code = document.getElementById('code').value;
    const display = document.getElementById('display').value;
    const definition = document.getElementById('definition').value;
    const isActive = document.getElementById('is_active').checked;
    
    if (!code || !display) {
        alert('Silakan lengkapi kode dan nama penyakit terlebih dahulu');
        return;
    }
    
    let changesHTML = '<div class="row">';
    
    // Show changes for each field
    if (code !== originalData.code) {
        changesHTML += `
            <div class="col-12 mb-3">
                <div class="alert alert-warning">
                    <strong>Kode ICD-10:</strong><br>
                    <span class="text-decoration-line-through text-muted">${originalData.code}</span>
                    <i class="fas fa-arrow-right mx-2"></i>
                    <span class="fw-bold text-success">${code}</span>
                </div>
            </div>
        `;
    }
    
    if (display !== originalData.display) {
        changesHTML += `
            <div class="col-12 mb-3">
                <div class="alert alert-warning">
                    <strong>Nama Penyakit:</strong><br>
                    <span class="text-decoration-line-through text-muted">${originalData.display}</span>
                    <i class="fas fa-arrow-right mx-2"></i>
                    <span class="fw-bold text-success">${display}</span>
                </div>
            </div>
        `;
    }
    
    if (definition !== originalData.definition) {
        changesHTML += `
            <div class="col-12 mb-3">
                <div class="alert alert-warning">
                    <strong>Deskripsi:</strong><br>
                    <span class="text-decoration-line-through text-muted">${originalData.definition || '(kosong)'}</span>
                    <i class="fas fa-arrow-down mx-2"></i><br>
                    <span class="fw-bold text-success">${definition || '(kosong)'}</span>
                </div>
            </div>
        `;
    }
    
    if (isActive !== originalData.is_active) {
        changesHTML += `
            <div class="col-12 mb-3">
                <div class="alert alert-warning">
                    <strong>Status:</strong><br>
                    <span class="text-decoration-line-through text-muted">${originalData.is_active ? 'Aktif' : 'Non-aktif'}</span>
                    <i class="fas fa-arrow-right mx-2"></i>
                    <span class="fw-bold text-success">${isActive ? 'Aktif' : 'Non-aktif'}</span>
                </div>
            </div>
        `;
    }
    
    if (changesHTML === '<div class="row">') {
        changesHTML = '<div class="col-12"><div class="alert alert-info">Tidak ada perubahan yang terdeteksi.</div></div>';
    } else {
        changesHTML += '</div>';
    }
    
    // Show final data
    changesHTML += `
        <hr>
        <h6 class="fw-semibold mb-3">Data Setelah Perubahan:</h6>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Kode ICD-10</label>
                    <div class="form-control-plaintext">
                        <span class="badge bg-primary fs-6">${code}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Penyakit</label>
                    <div class="form-control-plaintext">
                        <strong>${display}</strong>
                    </div>
                </div>
            </div>
        </div>
        ${definition ? `
        <div class="row">
            <div class="col-12">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Deskripsi</label>
                    <div class="form-control-plaintext">
                        ${definition}
                    </div>
                </div>
            </div>
        </div>
        ` : ''}
        <div class="row">
            <div class="col-12">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Status</label>
                    <div class="form-control-plaintext">
                        <span class="badge ${isActive ? 'bg-success' : 'bg-secondary'}">
                            ${isActive ? 'Aktif' : 'Non-aktif'}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="alert alert-success mt-3">
            <i class="fas fa-check-circle me-2"></i>
            Klik "Simpan Perubahan" untuk menyimpan semua perubahan ini.
        </div>
    `;
    
    document.getElementById('previewContent').innerHTML = changesHTML;
    new bootstrap.Modal(document.getElementById('previewModal')).show();
}

// Submit form from preview modal
function submitForm() {
    bootstrap.Modal.getInstance(document.getElementById('previewModal')).hide();
    document.getElementById('editForm').submit();
}

// Form validation
document.getElementById('editForm').addEventListener('submit', function(e) {
    const code = document.getElementById('code').value.trim();
    const display = document.getElementById('display').value.trim();
    
    if (!code) {
        e.preventDefault();
        alert('Kode ICD-10 harus diisi');
        document.getElementById('code').focus();
        return;
    }
    
    if (!display) {
        e.preventDefault();
        alert('Nama penyakit harus diisi');
        document.getElementById('display').focus();
        return;
    }
    
    if (code.length < 2) {
        e.preventDefault();
        alert('Kode ICD-10 minimal 2 karakter');
        document.getElementById('code').focus();
        return;
    }
    
    if (display.length < 3) {
        e.preventDefault();
        alert('Nama penyakit minimal 3 karakter');
        document.getElementById('display').focus();
        return;
    }
});

// Warn user if leaving with unsaved changes
window.addEventListener('beforeunload', function(e) {
    const code = document.getElementById('code').value;
    const display = document.getElementById('display').value;
    const definition = document.getElementById('definition').value;
    const isActive = document.getElementById('is_active').checked;
    
    if (code !== originalData.code || 
        display !== originalData.display || 
        definition !== originalData.definition || 
        isActive !== originalData.is_active) {
        e.preventDefault();
        e.returnValue = '';
    }
});

// Initialize tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
});
</script>
@endpush
