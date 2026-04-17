@extends('layouts.app')

@section('title', 'Detail Kode ICD-10 - ' . config('app.name'))

@section('page-title', 'Detail Kode ICD-10')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center">
            <a href="{{ route('icd10.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Detail Kode ICD-10</h2>
                <p class="text-gray-600">Informasi lengkap kode {{ $code->formatted_code }}</p>
            </div>
        </div>
            </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Main Information -->
        <div class="lg:col-span-2">
            <div class="bg-gray-50 rounded-lg p-6">
                <div class="flex items-start mb-6">
                    <div class="flex-shrink-0">
                        <div class="bg-blue-100 rounded-full p-4">
                            <span class="text-2xl font-bold text-blue-600">{{ $code->formatted_code }}</span>
                        </div>
                    </div>
                    <div class="ml-6 flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $code->display }}</h3>
                        <p class="text-sm text-gray-500 mb-4">Dibuat pada {{ $code->created_at->format('d F Y') }}</p>
                        <div class="flex space-x-3">
                            <a href="{{ route('icd10.edit', $code->id) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit
                            </a>
                            <button onclick="confirmDelete({{ $code->id }}, '{{ $code->display }}')" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>
                
                            </div>
        </div>

        <!-- Right Column - Meta Information -->
        <div class="lg:col-span-1">
            <div class="bg-gray-50 rounded-lg p-6">
                <h4 class="text-sm font-medium text-gray-900 mb-4">Informasi Tambahan</h4>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500">Tanggal dibuat</p>
                        <p class="text-sm font-medium text-gray-900">{{ $code->created_at->format('d F Y, H:i') }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-500">Tanggal diperbarui</p>
                        <p class="text-sm font-medium text-gray-900">{{ $code->updated_at->format('d F Y, H:i') }}</p>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="mt-6 pt-6 border-t">
                    <h4 class="text-sm font-medium text-gray-900 mb-3">Aksi Cepat</h4>
                    <div class="space-y-2">
                        <button onclick="copyToClipboard('{{ $code->formatted_code }} - {{ $code->display }}')" class="w-full inline-flex justify-center items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                            Salin Informasi
                        </button>
                        <button onclick="window.print()" class="w-full inline-flex justify-center items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                            Cetak
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id, name) {
    if (confirm(`Apakah Anda yakin ingin menghapus kode "${name}"?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/icd10/${id}`;
        
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

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('Informasi berhasil disalin!');
    }).catch(function(err) {
        alert('Gagal menyalin informasi');
    });
}
</script>
@endsection
@endsection

@php
    function getICD10Category($code) {
        $firstChar = strtoupper(substr($code, 0, 1));
        $categories = [
            'A' => 'Penyakit Menular dan Parasit (A00-B99)',
            'B' => 'Penyakit Menular dan Parasit (A00-B99)',
            'C' => 'Tumor (C00-D48)',
            'D' => 'Tumor (C00-D48)',
            'E' => 'Penyakit Endokrin, Nutrisi, dan Metabolik (E00-E90)',
            'F' => 'Gangguan Mental dan Perilaku (F00-F99)',
            'G' => 'Penyakit Sistem Saraf (G00-G99)',
            'H' => 'Penyakit Mata dan Adneksa (H00-H59)',
            'I' => 'Penyakit Sirkulasi (I00-I99)',
            'J' => 'Penyakit Sistem Pernapasan (J00-J99)',
            'K' => 'Penyakit Sistem Pencernaan (K00-K93)',
            'L' => 'Penyakit Kulit dan Jaringan Subkutan (L00-L99)',
            'M' => 'Penyakit Sistem Muskuloskeletal dan Jaringan Ikut (M00-M99)',
            'N' => 'Penyakit Sistem Genitourinari (N00-N99)',
            'O' => 'Kehamilan, Persalinan, dan Nifas (O00-O99)',
            'P' => 'Kondisi Yang Berasal dari Periode Perinatal (P00-P96)',
            'Q' => 'Malformasi Kongenital, Deformasi, dan Kelainan Kromosom (Q00-Q99)',
            'R' => 'Gejala, Tanda, dan Hasil Abnormal Laboratorium (R00-R99)',
            'S' => 'Cedera, Keracunan, dan Konsekuensi Lainnya (S00-T98)',
            'T' => 'Cedera, Keracunan, dan Konsekuensi Lainnya (S00-T98)',
            'V' => 'Penyebab Eksternal Morbiditas dan Mortalitas (V01-Y98)',
            'W' => 'Penyebab Eksternal Morbiditas dan Mortalitas (V01-Y98)',
            'X' => 'Penyebab Eksternal Morbiditas dan Mortalitas (V01-Y98)',
            'Y' => 'Penyebab Eksternal Morbiditas dan Mortalitas (V01-Y98)',
            'Z' => 'Faktor Yang Mempengaruhi Status Kesehatan (Z00-Z99)'
        ];
        
        return $categories[$firstChar] ?? 'Kategori Tidak Diketahui';
    }
@endphp

@push('scripts')
<script>
function confirmDelete(id, name) {
    if (confirm(`Apakah Anda yakin ingin menghapus kode "${name}"? Tindakan ini tidak dapat dibatalkan.`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/icd10/${id}`;
        
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

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show success message
        showToast('Informasi berhasil disalin!', 'success');
    }).catch(function(err) {
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = text;
        textArea.style.position = 'fixed';
        textArea.style.left = '-999999px';
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        
        try {
            document.execCommand('copy');
            showToast('Informasi berhasil disalin!', 'success');
        } catch (err) {
            showToast('Gagal menyalin informasi', 'error');
        }
        
        document.body.removeChild(textArea);
    });
}

function printCode() {
    window.print();
}

function showToast(message, type = 'info') {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'primary'} border-0`;
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');
    
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    `;
    
    // Create container if not exists
    let toastContainer = document.getElementById('toastContainer');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toastContainer';
        toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
        toastContainer.style.zIndex = '9999';
        document.body.appendChild(toastContainer);
    }
    
    toastContainer.appendChild(toast);
    
    // Show toast
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
    
    // Remove after hidden
    toast.addEventListener('hidden.bs.toast', function() {
        toast.remove();
    });
}

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
});
</script>
@endpush
