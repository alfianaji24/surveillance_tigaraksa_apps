@extends('layouts.app')

@section('title', 'Edit Poli')

@section('content')
<div class="w-full px-6 max-w-2xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center space-x-4">
            <a href="{{ route('poli.index') }}" class="text-gray-600 hover:text-gray-900">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Edit Poli</h1>
        </div>
        <p class="text-gray-600 mt-1">Perbarui data poli: {{ $poli->nama }}</p>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow">
        <form method="POST" action="{{ route('poli.update', $poli->id) }}" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Nama Poli -->
            <div>
                <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Poli <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="nama" 
                       name="nama" 
                       value="{{ old('nama', $poli->nama) }}"
                       required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                       placeholder="Contoh: Poli Umum">
                @error('nama')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Kode Poli -->
            <div>
                <label for="kode" class="block text-sm font-medium text-gray-700 mb-2">
                    Kode Poli <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="kode" 
                       name="kode" 
                       value="{{ old('kode', $poli->kode) }}"
                       required
                       maxlength="50"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent uppercase"
                       placeholder="Contoh: POL001">
                @error('kode')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Deskripsi -->
            <div>
                <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                    Deskripsi
                </label>
                <textarea id="deskripsi" 
                          name="deskripsi" 
                          rows="4"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                          placeholder="Deskripsi singkat tentang poli ini...">{{ old('deskripsi', $poli->deskripsi) }}</textarea>
                @error('deskripsi')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status Aktif -->
            <div>
                <div class="flex items-center">
                    <input type="checkbox" 
                           id="is_active" 
                           name="is_active" 
                           value="1"
                           {{ old('is_active', $poli->is_active) ? 'checked' : '' }}
                           class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                    <label for="is_active" class="ml-2 block text-sm text-gray-900">
                        Poli Aktif
                    </label>
                </div>
                <p class="mt-1 text-sm text-gray-500">
                    Centang jika poli ini dapat digunakan untuk pendaftaran pasien
                </p>
            </div>

            <!-- Info Tambahan -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-800">
                            <strong>Informasi:</strong> Poli ini memiliki {{ $poli->pasien()->count() }} pasien terdaftar.
                            Mengubah status menjadi tidak aktif tidak akan menghapus data pasien yang sudah ada.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-3 pt-6 border-t">
                <a href="{{ route('poli.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Perbarui Poli</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
