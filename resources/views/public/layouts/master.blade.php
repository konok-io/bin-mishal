<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ is_rtl() ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', __('app.app_name'))</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Noto+Sans+Arabic:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 RTL -->
    @if(is_rtl())
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
    @else
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    @endif
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        /* Custom Fonts */
        @font-face {
            font-family: 'Bangla';
            src: url('/fonts/bangla.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        @font-face {
            font-family: 'English';
            src: url('/fonts/English.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        @font-face {
            font-family: 'Arabic';
            src: url('/fonts/Arabic.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        
        :root {
            --primary: #006C35;
            --primary-dark: #004d26;
            --secondary: #C8A951;
            --accent: #1B3A5C;
            --success: #16A34A;
            --warning: #F59E0B;
            --danger: #DC2626;
            --bg-light: #F8FAFC;
            --text-dark: #1E293B;
            --text-muted: #64748B;
            --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        }
        
        /* Language-specific fonts */
        html[lang="bn"] body,
        html[lang="bn"] * {
            font-family: 'Bangla', 'Hind Siliguri', sans-serif !important;
        }
        html[lang="ar"] body,
        html[lang="ar"] * {
            font-family: 'Arabic', 'Noto Sans Arabic', sans-serif !important;
        }
        html[lang="en"] body,
        html[lang="en"] * {
            font-family: 'English', 'Inter', sans-serif !important;
        }
        
        body { 
            color: var(--text-dark);
            background: #fff;
        }
        
        .btn-primary-custom { background: var(--primary); border: none; color: #fff; }
        .btn-primary-custom:hover { background: var(--primary-dark); }
        
        .main-header {
            background: #fff;
            box-shadow: var(--shadow);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .navbar-brand { font-weight: 700; color: var(--primary) !important; font-size: 24px; }
        .navbar-nav .nav-link { color: var(--text-dark); font-weight: 500; padding: 20px 15px; }
        .navbar-nav .nav-link:hover { color: var(--primary); }
        
        .page-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: #fff;
            padding: 60px 0;
        }
        
        .main-footer { background: var(--accent); color: #fff; padding: 60px 0 30px; }
        .footer-title { font-weight: 700; margin-bottom: 20px; color: var(--secondary); }
        .footer-links { list-style: none; padding: 0; }
        .footer-links li { margin-bottom: 10px; }
        .footer-links a { color: rgba(255,255,255,0.8); text-decoration: none; }
        .footer-links a:hover { color: #fff; }
        .footer-bottom { border-top: 1px solid rgba(255,255,255,0.1); padding-top: 20px; margin-top: 40px; }
        .social-icons a { color: #fff; font-size: 20px; margin-inline-end: 15px; }
        .social-icons a:hover { color: var(--secondary); }
        
        .whatsapp-float {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            background: #25D366;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 30px;
            box-shadow: var(--shadow-lg);
            z-index: 9999;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <header class="main-header">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand" href="/{{ app()->getLocale() }}">{{ __('app.app_name') }}</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link" href="/{{ app()->getLocale() }}">{{ __('app.home') }}</a></li>
                        <li class="nav-item"><a class="nav-link" href="/{{ app()->getLocale() }}/services">{{ __('navigation.services') }}</a></li>
                        <li class="nav-item"><a class="nav-link" href="/{{ app()->getLocale() }}/about">{{ __('app.about') }}</a></li>
                        <li class="nav-item"><a class="nav-link" href="/{{ app()->getLocale() }}/news">News</a></li>
                        <li class="nav-item"><a class="nav-link" href="/{{ app()->getLocale() }}/contact">{{ __('app.contact') }}</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    @yield('content')

    <footer class="main-footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <h5 class="footer-title">{{ __('app.app_name') }}</h5>
                    <p class="opacity-75 mb-4">{{ __('home.footer_description') }}</p>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h5 class="footer-title">Quick Links</h5>
                    <ul class="footer-links">
                        <li><a href="/{{ app()->getLocale() }}/about">{{ __('app.about') }}</a></li>
                        <li><a href="/{{ app()->getLocale() }}/services">{{ __('navigation.services') }}</a></li>
                        <li><a href="/{{ app()->getLocale() }}/contact">{{ __('app.contact') }}</a></li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <h5 class="footer-title">{{ __('home.contact_info') }}</h5>
                    <ul class="footer-links">
                        <li><i class="fas fa-map-marker-alt me-2"></i> Riyadh, Saudi Arabia</li>
                        <li><i class="fas fa-phone me-2"></i> +966 XX XXX XXXX</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p class="mb-0 opacity-75">&copy; {{ date('Y') }} {{ __('app.app_name') }}. {{ __('common.all_rights_reserved') }}</p>
            </div>
        </div>
    </footer>

    <a href="https://wa.me/966XXXXXXXX" target="_blank" class="whatsapp-float">
        <i class="fab fa-whatsapp"></i>
    </a>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
