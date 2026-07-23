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
            .service-card .icon { width: 60px; height: 60px; margin: 0 auto 20px; color: var(--primary-color); }
            .service-card h3 { font-size: 22px; margin-bottom: 15px; color: #1f2937; }
            .service-card p { color: var(--text-muted); }
            .stats { padding: 60px 0; background: #fff; }
            .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; text-align: center; }
            .stat h3 { font-size: 48px; color: var(--primary-color); font-weight: 700; }
            .stat p { color: var(--text-muted); font-size: 18px; }
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
                <p>{{ __('home.hero_subtitle') }}</p>
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
                        <div class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716 6.273M12 21a9.004 9.004 0 01-8.716-6.273m0 0A8.966 8.966 0 014.5 12c0 .905.11 1.786.317 2.63m2.22-4.078A8.966 8.966 0 0112 5.25c2.995 0 5.726 1.35 7.5 3.5m-3.5 0a8.985 8.985 0 01-3.5 0m0 0a8.966 8.966 0 01-3.5 0m3.5 0c0 1.905-.11 3.786-.317 5.63m2.22-4.078A8.966 8.966 0 0112 5.25c-2.995 0-5.726 1.35-7.5 3.5m0 0a8.985 8.985 0 013.5 0m-3.5 0a8.966 8.966 0 013.5 0" />
                            </svg>
                        </div>
                        <h3>{{ __('app.umrah') }} {{ __('app.packages') }}</h3>
                        <p>{{ __('home.umrah_service_description') }}</p>
                    </div>
                    <div class="service-card">
                        <div class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                        </div>
                        <h3>{{ __('app.visa_processing') }}</h3>
                        <p>{{ __('home.visa_service_description') }}</p>
                    </div>
                    <div class="service-card">
                        <div class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                            </svg>
                        </div>
                        <h3>{{ __('app.flight_booking') }}</h3>
                        <p>{{ __('home.flight_service_description') }}</p>
                    </div>
                    <div class="service-card">
                        <div class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                            </svg>
                        </div>
                        <h3>{{ __('app.hotel_booking') }}</h3>
                        <p>{{ __('home.hotel_service_description') }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="stats">
            <div class="container">
                <div class="stats-grid">
                    <div class="stat">
                        <h3>{{ __('home.years_experience_number') }}</h3>
                        <p>{{ __('home.years_experience') }}</p>
                    </div>
                    <div class="stat">
                        <h3>{{ __('home.happy_customers_number') }}</h3>
                        <p>{{ __('home.happy_customers') }}</p>
                    </div>
                    <div class="stat">
                        <h3>{{ __('home.support_hours') }}</h3>
                        <p>{{ __('home.customer_support') }}</p>
                    </div>
                </div>
            </div>
        </section>

        <footer>
            <div class="container">
                <p>&copy; {{ date('Y') }} {{ __('app.app_name') }}. {{ __('common.all_rights_reserved') }}</p>
            </div>
        </footer>
    </body>
</html>
