@extends('layouts.app')

@section('title', 'Login - ' . config('app.name'))

@section('content')
    <!-- Error Messages -->
    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            @foreach ($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
        </div>
    @endif

    <!-- Login Form -->
    <form action="{{ route('login') }}" method="POST" class="space-y-6">
        @csrf
        <input type="hidden" name="login_type" value="user">
        
        <!-- Username/Email Field -->
        <div>
            <label for="id_user" class="block text-sm font-medium text-gray-700 mb-2">
                Username / Email
            </label>
            <input 
                type="text" 
                id="id_user" 
                name="id_user" 
                value="{{ old('id_user') }}" 
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                placeholder="Masukkan username atau email"
                required
                autocomplete="off"
            >
        </div>

        <!-- Password Field -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                Password
            </label>
            <input 
                type="password" 
                id="password" 
                name="password" 
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                placeholder="Masukkan password"
                required
                autocomplete="off"
            >
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <input 
                type="checkbox" 
                id="remember" 
                name="remember" 
                class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded"
            >
            <label for="remember" class="ml-2 block text-sm text-gray-900">
                Remember Me
            </label>
        </div>

        <!-- Submit Button -->
        <div>
            <button 
                type="submit" 
                id="btn-signin-submit"
                class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md transition duration-200 flex items-center justify-center"
            >
                <span id="button-text">Sign In</span>
            </button>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            const btn = document.getElementById('btn-signin-submit');
            const text = document.getElementById('button-text');
            
            btn.disabled = true;
            text.innerHTML = '<div class="inline-block animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>Tunggu...';
        });
    </script>
@endpush
