<?php

use Illuminate\Support\Facades\Route;
use Modules\Bill\Http\Controllers\BillController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('bills', BillController::class)->names('bill');
});
