<?php

use App\Http\Controllers\Reports\CustomerSearchController;
use App\Http\Controllers\Reports\ReportPageController;
use App\Http\Controllers\Reports\InvoicePreviewController;
use Illuminate\Support\Facades\Route;

// Customers page
Route::view('/customers', 'customers')
    ->name('customers');

// Generate Reports
Route::prefix('generate-reports')->name('reports.')->group(function () {
    Route::get('/', ReportPageController::class)->name('index');

    Route::get('/customers/search', CustomerSearchController::class)
        ->name('customers.search');
    
    Route::get('/invoices', InvoicePreviewController::class)
        ->name('invoices.preview');  
});