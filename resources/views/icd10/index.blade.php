@extends('layouts.app')

@section('title', 'Kode ICD-10 - ' . config('app.name'))

@section('page-title', 'Kode ICD-10')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-semibold text-gray-900">Kode ICD-10</h2>
            <p class="text-gray-600">Manajemen kode penyakit ICD-10</p>
        </div>
        <div class="flex space-x-3">
            <button type="button" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition" data-bs-toggle="modal" data-bs-target="#addCodeModal">
                <span class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Kode
                </span>
            </button>
        </div>
    </div>

    <!-- Search -->
    <div class="bg-gray-50 rounded-lg p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search Kode ICD-10</label>
                <form method="GET" action="{{ route('icd10.index') }}" class="flex">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Cari kode ICD-10..." 
                           class="flex-1 px-3 py-2 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-r-lg text-sm font-medium transition">
                        Cari
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Data Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Kode</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $codes->total() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-full">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Dibuat Hari Ini</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $codes->where('created_at', '>=', now()->startOfDay())->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Daftar Kode ICD-10</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Penyakit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dibuat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @if($codes->count() > 0)
                        @foreach($codes as $code)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ $code->formatted_code }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $code->display }}</div>
                                    @if($code->definition)
                                        <div class="text-sm text-gray-500">{{ Str::limit($code->definition, 50) }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $code->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('icd10.show', $code->id) }}" class="text-blue-600 hover:text-blue-900">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        <a href="{{ route('icd10.edit', $code->id) }}" class="text-yellow-600 hover:text-yellow-900">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        <button onclick="confirmDelete({{ $code->id }}, '{{ $code->display }}')" class="text-red-600 hover:text-red-900">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center">
                                <div class="text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-sm">Belum ada data kode ICD-10</p>
                                    <a href="{{ route('icd10.create') }}" class="mt-2 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Tambah Kode Baru
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($codes->hasPages())
        <div class="mt-6 flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Menampilkan <span class="font-medium">{{ $codes->firstItem() }}</span> hingga <span class="font-medium">{{ $codes->lastItem() }}</span> dari <span class="font-medium">{{ $codes->total() }}</span> hasil
            </div>
            {{ $codes->links() }}
        </div>
        @endif
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
</script>

<!-- Add Code Modal -->
<div class="modal fade" id="addCodeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus-circle me-2"></i>
                    Tambah Kode ICD-10
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('icd10.store') }}" id="addCodeForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Kode ICD-10 <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="code" 
                               required
                               placeholder="Contoh: A00, I10, E11"
                               maxlength="10"
                               style="text-transform: uppercase;"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                        <p class="mt-1 text-sm text-gray-500">Masukkan kode ICD-10 (maksimal 10 karakter)</p>
                    </div>
                    
                    <div class="mb-0">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Penyakit <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="display" 
                               required
                               placeholder="Contoh: Kolera, Hipertensi esensial"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                        <p class="mt-1 text-sm text-gray-500">Nama lengkap penyakit sesuai klasifikasi ICD-10</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                        <i class="fas fa-save me-2"></i>
                        Simpan Kode
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Auto-uppercase for code field in modal
document.addEventListener('DOMContentLoaded', function() {
    const codeInput = document.querySelector('input[name="code"]');
    if (codeInput) {
        codeInput.addEventListener('input', function(e) {
            e.target.value = e.target.value.toUpperCase();
        });
    }
});

// Reset form when modal is hidden
document.getElementById('addCodeModal').addEventListener('hidden.bs.modal', function () {
    document.getElementById('addCodeForm').reset();
});

// Handle form submission via AJAX
document.getElementById('addCodeForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    // Disable submit button and show loading
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
    
    // Submit form via AJAX
    fetch(form.action, {
        method: 'POST',
        body: new FormData(form),
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('addCodeModal'));
            modal.hide();
            
            // Show success message
            showSuccessNotification(data.message);
            
            // Reload page after a short delay
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showErrorNotification('Terjadi kesalahan. Silakan coba lagi.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorNotification('Terjadi kesalahan. Silakan coba lagi.');
    })
    .finally(() => {
        // Re-enable submit button
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});

// Show success notification
function showSuccessNotification(message) {
    const notification = document.createElement('div');
    notification.className = 'fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-lg z-50';
    notification.innerHTML = `
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span>${message}</span>
        </div>
    `;
    document.body.appendChild(notification);
    
    // Remove notification after 3 seconds
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Show error notification
function showErrorNotification(message) {
    const notification = document.createElement('div');
    notification.className = 'fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow-lg z-50';
    notification.innerHTML = `
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
            <span>${message}</span>
        </div>
    `;
    document.body.appendChild(notification);
    
    // Remove notification after 3 seconds
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script>

<!-- Search Modal -->
<div class="modal fade" id="searchModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cari Kode ICD-10 dari Satu Sehat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <div class="input-group">
                        <input type="text" 
                               id="api-search" 
                               class="form-control" 
                               placeholder="Masukkan nama penyakit atau kode ICD-10..."
                               autocomplete="off">
                        <button class="btn btn-primary" onclick="searchFromAPI()">
                            <i class="fas fa-search"></i> Cari
                        </button>
                    </div>
                </div>
                <div id="api-results">
                    <div class="text-center text-muted">
                        <i class="fas fa-search fa-2x mb-2"></i>
                        <p>Masukkan kata kunci untuk mencari kode ICD-10</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-success" onclick="importSelected()" id="importBtn" disabled>
                    <i class="fas fa-download me-2"></i>Import yang Dipilih
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
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

let selectedCodes = [];

function searchFromAPI() {
    const query = document.getElementById('api-search').value.trim();
    const resultsDiv = document.getElementById('api-results');
    
    if (!query) {
        alert('Silakan masukkan kata kunci pencarian');
        return;
    }
    
    resultsDiv.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Mencari...</div>';
    
    fetch(`/icd10/search-api?query=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            displayAPIResults(data);
        })
        .catch(error => {
            console.error('Error:', error);
            resultsDiv.innerHTML = '<div class="alert alert-danger">Terjadi kesalahan saat mencari data</div>';
        });
}

function displayAPIResults(data) {
    const resultsDiv = document.getElementById('api-results');
    
    if (!data || data.length === 0) {
        resultsDiv.innerHTML = '<div class="alert alert-info">Tidak ditemukan hasil untuk pencarian ini</div>';
        return;
    }
    
    let html = '<div class="list-group">';
    data.forEach(item => {
        const resource = item.resource || item;
        const code = resource.code || '';
        const display = resource.display || '';
        const definition = resource.definition || '';
        
        html += `
            <div class="list-group-item">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="${code}" id="code_${code}" 
                           onchange="toggleSelection('${code}', '${display}', '${definition.replace(/'/g, "\\'")}')">
                    <label class="form-check-label" for="code_${code}">
                        <span class="badge bg-primary me-2">${code}</span>
                        <strong>${display}</strong>
                        ${definition ? `<br><small class="text-muted">${definition}</small>` : ''}
                    </label>
                </div>
            </div>
        `;
    });
    html += '</div>';
    
    resultsDiv.innerHTML = html;
}

function toggleSelection(code, display, definition) {
    const index = selectedCodes.findIndex(item => item.code === code);
    
    if (index > -1) {
        selectedCodes.splice(index, 1);
    } else {
        selectedCodes.push({ code, display, definition });
    }
    
    document.getElementById('importBtn').disabled = selectedCodes.length === 0;
}

function importSelected() {
    if (selectedCodes.length === 0) {
        alert('Pilih setidaknya satu kode untuk diimport');
        return;
    }
    
    fetch('/icd10/import-api', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ codes: selectedCodes })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Gagal mengimport data');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengimport data');
    });
}

// Enter key to search
document.getElementById('api-search').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        searchFromAPI();
    }
});
</script>
@endpush
