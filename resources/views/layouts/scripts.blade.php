<!-- Scripts Component -->
<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Alpine.js for reactive components -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<!-- Chart.js for analytics -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Custom JavaScript -->
<script>
    // Global variables
    window.app = {
        baseUrl: '{{ url('/') }}',
        csrfToken: '{{ csrf_token() }}',
        user: @if(Auth::check()) {
            name: '{{ Auth::user()->name }}',
            username: '{{ Auth::user()->username }}',
            email: '{{ Auth::user()->email }}',
            role: '{{ Auth::user()->roles->pluck('name')->first() }}'
        } @else {
            name: null,
            username: null,
            email: null,
            role: null
        } @endif
    };

    // Utility functions
    function showLoading(element) {
        if (element) {
            element.disabled = true;
            element.innerHTML = '<div class="spinner inline-block mr-2"></div> Loading...';
        }
    }

    function hideLoading(element, originalText) {
        if (element) {
            element.disabled = false;
            element.innerHTML = originalText;
        }
    }

    function showAlert(type, title, message) {
        Swal.fire({
            icon: type,
            title: title,
            text: message,
            timer: type === 'success' ? 3000 : null,
            showConfirmButton: type !== 'success'
        });
    }

    function confirmAction(title, text, callback) {
        Swal.fire({
            title: title,
            text: text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, proceed!'
        }).then((result) => {
            if (result.isConfirmed) {
                callback();
            }
        });
    }

    // Sidebar toggle for mobile
    function toggleSidebar() {
        const sidebar = document.querySelector('.sidebar');
        if (sidebar) {
            sidebar.classList.toggle('open');
        }
    }

    // Initialize tooltips
    function initTooltips() {
        // Add tooltip initialization if needed
        console.log('Tooltips initialized');
    }

    // Initialize charts
    function initCharts() {
        // Add chart initialization if needed
        console.log('Charts initialized');
    }

    // Form validation
    function validateForm(formId) {
        const form = document.getElementById(formId);
        if (!form) return true;

        const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
        let isValid = true;

        inputs.forEach(input => {
            if (!input.value.trim()) {
                input.classList.add('border-red-500');
                isValid = false;
            } else {
                input.classList.remove('border-red-500');
            }
        });

        return isValid;
    }

    // Auto-resize textarea
    function autoResizeTextarea() {
        const textareas = document.querySelectorAll('textarea[data-auto-resize]');
        textareas.forEach(textarea => {
            textarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = this.scrollHeight + 'px';
            });
        });
    }

    // Copy to clipboard
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            showAlert('success', 'Copied!', 'Text copied to clipboard');
        }).catch(err => {
            console.error('Failed to copy: ', err);
        });
    }

    // Dark mode toggle
    function toggleDarkMode() {
        document.body.classList.toggle('dark');
        localStorage.setItem('darkMode', document.body.classList.contains('dark'));
    }

    // Initialize dark mode
    function initDarkMode() {
        const darkMode = localStorage.getItem('darkMode') === 'true';
        if (darkMode) {
            document.body.classList.add('dark');
        }
    }

    // Initialize everything when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        initDarkMode();
        initTooltips();
        initCharts();
        autoResizeTextarea();
        
        // Add fade-in animation to cards
        const cards = document.querySelectorAll('.card');
        cards.forEach((card, index) => {
            setTimeout(() => {
                card.classList.add('fade-in');
            }, index * 100);
        });
    });

    // Handle AJAX requests
    function makeAjaxRequest(url, method = 'GET', data = null) {
        const options = {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': app.csrfToken
            }
        };

        if (data && method !== 'GET') {
            options.body = JSON.stringify(data);
        }

        return fetch(url, options)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('error', 'Error', 'Something went wrong. Please try again.');
            });
    }

    // Search functionality
    function initSearch() {
        const searchInput = document.querySelector('input[type="search"]');
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                const query = e.target.value.toLowerCase();
                const rows = document.querySelectorAll('tbody tr');
                
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(query) ? '' : 'none';
                });
            });
        }
    }

    // Initialize search
    document.addEventListener('DOMContentLoaded', initSearch);
</script>

@stack('scripts')
