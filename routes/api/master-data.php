<?php

use App\Http\Controllers\Api\MasterData\MasterUnitController;
use Illuminate\Support\Facades\Route;

Route::prefix('settings')->group(function () {
    Route::prefix('unit')->group(function () {
        Route::get('/', [MasterUnitController::class, 'index']);
        // Route::get('/history', [MasterUnitController::class, 'history']);
    });
    Route::prefix('supplier')->group(function () {
        Route::get('/', [MasterSupplierController::class, 'index']);
        // Route::get('/history', [MasterUnitController::class, 'history']);
    });
});
