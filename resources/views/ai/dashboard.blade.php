<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AI Dashboard - Laravel 13</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .tab-content { display: none; }
        .tab-content.active { display: block; }
        .loading { 
            display: none; 
            position: fixed; 
            top: 0; left: 0; right: 0; bottom: 0; 
            background: rgba(0,0,0,0.5); z-index: 9999; 
        }
        .loading.active { display: flex; }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Loading Spinner -->
    <div id="loading" class="loading items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-xl">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
            <p class="mt-4 text-gray-700">Processing your request...</p>
        </div>
    </div>

    <!-- Header -->
    <header class="bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg">
        <div class="container mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-robot text-3xl"></i>
                    <div>
                        <h1 class="text-2xl font-bold">AI Dashboard</h1>
                        <p class="text-blue-100">Powered by Laravel 13 & Multiple AI Providers</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <label class="text-sm font-medium">AI Provider:</label>
                        <select id="aiProvider" class="bg-white/20 border border-white/30 text-white rounded px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-white/50">
                            <option value="openai">OpenAI GPT</option>
                            <option value="gemini">Google Gemini</option>
                        </select>
                    </div>
                    <span class="bg-white/20 px-3 py-1 rounded-full text-sm">
                        <i class="fas fa-circle text-green-400 text-xs mr-2"></i>
                        AI Service Active
                    </span>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <!-- Tab Navigation -->
        <div class="bg-white rounded-lg shadow-md mb-6">
            <div class="flex flex-wrap border-b">
                <button onclick="showTab('text-generation')" class="tab-btn px-6 py-3 font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50 border-b-2 border-transparent hover:border-blue-600 transition-colors active">
                    <i class="fas fa-pen-fancy mr-2"></i>Text Generation
                </button>
                <button onclick="showTab('code-assistant')" class="tab-btn px-6 py-3 font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50 border-b-2 border-transparent hover:border-blue-600 transition-colors">
                    <i class="fas fa-code mr-2"></i>Code Assistant
                </button>
                <button onclick="showTab('content-creator')" class="tab-btn px-6 py-3 font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50 border-b-2 border-transparent hover:border-blue-600 transition-colors">
                    <i class="fas fa-file-alt mr-2"></i>Content Creator
                </button>
                <button onclick="showTab('text-tools')" class="tab-btn px-6 py-3 font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50 border-b-2 border-transparent hover:border-blue-600 transition-colors">
                    <i class="fas fa-tools mr-2"></i>Text Tools
                </button>
                <button onclick="showTab('ai-chat')" class="tab-btn px-6 py-3 font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50 border-b-2 border-transparent hover:border-blue-600 transition-colors">
                    <i class="fas fa-comments mr-2"></i>AI Chat
                </button>
            </div>
        </div>

        <!-- Tab Contents -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Input Panel -->
            <div class="space-y-6">
                <!-- Text Generation Tab -->
                <div id="text-generation" class="tab-content active">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800">
                            <i class="fas fa-pen-fancy text-blue-600 mr-2"></i>Generate Text
                        </h3>
                        <form id="textForm" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Prompt</label>
                                <textarea name="prompt" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter your prompt here..." required></textarea>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Temperature</label>
                                    <input type="number" name="temperature" min="0" max="2" step="0.1" value="0.7" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Max Tokens</label>
                                    <input type="number" name="max_tokens" min="1" max="4000" value="1000" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                            <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition-colors">
                                <i class="fas fa-magic mr-2"></i>Generate Text
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Code Assistant Tab -->
                <div id="code-assistant" class="tab-content">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800">
                            <i class="fas fa-code text-green-600 mr-2"></i>Code Assistant
                        </h3>
                        
                        <!-- Generate Code -->
                        <div class="mb-6">
                            <h4 class="font-medium text-gray-700 mb-3">Generate Code</h4>
                            <form id="codeForm" class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                    <textarea name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Describe the code you want..." required></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Language</label>
                                    <select name="language" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                        <option value="php">PHP</option>
                                        <option value="javascript">JavaScript</option>
                                        <option value="python">Python</option>
                                        <option value="java">Java</option>
                                        <option value="cpp">C++</option>
                                        <option value="html">HTML</option>
                                        <option value="css">CSS</option>
                                    </select>
                                </div>
                                <button type="submit" class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 transition-colors">
                                    <i class="fas fa-code mr-2"></i>Generate Code
                                </button>
                            </form>
                        </div>

                        <!-- Explain Code -->
                        <div>
                            <h4 class="font-medium text-gray-700 mb-3">Explain Code</h4>
                            <form id="explainForm" class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Code</label>
                                    <textarea name="code" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 font-mono text-sm" placeholder="Paste your code here..." required></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Language</label>
                                    <select name="language" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                        <option value="php">PHP</option>
                                        <option value="javascript">JavaScript</option>
                                        <option value="python">Python</option>
                                        <option value="java">Java</option>
                                        <option value="cpp">C++</option>
                                        <option value="html">HTML</option>
                                        <option value="css">CSS</option>
                                    </select>
                                </div>
                                <button type="submit" class="w-full bg-purple-600 text-white py-2 px-4 rounded-md hover:bg-purple-700 transition-colors">
                                    <i class="fas fa-lightbulb mr-2"></i>Explain Code
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Content Creator Tab -->
                <div id="content-creator" class="tab-content">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800">
                            <i class="fas fa-file-alt text-indigo-600 mr-2"></i>Content Creator
                        </h3>
                        
                        <!-- Blog Post -->
                        <div class="mb-6">
                            <h4 class="font-medium text-gray-700 mb-3">Generate Blog Post</h4>
                            <form id="blogForm" class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Topic</label>
                                    <input type="text" name="topic" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Enter blog topic..." required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Word Count</label>
                                    <input type="number" name="words" min="100" max="2000" value="500" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 transition-colors">
                                    <i class="fas fa-newspaper mr-2"></i>Generate Blog Post
                                </button>
                            </form>
                        </div>

                        <!-- Email -->
                        <div class="mb-6">
                            <h4 class="font-medium text-gray-700 mb-3">Generate Email</h4>
                            <form id="emailForm" class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Purpose</label>
                                    <input type="text" name="purpose" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Email purpose..." required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Recipient</label>
                                    <input type="text" name="recipient" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Recipient..." required>
                                </div>
                                <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-envelope mr-2"></i>Generate Email
                                </button>
                            </form>
                        </div>

                        <!-- Social Media -->
                        <div>
                            <h4 class="font-medium text-gray-700 mb-3">Generate Social Media Post</h4>
                            <form id="socialForm" class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Topic</label>
                                    <input type="text" name="topic" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Post topic..." required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Platform</label>
                                    <select name="platform" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        <option value="twitter">Twitter</option>
                                        <option value="facebook">Facebook</option>
                                        <option value="linkedin">LinkedIn</option>
                                        <option value="instagram">Instagram</option>
                                    </select>
                                </div>
                                <button type="submit" class="w-full bg-pink-600 text-white py-2 px-4 rounded-md hover:bg-pink-700 transition-colors">
                                    <i class="fas fa-share-alt mr-2"></i>Generate Post
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Text Tools Tab -->
                <div id="text-tools" class="tab-content">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800">
                            <i class="fas fa-tools text-orange-600 mr-2"></i>Text Tools
                        </h3>
                        
                        <!-- Translate -->
                        <div class="mb-6">
                            <h4 class="font-medium text-gray-700 mb-3">Translate Text</h4>
                            <form id="translateForm" class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Text</label>
                                    <textarea name="text" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500" placeholder="Text to translate..." required></textarea>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">From</label>
                                        <input type="text" name="from_lang" value="English" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">To</label>
                                        <input type="text" name="to_lang" value="Indonesian" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500" required>
                                    </div>
                                </div>
                                <button type="submit" class="w-full bg-orange-600 text-white py-2 px-4 rounded-md hover:bg-orange-700 transition-colors">
                                    <i class="fas fa-language mr-2"></i>Translate
                                </button>
                            </form>
                        </div>

                        <!-- Summarize -->
                        <div>
                            <h4 class="font-medium text-gray-700 mb-3">Summarize Text</h4>
                            <form id="summarizeForm" class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Text</label>
                                    <textarea name="text" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500" placeholder="Text to summarize..." required></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Max Length</label>
                                    <input type="number" name="max_length" min="50" max="500" value="200" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500">
                                </div>
                                <button type="submit" class="w-full bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 transition-colors">
                                    <i class="fas fa-compress-alt mr-2"></i>Summarize
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- AI Chat Tab -->
                <div id="ai-chat" class="tab-content">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800">
                            <i class="fas fa-comments text-teal-600 mr-2"></i>AI Chat
                        </h3>
                        <div id="chatMessages" class="bg-gray-50 rounded-lg p-4 h-64 overflow-y-auto mb-4">
                            <div class="text-gray-500 text-center">Start a conversation with AI...</div>
                        </div>
                        <form id="chatForm" class="space-y-4">
                            <div>
                                <textarea name="message" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="Type your message..." required></textarea>
                            </div>
                            <button type="submit" class="w-full bg-teal-600 text-white py-2 px-4 rounded-md hover:bg-teal-700 transition-colors">
                                <i class="fas fa-paper-plane mr-2"></i>Send Message
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Output Panel -->
            <div class="lg:sticky lg:top-4">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800">
                        <i class="fas fa-output text-gray-600 mr-2"></i>Output
                    </h3>
                    <div id="output" class="bg-gray-50 rounded-lg p-4 min-h-[400px] whitespace-pre-wrap font-mono text-sm">
                        <div class="text-gray-500 text-center">AI output will appear here...</div>
                    </div>
                    <div class="mt-4 flex space-x-2">
                        <button onclick="copyOutput()" class="flex-1 bg-gray-600 text-white py-2 px-4 rounded-md hover:bg-gray-700 transition-colors text-sm">
                            <i class="fas fa-copy mr-2"></i>Copy
                        </button>
                        <button onclick="clearOutput()" class="flex-1 bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 transition-colors text-sm">
                            <i class="fas fa-trash mr-2"></i>Clear
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-12">
        <div class="container mx-auto px-4 py-6">
            <div class="text-center">
                <p class="text-gray-400">Laravel 13 AI Dashboard © 2026</p>
                <p class="text-sm text-gray-500 mt-2">Powered by OpenAI GPT</p>
            </div>
        </div>
    </footer>

    <script>
        // Tab functionality
        function showTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Remove active class from all buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active', 'text-blue-600', 'border-blue-600');
                btn.classList.add('text-gray-700', 'border-transparent');
            });
            
            // Show selected tab
            document.getElementById(tabName).classList.add('active');
            
            // Add active class to clicked button
            event.target.classList.add('active', 'text-blue-600', 'border-blue-600');
            event.target.classList.remove('text-gray-700', 'border-transparent');
        }

        // Loading state
        function showLoading() {
            document.getElementById('loading').classList.add('active');
        }

        function hideLoading() {
            document.getElementById('loading').classList.remove('active');
        }

        // Output functions
        function showOutput(text) {
            document.getElementById('output').textContent = text;
        }

        function copyOutput() {
            const output = document.getElementById('output').textContent;
            navigator.clipboard.writeText(output).then(() => {
                alert('Output copied to clipboard!');
            });
        }

        function clearOutput() {
            document.getElementById('output').innerHTML = '<div class="text-gray-500 text-center">AI output will appear here...</div>';
        }

        // Chat functionality
        let conversationHistory = [];

        function addChatMessage(role, message) {
            const chatMessages = document.getElementById('chatMessages');
            const messageDiv = document.createElement('div');
            messageDiv.className = `mb-3 ${role === 'user' ? 'text-right' : 'text-left'}`;
            
            const messageContent = document.createElement('div');
            messageContent.className = `inline-block max-w-xs px-4 py-2 rounded-lg ${
                role === 'user' 
                    ? 'bg-blue-600 text-white' 
                    : 'bg-gray-200 text-gray-800'
            }`;
            messageContent.textContent = message;
            
            messageDiv.appendChild(messageContent);
            chatMessages.appendChild(messageDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        // Get current AI provider
        function getCurrentProvider() {
            return document.getElementById('aiProvider').value;
        }

        // Add provider to form data
        function addProviderToData(data) {
            data.provider = getCurrentProvider();
            return data;
        }

        // Form submissions
        document.getElementById('textForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            showLoading();
            
            const formData = new FormData(e.target);
            let data = Object.fromEntries(formData.entries());
            data = addProviderToData(data);
            
            try {
                const response = await fetch('/ai/generate-text', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                if (result.success) {
                    showOutput(`[${result.provider.toUpperCase()}] ${result.result}`);
                } else {
                    showOutput('Error: ' + result.error);
                }
            } catch (error) {
                showOutput('Error: ' + error.message);
            } finally {
                hideLoading();
            }
        });

        document.getElementById('codeForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            showLoading();
            
            const formData = new FormData(e.target);
            let data = Object.fromEntries(formData.entries());
            data = addProviderToData(data);
            
            try {
                const response = await fetch('/ai/generate-code', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                if (result.success) {
                    showOutput(`[${result.provider.toUpperCase()}] ${result.result}`);
                } else {
                    showOutput('Error: ' + result.error);
                }
            } catch (error) {
                showOutput('Error: ' + error.message);
            } finally {
                hideLoading();
            }
        });

        document.getElementById('explainForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            showLoading();
            
            const formData = new FormData(e.target);
            let data = Object.fromEntries(formData.entries());
            data = addProviderToData(data);
            
            try {
                const response = await fetch('/ai/explain-code', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                if (result.success) {
                    showOutput(`[${result.provider.toUpperCase()}] ${result.result}`);
                } else {
                    showOutput('Error: ' + result.error);
                }
            } catch (error) {
                showOutput('Error: ' + error.message);
            } finally {
                hideLoading();
            }
        });

        document.getElementById('blogForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            showLoading();
            
            const formData = new FormData(e.target);
            let data = Object.fromEntries(formData.entries());
            data.words = parseInt(data.words);
            data = addProviderToData(data);
            
            try {
                const response = await fetch('/ai/generate-blog', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                if (result.success) {
                    showOutput(`[${result.provider.toUpperCase()}] ${result.result}`);
                } else {
                    showOutput('Error: ' + result.error);
                }
            } catch (error) {
                showOutput('Error: ' + error.message);
            } finally {
                hideLoading();
            }
        });

        document.getElementById('emailForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            showLoading();
            
            const formData = new FormData(e.target);
            let data = Object.fromEntries(formData.entries());
            data = addProviderToData(data);
            
            try {
                const response = await fetch('/ai/generate-email', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                if (result.success) {
                    showOutput(`[${result.provider.toUpperCase()}] ${result.result}`);
                } else {
                    showOutput('Error: ' + result.error);
                }
            } catch (error) {
                showOutput('Error: ' + error.message);
            } finally {
                hideLoading();
            }
        });

        document.getElementById('socialForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            showLoading();
            
            const formData = new FormData(e.target);
            let data = Object.fromEntries(formData.entries());
            data = addProviderToData(data);
            
            try {
                const response = await fetch('/ai/generate-social', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                if (result.success) {
                    showOutput(`[${result.provider.toUpperCase()}] ${result.result}`);
                } else {
                    showOutput('Error: ' + result.error);
                }
            } catch (error) {
                showOutput('Error: ' + error.message);
            } finally {
                hideLoading();
            }
        });

        document.getElementById('translateForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            showLoading();
            
            const formData = new FormData(e.target);
            let data = Object.fromEntries(formData.entries());
            data = addProviderToData(data);
            
            try {
                const response = await fetch('/ai/translate', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                if (result.success) {
                    showOutput(`[${result.provider.toUpperCase()}] ${result.result}`);
                } else {
                    showOutput('Error: ' + result.error);
                }
            } catch (error) {
                showOutput('Error: ' + error.message);
            } finally {
                hideLoading();
            }
        });

        document.getElementById('summarizeForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            showLoading();
            
            const formData = new FormData(e.target);
            let data = Object.fromEntries(formData.entries());
            data.max_length = parseInt(data.max_length);
            data = addProviderToData(data);
            
            try {
                const response = await fetch('/ai/summarize', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                if (result.success) {
                    showOutput(`[${result.provider.toUpperCase()}] ${result.result}`);
                } else {
                    showOutput('Error: ' + result.error);
                }
            } catch (error) {
                showOutput('Error: ' + error.message);
            } finally {
                hideLoading();
            }
        });

        document.getElementById('chatForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            const message = formData.get('message');
            
            // Add user message to chat
            addChatMessage('user', message);
            
            // Clear form
            e.target.reset();
            
            showLoading();
            
            try {
                let data = {
                    message: message,
                    conversation: conversationHistory,
                    provider: getCurrentProvider()
                };
                
                const response = await fetch('/ai/chat', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                if (result.success) {
                    // Add AI response to chat
                    addChatMessage('assistant', `[${result.provider.toUpperCase()}] ${result.result}`);
                    
                    // Update conversation history
                    conversationHistory.push(
                        { role: 'user', content: message },
                        { role: 'assistant', content: result.result }
                    );
                    
                    // Keep only last 10 messages
                    if (conversationHistory.length > 10) {
                        conversationHistory = conversationHistory.slice(-10);
                    }
                } else {
                    addChatMessage('assistant', 'Error: ' + result.error);
                }
            } catch (error) {
                addChatMessage('assistant', 'Error: ' + error.message);
            } finally {
                hideLoading();
            }
        });

        // Initialize first tab as active
        document.querySelector('.tab-btn').classList.add('active', 'text-blue-600', 'border-blue-600');
        document.querySelector('.tab-btn').classList.remove('text-gray-700', 'border-transparent');
    </script>
</body>
</html>
