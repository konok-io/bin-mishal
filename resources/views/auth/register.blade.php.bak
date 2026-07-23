<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - {{ config('app.name') }}</title>
    
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
        
        html[lang="bn"] body {
            font-family: 'BanglaFont', 'Hind Siliguri', 'EnglishFont', 'ArabicFont', sans-serif;
        }
        html[lang="ar"] body {
            font-family: 'ArabicFont', 'Noto Sans Arabic', 'EnglishFont', 'BanglaFont', sans-serif;
        }
        html[lang="en"] body {
            font-family: 'EnglishFont', 'Inter', 'BanglaFont', 'ArabicFont', sans-serif;
        }
        
        .register-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
        }
        .register-card {
            width: 100%;
            max-width: 450px;
            border: none;
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <div class="card">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h3><i class="bi bi-airplane"></i> {{ config('app.name') }}</h3>
                        <p class="text-muted">Create your account</p>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" 
                                   value="{{ old('name') }}" required autofocus>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" 
                                   value="{{ old('email') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" class="form-control" 
                                   value="{{ old('phone') }}" placeholder="+966 XX XXX XXXX">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-person-plus"></i> Create Account
                        </button>
                    </form>

                    <div class="text-center mt-3">
                        <p class="text-muted">Already have an account? 
                            <a href="{{ route('login') }}">Sign In</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
