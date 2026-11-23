<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CustomersController;


Route::redirect('/', '/customers');

Route::get('/customers', function () {return view('pages.customers');
})->name('customers');


Route::get('/generate-reports', [ReportController::class, 'index'])
    ->name('generate-reports');
Route::get('/customers', [CustomersController::class, 'index'])
    ->name('customers');


