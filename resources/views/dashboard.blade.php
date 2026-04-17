@extends('layouts.app')

@section('title', 'Dashboard - ' . config('app.name'))

@section('page-title', 'Dashboard')

@section('content')
    @guest
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="text-center">
            <h1 class="text-2xl font-bold text-gray-900 mb-4">Selamat Datang!</h1>
            <p class="text-gray-600 mb-4">Silakan login untuk mengakses dashboard.</p>
            <a href="{{ route('login') }}" class="btn btn-primary">
                <i class="fas fa-sign-in-alt me-2"></i>Login
            </a>
        </div>
    </div>
    @else
    <!-- Welcome Section -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Selamat Datang, {{ Auth::user()->name ?? 'User' }}!</h1>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">Login sebagai:</p>
                <p class="font-semibold text-green-600">{{ Auth::user()->roles->pluck('name')->first() ?? 'User' }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Pasien</p>
                    <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Pasien::count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Pengguna</p>
                    <p class="text-2xl font-bold text-gray-900">1</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-full">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Laporan</p>
                    <p class="text-2xl font-bold text-gray-900">0</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-full">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">AI Assistant</p>
                    <p class="text-2xl font-bold text-gray-900">Active</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @if(Auth::check() && Auth::user()->hasPermission('create-pasien'))
            <a href="{{ route('pasien.create') }}" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition">
                <svg class="w-8 h-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <div>
                    <p class="font-medium text-gray-900">Tambah Pasien</p>
                    <p class="text-sm text-gray-600">Input data pasien baru</p>
                </div>
            </a>
            @endif

            @if(Auth::check() && Auth::user()->hasPermission('create-laporan'))
            <a href="{{ route('laporan.create') }}" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                <svg class="w-8 h-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <div>
                    <p class="font-medium text-gray-900">Buat Laporan</p>
                    <p class="text-sm text-gray-600">Generate laporan analisis</p>
                </div>
            </a>
            @endif

            <a href="{{ route('rekap-diagnosa.index') }}" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition">
                <svg class="w-8 h-8 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v1a1 1 0 001 1h4a1 1 0 001-1v-1m3-2V8a2 2 0 00-2-2H8a2 2 0 00-2 2v6m9-2h-4"></path>
                </svg>
                <div>
                    <p class="font-medium text-gray-900">Rekap Diagnosa</p>
                    <p class="text-sm text-gray-600">Analisis diagnosa penyakit</p>
                </div>
            </a>

            <a href="{{ route('icd10.index') }}" class="flex items-center p-4 bg-red-50 rounded-lg hover:bg-red-100 transition">
                <svg class="w-8 h-8 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <div>
                    <p class="font-medium text-gray-900">Kode ICD-10</p>
                    <p class="text-sm text-gray-600">Manajemen kode penyakit</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h2>
        <div class="space-y-4">
            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">Sistem berhasil diinstall</p>
                    <p class="text-xs text-gray-500">{{ now()->format('d M Y H:i') }}</p>
                </div>
            </div>
            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                <div class="w-2 h-2 bg-blue-500 rounded-full mr-3"></div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">User "{{ Auth::user()->name ?? 'User' }}" login</p>
                    <p class="text-xs text-gray-500">{{ now()->format('d M Y H:i') }}</p>
                </div>
            </div>
            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                <div class="w-2 h-2 bg-purple-500 rounded-full mr-3"></div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">Permission groups dibuat</p>
                    <p class="text-xs text-gray-500">{{ now()->format('d M Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Admin Info -->
    @if(Auth::check() && Auth::user()->hasRole('admin'))
    <div class="mt-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
            </svg>
            <div>
                <p class="font-semibold">Admin Access</p>
                <p class="text-sm">Anda memiliki akses penuh ke semua fitur sistem termasuk manajemen pengguna, role, dan permission.</p>
            </div>
        </div>
    </div>
    @endif
@endsection
