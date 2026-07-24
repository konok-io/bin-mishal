<?php

use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\CityTVConnectController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FlightRequestController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\UmrahController;
use App\Http\Controllers\Admin\VisaController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CMS\PageController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\Public\PublicController;
use App\Http\Controllers\RssFeedController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

// Root redirect - check user's saved locale preference first
Route::get('/', function () {
    $savedLocale = session('locale') ?? \Illuminate\Support\Facades\Cookie::get('locale');
    $defaultLocale = config('app.locale', 'bn');
    $locale = ($savedLocale && in_array($savedLocale, ['bn', 'en', 'ar'])) ? $savedLocale : $defaultLocale;
    return redirect('/' . $locale);
})->name('root');

// =============================================================================
// AUTH ROUTES - Login pages for different user types
// =============================================================================

Route::middleware('guest')->group(function () {
    // Admin Login
    Route::get('/admin/login', fn() => view('auth.login', ['guard' => 'admin']))->name('admin.login');
    Route::post('/admin/login', [AuthenticatedSessionController::class, 'store'])->name('admin.login.post');
    
    // Customer Portal Login
    Route::prefix('{locale}/portal')->where(['locale' => 'bn|en|ar'])->name('portal.')->group(function () {
        Route::get('/login', fn() => view('auth.login', ['guard' => 'customer']))->name('login');
        Route::post('/login', [AuthenticatedSessionController::class, 'storeCustomer'])->name('login.post');
        Route::get('/register', fn() => view('auth.register', ['guard' => 'customer']))->name('register');
        Route::post('/register', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'store'])->name('register.post');
    });
    
    // Employee Login
    Route::prefix('{locale}/employee')->where(['locale' => 'bn|en|ar'])->name('employee.')->group(function () {
        Route::get('/login', fn() => view('auth.login', ['guard' => 'employee']))->name('login');
        Route::post('/login', [AuthenticatedSessionController::class, 'storeEmployee'])->name('login.post');
    });
});

// =============================================================================
// PUBLIC FEEDS & SEO ROUTES
// =============================================================================

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/feed/rss', [RssFeedController::class, 'index'])->name('feed.rss');
Route::get('/feed/atom', [RssFeedController::class, 'atom'])->name('feed.atom');

// =============================================================================
// NEWSLETTER ROUTES
// =============================================================================

Route::prefix('newsletter')->name('newsletter.')->group(function () {
    Route::post('/subscribe', [NewsletterController::class, 'subscribe'])->name('subscribe');
    Route::get('/verify/{token}', [NewsletterController::class, 'verify'])->name('verify');
    Route::get('/unsubscribe', [NewsletterController::class, 'unsubscribe'])->name('unsubscribe');
    Route::post('/unsubscribe', [NewsletterController::class, 'unsubscribe']);
    Route::get('/status', [NewsletterController::class, 'status'])->name('status');
});

// =============================================================================
// EMPLOYEE DASHBOARD ROUTES - Protected
// =============================================================================

Route::prefix('{locale}/employee')->name('employee.')->middleware(['auth:web', 'role:employee'])->group(function () {
    Route::get('/dashboard', [EmployeeController::class, 'dashboard'])->name('dashboard');
    Route::get('/payslips', [EmployeeController::class, 'payslips'])->name('payslips');
    Route::get('/payslips/{payroll}/download', [EmployeeController::class, 'downloadPayslip'])->name('payslip.download');
    Route::get('/attendance', [EmployeeController::class, 'attendance'])->name('attendance');
    Route::get('/leave', [EmployeeController::class, 'leave'])->name('leave');
    Route::get('/expenses', [EmployeeController::class, 'expenses'])->name('expenses');
    Route::post('/expenses', [EmployeeController::class, 'storeExpense'])->name('expenses.store');
});

// =============================================================================
// ADMIN ROUTES - Protected
// =============================================================================

Route::prefix('admin')->name('admin.')->middleware(['auth:web', 'role:admin,super_admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', fn() => view('admin.profile.index'))->name('profile');
    Route::get('/settings', fn() => view('admin.settings.index'))->name('settings.index');

    // CRUD Routes
    Route::resource('customers', CustomerController::class)->names(['index' => 'customers.index', 'create' => 'customers.create', 'store' => 'customers.store', 'show' => 'customers.show', 'edit' => 'customers.edit', 'update' => 'customers.update', 'destroy' => 'customers.destroy']);
    Route::resource('leads', LeadController::class)->names(['index' => 'leads.index', 'create' => 'leads.create', 'store' => 'leads.store', 'show' => 'leads.show', 'edit' => 'leads.edit', 'update' => 'leads.update', 'destroy' => 'leads.destroy']);
    Route::resource('bookings', BookingController::class)->names(['index' => 'bookings.index', 'create' => 'bookings.create', 'store' => 'bookings.store', 'show' => 'bookings.show', 'edit' => 'bookings.edit', 'update' => 'bookings.update', 'destroy' => 'bookings.destroy']);
    Route::resource('visas', VisaController::class)->names(['index' => 'visas.index', 'create' => 'visas.create', 'store' => 'visas.store', 'show' => 'visas.show', 'edit' => 'visas.edit', 'update' => 'visas.update', 'destroy' => 'visas.destroy']);
    Route::resource('flights', FlightRequestController::class)->names(['index' => 'flights.index', 'create' => 'flights.create', 'store' => 'flights.store', 'show' => 'flights.show', 'edit' => 'flights.edit', 'update' => 'flights.update', 'destroy' => 'flights.destroy']);
    Route::resource('umrah', UmrahController::class)->names(['index' => 'umrah.index', 'create' => 'umrah.create', 'store' => 'umrah.store', 'show' => 'umrah.show', 'edit' => 'umrah.edit', 'update' => 'umrah.update', 'destroy' => 'umrah.destroy']);
    Route::resource('invoices', InvoiceController::class)->names(['index' => 'invoices.index', 'create' => 'invoices.create', 'store' => 'invoices.store', 'show' => 'invoices.show', 'edit' => 'invoices.edit', 'update' => 'invoices.update', 'destroy' => 'invoices.destroy']);
    Route::resource('payments', PaymentController::class)->names(['index' => 'payments.index', 'create' => 'payments.create', 'store' => 'payments.store', 'show' => 'payments.show', 'edit' => 'payments.edit', 'update' => 'payments.update', 'destroy' => 'payments.destroy']);
    
    // City TV Connect - Branch Management
    Route::resource('city-tv-connect', CityTVConnectController::class)->names(['index' => 'city-tv-connect.index', 'create' => 'city-tv-connect.create', 'store' => 'city-tv-connect.store', 'show' => 'city-tv-connect.show', 'edit' => 'city-tv-connect.edit', 'update' => 'city-tv-connect.update', 'destroy' => 'city-tv-connect.destroy']);
    Route::get('/surveillance', [CityTVConnectController::class, 'cameras'])->name('city-tv-connect.cameras');
});


// City TV Connect - Branch Management

// Locale change route (for admin panel)
Route::post('/locale/change', [LocaleController::class, 'change'])->name('locale.change');

// =============================================================================
// PUBLIC ROUTES - Localized
// =============================================================================

Route::prefix('{locale}')
    ->where(['locale' => 'bn|en|ar'])
    ->middleware(['web', 'setlocale'])
    ->group(function () {
        // Homepage
        Route::get('/', [PublicController::class, 'home'])->name('home');
        
        // About & Contact
        Route::get('/about', [PublicController::class, 'about'])->name('about');
        Route::get('/contact', [PublicController::class, 'contact'])->name('contact');
        
        // FAQ & Legal Pages
        Route::get('/faqs', [PublicController::class, 'faqs'])->name('faqs');
        Route::get('/privacy-policy', [PublicController::class, 'privacyPolicy'])->name('privacy-policy');
        Route::get('/terms', [PublicController::class, 'terms'])->name('terms');
        Route::get('/refund-policy', [PublicController::class, 'refundPolicy'])->name('refund-policy');
        
        // Services
        Route::get('/services', [PublicController::class, 'services'])->name('services');
        Route::get('/services/umrah', [PublicController::class, 'umrah'])->name('services.umrah');
        Route::get('/services/umrah/{slug}', [PublicController::class, 'umrahPackage'])->name('services.umrah.package');
        Route::get('/services/visa', [PublicController::class, 'visa'])->name('services.visa');
        Route::get('/services/visa/{slug}', [PublicController::class, 'visaService'])->name('services.visa.service');
        Route::get('/services/airticket', [PublicController::class, 'airticket'])->name('services.airticket');
        Route::get('/services/hotel', [PublicController::class, 'hotel'])->name('services.hotel');
        
        // News & Blog
        Route::get('/news', [PublicController::class, 'news'])->name('news');
        Route::get('/news/{slug}', [PublicController::class, 'newsDetail'])->name('news.detail');
        Route::get('/blog', [PublicController::class, 'blog'])->name('blog');
        Route::get('/blog/{slug}', [PublicController::class, 'blogDetail'])->name('blog.detail');
        
        // Cargo
        Route::get('/cargo', [PublicController::class, 'cargo'])->name('cargo');
        Route::get('/cargo/track/{trackingNumber}', [PublicController::class, 'trackCargo'])->name('cargo.track');
        
        // Other Pages
        Route::get('/labour-law', [PublicController::class, 'labourLaw'])->name('labour-law');
        Route::get('/labour-law/{slug}', [PublicController::class, 'labourLawDetail'])->name('labour-law.detail');
        Route::get('/visa-checker', [PublicController::class, 'visaChecker'])->name('visa-checker');
        Route::get('/track', [PublicController::class, 'track'])->name('track');
        Route::get('/appointment', [PublicController::class, 'appointment'])->name('appointment');
        
        // Testimonials
        Route::get('/testimonials', [\App\Http\Controllers\Public\PublicController::class, 'testimonials'])->name('testimonials');
        
        // Careers
        Route::get('/careers', [\App\Http\Controllers\Public\PublicController::class, 'careers'])->name('careers');
        Route::get('/careers/{slug}', [\App\Http\Controllers\Public\PublicController::class, 'careerDetail'])->name('careers.detail');
        Route::post('/careers/{slug}/apply', [\App\Http\Controllers\Public\PublicController::class, 'careerApply'])->name('careers.apply');
        
        // CMS Pages - Catch-all (MUST be last)
        Route::get('/{slug}', [PageController::class, 'show'])->where('slug', '.*')->name('page');
        
        // Preview route (admin only)
        Route::get('/{slug}/preview', [PageController::class, 'preview'])
            ->where('slug', '.*')
            ->name('page.preview')
            ->middleware('auth');
    });

// =============================================================================
// EXTERNAL ROUTES
// =============================================================================

require __DIR__ . '/admin_cargo.php';

// Contact Form
Route::post('/contact/submit', [ContactController::class, 'submit'])->name('contact.submit');

// Search
Route::get('/search', [SearchController::class, 'results'])->name('search');
Route::get('/api/search', [SearchController::class, 'search'])->name('api.search');


// Include portal routes
require __DIR__.'/portal.php';
