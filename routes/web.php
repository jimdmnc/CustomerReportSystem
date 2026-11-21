<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;


Route::redirect('/', '/customers');

Route::get('/customers', function () {return view('pages.customers');
})->name('customers');


Route::get('/generate-reports', [CustomerController::class, 'index'])
    ->name('generate-reports');

