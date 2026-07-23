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
use App\Http\Controllers\CMS\PageController;
use App\Http\Controllers\Public\PublicController;
use Illuminate\Support\Facades\Route;

// Root redirect to default locale
Route::get('/', fn() => redirect('/' . config('app.locale')))->name('root');

// ADMIN ROUTES - Outside locale prefix (English only)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin,super_admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
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

    // Flight Requests
    Route::get('/flights', [FlightRequestController::class, 'index'])->name('flights.index');
    Route::get('/flights/create', [FlightRequestController::class, 'create'])->name('flights.create');
    Route::post('/flights', [FlightRequestController::class, 'store'])->name('flights.store');
    Route::get('/flights/{id}', [FlightRequestController::class, 'show'])->name('flights.show');

    // Umrah Packages
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

// LOCALIZED PUBLIC ROUTES — ALL public routes go inside this group
Route::prefix('{locale}')
    ->where(['locale' => 'bn|en|ar'])
    ->middleware(['web', 'setlocale'])
    ->group(function () {
        // CMS Pages - Catch-all route (MUST be last)
        Route::get('/', [PageController::class, 'show'])->name('home');
        Route::get('/{slug}', [PageController::class, 'show'])->where('slug', '.*')->name('page');

        // Preview route (admin only via middleware)
        Route::get('/{slug}/preview', [PageController::class, 'preview'])
            ->where('slug', '.*')
            ->name('page.preview')
            ->middleware('auth');

        // Temporary locale diagnostic page
        Route::get('/locale-test', fn() => view('temp.locale-test'))->name('locale.test');
    });

/*
|--------------------------------------------------------------------------
| NOTE: Auth Routes (Fortify + Passkeys + 2FA)
|--------------------------------------------------------------------------
|
| Fortify registers its own auth routes via config/fortify.php → 'routes => true'.
| These live at /login, /register, /logout, /user/confirmed-two-factor-authentication,
| /user/two-factor-authentication, etc. They are OUTSIDE the locale prefix.
|
| TO LOCALIZE AUTH ROUTES LATER:
|   1. Set Fortify config: 'routes' => false
|   2. Copy Fortify's route definitions into the {locale} group in web.php
|   3. Replace the hardcoded strings with __() translated versions
|   4. Update Fortify's view factories to load translated views
|   5. Localize the redirect URLs in FortifyServiceProvider
|
| Leave them outside the locale prefix for now.
|
|--------------------------------------------------------------------------
| NOTE: Portal Routes (routes/portal.php)
|--------------------------------------------------------------------------
|
| routes/portal.php exists with Route::prefix('portal') already defined.
| It is NOT loaded here to avoid Route::prefix('portal') × {locale} → /bn/portal/portal/...
|
| TO ENABLE LOCALIZED PORTAL ROUTES:
|   1. Remove the 'portal' prefix from routes/portal.php
|   2. Add the following inside the locale group:
|       Route::prefix('portal')->name('portal.')->group(base_path('routes/portal.php'));
|   3. Publish all portal controllers first (they don't exist yet)
|
| Leave portal routes unloaded for now.
|
*/

// PUBLIC ROUTES - Add inside locale prefix
Route::prefix('{locale}')
    ->where(['locale' => 'bn|en|ar'])
    ->middleware(['web', 'setlocale'])
    ->group(function () {
        Route::get('/', [PublicController::class, 'home'])->name('home');
        Route::get('/about', [PublicController::class, 'about'])->name('about');
        Route::get('/contact', [PublicController::class, 'contact'])->name('contact');
        Route::get('/faqs', [PublicController::class, 'faqs'])->name('faqs');
        Route::get('/careers', [PublicController::class, 'careers'])->name('careers');
        Route::get('/services', [PublicController::class, 'services'])->name('services');
        Route::get('/services/umrah', [PublicController::class, 'umrah'])->name('services.umrah');
        Route::get('/services/umrah/{slug}', [PublicController::class, 'umrahPackage'])->name('services.umrah.package');
        Route::get('/services/visa', [PublicController::class, 'visa'])->name('services.visa');
        Route::get('/services/visa/{slug}', [PublicController::class, 'visaService'])->name('services.visa.service');
        Route::get('/services/airticket', [PublicController::class, 'airticket'])->name('services.airticket');
        Route::get('/services/hotel', [PublicController::class, 'hotel'])->name('services.hotel');
        Route::get('/news', [PublicController::class, 'news'])->name('news');
        Route::get('/news/{slug}', [PublicController::class, 'newsDetail'])->name('news.detail');
        Route::get('/blog', [PublicController::class, 'blog'])->name('blog');
        Route::get('/blog/{slug}', [PublicController::class, 'blogDetail'])->name('blog.detail');
        Route::get('/labour-law', [PublicController::class, 'labourLaw'])->name('labour-law');
        Route::get('/labour-law/{slug}', [PublicController::class, 'labourLawDetail'])->name('labour-law.detail');
        Route::get('/visa-checker', [PublicController::class, 'visaChecker'])->name('visa-checker');
        Route::get('/track', [PublicController::class, 'track'])->name('track');
        Route::get('/appointment', [PublicController::class, 'appointment'])->name('appointment');
        Route::get('/privacy-policy', [PublicController::class, 'privacyPolicy'])->name('privacy-policy');
        Route::get('/terms', [PublicController::class, 'terms'])->name('terms');
        Route::get('/refund-policy', [PublicController::class, 'refundPolicy'])->name('refund-policy');
    });
