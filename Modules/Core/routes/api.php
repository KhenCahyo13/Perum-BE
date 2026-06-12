<?php

use Illuminate\Support\Facades\Route;
use Modules\Core\Http\Controllers\ReportController;

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/dashboard', [ReportController::class, 'dashboard'])->name('dashboard');
    });
});
