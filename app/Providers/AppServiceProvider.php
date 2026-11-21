<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Contracts\CustomerDataInterface;
use App\Contracts\CustomerAccountReportInterface;

use App\Services\CustomerDataService;
use App\Services\CustomerAccountReportService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register application services.
     */
    public function register(): void
    {
        $this->app->bind(
            CustomerDataInterface::class,
            CustomerDataService::class
        );

        $this->app->bind(
            CustomerAccountReportInterface::class,
            CustomerAccountReportService::class
        );
    }

    /**
     * Bootstrap application services.
     */
    public function boot(): void
    {
        //
    }
}