<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Create Account - {{ config('app.name') }}</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    
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
        }
        
        * { box-sizing: border-box; }
        
        body {
            min-height: 100vh;
            margin: 0;
            font-size: 15px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
        }
        
        .register-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }
        
        .register-card {
            width: 100%;
            max-width: 600px;
            background: white;
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
        }
        
        .register-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .register-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        }
        
        .register-header .icon {
            width: 80px;
            height: 80px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 36px;
            position: relative;
        }
        
        .register-header h2 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            position: relative;
        }
        
        .register-header p {
            margin: 10px 0 0;
            opacity: 0.9;
            font-size: 15px;
            position: relative;
        }
        
        .register-body {
            padding: 40px 30px;
        }
        
        .form-section {
            margin-bottom: 25px;
        }
        
        .form-section-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--accent);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .form-floating {
            margin-bottom: 18px;
        }
        
        .form-floating > .form-control,
        .form-floating > .form-select {
            border-radius: 12px;
            border: 2px solid #e2e8f0;
            padding: 20px 16px 8px;
            height: auto;
            min-height: 56px;
            transition: all 0.3s ease;
        }
        
        .form-floating > .form-control:focus,
        .form-floating > .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(0, 108, 53, 0.1);
        }
        
        .form-floating > label {
            padding: 16px;
        }
        
        .form-icon {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            z-index: 5;
        }
        
        .input-group-text {
            background: #f1f5f9;
            border: 2px solid #e2e8f0;
            border-right: none;
            color: #64748b;
        }
        
        .required-label::after {
            content: ' *';
            color: #dc2626;
        }
        
        .btn-register {
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 16px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
        }
        
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 108, 53, 0.3);
            color: white;
        }
        
        .terms-check {
            background: #f8fafc;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .terms-check a {
            color: var(--primary);
            text-decoration: none;
        }
        
        .terms-check a:hover {
            text-decoration: underline;
        }
        
        .login-section {
            text-align: center;
            padding-top: 25px;
            border-top: 1px solid #e2e8f0;
        }
        
        .login-section p {
            color: #64748b;
            margin-bottom: 10px;
        }
        
        .btn-login-link {
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
        
        .btn-login-link:hover {
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
        
        .form-hint {
            font-size: 12px;
            color: #94a3b8;
            margin-top: 4px;
        }
        
        .alert {
            border-radius: 12px;
            border: none;
        }
        
        .password-strength {
            height: 4px;
            border-radius: 2px;
            margin-top: 8px;
            background: #e2e8f0;
            overflow: hidden;
        }
        
        .password-strength-bar {
            height: 100%;
            width: 0;
            transition: all 0.3s ease;
        }
        
        .password-strength-bar.weak { width: 33%; background: #dc2626; }
        .password-strength-bar.medium { width: 66%; background: #f59e0b; }
        .password-strength-bar.strong { width: 100%; background: #16a34a; }
        
        @media (max-width: 576px) {
            .register-card {
                border-radius: 0;
            }
            
            .register-header,
            .register-body {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <div class="register-header">
                <div class="icon">
                    <i class="bi bi-person-plus"></i>
                </div>
                <h2>Create Account</h2>
                <p>Join {{ config('app.name') }} - Your trusted travel partner</p>
            </div>
            
            <div class="register-body">
                @if($errors->any())
                    <div class="alert alert-danger mb-4">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success mb-4">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('portal.register.post') }}">
                    @csrf
                    
                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="bi bi-person me-2"></i>Personal Information
                        </div>
                        
                        <div class="form-floating">
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="{{ old('name') }}" placeholder="Full Name" required>
                            <label for="name"><i class="bi bi-person me-2"></i>Full Name</label>
                        </div>
                        
                        <div class="form-floating">
                            <input type="tel" class="form-control" id="phone" name="phone" 
                                   value="{{ old('phone') }}" placeholder="Mobile Number" required>
                            <label for="phone"><i class="bi bi-phone me-2"></i>Mobile Number</label>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="bi bi-card-text me-2"></i>Identification
                        </div>
                        
                        <div class="form-floating">
                            <input type="text" class="form-control" id="iqama_no" name="iqama_no" 
                                   value="{{ old('iqama_no') }}" placeholder="Iqama Number" required
                                   pattern="[0-9]{10}" maxlength="10">
                            <label for="iqama_no"><i class="bi bi-credit-card-2-front me-2"></i>Iqama Number</label>
                            <div class="form-hint">Enter your 10-digit Iqama/Resident ID number</div>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="bi bi-lock me-2"></i>Account Credentials
                        </div>
                        
                        <div class="form-floating">
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="{{ old('email') }}" placeholder="Email Address" required>
                            <label for="email"><i class="bi bi-envelope me-2"></i>Email Address</label>
                        </div>
                        
                        <div class="form-floating">
                            <input type="password" class="form-control" id="password" name="password" 
                                   placeholder="Password" required minlength="8"
                                   oninput="checkPasswordStrength(this.value)">
                            <label for="password"><i class="bi bi-lock me-2"></i>Password</label>
                            <div class="password-strength">
                                <div class="password-strength-bar" id="passwordStrengthBar"></div>
                            </div>
                            <div class="form-hint">Minimum 8 characters with numbers and letters</div>
                        </div>
                        
                        <div class="form-floating">
                            <input type="password" class="form-control" id="password_confirmation" 
                                   name="password_confirmation" placeholder="Confirm Password" required>
                            <label for="password_confirmation"><i class="bi bi-lock-fill me-2"></i>Confirm Password</label>
                        </div>
                    </div>
                    
                    <div class="terms-check">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                            <label class="form-check-label" for="terms">
                                I agree to the <a href="{{ url('/' . app()->getLocale() . '/terms') }}">Terms of Service</a> 
                                and <a href="{{ url('/' . app()->getLocale() . '/privacy') }}">Privacy Policy</a>
                            </label>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-register">
                        <i class="bi bi-person-plus"></i>
                        Create Account
                    </button>
                </form>
                
                <div class="login-section">
                    <p>Already have an account?</p>
                    <a href="{{ route('portal.login') }}" class="btn-login-link">
                        <i class="bi bi-box-arrow-in-right"></i>
                        Sign In
                    </a>
                </div>
                
                <a href="{{ url('/' . app()->getLocale()) }}" class="back-home">
                    <i class="bi bi-house"></i>
                    Back to Website
                </a>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function checkPasswordStrength(password) {
            const bar = document.getElementById('passwordStrengthBar');
            let strength = 0;
            
            if (password.length >= 8) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^a-zA-Z0-9]/.test(password)) strength++;
            
            bar.className = 'password-strength-bar';
            if (strength < 3) {
                bar.classList.add('weak');
            } else if (strength < 5) {
                bar.classList.add('medium');
            } else {
                bar.classList.add('strong');
            }
        }
    </script>
</body>
</html>
