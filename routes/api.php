<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\BookingController;
use App\Http\Controllers\Api\V1\CustomerController;
use App\Http\Controllers\Api\V1\DashboardController;
use App\Http\Controllers\Api\V1\FlightRequestController;
use App\Http\Controllers\Api\V1\InvoiceController;
use App\Http\Controllers\Api\V1\LeadController;
use App\Http\Controllers\Api\V1\MasterDataController;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\Api\V1\UmrahController;
use App\Http\Controllers\Api\V1\VisaController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    // Public Routes
    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('register', [AuthController::class, 'register']);
    });

    // Public Master Data
    Route::get('airlines', [MasterDataController::class, 'airlines']);
    Route::get('airports', [MasterDataController::class, 'airports']);
    Route::get('countries', [MasterDataController::class, 'countries']);
    Route::get('cities', [MasterDataController::class, 'cities']);
    Route::get('visa-types', [MasterDataController::class, 'visaTypes']);
    Route::get('umrah-packages', [UmrahController::class, 'index']);
    Route::get('umrah-packages/featured', [UmrahController::class, 'featured']);
    Route::get('umrah-packages/{id}', [UmrahController::class, 'show']);

    // Protected Routes
    Route::middleware('auth:sanctum')->group(function () {

        // Auth
        Route::prefix('auth')->group(function () {
            Route::post('logout', [AuthController::class, 'logout']);
            Route::get('user', [AuthController::class, 'user']);
            Route::put('profile', [AuthController::class, 'updateProfile']);
            Route::put('password', [AuthController::class, 'changePassword']);
        });

        // Dashboard
        Route::get('dashboard', [DashboardController::class, 'index']);
        Route::get('dashboard/recent-bookings', [DashboardController::class, 'recentBookings']);
        Route::get('dashboard/recent-payments', [DashboardController::class, 'recentPayments']);
        Route::get('dashboard/monthly-revenue', [DashboardController::class, 'monthlyRevenue']);
        Route::get('dashboard/booking-stats', [DashboardController::class, 'bookingStats']);
        Route::get('dashboard/payment-stats', [DashboardController::class, 'paymentStats']);

        // Customers
        Route::prefix('customers')->group(function () {
            Route::get('/', [CustomerController::class, 'index']);
            Route::post('/', [CustomerController::class, 'store']);
            Route::get('/{id}', [CustomerController::class, 'show']);
            Route::put('/{id}', [CustomerController::class, 'update']);
            Route::delete('/{id}', [CustomerController::class, 'destroy']);
            Route::get('/{id}/documents', [CustomerController::class, 'documents']);
            Route::get('/{id}/bookings', [CustomerController::class, 'bookings']);
            Route::get('/{id}/payments', [CustomerController::class, 'payments']);
        });

        // Bookings
        Route::prefix('bookings')->group(function () {
            Route::get('/', [BookingController::class, 'index']);
            Route::post('/', [BookingController::class, 'store']);
            Route::get('/stats', [BookingController::class, 'stats']);
            Route::get('/{id}', [BookingController::class, 'show']);
            Route::put('/{id}', [BookingController::class, 'update']);
            Route::post('/{id}/issue', [BookingController::class, 'issue']);
            Route::post('/{id}/cancel', [BookingController::class, 'cancel']);
            Route::post('/{id}/payment', [BookingController::class, 'addPayment']);
            Route::get('/{id}/passengers', [BookingController::class, 'passengers']);
            Route::put('/{bookingId}/passengers/{passengerId}', [BookingController::class, 'updatePassenger']);
        });

        // Flight Requests
        Route::prefix('flight-requests')->group(function () {
            Route::get('/', [FlightRequestController::class, 'index']);
            Route::post('/', [FlightRequestController::class, 'store']);
            Route::get('/{id}', [FlightRequestController::class, 'show']);
            Route::put('/{id}', [FlightRequestController::class, 'update']);
            Route::post('/{id}/assign', [FlightRequestController::class, 'assign']);
            Route::post('/{id}/cancel', [FlightRequestController::class, 'cancel']);
            Route::get('/{id}/quotes', [FlightRequestController::class, 'quotes']);
            Route::post('/{id}/quotes', [FlightRequestController::class, 'addQuote']);
        });

        // Visa Applications
        Route::prefix('visas')->group(function () {
            Route::get('/', [VisaController::class, 'index']);
            Route::get('/types', [VisaController::class, 'types']);
            Route::post('/', [VisaController::class, 'store']);
            Route::get('/stats', [VisaController::class, 'stats']);
            Route::get('/{id}', [VisaController::class, 'show']);
            Route::put('/{id}', [VisaController::class, 'update']);
            Route::post('/{id}/submit', [VisaController::class, 'submit']);
            Route::post('/{id}/approve', [VisaController::class, 'approve']);
            Route::post('/{id}/reject', [VisaController::class, 'reject']);
            Route::post('/{id}/deliver', [VisaController::class, 'deliver']);
            Route::get('/{id}/documents', [VisaController::class, 'documents']);
            Route::post('/{id}/documents', [VisaController::class, 'addDocument']);
            Route::post('/{id}/documents/{documentId}/verify', [VisaController::class, 'verifyDocument']);
            Route::get('/{id}/history', [VisaController::class, 'statusHistory']);
        });

        // Umrah Packages (Admin)
        Route::prefix('umrah')->group(function () {
            Route::post('/', [UmrahController::class, 'store']);
            Route::put('/{id}', [UmrahController::class, 'update']);
            Route::get('/search', [UmrahController::class, 'search']);
        });

        // Invoices
        Route::prefix('invoices')->group(function () {
            Route::get('/', [InvoiceController::class, 'index']);
            Route::post('/', [InvoiceController::class, 'store']);
            Route::get('/{id}', [InvoiceController::class, 'show']);
            Route::put('/{id}', [InvoiceController::class, 'update']);
            Route::post('/{id}/send', [InvoiceController::class, 'send']);
            Route::post('/{id}/payment', [InvoiceController::class, 'addPayment']);
            Route::get('/{id}/items', [InvoiceController::class, 'items']);
            Route::post('/{id}/items', [InvoiceController::class, 'addItem']);
            Route::post('/mark-overdue', [InvoiceController::class, 'markOverdue']);
            Route::get('/stats', [InvoiceController::class, 'stats']);
        });

        // Payments
        Route::prefix('payments')->group(function () {
            Route::get('/', [PaymentController::class, 'index']);
            Route::post('/', [PaymentController::class, 'store']);
            Route::get('/stats', [PaymentController::class, 'stats']);
            Route::get('/by-method', [PaymentController::class, 'byMethod']);
            Route::get('/{id}', [PaymentController::class, 'show']);
            Route::post('/{id}/complete', [PaymentController::class, 'complete']);
            Route::post('/{id}/verify', [PaymentController::class, 'verify']);
            Route::post('/{id}/fail', [PaymentController::class, 'fail']);
            Route::post('/{id}/refund', [PaymentController::class, 'refund']);
        });

        // Leads
        Route::prefix('leads')->group(function () {
            Route::get('/', [LeadController::class, 'index']);
            Route::post('/', [LeadController::class, 'store']);
            Route::get('/stats', [LeadController::class, 'stats']);
            Route::get('/{id}', [LeadController::class, 'show']);
            Route::put('/{id}', [LeadController::class, 'update']);
            Route::post('/{id}/convert', [LeadController::class, 'convert']);
            Route::post('/{id}/lost', [LeadController::class, 'markAsLost']);
            Route::get('/{id}/activities', [LeadController::class, 'activities']);
            Route::post('/{id}/activities', [LeadController::class, 'addActivity']);
        });

        // Branches
        Route::get('branches', [MasterDataController::class, 'branches']);
    });
});
