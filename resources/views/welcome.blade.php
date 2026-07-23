<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ is_rtl() ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ __('app.app_name') }} - {{ __('app.welcome') }}</title>
        
        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Noto+Sans+Arabic:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        
        <style>
            :root {
                --font-family: {{ locale_config()['font_family'] ?? "'Inter', 'Segoe UI', system-ui, sans-serif" }};
                --direction: {{ is_rtl() ? 'rtl' : 'ltr' }};
                --primary-color: #059669;
                --primary-dark: #047857;
                --text-color: #333;
                --text-muted: #6b7280;
                --bg-light: #f9fafb;
                --bg-dark: #1f2937;
            }
            
            * { margin: 0; padding: 0; box-sizing: border-box; }
            body { 
                font-family: var(--font-family); 
                line-height: 1.6; 
                color: var(--text-color); 
                direction: var(--direction);
            }
            .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
            header { background: #fff; border-bottom: 1px solid #eee; padding: 15px 0; }
            header .container { display: flex; justify-content: space-between; align-items: center; }
            {{ is_rtl() ? 'header nav { display: flex; align-items: center; }' : '' }}
            .logo { font-size: 24px; font-weight: bold; color: var(--primary-color); }
            nav a { {{ is_rtl() ? 'margin-right: 20px; margin-left: 0;' : 'margin-left: 20px;' }} text-decoration: none; color: var(--text-muted); }
            nav a.btn { background: var(--primary-color); color: #fff; padding: 8px 20px; border-radius: 5px; }
            .hero { background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)); color: #fff; padding: 80px 0; text-align: center; }
            .hero h1 { font-size: 48px; margin-bottom: 20px; }
            .hero p { font-size: 20px; opacity: 0.9; margin-bottom: 30px; }
            .hero .btn { display: inline-block; padding: 15px 40px; background: #fff; color: var(--primary-color); text-decoration: none; border-radius: 5px; font-weight: bold; margin: 0 10px; }
            .hero .btn-outline { background: transparent; border: 2px solid #fff; color: #fff; }
            .services { padding: 80px 0; background: var(--bg-light); }
            .services h2 { text-align: center; font-size: 36px; margin-bottom: 50px; }
            .services-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px; }
            .service-card { background: #fff; padding: 40px 30px; border-radius: 10px; text-align: center; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
            .service-card .icon { font-size: 60px; margin-bottom: 20px; }
            .service-card h3 { font-size: 22px; margin-bottom: 15px; color: #1f2937; }
            .service-card p { color: var(--text-muted); }
            .stats { padding: 60px 0; background: #fff; }
            .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; text-align: center; }
            .stat h3 { font-size: 48px; color: var(--primary-color); }
            .stat p { color: var(--text-muted); }
            footer { background: var(--bg-dark); color: #fff; padding: 40px 0; text-align: center; }
            footer p { opacity: 0.7; }
        </style>
    </head>
    <body>
        <header>
            <div class="container">
                <div class="logo">{{ __('app.app_name') }}</div>
                <nav>
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}">{{ __('navigation.login') }}</a>
                    @endif
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn">{{ __('navigation.register') }}</a>
                    @endif
                </nav>
            </div>
        </header>

        <section class="hero">
            <div class="container">
                <h1>{{ __('app.welcome') }} {{ __('app.app_name') }}</h1>
                <p>{{ __('app.welcome_message') ?? 'Your trusted partner for Umrah, Visa & Travel Services' }}</p>
                <div>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn">{{ __('navigation.book_now') }}</a>
                    @endif
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}" class="btn btn-outline">{{ __('navigation.login') }}</a>
                    @endif
                </div>
            </div>
        </section>

        <section class="services">
            <div class="container">
                <h2>{{ __('navigation.services') }}</h2>
                <div class="services-grid">
                    <div class="service-card">
                        <div class="icon">🕌</div>
                        <h3>{{ __('app.umrah') }} {{ __('app.package') }}s</h3>
                        <p>{{ __('app.umrah') }} packages with visa, accommodation & transport</p>
                    </div>
                    <div class="service-card">
                        <div class="icon">📋</div>
                        <h3>{{ __('app.visa_processing') }}</h3>
                        <p>Fast and reliable visa processing for Saudi Arabia</p>
                    </div>
                    <div class="service-card">
                        <div class="icon">✈️</div>
                        <h3>{{ __('app.flight_booking') }}</h3>
                        <p>Best deals on domestic and international flights</p>
                    </div>
                    <div class="service-card">
                        <div class="icon">🏨</div>
                        <h3>{{ __('app.hotel_booking') }}</h3>
                        <p>Premium hotels near Holy places in Makkah & Madinah</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="stats">
            <div class="container">
                <div class="stats-grid">
                    <div class="stat">
                        <h3>10+</h3>
                        <p>Years Experience</p>
                    </div>
                    <div class="stat">
                        <h3>5000+</h3>
                        <p>Happy Customers</p>
                    </div>
                    <div class="stat">
                        <h3>24/7</h3>
                        <p>Customer Support</p>
                    </div>
                </div>
            </div>
        </section>

        <footer>
            <div class="container">
                <p>&copy; {{ date('Y') }} {{ __('app.app_name') }}. All rights reserved.</p>
            </div>
        </footer>
    </body>
</html>
