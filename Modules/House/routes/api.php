<?php

use Illuminate\Support\Facades\Route;
use Modules\House\Http\Controllers\HouseController;
use Modules\House\Http\Controllers\ResidentController;

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('houses')->name('houses.')->group(function () {
        Route::get('/stats', [HouseController::class, 'stats'])->name('stats');
        Route::get('/', [HouseController::class, 'index'])->name('index');
        Route::post('/', [HouseController::class, 'store'])->name('store');
        Route::get('/{house}', [HouseController::class, 'show'])->name('show');
        Route::patch('/{house}', [HouseController::class, 'update'])->name('update');
        Route::delete('/{house}', [HouseController::class, 'destroy'])->name('destroy');
        Route::post('/{house}/assign-resident', [HouseController::class, 'assignResident'])->name('assign-resident');
        Route::delete('/{house}/remove-resident', [HouseController::class, 'removeResident'])->name('remove-resident');
    });

    Route::prefix('residents')->name('residents.')->group(function () {
        Route::get('/', [ResidentController::class, 'index'])->name('index');
        Route::post('/', [ResidentController::class, 'store'])->name('store');
        Route::get('/{resident}', [ResidentController::class, 'show'])->name('show');
        Route::patch('/{resident}', [ResidentController::class, 'update'])->name('update');
    });
});
