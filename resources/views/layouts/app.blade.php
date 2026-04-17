<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    
    <!-- Include all styles -->
    <x-styles />
</head>
<body class="@if(request()->routeIs('login*') || request()->routeIs('register*')) auth-body @else app-body @endif">
    @if(request()->routeIs('login*') || request()->routeIs('register*'))
        <!-- Auth Layout -->
        <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-md w-full space-y-8">
                <!-- Auth Header -->
                <div class="text-center">
                    @section('auth-header')
                        <div class="mx-auto w-20 h-20 bg-green-600 rounded-full flex items-center justify-center mb-4">
                            <x-application-logo class="w-10 h-10 text-white" />
                        </div>
                        <h2 class="text-3xl font-bold text-white">{{ config('app.name') }}</h2>
                        <p class="mt-2 text-green-100">Sistem Informasi Penyakit</p>
                    @show
                </div>
                
                <!-- Auth Content -->
                <div class="bg-white rounded-lg shadow-xl p-8">
                    @yield('content')
                </div>
                
                <!-- Auth Footer -->
                <div class="text-center">
                    <p class="text-sm text-green-100">
                        © 2026 {{ config('app.name') }}. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    @else
        <!-- App Layout -->
        <div class="flex h-screen">
            <!-- Include Sidebar Component -->
            <x-sidebar />
            
            <!-- Main Content -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Include Navbar Component -->
                <x-navbar />
                
                <!-- Page Content -->
                <main class="flex-1 overflow-auto">
                    <div class="p-6">
                        <!-- Session Messages -->
                        @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span>{{ session('success') }}</span>
                            </div>
                        </div>
                        @endif
                        
                        @if(session('error'))
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                <span>{{ session('error') }}</span>
                            </div>
                        </div>
                        @endif
                        
                        @if(session('warning'))
                        <div class="mb-4 p-4 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                <span>{{ session('warning') }}</span>
                            </div>
                        </div>
                        @endif
                        
                        @yield('content')
                    </div>
                </main>
                
                <!-- Include Footer Component -->
                <x-footer />
            </div>
        </div>
    @endif
    
    <!-- Include all scripts -->
    <x-scripts />
</body>
</html>
