<!-- Styles Component -->
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Tailwind CSS -->
<script src="https://cdn.tailwindcss.com"></script>

<!-- Custom Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<!-- Favicon -->
<link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">

<!-- Custom Styles -->
<style>
    /* Font Family */
    body {
        font-family: 'Inter', sans-serif;
    }
    
    /* Background Styles */
    .auth-body {
        background: linear-gradient(135deg, #053b22, #0b6a3a);
        min-height: 100vh;
    }
    
    .app-body {
        background-color: #f3f4f6;
        min-height: 100vh;
    }
    
    /* Sidebar Styles */
    .sidebar {
        background: linear-gradient(180deg, #053b22, #0b6a3a);
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
    }
    
    /* Navigation Styles */
    .nav-item:hover {
        background-color: rgba(255, 255, 255, 0.1);
        transition: all 0.3s ease;
    }
    
    .nav-item.active {
        background-color: rgba(255, 255, 255, 0.2);
        border-left: 4px solid #10b981;
    }
    
    /* Card Styles */
    .card {
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        transition: all 0.3s ease;
    }
    
    .card:hover {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    
    /* Button Styles */
    .btn-primary {
        background-color: #053b22;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        background-color: #0b6a3a;
        transform: translateY(-1px);
    }
    
    .btn-secondary {
        background-color: #6b7280;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-secondary:hover {
        background-color: #4b5563;
        transform: translateY(-1px);
    }
    
    /* Form Styles */
    .form-input {
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        padding: 0.5rem 0.75rem;
        transition: all 0.3s ease;
    }
    
    .form-input:focus {
        border-color: #10b981;
        outline: none;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }
    
    /* Table Styles */
    .table-hover tbody tr:hover {
        background-color: #f9fafb;
    }
    
    /* Animation Styles */
    .fade-in {
        animation: fadeIn 0.5s ease-in;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    .slide-in {
        animation: slideIn 0.3s ease-out;
    }
    
    @keyframes slideIn {
        from {
            transform: translateX(-100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    /* Loading Spinner */
    .spinner {
        border: 2px solid #f3f3f3;
        border-top: 2px solid #10b981;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    /* Responsive Sidebar */
    @media (max-width: 1024px) {
        .sidebar {
            position: fixed;
            left: -100%;
            top: 0;
            height: 100vh;
            z-index: 50;
            transition: left 0.3s ease;
        }
        
        .sidebar.open {
            left: 0;
        }
        
        /* Full width on mobile */
        main {
            padding: 1rem;
        }
    }
    
    /* Custom Scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }
    
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    
    ::-webkit-scrollbar-thumb {
        background: #10b981;
        border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: #053b22;
    }
</style>

@stack('styles')
