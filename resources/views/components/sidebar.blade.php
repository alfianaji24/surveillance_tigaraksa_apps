<!-- Sidebar Component -->
<aside class="sidebar w-64 text-white flex flex-col">
    <!-- Logo -->
    <div class="p-6 border-b border-green-700">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center">
                <x-application-logo class="w-6 h-6 text-white" />
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
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <!-- Data Pasien -->
            @if(Auth::check() && (Auth::user()->hasRole('Superadmin') || Auth::user()->hasRole('admin') || Auth::user()->hasPermission('read-pasien')))
            <li>
                <a href="{{ route('pasien.index') }}" class="nav-item flex items-center space-x-3 p-3 rounded-lg text-white hover:bg-green-700 @if(request()->routeIs('pasien.*')) active @endif">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span>Data Pasien</span>
                </a>
            </li>
            @endif
            
            <!-- Dashboard Survailance -->
            @if(Auth::check() && (Auth::user()->hasRole('Superadmin') || Auth::user()->hasRole('admin') || Auth::user()->hasPermission('read-survailance')))
            <li>
                <a href="{{ route('survailance.dashboard') }}" class="nav-item flex items-center space-x-3 p-3 rounded-lg text-white hover:bg-green-700 @if(request()->routeIs('survailance.*')) active @endif">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span>Survailance</span>
                </a>
            </li>
            @endif
            
            <!-- Rekap Diagnosa -->
            @if(Auth::check() && (Auth::user()->hasRole('Superadmin') || Auth::user()->hasRole('admin') || Auth::user()->hasPermission('read-rekap-diagnosa')))
            <li>
                <a href="{{ route('rekap-diagnosa.index') }}" class="nav-item flex items-center space-x-3 p-3 rounded-lg text-white hover:bg-green-700 @if(request()->routeIs('rekap-diagnosa.*')) active @endif">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v1a1 1 0 001 1h4a1 1 0 001-1v-1m3-2V8a2 2 0 00-2-2H8a2 2 0 00-2 2v6m9-2h-4"></path>
                    </svg>
                    <span>Rekap Diagnosa</span>
                </a>
            </li>
            @endif
            
            <!-- Laporan -->
            @if(Auth::check() && (Auth::user()->hasRole('superadmin') || Auth::user()->hasPermission('read-laporan')))
            <li>
                <a href="{{ route('laporan.index') }}" class="nav-item flex items-center space-x-3 p-3 rounded-lg text-white hover:bg-green-700 @if(request()->routeIs('laporan.*')) active @endif">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span>Laporan</span>
                </a>
            </li>
            @endif
            
            <!-- Utilitas -->
            @if(Auth::check() && (Auth::user()->hasRole('superadmin') || Auth::user()->hasPermission('manage-users') || Auth::user()->hasPermission('manage-roles') || Auth::user()->hasPermission('manage-permissions') || Auth::user()->hasPermission('manage-permission-groups')))
            <li x-data="{ open: false }">
                <button @click="open = !open" class="nav-item flex items-center justify-between space-x-3 p-3 rounded-lg text-white hover:bg-green-700 w-full text-left @if(request()->routeIs('users.*') || request()->routeIs('roles.*') || request()->routeIs('permissions.*') || request()->routeIs('permission-groups.*')) active @endif">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span>Utilitas</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                
                <!-- Submenu -->
                <ul x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" class="mt-2 space-y-1 pl-8" style="display: none;">
                    <!-- Manajemen Pengguna -->
                    @if(Auth::check() && (Auth::user()->hasRole('superadmin') || Auth::user()->hasPermission('read-user')))
                    <li>
                        <a href="{{ route('users.index') }}" class="flex items-center space-x-2 p-2 rounded-lg text-white hover:bg-green-700 text-sm @if(request()->routeIs('users.index')) bg-green-700 @endif">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <span>Pengguna</span>
                        </a>
                    </li>
                    @endif
                    
                    <!-- Peran -->
                    @if(Auth::check() && (Auth::user()->hasRole('superadmin') || Auth::user()->hasPermission('manage-roles')))
                    <li>
                        <a href="{{ route('roles') }}" class="flex items-center space-x-2 p-2 rounded-lg text-white hover:bg-green-700 text-sm @if(request()->routeIs('roles')) bg-green-700 @endif">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span>Peran</span>
                        </a>
                    </li>
                    @endif
                    
                    <!-- Izin -->
                    @if(Auth::check() && (Auth::user()->hasRole('superadmin') || Auth::user()->hasPermission('manage-permissions')))
                    <li>
                        <a href="{{ route('permissions') }}" class="flex items-center space-x-2 p-2 rounded-lg text-white hover:bg-green-700 text-sm @if(request()->routeIs('permissions')) bg-green-700 @endif">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            <span>Izin</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif
            
            <!-- Pengaturan Poli -->
            @if(Auth::check() && (Auth::user()->hasRole('Superadmin') || Auth::user()->hasRole('admin') || Auth::user()->hasPermission('manage-poli')))
            <li>
                <a href="{{ route('poli.index') }}" class="nav-item flex items-center space-x-3 p-3 rounded-lg text-white hover:bg-green-700 @if(request()->routeIs('poli.*')) active @endif">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <span>Pengaturan Poli</span>
                </a>
            </li>
            @endif
            
            <!-- Kode ICD-10 -->
            @if(Auth::check() && (Auth::user()->hasRole('superadmin') || Auth::user()->hasPermission('read-cdi')))
            <li>
                <a href="{{ route('icd10.index') }}" class="nav-item flex items-center space-x-3 p-3 rounded-lg text-white hover:bg-green-700 @if(request()->routeIs('icd10.*')) active @endif">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    <span>Kode ICD-10</span>
                </a>
            </li>
            @endif
            
            <!-- AI Assistant -->
            <li>
                <a href="{{ route('ai.dashboard') }}" class="nav-item flex items-center space-x-3 p-3 rounded-lg text-white hover:bg-green-700 @if(request()->routeIs('ai.*')) active @endif">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <span>AI Assistant</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>
