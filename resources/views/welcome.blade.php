<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="SI-PTM Puskesmas Tigaraksa - Sistem Informasi Penyakit Terpadu untuk monitoring data kesehatan masyarakat. Platform digital internal untuk manajemen data pasien dan rekam medis.">
    <meta name="keywords" content="sistem informasi penyakit, puskesmas tigaraksa, monitoring kesehatan, data pasien, rekam medis, kesehatan masyarakat, SI-PTM">
    <meta name="author" content="Tim IT Puskesmas Tigaraksa">
    
    <title>SI-PTM Puskesmas Tigaraksa - Sistem Informasi Penyakit</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Styles -->
    <style>
        :root {
            --primary-color: #10b981;
            --primary-dark: #059669;
            --secondary-color: #065f46;
            --accent-color: #34d399;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --text-light: #9ca3af;
            --bg-primary: #ffffff;
            --bg-secondary: #f9fafb;
            --bg-tertiary: #f3f4f6;
            --border-color: #e5e7eb;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: var(--text-primary);
            background-color: var(--bg-primary);
            overflow-x: hidden;
        }
        
        /* Typography */
        h1 { font-size: 3.75rem; font-weight: 800; line-height: 1.2; }
        h2 { font-size: 3rem; font-weight: 700; line-height: 1.3; }
        h3 { font-size: 2.25rem; font-weight: 600; line-height: 1.4; }
        h4 { font-size: 1.875rem; font-weight: 600; line-height: 1.4; }
        h5 { font-size: 1.5rem; font-weight: 600; line-height: 1.4; }
        h6 { font-size: 1.25rem; font-weight: 600; line-height: 1.4; }
        
        /* Container */
        .container {
            width: 100%;
            margin: 0 auto;
            padding: 0 1rem;
        }
        
        .container-narrow {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }
        
        /* Navigation */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border-color);
            z-index: 1000;
            transition: all 0.3s ease;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        }
        
        .navbar-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.25rem 0;
            min-height: 80px;
        }
        
        .navbar-center {
            flex: 2; /* Memberikan ruang lebih besar untuk menu di tengah */
            display: flex;
            justify-content: center;
        }

        .nav-links {
            display: flex;
            gap: 2.5rem;
            list-style: none;
            margin: 0;
            padding: 0;
            white-space: nowrap; /* Mencegah menu turun ke bawah jika layar menyempit */
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 1rem;
            text-decoration: none;
            color: var(--text-primary);
            transition: all 0.3s ease;
            padding: 0.5rem;
            border-radius: 0.75rem;
        }
        
        .logo:hover {
            transform: translateY(-2px);
            background: rgba(16, 185, 129, 0.05);
        }
        
        .logo-icon {
            width: 3rem;
            height: 3rem;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border-radius: 0.875rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            box-shadow: var(--shadow-lg);
            flex-shrink: 0;
        }
        
        .logo-text h1 {
            font-size: 1.75rem;
            font-weight: 800;
            margin: 0;
            color: var(--text-primary);
            letter-spacing: -0.025em;
        }
        
        .logo-text .institution {
            font-size: 1.125rem;
            color: var(--primary-color);
            font-weight: 600;
            margin: 0.125rem 0;
            letter-spacing: 0.025em;
        }
        
        .logo-text .subtitle {
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin: 0;
            font-weight: 500;
            opacity: 0.85;
        }
        
        .header-divider {
            width: 100%;
            height: 1px;
            background: linear-gradient(90deg, var(--border-color) 0%, transparent 100%);
            margin: 0.5rem 0;
        }
        
        .nav-links a {
            color: var(--text-primary);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            position: relative;
            padding: 0.5rem 0;
        }
        
        .nav-links a:hover {
            color: var(--primary-color);
        }
        
        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary-color);
            transition: width 0.3s ease;
        }
        
        .nav-links a:hover::after {
            width: 100%;
        }
        
        .nav-menu {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 0;
        }
        
        .nav-links {
            display: flex;
            gap: 2.5rem;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        
        .nav-actions {
            display: flex;
            justify-content: flex-end; /* Tombol masuk tetap di kanan */
        }
        
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: var(--text-primary);
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 0.5rem;
            transition: background-color 0.2s ease;
        }
        
        .mobile-menu-btn:hover {
            background-color: var(--bg-tertiary);
        }
        
        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            text-decoration: none;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            box-shadow: var(--shadow-md);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }
        
        .btn-secondary {
            background: white;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
        }
        
        .btn-secondary:hover {
            background: var(--primary-color);
            color: white;
        }
        
        .btn-outline {
            background: transparent;
            color: white;
            border: 2px solid white;
        }
        
        .btn-outline:hover {
            background: white;
            color: var(--primary-color);
        }
        
        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 10rem 0 4rem;
            position: relative;
            overflow: hidden;
        }
        
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><g fill="%23ffffff" fill-opacity="0.05"><path d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/></g></g></svg>') repeat;
            opacity: 0.1;
        }
        
        .hero-content {
            position: relative;
            z-index: 1;
        }
        
        .hero-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }
        
        .hero-text h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }
        
        .hero-text p {
            font-size: 1.25rem;
            margin-bottom: 2rem;
            opacity: 0.9;
            line-height: 1.6;
        }
        
        .hero-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .hero-stats {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 1rem;
            padding: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .hero-stats-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-item i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            opacity: 0.9;
        }
        
        .stat-item p {
            font-size: 0.875rem;
            opacity: 0.8;
            margin: 0;
        }
        
        /* Features Section */
        .features {
            padding: 6rem 0;
            background: var(--bg-secondary);
        }
        
        .section-header {
            text-align: center;
            margin-bottom: 4rem;
        }
        
        .section-header h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--text-primary);
        }
        
        .section-header p {
            font-size: 1.125rem;
            color: var(--text-secondary);
            max-width: 600px;
            margin: 0 auto;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
        }
        
        .feature-card {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
            transform: translateY(-100%);
            transition: transform 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }
        
        .feature-card:hover::before {
            transform: translateY(0);
        }
        
        .feature-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .feature-card h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            color: var(--text-primary);
        }
        
        .feature-card p {
            color: var(--text-secondary);
            line-height: 1.6;
        }
        
        /* Statistics Section */
        .statistics {
            padding: 6rem 0;
            background: white;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 3rem;
            text-align: center;
        }
        
        .stat-box {
            padding: 2rem;
        }
        
        .stat-number {
            font-size: 3rem;
            font-weight: 800;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
            display: block;
        }
        
        .stat-label {
            font-size: 1.125rem;
            color: var(--text-secondary);
            font-weight: 500;
        }
        
        /* Media Section */
        .media {
            padding: 6rem 0;
            background: white;
        }
        
        .media-container {
            max-width: 900px;
            margin: 0 auto;
        }
        
        .video-wrapper {
            position: relative;
            padding-bottom: 56.25%; /* 16:9 Aspect Ratio */
            height: 0;
            overflow: hidden;
            border-radius: 1rem;
            box-shadow: var(--shadow-xl);
            background: #000;
        }
        
        .video-wrapper iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
            border-radius: 1rem;
        }
        
        .video-info {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .video-info h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--text-primary);
        }
        
        .video-info p {
            font-size: 1.125rem;
            color: var(--text-secondary);
            max-width: 600px;
            margin: 0 auto;
            line-height: 1.6;
        }
        
        /* About Section */
        .about {
            padding: 6rem 0;
            background: var(--bg-secondary);
        }
        
        .about-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }
        
        .about-content h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: var(--text-primary);
        }
        
        .about-content p {
            font-size: 1.125rem;
            color: var(--text-secondary);
            margin-bottom: 1.5rem;
            line-height: 1.7;
        }
        
        .benefits-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .benefit-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .benefit-item i {
            color: var(--primary-color);
            font-size: 1.25rem;
        }
        
        .about-card {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            border-radius: 1rem;
            padding: 2.5rem;
            box-shadow: var(--shadow-lg);
        }
        
        .about-card h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }
        
        .about-feature {
            display: flex;
            align-items: start;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .about-feature i {
            color: #fbbf24;
            font-size: 1.25rem;
            margin-top: 0.25rem;
        }
        
        .about-feature h4 {
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .about-feature p {
            opacity: 0.9;
            line-height: 1.5;
        }
        
        /* Contact Section */
        .contact {
            padding: 6rem 0;
            background: white;
        }
        
        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .contact-info h3 {
            font-size: 1.875rem;
            font-weight: 600;
            margin-bottom: 2rem;
            color: var(--text-primary);
        }
        
        .contact-item {
            display: flex;
            align-items: start;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .contact-icon {
            width: 3rem;
            height: 3rem;
            background: var(--bg-tertiary);
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-size: 1.25rem;
            flex-shrink: 0;
        }
        
        .contact-details h4 {
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
            color: var(--text-primary);
        }
        
        .contact-details p {
            color: var(--text-secondary);
        }
        
        .social-links {
            margin-top: 2rem;
        }
        
        .social-links h4 {
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--text-primary);
        }
        
        .social-icons {
            display: flex;
            gap: 0.75rem;
        }
        
        .social-icon {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: transform 0.2s ease;
        }
        
        .social-icon:hover {
            transform: translateY(-2px);
        }
        
        .social-icon.facebook { background: #1877f2; }
        .social-icon.twitter { background: #1da1f2; }
        .social-icon.linkedin { background: #0077b5; }
        .social-icon.instagram { background: #e4405f; }
        
        .contact-form {
            background: var(--bg-secondary);
            border-radius: 1rem;
            padding: 2rem;
        }
        
        .contact-form h3 {
            font-size: 1.875rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: var(--text-primary);
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }
        
        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            font-size: 1rem;
            transition: all 0.2s ease;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }
        
        textarea.form-control {
            resize: vertical;
            min-height: 120px;
        }
        
        /* Footer */
        .footer {
            background: var(--text-primary);
            color: white;
            padding: 3rem 0 1.5rem;
        }
        
        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1.5fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        .footer-brand h3 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .footer-brand p {
            color: var(--text-light);
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }
        
        .footer-column h4 {
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        
        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .footer-links li {
            margin-bottom: 0.5rem;
        }
        
        .footer-links a {
            color: var(--text-light);
            text-decoration: none;
            transition: color 0.2s ease;
        }
        
        .footer-links a:hover {
            color: white;
        }
        
        .newsletter-form {
            display: flex;
            gap: 0.5rem;
        }
        
        .newsletter-form input {
            flex: 1;
            padding: 0.75rem;
            border: 1px solid #374151;
            background: #374151;
            border-radius: 0.5rem;
            color: white;
        }
        
        .newsletter-form input::placeholder {
            color: var(--text-light);
        }
        
        .newsletter-form button {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }
        
        .newsletter-form button:hover {
            background: var(--primary-dark);
        }
        
        .footer-bottom {
            border-top: 1px solid #374151;
            padding-top: 1.5rem;
            text-align: center;
            color: var(--text-light);
        }
        
        .footer-bottom a {
            color: var(--text-light);
            text-decoration: none;
            margin: 0 0.5rem;
            transition: color 0.2s ease;
        }
        
        .footer-bottom a:hover {
            color: white;
        }
        
        /* Mobile Responsive */
        @media (max-width: 768px) {
            .nav-menu {
                display: none;
            }
            
            .mobile-menu-btn {
                display: block;
            }
            
            .mobile-menu {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: white;
                border-top: 1px solid var(--border-color);
                padding: 1rem;
                box-shadow: var(--shadow-lg);
            }
            
            .mobile-menu.active {
                display: block;
            }
            
            .mobile-menu .nav-links {
                flex-direction: column;
                gap: 1rem;
            }
            
            .hero-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            
            .hero-text h1 {
                font-size: 2.5rem;
            }
            
            .hero-buttons {
                flex-direction: column;
            }
            
            .features-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 2rem;
            }
            
            .about-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            
            .contact-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            
            .footer-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            
            .benefits-grid {
                grid-template-columns: 1fr;
            }
        }
        
        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }
        
        @keyframes countUp {
            from {
                opacity: 0;
                transform: scale(0.5);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        .count-animation {
            animation: countUp 0.8s ease-out;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
    <div class="container-narrow">
        <div class="navbar-content">
            
            <a href="#" class="logo">
                <div class="logo-icon">
                    <i class="fas fa-hospital"></i>
                </div>
                <div class="logo-text">
                    <h1>SI-PTM</h1>
                    <div class="institution">Puskesmas Tigaraksa</div>
                </div>
            </a>

            <div class="navbar-center">
                <ul class="nav-links">
                    <li><a href="#features">Fitur</a></li>
                    <li><a href="#statistics">Statistik</a></li>
                    <li><a href="#media">Media</a></li>
                    <li><a href="#about">Tentang</a></li>
                    <li><a href="#contact">Kontak</a></li>
                </ul>
            </div>

            <div class="nav-actions">
                @guest
                    <a href="{{ route('login') }}" class="btn btn-primary">
                        <i class="fas fa-sign-in-alt"></i> Masuk
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                @endguest
                
                <button class="mobile-menu-btn" onclick="toggleMobileMenu()" style="margin-left: 10px;">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

        </div>
    </div>
</nav>
    
    <!-- Mobile Menu -->
    <div id="mobileMenu" class="mobile-menu">
            <ul class="nav-links">
                <li><a href="#features" onclick="toggleMobileMenu()">Fitur</a></li>
                <li><a href="#statistics" onclick="toggleMobileMenu()">Statistik</a></li>
                <li><a href="#media" onclick="toggleMobileMenu()">Media</a></li>
                <li><a href="#about" onclick="toggleMobileMenu()">Tentang</a></li>
                <li><a href="#contact" onclick="toggleMobileMenu()">Kontak</a></li>
            </ul>
            <div class="nav-actions" style="width: 100%; margin-top: 1rem;">
                @guest
                    <a href="{{ route('login') }}" class="btn btn-primary" style="width: 100%; justify-content: center;">
                        <i class="fas fa-sign-in-alt"></i>
                        Masuk
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="btn btn-primary" style="width: 100%; justify-content: center;">
                        <i class="fas fa-tachometer-alt"></i>
                        Dashboard
                    </a>
                @endguest
            </div>
        </div>
    </nav>
    
    <!-- Hero Section -->
    <section class="hero">
        <div class="container-narrow">
            <div class="hero-content">
                <div class="hero-grid">
                    <div class="hero-text fade-in-up">
                        <h1>Sistem <span style="color: #fbbf24;">Monitoring Kesehatan</span> Masyarakat</h1>
                        <p>SI-PTM Puskesmas Tigaraksa adalah sistem informasi penyakit terpadu untuk monitoring data kesehatan masyarakat dalam lingkup Puskesmas Tigaraksa. Membantu tenaga kesehatan dalam mengelola data pasien, diagnosa, dan treatment dengan efisien.</p>
                        <div class="hero-buttons">
                            <a href="#features" class="btn btn-outline">
                                <i class="fas fa-info-circle"></i>
                                Informasi Sistem
                            </a>
                            <a href="#contact" class="btn btn-secondary">
                                <i class="fas fa-phone"></i>
                                Hubungi Kami
                            </a>
                        </div>
                    </div>
                    <div class="hero-stats fade-in-up">
                        <div class="hero-stats-grid">
                            <div class="stat-item">
                                <i class="fas fa-users"></i>
                                <p><strong>5,000+</strong><br>Pasien Terdaftar</p>
                            </div>
                            <div class="stat-item">
                                <i class="fas fa-home"></i>
                                <p><strong>15+</strong><br>Wilayah Pelayanan</p>
                            </div>
                            <div class="stat-item">
                                <i class="fas fa-user-nurse"></i>
                                <p><strong>50+</strong><br>Tenaga Kesehatan</p>
                            </div>
                            <div class="stat-item">
                                <i class="fas fa-file-medical"></i>
                                <p><strong>25,000+</strong><br>Kunjungan/Tahun</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Features Section -->
    <section id="features" class="features">
        <div class="container-narrow">
            <div class="section-header">
                <h2>Fitur Sistem Monitoring Kesehatan</h2>
                <p>Platform digital internal Puskesmas Tigaraksa untuk monitoring data kesehatan masyarakat</p>
            </div>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon" style="background: #dbeafe; color: #1e40af;">
                        <i class="fas fa-database"></i>
                    </div>
                    <h3>Manajemen Data Pasien</h3>
                    <p>Kelola data pasien secara komprehensif dengan sistem pencarian dan filtering yang canggih untuk akses informasi yang cepat dan akurat.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon" style="background: #d1fae5; color: #065f46;">
                        <i class="fas fa-file-import"></i>
                    </div>
                    <h3>Import Excel/CSV</h3>
                    <p>Import data pasien massal dari file Excel atau CSV dengan validasi otomatis, error handling, dan progress tracking yang real-time.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon" style="background: #e9d5ff; color: #6b21a8;">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>Analisis & Laporan</h3>
                    <p>Generate laporan kesehatan dan statistik penyakit dengan visualisasi data yang interaktif dan insight yang mendalam.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon" style="background: #fee2e2; color: #991b1b;">
                        <i class="fas fa-notes-medical"></i>
                    </div>
                    <h3>Diagnosa ICD-10</h3>
                    <p>Integrasi kode diagnosa ICD-10 untuk klasifikasi penyakit yang standar internasional dan konsistensi data medis.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon" style="background: #fef3c7; color: #92400e;">
                        <i class="fas fa-users-cog"></i>
                    </div>
                    <h3>Manajemen User</h3>
                    <p>Sistem role-based access control untuk mengelola hak akses pengguna dengan keamanan berlapis dan audit trail.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon" style="background: #ddd6fe; color: #4c1d95;">
                        <i class="fas fa-sync"></i>
                    </div>
                    <h3>Sinkronisasi Data</h3>
                    <p>Sinkronisasi data real-time antar lokasi untuk konsistensi informasi kesehatan dan backup otomatis yang aman.</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Statistics Section -->
    <section id="statistics" class="statistics">
        <div class="container-narrow">
            <div class="section-header">
                <h2>Kinerja Pelayanan Kesehatan</h2>
                <p>Data monitoring kesehatan masyarakat dalam lingkup Puskesmas Tigaraksa</p>
            </div>
            
            <div class="stats-grid">
                <div class="stat-box">
                    <span class="stat-number" data-target="5000">0</span>
                    <div class="stat-label">Pasien Terdaftar</div>
                </div>
                <div class="stat-box">
                    <span class="stat-number" data-target="15">0</span>
                    <div class="stat-label">Wilayah Pelayanan</div>
                </div>
                <div class="stat-box">
                    <span class="stat-number" data-target="50">0</span>
                    <div class="stat-label">Tenaga Kesehatan</div>
                </div>
                <div class="stat-box">
                    <span class="stat-number" data-target="25000">0</span>
                    <div class="stat-label">Kunjungan/Tahun</div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Media Informasi Section -->
    <section id="media" class="media">
        <div class="container-narrow">
            <div class="video-info">
                <h2>Media Informasi</h2>
                <p>Tonton video informasi tentang sistem monitoring kesehatan Puskesmas Tigaraksa dan bagaimana SI-PTM membantu meningkatkan pelayanan kesehatan masyarakat.</p>
            </div>
            
            <div class="media-container">
                <div class="video-wrapper">
                    <iframe 
                        src="https://www.youtube.com/embed/XV3bC2KG2EQ?start=10&rel=0&modestbranding=1&autohide=1&showinfo=0&controls=1" 
                        title="Video Informasi SI-PTM Puskesmas Tigaraksa"
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen>
                    </iframe>
                </div>
            </div>
        </div>
    </section>
    
    <!-- About Section -->
    <section id="about" class="about">
        <div class="container-narrow">
            <div class="about-grid">
                <div class="about-content">
                    <h2>Tentang SI-PTM Puskesmas Tigaraksa</h2>
                    <p>SI-PTM (Sistem Informasi Penyakit Terpadu) adalah sistem monitoring data kesehatan internal yang dikembangkan khusus untuk Puskesmas Tigaraksa. Sistem ini membantu tenaga kesehatan dalam mengelola data pasien, monitoring penyakit, dan tracking treatment medis secara digital.</p>
                    <p>Dengan interface yang user-friendly dan fitur yang disesuaikan dengan kebutuhan Puskesmas Tigaraksa, sistem ini menjadi solusi optimal untuk monitoring kesehatan masyarakat di wilayah pelayanan kami.</p>
                    
                    <div class="benefits-grid">
                        <div class="benefit-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Monitoring Real-Time</span>
                        </div>
                        <div class="benefit-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Data Terintegrasi</span>
                        </div>
                        <div class="benefit-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Laporan Otomatis</span>
                        </div>
                        <div class="benefit-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Mudah Diakses</span>
                        </div>
                    </div>
                    
                    <a href="#contact" class="btn btn-primary">
                        <i class="fas fa-phone"></i>
                        Hubungi Puskesmas
                    </a>
                </div>
                
                <div class="about-card">
                    <h3>Keunggulan Sistem Monitoring</h3>
                    <div class="about-feature">
                        <i class="fas fa-shield-alt"></i>
                        <div>
                            <h4>Keamanan Data Pasien</h4>
                            <p>Enkripsi data dan backup otomatis untuk melindungi informasi kesehatan pasien sesuai standar privasi data medis.</p>
                        </div>
                    </div>
                    <div class="about-feature">
                        <i class="fas fa-chart-line"></i>
                        <div>
                            <h4>Analisis Data Real-Time</h4>
                            <p>Dashboard monitoring yang memberikan insight kesehatan masyarakat secara real-time untuk pengambilan keputusan cepat.</p>
                        </div>
                    </div>
                    <div class="about-feature">
                        <i class="fas fa-users"></i>
                        <div>
                            <h4>User-Friendly Interface</h4>
                            <p>Interface yang mudah digunakan untuk tenaga kesehatan dengan berbagai tingkat kemampuan teknis.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Contact Section -->
    <section id="contact" class="contact">
        <div class="container-narrow">
            <div class="section-header">
                <h2>Hubungi Puskesmas Tigaraksa</h2>
                <p>Informasi kontak dan lokasi Puskesmas Tigaraksa untuk layanan kesehatan masyarakat</p>
            </div>
            
            <div class="contact-grid">
                <div class="contact-info">
                    <h3>Informasi Kontak Puskesmas</h3>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="contact-details">
                            <h4>Alamat</h4>
                            <p>Jl. Raya Tigaraksa No. 45, Kec. Tigaraksa, Kab. Tangerang, Banten 15520</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="contact-details">
                            <h4>Telepon</h4>
                            <p>(021) 5937 1234</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-details">
                            <h4>Email</h4>
                            <p>puskesmas.tigaraksa@tangerangkab.go.id</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="contact-details">
                            <h4>Jam Pelayanan</h4>
                            <p>Senin - Jumat: 07:00 - 20:00 WIB<br>Sabtu: 07:00 - 12:00 WIB</p>
                        </div>
                    </div>
                    
                    <div class="social-links">
                        <h4>Ikuti Kami</h4>
                        <div class="social-icons">
                            <a href="#" class="social-icon facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="social-icon twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="social-icon linkedin">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="#" class="social-icon instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="contact-form">
                    <h3>Kirim Pesan</h3>
                    <form onsubmit="handleSubmit(event)">
                        <div class="form-group">
                            <label for="name">Nama Lengkap</label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="John Doe" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" class="form-control" placeholder="john@example.com" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="message">Pesan</label>
                            <textarea id="message" name="message" class="form-control" placeholder="Tulis pesan Anda..." required></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                            <i class="fas fa-paper-plane"></i>
                            Kirim Pesan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="container-narrow">
            <div class="footer-grid">
                <div class="footer-brand">
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                        <div style="width: 2.5rem; height: 2.5rem; background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)); border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.25rem;">
                            <i class="fas fa-hospital"></i>
                        </div>
                        <div>
                            <h3>SI-PTM</h3>
                            <p style="font-size: 0.75rem; color: var(--text-light); margin: 0;">Puskesmas Tigaraksa</p>
                        </div>
                    </div>
                    <p>Sistem monitoring data kesehatan internal Puskesmas Tigaraksa untuk mendukung pelayanan kesehatan masyarakat yang lebih baik.</p>
                </div>
                
                <div class="footer-column">
                    <h4>Quick Links</h4>
                    <ul class="footer-links">
                        <li><a href="#features">Fitur</a></li>
                        <li><a href="#about">Tentang</a></li>
                        <li><a href="#statistics">Statistik</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h4>Layanan</h4>
                    <ul class="footer-links">
                        <li><a href="#">Manajemen Pasien</a></li>
                        <li><a href="#">Import Data</a></li>
                        <li><a href="#">Laporan</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h4>Newsletter</h4>
                    <p style="color: var(--text-light); margin-bottom: 1rem;">Dapatkan update terbaru tentang SI-PTM</p>
                    <form class="newsletter-form" onsubmit="handleNewsletter(event)">
                        <input type="email" placeholder="Email Anda" required>
                        <button type="submit">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <span id="year"></span> Puskesmas Tigaraksa - SI-PTM. Sistem Internal Monitoring Kesehatan. | <a href="#">Kebijakan Privasi</a> | <a href="#">Ketentuan Penggunaan</a></p>
            </div>
        </div>
    </footer>
    
    <!-- JavaScript -->
    <script>
        // Mobile Menu Toggle
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobileMenu');
            mobileMenu.classList.toggle('active');
        }
        document.getElementById("year").innerHTML = new Date().getFullYear();
        // Smooth Scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // Counter Animation
        function animateCounters() {
            const counters = document.querySelectorAll('.stat-number');
            
            counters.forEach(counter => {
                const target = parseInt(counter.getAttribute('data-target'));
                const duration = 2000;
                const increment = target / (duration / 16);
                let current = 0;
                
                const updateCounter = () => {
                    current += increment;
                    if (current < target) {
                        counter.textContent = Math.ceil(current).toLocaleString('id-ID');
                        requestAnimationFrame(updateCounter);
                    } else {
                        counter.textContent = target.toLocaleString('id-ID');
                    }
                };
                
                updateCounter();
            });
        }
        
        // Intersection Observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    if (entry.target.classList.contains('statistics')) {
                        animateCounters();
                        observer.unobserve(entry.target);
                    }
                    
                    if (entry.target.classList.contains('fade-in-up')) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                }
            });
        }, observerOptions);
        
        // Observe elements
        document.querySelectorAll('.fade-in-up').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(el);
        });
        
        const statsSection = document.querySelector('.statistics');
        if (statsSection) {
            observer.observe(statsSection);
        }
        
        // Form submissions
        function handleSubmit(event) {
            event.preventDefault();
            alert('Terima kasih! Pesan Anda telah terkirim. Kami akan segera menghubungi Anda.');
            event.target.reset();
        }
        
        function handleNewsletter(event) {
            event.preventDefault();
            alert('Terima kasih telah berlangganan newsletter kami!');
            event.target.reset();
        }
        
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.style.boxShadow = '0 4px 6px -1px rgba(0, 0, 0, 0.1)';
            } else {
                navbar.style.boxShadow = '0 1px 2px 0 rgba(0, 0, 0, 0.05)';
            }
        });
    </script>
</body>
</html>
