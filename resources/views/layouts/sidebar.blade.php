<!-- Sidebar Component -->
<aside class="sidebar w-64 text-white flex flex-col">
    <!-- Logo -->
    <div class="p-6 border-b border-green-700">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-bold">{{ config('app.name') }}</h1>
                <p class="text-xs text-green-200">Sistem Informasi Penyakit</p>
            </div>
        </div>
    </div>
    
    <!-- Navigation -->
    <nav class="flex-1 p-4">
        <ul class="space-y-2">
            <!-- Dashboard -->
            <li>
                <a href="{{ route('dashboard') }}" class="nav-item flex items-center space-x-3 p-3 rounded-lg text-white hover:bg-green-700 @if(request()->routeIs('dashboard')) active @endif">
                    @include('layouts.components.icons.dashboard')
                    <span>Dashboard</span>
                </a>
            </li>
            
            <!-- Rekap Diagnosa -->
            @if(Auth::user()->hasRole('superadmin') || Auth::user()->hasPermission('read-rekap-diagnosa'))
            <li>
                <a href="{{ route('rekap-diagnosa.index') }}" class="nav-item flex items-center space-x-3 p-3 rounded-lg text-white hover:bg-green-700 @if(request()->routeIs('rekap-diagnosa.*')) active @endif">
                    <i class="fas fa-chart-line"></i>
                    <span>Rekap Diagnosa</span>
                </a>
            </li>
            @endif
            
            <!-- Laporan -->
            @if(Auth::user()->hasRole('superadmin') || Auth::user()->hasPermission('read-laporan'))
            <li>
                <a href="{{ route('laporan.index') }}" class="nav-item flex items-center space-x-3 p-3 rounded-lg text-white hover:bg-green-700 @if(request()->routeIs('laporan.*')) active @endif">
                    @include('layouts.components.icons.chart-bar')
                    <span>Laporan</span>
                </a>
            </li>
            @endif
            
            <!-- Pengguna -->
            @if(Auth::user()->hasRole('superadmin') || Auth::user()->hasPermission('manage-roles'))
            <li>
                <a href="{{ route('users.index') }}" class="nav-item flex items-center space-x-3 p-3 rounded-lg text-white hover:bg-green-700 @if(request()->routeIs('users.*')) active @endif">
                    @include('layouts.components.icons.users')
                    <span>Pengguna</span>
                </a>
            </li>
            @endif
            
            <!-- Kode ICD-10 -->
            @if(Auth::user()->hasRole('superadmin') || Auth::user()->hasPermission('read-cdi'))
            <li>
                <a href="{{ route('icd10.index') }}" class="nav-item flex items-center space-x-3 p-3 rounded-lg text-white hover:bg-green-700 @if(request()->routeIs('icd10.*')) active @endif">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span>Kode ICD-10</span>
                </a>
            </li>
            @endif
            
            <!-- AI Assistant -->
            <li>
                <a href="{{ route('ai.dashboard') }}" class="nav-item flex items-center space-x-3 p-3 rounded-lg text-white hover:bg-green-700 @if(request()->routeIs('ai.*')) active @endif">
                    @include('layouts.components.icons.computer')
                    <span>AI Assistant</span>
                </a>
            </li>
        </ul>
    </nav>
    @endif
</aside>
