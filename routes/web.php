<?php

use App\Http\Controllers\Reports\CustomerSearchController;
use App\Http\Controllers\Reports\InvoicePreviewController;
use App\Http\Controllers\Reports\GenerateReportPdfController;
use App\Http\Controllers\Reports\SendReportEmailController;
use Illuminate\Support\Facades\Route;

// API / Data Endpoints (Keep these above the catch-all route)
Route::prefix('generate-reports')->name('reports.')->group(function () {
    Route::get('/customers/search', CustomerSearchController::class)
        ->name('customers.search');
    
    Route::get('/invoices', InvoicePreviewController::class)
        ->name('invoices.preview');  
    
    Route::get('/pdf', GenerateReportPdfController::class)
        ->name('pdf');

    Route::post('/email', SendReportEmailController::class)
        ->middleware('throttle:10,1')
        ->name('email');
}); // <-- The generate-reports group MUST close here

// Catch-all route: MUST be outside the group at the very bottom
Route::get('/{any?}', function () {
    return view('app'); // Make sure 'app.blade.php' exists in resources/views/
})->where('any', '.*');
