<?php

use App\Http\Controllers\Admin\BookingController;
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
    Route::prefix('portal')->name('portal.')->group(function () {
        Route::get('/login', fn() => view('auth.login', ['guard' => 'customer']))->name('login');
        Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.post');
        Route::get('/register', fn() => view('auth.register', ['guard' => 'customer']))->name('register');
        Route::post('/register', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'store'])->name('register.post');
    });
    
    // Employee Login
    Route::prefix('employee')->name('employee.')->group(function () {
        Route::get('/login', fn() => view('auth.login', ['guard' => 'employee']))->name('login');
        Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.post');
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

    // Customers
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
    Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('/customers/{id}', [CustomerController::class, 'show'])->name('customers.show');
    Route::get('/customers/{id}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('/customers/{id}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{id}', [CustomerController::class, 'destroy'])->name('customers.destroy');

    // Bookings
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{id}', [BookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{id}/issue', [BookingController::class, 'issue'])->name('bookings.issue');
    Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');

    // Visas
    Route::get('/visas', [VisaController::class, 'index'])->name('visas.index');
    Route::get('/visas/create', [VisaController::class, 'create'])->name('visas.create');
    Route::post('/visas', [VisaController::class, 'store'])->name('visas.store');
    Route::get('/visas/{id}', [VisaController::class, 'show'])->name('visas.show');
    Route::post('/visas/{id}/submit', [VisaController::class, 'submit'])->name('visas.submit');
    Route::post('/visas/{id}/approve', [VisaController::class, 'approve'])->name('visas.approve');
    Route::post('/visas/{id}/reject', [VisaController::class, 'reject'])->name('visas.reject');
    Route::post('/visas/{id}/deliver', [VisaController::class, 'deliver'])->name('visas.deliver');

    // Flights
    Route::get('/flights', [FlightRequestController::class, 'index'])->name('flights.index');
    Route::get('/flights/create', [FlightRequestController::class, 'create'])->name('flights.create');
    Route::post('/flights', [FlightRequestController::class, 'store'])->name('flights.store');
    Route::get('/flights/{id}', [FlightRequestController::class, 'show'])->name('flights.show');

    // Umrah
    Route::get('/umrah', [UmrahController::class, 'index'])->name('umrah.index');
    Route::get('/umrah/create', [UmrahController::class, 'create'])->name('umrah.create');
    Route::post('/umrah', [UmrahController::class, 'store'])->name('umrah.store');
    Route::get('/umrah/{id}', [UmrahController::class, 'show'])->name('umrah.show');
    Route::get('/umrah/{id}/edit', [UmrahController::class, 'edit'])->name('umrah.edit');
    Route::put('/umrah/{id}', [UmrahController::class, 'update'])->name('umrah.update');

    // Leads
    Route::get('/leads', [LeadController::class, 'index'])->name('leads.index');
    Route::get('/leads/create', [LeadController::class, 'create'])->name('leads.create');
    Route::post('/leads', [LeadController::class, 'store'])->name('leads.store');
    Route::get('/leads/{id}', [LeadController::class, 'show'])->name('leads.show');
    Route::put('/leads/{id}', [LeadController::class, 'update'])->name('leads.update');
    Route::post('/leads/{id}/convert', [LeadController::class, 'convert'])->name('leads.convert');
    Route::post('/leads/{id}/activities', [LeadController::class, 'addActivity'])->name('leads.addActivity');

    // Invoices
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/create', [InvoiceController::class, 'create'])->name('invoices.create');
    Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('/invoices/{id}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::post('/invoices/{id}/send', [InvoiceController::class, 'send'])->name('invoices.send');

    // Payments
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/create', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('/payments/{id}', [PaymentController::class, 'show'])->name('payments.show');
    Route::post('/payments/{id}/complete', [PaymentController::class, 'complete'])->name('payments.complete');
    Route::post('/payments/{id}/refund', [PaymentController::class, 'refund'])->name('payments.refund');
});

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
