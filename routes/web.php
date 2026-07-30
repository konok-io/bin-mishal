<?php

use App\Http\Controllers\ScanController;
use Illuminate\Support\Facades\Route;

// Public scanner routes (no authentication required)
Route::get('/', [ScanController::class, 'index'])->name('scanner.index');
Route::post('/upload', [ScanController::class, 'upload'])->name('scanner.upload');
Route::get('/scan/{sessionId}', [ScanController::class, 'edit'])->name('scanner.edit');
Route::post('/scan/{sessionId}/crop', [ScanController::class, 'crop'])->name('scanner.crop');
Route::post('/scan/{sessionId}/effect', [ScanController::class, 'applyEffect'])->name('scanner.effect');
Route::post('/scan/{sessionId}/thumbnail', [ScanController::class, 'generateThumbnail'])->name('scanner.thumbnail');
Route::post('/scan/{sessionId}/save', [ScanController::class, 'save'])->name('scanner.save');
Route::get('/scan/{sessionId}/download', [ScanController::class, 'download'])->name('scanner.download');
Route::post('/scan/merge-pdf', [ScanController::class, 'mergePdf'])->name('scanner.mergePdf');
