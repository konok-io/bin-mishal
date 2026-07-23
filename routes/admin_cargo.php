<?php

use App\Http\Controllers\Admin\Cargo\CargoController;
use App\Http\Controllers\Admin\Cargo\CargoTypeController;
use App\Http\Controllers\Admin\Cargo\CargoPackageController;
use App\Http\Controllers\Admin\Cargo\CargoCityController;
use App\Http\Controllers\Admin\Cargo\CargoZoneController;
use App\Http\Controllers\Admin\Cargo\CargoCouponController;
use App\Http\Controllers\Admin\Cargo\CargoPricingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Cargo Management Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('cargo')
    ->name('admin.cargo.')
    ->middleware(['auth:web', 'role:admin,super_admin'])
    ->group(function () {
    
    // Dashboard
    Route::get('/', [CargoController::class, 'dashboard'])->name('dashboard');
    Route::get('/index', [CargoController::class, 'index'])->name('index');
    Route::get('/create', [CargoController::class, 'create'])->name('create');
    Route::post('/', [CargoController::class, 'store'])->name('store');
    Route::get('/{cargo}', [CargoController::class, 'show'])->name('show');
    Route::post('/{cargo}/status', [CargoController::class, 'updateStatus'])->name('status');
    Route::get('/{cargo}/invoice', [CargoController::class, 'invoice'])->name('invoice');
    Route::get('/{cargo}/label', [CargoController::class, 'label'])->name('label');
    Route::get('/export', [CargoController::class, 'export'])->name('export');

    // Cargo Types
    Route::get('/types', [CargoTypeController::class, 'index'])->name('types');
    Route::post('/types', [CargoTypeController::class, 'store'])->name('types.store');
    Route::put('/types/{type}', [CargoTypeController::class, 'update'])->name('types.update');
    Route::delete('/types/{type}', [CargoTypeController::class, 'destroy'])->name('types.destroy');

    // Cargo Packages
    Route::get('/packages', [CargoPackageController::class, 'index'])->name('packages');
    Route::post('/packages', [CargoPackageController::class, 'store'])->name('packages.store');
    Route::put('/packages/{package}', [CargoPackageController::class, 'update'])->name('packages.update');
    Route::delete('/packages/{package}', [CargoPackageController::class, 'destroy'])->name('packages.destroy');

    // Cargo Cities
    Route::get('/cities', [CargoCityController::class, 'index'])->name('cities');
    Route::post('/cities', [CargoCityController::class, 'store'])->name('cities.store');
    Route::put('/cities/{city}', [CargoCityController::class, 'update'])->name('cities.update');
    Route::delete('/cities/{city}', [CargoCityController::class, 'destroy'])->name('cities.destroy');

    // Cargo Zones
    Route::get('/cities/{city}/zones', [CargoZoneController::class, 'index'])->name('zones');
    Route::post('/zones', [CargoZoneController::class, 'store'])->name('zones.store');
    Route::put('/zones/{zone}', [CargoZoneController::class, 'update'])->name('zones.update');
    Route::delete('/zones/{zone}', [CargoZoneController::class, 'destroy'])->name('zones.destroy');

    // Cargo Coupons
    Route::get('/coupons', [CargoCouponController::class, 'index'])->name('coupons');
    Route::post('/coupons', [CargoCouponController::class, 'store'])->name('coupons.store');
    Route::put('/coupons/{coupon}', [CargoCouponController::class, 'update'])->name('coupons.update');
    Route::delete('/coupons/{coupon}', [CargoCouponController::class, 'destroy'])->name('coupons.destroy');

    // Cargo Pricing
    Route::get('/pricing', [CargoPricingController::class, 'index'])->name('pricing');
    Route::post('/pricing', [CargoPricingController::class, 'store'])->name('pricing.store');
    Route::put('/pricing/{pricing}', [CargoPricingController::class, 'update'])->name('pricing.update');
    Route::delete('/pricing/{pricing}', [CargoPricingController::class, 'destroy'])->name('pricing.destroy');
});
