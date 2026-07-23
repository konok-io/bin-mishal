<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @switch($guard ?? 'default')
            @case('admin') Admin Login
            @case('employee') Employee Login
            @case('customer') Customer Login
            @default Login
        @endswitch
        - {{ config('app.name') }}
    </title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Custom Fonts with Unicode Range -->
    <style>
        @font-face {
            font-family: 'BanglaFont';
            src: url('/fonts/bangla.ttf') format('truetype');
            unicode-range: U+0980-09FF, U+09E0-09EF, U+200C-200D, U+20B9;
        }
        @font-face {
            font-family: 'EnglishFont';
            src: url('/fonts/English.ttf') format('truetype');
            unicode-range: U+0000-007F, U+0080-00FF, U+0100-017F, U+1E00-1EFF;
        }
        @font-face {
            font-family: 'ArabicFont';
            src: url('/fonts/Arabic.ttf') format('truetype');
            unicode-range: U+0600-06FF, U+0750-077F, U+FB50-FDFF, U+FE70-FEFF;
        }
        
        html[lang="bn"] body { font-family: 'BanglaFont', 'Hind Siliguri', sans-serif; }
        html[lang="ar"] body { font-family: 'ArabicFont', 'Noto Sans Arabic', sans-serif; direction: rtl; }
        html[lang="en"] body { font-family: 'EnglishFont', 'Inter', sans-serif; }
        
        :root {
            --primary: #006C35;
            --primary-dark: #004d26;
            --secondary: #C8A951;
            --accent: #1B3A5C;
            --success: #16A34A;
            --warning: #F59E0B;
            --danger: #DC2626;
        }
        
        * { box-sizing: border-box; }
        
        body {
            min-height: 100vh;
            margin: 0;
            font-size: 15px;
        }
        
        /* Split Screen Layout */
        .login-wrapper {
            display: flex;
            min-height: 100vh;
        }
        
        /* Left Side - Branding */
        .login-branding {
            flex: 1;
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
            position: relative;
            overflow: hidden;
        }
        
        .login-branding::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse 15s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 0.3; }
        }
        
        .branding-content {
            position: relative;
            z-index: 1;
            text-align: center;
            max-width: 400px;
        }
        
        .branding-content .logo {
            font-size: 60px;
            margin-bottom: 20px;
        }
        
        .branding-content h1 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 15px;
        }
        
        .branding-content p {
            font-size: 16px;
            opacity: 0.9;
            line-height: 1.6;
        }
        
        .branding-features {
            margin-top: 40px;
            text-align: left;
        }
        
        .branding-features li {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 15px;
            font-size: 14px;
        }
        
        .branding-features li i {
            width: 32px;
            height: 32px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Right Side - Form */
        .login-form-section {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background: #f8fafc;
        }
        
        .login-card {
            width: 100%;
            max-width: 420px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .login-card-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .login-card-header .icon {
            width: 70px;
            height: 70px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 30px;
        }
        
        .login-card-header h3 {
            margin: 0;
            font-size: 22px;
            font-weight: 600;
        }
        
        .login-card-header p {
            margin: 5px 0 0;
            opacity: 0.9;
            font-size: 14px;
        }
        
        .login-card-body {
            padding: 30px;
        }
        
        /* Form Styling */
        .form-floating {
            margin-bottom: 20px;
        }
        
        .form-floating > .form-control {
            border-radius: 12px;
            border: 2px solid #e2e8f0;
            padding: 20px 16px 8px;
            height: auto;
            min-height: 56px;
            transition: all 0.3s ease;
        }
        
        .form-floating > .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(0, 108, 53, 0.1);
        }
        
        .form-floating > label {
            padding: 16px;
        }
        
        .btn-login {
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 108, 53, 0.3);
            color: white;
        }
        
        .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        
        .forgot-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }
        
        .forgot-link:hover {
            text-decoration: underline;
        }
        
        .register-section {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            margin-top: 20px;
        }
        
        .register-section p {
            color: #64748b;
            margin-bottom: 10px;
        }
        
        .btn-register {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 30px;
            border: 2px solid var(--primary);
            color: var(--primary);
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-register:hover {
            background: var(--primary);
            color: white;
        }
        
        .back-home {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 20px;
            color: #94a3b8;
            text-decoration: none;
            font-size: 14px;
        }
        
        .back-home:hover {
            color: var(--primary);
        }
        
        /* Alert styling */
        .alert {
            border-radius: 12px;
            border: none;
        }
        
        /* Mobile Responsive */
        @media (max-width: 992px) {
            .login-wrapper {
                flex-direction: column;
            }
            
            .login-branding {
                padding: 40px 20px;
                min-height: auto;
            }
            
            .branding-features {
                display: none;
            }
            
            .login-form-section {
                padding: 30px 20px;
            }
        }
        
        @media (max-width: 576px) {
            .login-card {
                border-radius: 0;
            }
            
            .login-card-body {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <!-- Left Side - Branding -->
        <div class="login-branding">
            <div class="branding-content">
                <div class="logo">
                    <i class="bi bi-airplane-engines"></i>
                </div>
                <h1>{{ config('app.name') }}</h1>
                <p>
                    @switch($guard ?? 'default')
                        @case('admin')
                            Secure administrative access to manage all platform operations.
                        @case('employee')
                            Employee portal for managing daily tasks and workflows.
                        @case('customer')
                            Your trusted partner for Umrah, Visa, and travel services in Saudi Arabia.
                        @default
                            Welcome back! Please login to continue.
                    @endswitch
                </p>
                
                <ul class="branding-features list-unstyled">
                    @if(($guard ?? 'default') === 'customer')
                        <li><i class="bi bi-shield-check"></i> Secure & encrypted data</li>
                        <li><i class="bi bi-credit-card"></i> Easy payment processing</li>
                        <li><i class="bi bi-headset"></i> 24/7 customer support</li>
                        <li><i class="bi bi-clock-history"></i> Real-time tracking</li>
                    @elseif(($guard ?? 'default') === 'employee')
                        <li><i class="bi bi-calendar-check"></i> Attendance tracking</li>
                        <li><i class="bi bi-file-earmark-text"></i> Document management</li>
                        <li><i class="bi bi-chat-dots"></i> Internal messaging</li>
                        <li><i class="bi bi-graph-up"></i> Performance analytics</li>
                    @else
                        <li><i class="bi bi-gear"></i> Full system control</li>
                        <li><i class="bi bi-people"></i> User management</li>
                        <li><i class="bi bi-bar-chart"></i> Analytics & reports</li>
                        <li><i class="bi bi-bell"></i> Notifications</li>
                    @endif
                </ul>
            </div>
        </div>
        
        <!-- Right Side - Login Form -->
        <div class="login-form-section">
            <div class="login-card">
                <div class="login-card-header">
                    <div class="icon">
                        @switch($guard ?? 'default')
                            @case('admin')
                                <i class="bi bi-shield-lock"></i>
                            @case('employee')
                                <i class="bi bi-briefcase"></i>
                            @case('customer')
                                <i class="bi bi-person-circle"></i>
                            @default
                                <i class="bi bi-airplane"></i>
                        @endswitch
                    </div>
                    <h3>
                        @switch($guard ?? 'default')
                            @case('admin') Admin Panel
                            @case('employee') Employee Portal
                            @case('customer') Customer Portal
                            @default {{ config('app.name') }}
                        @endswitch
                    </h3>
                    <p>
                        @switch($guard ?? 'default')
                            @case('admin') Sign in to admin dashboard
                            @case('employee') Sign in to employee portal
                            @case('customer') Sign in to your account
                            @default Welcome back
                        @endswitch
                    </p>
                </div>
                
                <div class="login-card-body">
                    <!-- Error/Success Messages -->
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <!-- Login Form -->
                    @switch($guard ?? 'default')
                        @case('admin')
                            <form method="POST" action="{{ route('admin.login.post') }}">
                            @break
                        @case('employee')
                            <form method="POST" action="{{ route('employee.login.post') }}">
                            @break
                        @case('customer')
                            <form method="POST" action="{{ route('portal.login.post') }}">
                            @break
                        @default
                            <form method="POST" action="{{ route('login') }}">
                    @endswitch
                        @csrf
                        
                        <div class="form-floating">
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="{{ old('email') }}" placeholder="Enter email" required autofocus>
                            <label for="email"><i class="bi bi-envelope me-2"></i>Email Address</label>
                        </div>
                        
                        <div class="form-floating">
                            <input type="password" class="form-control" id="password" name="password" 
                                   placeholder="Enter password" required>
                            <label for="password"><i class="bi bi-lock me-2"></i>Password</label>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Remember me</label>
                            </div>
                            <a href="#" class="forgot-link">Forgot Password?</a>
                        </div>
                        
                        <button type="submit" class="btn btn-login w-100">
                            <i class="bi bi-box-arrow-in-right"></i>
                            Sign In
                        </button>
                    </form>

                    <!-- Register Section (Only for Customer Portal) -->
                    @if(($guard ?? '') === 'customer')
                        <div class="register-section">
                            <p>Don't have an account?</p>
                            <a href="{{ route('portal.register') }}" class="btn-register">
                                <i class="bi bi-person-plus"></i>
                                Create Account
                            </a>
                        </div>
                    @endif
                    
                    <!-- Back to Website -->
                    <a href="{{ url('/' . app()->getLocale()) }}" class="back-home">
                        <i class="bi bi-house"></i>
                        Back to Website
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
