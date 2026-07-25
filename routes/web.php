<?php

use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\BiometricDeviceController;
use App\Http\Controllers\Admin\BlogPostController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\ChartOfAccountController;
use App\Http\Controllers\Admin\CityTVConnectController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmployeeControllerAdmin;
use App\Http\Controllers\Admin\ExpenseClaimController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\FlightRequestController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\JobApplicationController;
use App\Http\Controllers\Admin\JobPostingController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\LedgerController;
use App\Http\Controllers\Admin\LeaveRequestController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\Admin\NewsletterSubscriberController;
use App\Http\Controllers\Admin\NoticeController;
use App\Http\Controllers\Admin\PageControllerAdmin;
use App\Http\Controllers\Admin\SeoSettingController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\PayrollControllerAdmin;
use App\Http\Controllers\Admin\SocialLinkController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\TranslationController;
use App\Http\Controllers\Admin\UmrahController;
use App\Http\Controllers\Admin\VisaController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CMS\PageController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\EmployeeController;
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

    // User Management (Phase 18)
    Route::resource('users', \App\Http\Controllers\Admin\UsersController::class)->names([
        'index' => 'users.index',
        'create' => 'users.create',
        'store' => 'users.store',
        'show' => 'users.show',
        'edit' => 'users.edit',
        'update' => 'users.update',
        'destroy' => 'users.destroy',
    ]);
    Route::post('/users/{user}/suspend', [\App\Http\Controllers\Admin\UsersController::class, 'suspend'])->name('users.suspend');
    Route::post('/users/{user}/activate', [\App\Http\Controllers\Admin\UsersController::class, 'activate'])->name('users.activate');

    // Roles & Permissions (Phase 18)
    Route::resource('roles', \App\Http\Controllers\Admin\RolesController::class)->names([
        'index' => 'roles.index',
        'create' => 'roles.create',
        'store' => 'roles.store',
        'show' => 'roles.show',
        'edit' => 'roles.edit',
        'update' => 'roles.update',
        'destroy' => 'roles.destroy',
    ]);

    // CRUD Routes
    Route::resource('customers', CustomerController::class)->names(['index' => 'customers.index', 'create' => 'customers.create', 'store' => 'customers.store', 'show' => 'customers.show', 'edit' => 'customers.edit', 'update' => 'customers.update', 'destroy' => 'customers.destroy']);
    Route::resource('leads', LeadController::class)->names(['index' => 'leads.index', 'create' => 'leads.create', 'store' => 'leads.store', 'show' => 'leads.show', 'edit' => 'leads.edit', 'update' => 'leads.update', 'destroy' => 'leads.destroy']);
    Route::resource('bookings', BookingController::class)->names(['index' => 'bookings.index', 'create' => 'bookings.create', 'store' => 'bookings.store', 'show' => 'bookings.show', 'edit' => 'bookings.edit', 'update' => 'bookings.update', 'destroy' => 'bookings.destroy']);
    Route::post('bookings/{id}/issue', [BookingController::class, 'issue'])->name('bookings.issue');
    Route::post('bookings/{id}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::resource('visas', VisaController::class)->names(['index' => 'visas.index', 'create' => 'visas.create', 'store' => 'visas.store', 'show' => 'visas.show', 'edit' => 'visas.edit', 'update' => 'visas.update', 'destroy' => 'visas.destroy']);
    Route::resource('flights', FlightRequestController::class)->names(['index' => 'flights.index', 'create' => 'flights.create', 'store' => 'flights.store', 'show' => 'flights.show', 'edit' => 'flights.edit', 'update' => 'flights.update', 'destroy' => 'flights.destroy']);
    Route::resource('umrah', UmrahController::class)->names(['index' => 'umrah.index', 'create' => 'umrah.create', 'store' => 'umrah.store', 'show' => 'umrah.show', 'edit' => 'umrah.edit', 'update' => 'umrah.update', 'destroy' => 'umrah.destroy']);
    Route::resource('invoices', InvoiceController::class)->names(['index' => 'invoices.index', 'create' => 'invoices.create', 'store' => 'invoices.store', 'show' => 'invoices.show', 'edit' => 'invoices.edit', 'update' => 'invoices.update', 'destroy' => 'invoices.destroy']);
    Route::resource('payments', PaymentController::class)->names(['index' => 'payments.index', 'create' => 'payments.create', 'store' => 'payments.store', 'show' => 'payments.show', 'edit' => 'payments.edit', 'update' => 'payments.update', 'destroy' => 'payments.destroy']);
    Route::post('/payments/{payment}/refund', [PaymentController::class, 'refund'])->name('payments.refund');
    
    // City TV Connect - Branch Management
    Route::resource('city-tv-connect', CityTVConnectController::class)->names(['index' => 'city-tv-connect.index', 'create' => 'city-tv-connect.create', 'store' => 'city-tv-connect.store', 'show' => 'city-tv-connect.show', 'edit' => 'city-tv-connect.edit', 'update' => 'city-tv-connect.update', 'destroy' => 'city-tv-connect.destroy']);
    Route::get('/surveillance', [CityTVConnectController::class, 'cameras'])->name('city-tv-connect.cameras');

    // HR - Employees
    Route::resource('employees', EmployeeControllerAdmin::class);
    Route::resource('leave-requests', LeaveRequestController::class);
    Route::post('/leave-requests/{leave}/approve', [LeaveRequestController::class, 'approve'])->name('leave-requests.approve');
    Route::post('/leave-requests/{leave}/reject', [LeaveRequestController::class, 'reject'])->name('leave-requests.reject');
    Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('attendance/create', [AttendanceController::class, 'create'])->name('attendance.create');
    Route::post('attendance', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::put('attendance/{attendance}', [AttendanceController::class, 'update'])->name('attendance.update');
    Route::resource('payroll', PayrollControllerAdmin::class)->names(['index' => 'payroll.index', 'create' => 'payroll.create', 'store' => 'payroll.store', 'show' => 'payroll.show', 'edit' => 'payroll.edit', 'update' => 'payroll.update', 'destroy' => 'payroll.destroy']);
    Route::resource('biometric-devices', BiometricDeviceController::class)->names(['index' => 'biometric-devices.index', 'create' => 'biometric-devices.create', 'store' => 'biometric-devices.store', 'show' => 'biometric-devices.show', 'edit' => 'biometric-devices.edit', 'update' => 'biometric-devices.update', 'destroy' => 'biometric-devices.destroy']);

    // Accounting
    Route::resource('chart-of-accounts', ChartOfAccountController::class)->names(['index' => 'chart-of-accounts.index', 'create' => 'chart-of-accounts.create', 'store' => 'chart-of-accounts.store', 'show' => 'chart-of-accounts.show', 'edit' => 'chart-of-accounts.edit', 'update' => 'chart-of-accounts.update', 'destroy' => 'chart-of-accounts.destroy']);
    Route::get('chart-of-accounts/initialize', [ChartOfAccountController::class, 'initializeSystemAccounts'])->name('chart-of-accounts.initialize');
    Route::resource('ledger-entries', LedgerController::class)->names(['index' => 'ledger-entries.index', 'create' => 'ledger-entries.create', 'store' => 'ledger-entries.store', 'show' => 'ledger-entries.show', 'edit' => 'ledger-entries.edit', 'update' => 'ledger-entries.update', 'destroy' => 'ledger-entries.destroy']);
    Route::resource('expense-claims', ExpenseClaimController::class)->names(['index' => 'expense-claims.index', 'create' => 'expense-claims.create', 'store' => 'expense-claims.store', 'show' => 'expense-claims.show', 'edit' => 'expense-claims.edit', 'update' => 'expense-claims.update', 'destroy' => 'expense-claims.destroy']);

    // Recruitment
    Route::resource('job-postings', JobPostingController::class)->names(['index' => 'job-postings.index', 'create' => 'job-postings.create', 'store' => 'job-postings.store', 'show' => 'job-postings.show', 'edit' => 'job-postings.edit', 'update' => 'job-postings.update', 'destroy' => 'job-postings.destroy']);
    Route::resource('job-applications', JobApplicationController::class)->names(['index' => 'job-applications.index', 'create' => 'job-applications.create', 'store' => 'job-applications.store', 'show' => 'job-applications.show', 'edit' => 'job-applications.edit', 'update' => 'job-applications.update', 'destroy' => 'job-applications.destroy']);

    // CMS
    Route::resource('pages', PageControllerAdmin::class);
    Route::resource('menus', MenuController::class);
    Route::resource('blog-posts', BlogPostController::class);
    Route::resource('testimonials', TestimonialController::class);
    Route::resource('gallery', GalleryController::class);
    Route::resource('faqs', FaqController::class);

    // Support
    Route::resource('contact-messages', ContactMessageController::class)->only(['index', 'show', 'destroy']);
    Route::resource('newsletter-subscribers', NewsletterSubscriberController::class);
    Route::resource('comments', CommentController::class)->only(['index', 'update', 'destroy']);

    // Settings
    Route::resource('seo-settings', SeoSettingController::class);
    Route::resource('social-links', SocialLinkController::class);
    Route::resource('notices', NoticeController::class);
    Route::resource('translations', TranslationController::class);
    Route::resource('audit-logs', AuditLogController::class)->only(['index', 'show', 'destroy']);
    Route::get('/audit-logs/export', [AuditLogController::class, 'export'])->name('audit-logs.export');

    // Cargo Management
    Route::prefix('cargo')->name('cargo.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\Cargo\CargoController::class, 'dashboard'])->name('dashboard');
        Route::get('/all', [\App\Http\Controllers\Admin\Cargo\CargoController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\Cargo\CargoController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\Cargo\CargoController::class, 'store'])->name('store');
        Route::get('/{cargo}', [\App\Http\Controllers\Admin\Cargo\CargoController::class, 'show'])->name('show');
        Route::post('/{cargo}/status', [\App\Http\Controllers\Admin\Cargo\CargoController::class, 'updateStatus'])->name('status');
        Route::get('/{cargo}/invoice', [\App\Http\Controllers\Admin\Cargo\CargoController::class, 'invoice'])->name('invoice');
        Route::get('/{cargo}/label', [\App\Http\Controllers\Admin\Cargo\CargoController::class, 'label'])->name('label');
        Route::get('/export', [\App\Http\Controllers\Admin\Cargo\CargoController::class, 'export'])->name('export');

        // Cargo Types
        Route::get('/types', [\App\Http\Controllers\Admin\Cargo\CargoTypeController::class, 'index'])->name('types');
        Route::post('/types', [\App\Http\Controllers\Admin\Cargo\CargoTypeController::class, 'store'])->name('types.store');
        Route::put('/types/{type}', [\App\Http\Controllers\Admin\Cargo\CargoTypeController::class, 'update'])->name('types.update');
        Route::delete('/types/{type}', [\App\Http\Controllers\Admin\Cargo\CargoTypeController::class, 'destroy'])->name('types.destroy');

        // Cargo Packages
        Route::get('/packages', [\App\Http\Controllers\Admin\Cargo\CargoPackageController::class, 'index'])->name('packages');
        Route::post('/packages', [\App\Http\Controllers\Admin\Cargo\CargoPackageController::class, 'store'])->name('packages.store');
        Route::put('/packages/{package}', [\App\Http\Controllers\Admin\Cargo\CargoPackageController::class, 'update'])->name('packages.update');
        Route::delete('/packages/{package}', [\App\Http\Controllers\Admin\Cargo\CargoPackageController::class, 'destroy'])->name('packages.destroy');

        // Cargo Cities
        Route::get('/cities', [\App\Http\Controllers\Admin\Cargo\CargoCityController::class, 'index'])->name('cities');
        Route::post('/cities', [\App\Http\Controllers\Admin\Cargo\CargoCityController::class, 'store'])->name('cities.store');
        Route::put('/cities/{city}', [\App\Http\Controllers\Admin\Cargo\CargoCityController::class, 'update'])->name('cities.update');
        Route::delete('/cities/{city}', [\App\Http\Controllers\Admin\Cargo\CargoCityController::class, 'destroy'])->name('cities.destroy');

        // Cargo Zones
        Route::get('/cities/{city}/zones', [\App\Http\Controllers\Admin\Cargo\CargoZoneController::class, 'index'])->name('zones');
        Route::post('/zones', [\App\Http\Controllers\Admin\Cargo\CargoZoneController::class, 'store'])->name('zones.store');
        Route::put('/zones/{zone}', [\App\Http\Controllers\Admin\Cargo\CargoZoneController::class, 'update'])->name('zones.update');
        Route::delete('/zones/{zone}', [\App\Http\Controllers\Admin\Cargo\CargoZoneController::class, 'destroy'])->name('zones.destroy');

        // Cargo Coupons
        Route::get('/coupons', [\App\Http\Controllers\Admin\Cargo\CargoCouponController::class, 'index'])->name('coupons');
        Route::post('/coupons', [\App\Http\Controllers\Admin\Cargo\CargoCouponController::class, 'store'])->name('coupons.store');
        Route::put('/coupons/{coupon}', [\App\Http\Controllers\Admin\Cargo\CargoCouponController::class, 'update'])->name('coupons.update');
        Route::delete('/coupons/{coupon}', [\App\Http\Controllers\Admin\Cargo\CargoCouponController::class, 'destroy'])->name('coupons.destroy');

        // Cargo Pricing
        Route::get('/pricing', [\App\Http\Controllers\Admin\Cargo\CargoPricingController::class, 'index'])->name('pricing');
        Route::post('/pricing', [\App\Http\Controllers\Admin\Cargo\CargoPricingController::class, 'store'])->name('pricing.store');
        Route::put('/pricing/{pricing}', [\App\Http\Controllers\Admin\Cargo\CargoPricingController::class, 'update'])->name('pricing.update');
        Route::delete('/pricing/{pricing}', [\App\Http\Controllers\Admin\Cargo\CargoPricingController::class, 'destroy'])->name('pricing.destroy');
    });
});

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

// Contact Form
Route::post('/contact/submit', [ContactController::class, 'submit'])->name('contact.submit');

// Search
Route::get('/search', [SearchController::class, 'results'])->name('search');
Route::get('/api/search', [SearchController::class, 'search'])->name('api.search');


// Include portal routes
require __DIR__.'/portal.php';
