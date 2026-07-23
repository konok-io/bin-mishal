<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - {{ config('app.name') }}</title>
    
    <!-- Custom Fonts -->
    <style>
        @font-face {
            font-family: 'Bangla';
            src: url('/fonts/bangla.ttf') format('truetype');
        }
        @font-face {
            font-family: 'English';
            src: url('/fonts/English.ttf') format('truetype');
        }
        @font-face {
            font-family: 'Arabic';
            src: url('/fonts/Arabic.ttf') format('truetype');
        }
        
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
        
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            border: none;
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="card">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h3><i class="bi bi-airplane"></i> {{ config('app.name') }}</h3>
                        <p class="text-muted">Sign in to your account</p>
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

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" 
                                   value="{{ old('email') }}" required autofocus>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" name="remember" class="form-check-input" id="remember">
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-box-arrow-in-right"></i> Sign In
                        </button>
                    </form>

                    <div class="text-center mt-3">
                        <p class="text-muted">Don't have an account? 
                            <a href="{{ route('register') }}">Register</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
