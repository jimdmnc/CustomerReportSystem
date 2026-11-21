<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;


Route::redirect('/', '/customers');

Route::get('/customers', function () {
    return view('pages.customers');
})->name('customers');


Route::get('/generate-reports', function () {
    return view('pages.generate-reports');
})->name('generate-reports');

Route::get('/customers', [CustomerController::class, 'index'])->name('customers');