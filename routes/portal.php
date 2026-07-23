<?php

use App\Http\Controllers\Portal\DashboardController;
use App\Http\Controllers\Portal\BookingController;
use App\Http\Controllers\Portal\DocumentController;
use App\Http\Controllers\Portal\AppointmentController;
use App\Http\Controllers\Portal\PaymentController;
use App\Http\Controllers\Portal\ProfileController;
use App\Http\Controllers\Portal\VisaController;
use Illuminate\Support\Facades\Route;

// Customer Portal Routes (Authenticated Customers)
Route::prefix('portal')->name('portal.')->middleware(['auth', 'role:customer'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Documents
    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::get('/documents/create', [DocumentController::class, 'create'])->name('documents.create');
    Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
    Route::get('/documents/{id}', [DocumentController::class, 'show'])->name('documents.show');

    // Bookings
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{id}', [BookingController::class, 'show'])->name('bookings.show');

    // Appointments
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::get('/appointments/{id}', [AppointmentController::class, 'show'])->name('appointments.show');

    // Payments
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/{id}', [PaymentController::class, 'show'])->name('payments.show');

    // Visas
    Route::get('/visas', [VisaController::class, 'index'])->name('visas.index');
    Route::get('/visas/create', [VisaController::class, 'create'])->name('visas.create');
    Route::post('/visas', [VisaController::class, 'store'])->name('visas.store');
    Route::get('/visas/{id}', [VisaController::class, 'show'])->name('visas.show');
});
