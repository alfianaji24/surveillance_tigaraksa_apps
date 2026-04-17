@extends('layouts.app')

@section('title', 'Tambah Kode ICD-10 - ' . config('app.name'))

@section('page-title', 'Tambah Kode ICD-10')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-semibold text-gray-900">Tambah Kode ICD-10</h2>
            <p class="text-gray-600">Tambah kode penyakit baru ke database</p>
        </div>
        <a href="{{ route('icd10.index') }}" class="text-gray-600 hover:text-gray-900">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
        </a>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('icd10.store') }}">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kode ICD-10 <span class="text-red-500">*</span></label>
                <input type="text" 
                       name="code" 
                       required
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
            <a href="{{ route('icd10.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                Batal
            </a>
            <button type="submit" class="px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                Simpan Kode
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
                    Preview Kode ICD-10
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="previewContent">
                <!-- Preview content will be inserted here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="submitForm()">
                    <i class="fas fa-save me-2"></i>Simpan
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Auto-uppercase for code field
document.getElementById('code').addEventListener('input', function(e) {
    e.target.value = e.target.value.toUpperCase();
});

// Character counter for definition
document.getElementById('definition').addEventListener('input', function(e) {
    document.getElementById('charCount').textContent = e.target.value.length;
});

// Preview functionality
function previewForm() {
    const code = document.getElementById('code').value;
    const display = document.getElementById('display').value;
    const definition = document.getElementById('definition').value;
    
    if (!code || !display) {
        alert('Silakan lengkapi kode dan nama penyakit terlebih dahulu');
        return;
    }
    
    const previewHTML = `
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
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            Preview data yang akan disimpan. Klik "Simpan" untuk menyimpan kode ICD-10 ini.
        </div>
    `;
    
    document.getElementById('previewContent').innerHTML = previewHTML;
    new bootstrap.Modal(document.getElementById('previewModal')).show();
}

// Submit form from preview modal
function submitForm() {
    bootstrap.Modal.getInstance(document.getElementById('previewModal')).hide();
    document.getElementById('createForm').submit();
}

// Form validation
document.getElementById('createForm').addEventListener('submit', function(e) {
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

// Initialize tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
});
</script>
@endpush
