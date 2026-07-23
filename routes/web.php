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
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Admin Routes (Protected)
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
