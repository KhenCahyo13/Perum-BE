<?php

use Illuminate\Support\Facades\Route;
use Modules\Bill\Http\Controllers\BillController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('bills', BillController::class)->names('bill');
});
