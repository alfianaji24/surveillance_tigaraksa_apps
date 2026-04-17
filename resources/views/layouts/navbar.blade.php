<!-- Navbar Component -->
<header class="bg-white shadow-sm border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center">
                <!-- Mobile Menu Button -->
                <button class="lg:hidden p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500">
                    @include('layouts.components.icons.menu')
                </button>
                
                <!-- Page Title -->
                <h1 class="ml-3 lg:ml-0 text-xl font-semibold text-gray-900">@yield('page-title', 'Dashboard')</h1>
            </div>
            
            <div class="flex items-center space-x-4">
                <!-- Search -->
                <div class="hidden lg:block">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            @include('layouts.components.icons.search')
                        </div>
                        <input class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-green-500 focus:border-green-500 sm:text-sm" placeholder="Search..." type="search">
                    </div>
                </div>
                
                <!-- Notifications -->
                <button class="p-2 rounded-full text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    @include('layouts.components.icons.bell')
                </button>
                
                <!-- User Menu -->
                @if(Auth::check())
                @php
                    $user = Auth::user();
                    $initial = strtoupper(substr($user->name, 0, 1));
                @endphp
                <div class="relative">
                    <button class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <div class="h-8 w-8 rounded-full bg-green-600 flex items-center justify-center">
                            <span class="text-sm font-medium text-white">{{ $initial }}</span>
                        </div>
                    </button>
                </div>
                @endif
                
                <!-- Current Time -->
                <div class="hidden sm:block">
                    <span class="text-sm text-gray-500">{{ now()->format('d M Y H:i') }}</span>
                </div>
                
                <!-- Logout Button -->
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="hidden sm:flex items-center text-sm text-gray-500 hover:text-gray-700">
                        @include('layouts.components.icons.logout')
                        <span class="ml-2">Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
