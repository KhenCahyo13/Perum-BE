<?php

use Illuminate\Support\Facades\Route;
use Modules\Bill\Http\Controllers\BillController;
use Modules\Bill\Http\Controllers\FeeTypeController;
use Modules\Bill\Http\Controllers\PaymentController;

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('fee-types')->name('fee-types.')->group(function () {
        Route::get('/', [FeeTypeController::class, 'index'])->name('index');
        Route::post('/', [FeeTypeController::class, 'store'])->name('store');
        Route::get('/{feeType}', [FeeTypeController::class, 'show'])->name('show');
        Route::patch('/{feeType}', [FeeTypeController::class, 'update'])->name('update');
        Route::delete('/{feeType}', [FeeTypeController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('bills')->name('bills.')->group(function () {
        Route::get('/stats', [BillController::class, 'stats'])->name('stats');
        Route::get('/', [BillController::class, 'index'])->name('index');
        Route::post('/', [BillController::class, 'store'])->name('store');
        Route::get('/{bill}', [BillController::class, 'show'])->name('show');
        Route::patch('/{bill}', [BillController::class, 'update'])->name('update');
        Route::delete('/{bill}', [BillController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('payments')->name('payments.')->group(function () {
        Route::post('/', [PaymentController::class, 'store'])->name('store');
        Route::delete('/{payment}', [PaymentController::class, 'destroy'])->name('destroy');
    });
});
