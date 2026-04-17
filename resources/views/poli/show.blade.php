@extends('layouts.app')

@section('title', 'Detail Poli')

@section('content')
<div class="w-full px-6 max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('poli.index') }}" class="text-gray-600 hover:text-gray-900">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <h1 class="text-2xl font-bold text-gray-900">Detail Poli</h1>
            </div>
            @if(Auth::user()->hasRole('Superadmin') || Auth::user()->hasRole('admin') || Auth::user()->hasPermission('manage-poli'))
            <div class="flex space-x-2">
                <a href="{{ route('poli.edit', $poli->id) }}" 
                   class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    <span>Edit</span>
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- Detail Card -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kode Poli -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Kode Poli</h3>
                    <p class="text-lg font-semibold text-gray-900">{{ $poli->kode }}</p>
                </div>

                <!-- Nama Poli -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Nama Poli</h3>
                    <p class="text-lg font-semibold text-gray-900">{{ $poli->nama }}</p>
                </div>

                <!-- Status -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Status</h3>
                    <span class="px-3 inline-flex text-sm leading-5 font-semibold rounded-full {{ $poli->status_badge_class }}">
                        {{ $poli->status }}
                    </span>
                </div>

                <!-- Jumlah Pasien -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Jumlah Pasien</h3>
                    <p class="text-lg font-semibold text-gray-900">{{ $poli->pasien()->count() }} pasien</p>
                </div>

                <!-- Deskripsi -->
                <div class="md:col-span-2">
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Deskripsi</h3>
                    <p class="text-gray-900">{{ $poli->deskripsi ?: 'Tidak ada deskripsi' }}</p>
                </div>

                <!-- Informasi Waktu -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Dibuat</h3>
                    <p class="text-gray-900">{{ $poli->created_at->format('d M Y H:i') }}</p>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Diperbarui</h3>
                    <p class="text-gray-900">{{ $poli->updated_at->format('d M Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Pasien -->
    @if($poli->pasien()->count() > 0)
    <div class="mt-8 bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Daftar Pasien di Poli Ini</h2>
            <p class="text-sm text-gray-600">Menampilkan {{ $poli->pasien()->count() }} pasien</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            No. RM
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nama Pasien
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                           Tanggal Lahir
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jenis Kelamin
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($poli->pasien()->limit(10)->get() as $pasien)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $pasien->no_rm }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $pasien->nama }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $pasien->tanggal_lahir->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $pasien->jenis_kelamin }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('pasien.show', $pasien->id) }}" 
                               class="text-green-600 hover:text-green-900">
                                Lihat Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($poli->pasien()->count() > 10)
        <div class="px-6 py-4 border-t border-gray-200">
            <p class="text-sm text-gray-600">
                Menampilkan 10 dari {{ $poli->pasien()->count() }} pasien. 
                <a href="{{ route('pasien.index', ['poli' => $poli->id]) }}" class="text-green-600 hover:text-green-800">
                    Lihat semua pasien
                </a>
            </p>
        </div>
        @endif
    </div>
    @endif

    <!-- Actions -->
    <div class="mt-6 flex justify-end space-x-3">
        <a href="{{ route('poli.index') }}" 
           class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
            Kembali ke Daftar Poli
        </a>
        @if(Auth::user()->hasRole('Superadmin') || Auth::user()->hasRole('admin') || Auth::user()->hasPermission('manage-poli'))
        <form method="POST" action="{{ route('poli.toggle-status', $poli->id) }}" class="inline">
            @csrf
            <button type="submit" 
                    class="px-4 py-2 {{ $poli->is_active ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700' }} text-white rounded-lg flex items-center space-x-2"
                    onclick="return confirm('Apakah Anda yakin ingin {{ $poli->is_active ? 'menonaktifkan' : 'mengaktifkan' }} poli ini?')">
                @if($poli->is_active)
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                    </svg>
                    <span>Nonaktifkan</span>
                @else
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Aktifkan</span>
                @endif
            </button>
        </form>
        @endif
    </div>
</div>
@endsection
