<!-- Footer Component -->
<footer class="bg-white border-t border-gray-200 mt-auto">
    <div class="py-4 px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-500">© {{ now()->format('Y') }} {{ config('app.name') }}.</span>
                <span class="text-sm text-gray-400">All rights reserved.</span>
            </div>
            
            <div class="flex items-center space-x-4">
                <!-- Version Info -->
                <span class="text-sm text-gray-400">v1.0.0</span>
                
                <!-- Help Link -->
                <a href="#" class="text-sm text-gray-500 hover:text-gray-700">
                    Help
                </a>
                
                <!-- Documentation Link -->
                <a href="#" class="text-sm text-gray-500 hover:text-gray-700">
                    Docs
                </a>
                
                <!-- Settings Link -->
                <button class="text-sm text-gray-500 hover:text-gray-700">
                    Settings
                </button>
            </div>
        </div>
    </div>
</footer>
