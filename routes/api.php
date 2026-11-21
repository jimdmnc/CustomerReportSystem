<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\CustomerAccountReportController;

// Customer API
Route::prefix('customers')->group(function () {

    Route::get('/', [CustomerController::class, 'index'])
        ->name('customers.index');

    Route::get('/{customerNumber}', [CustomerController::class, 'show'])
        ->name('customers.show');

});

// Customer Account Report API
Route::get(
    '/customer-account-reports',
    CustomerAccountReportController::class
)->name('customer-account-reports.generate');