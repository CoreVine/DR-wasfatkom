<?php

namespace App\Providers;

use App\Models\Invoice;
use App\Observers\InvoiceObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();
        Invoice::observe(InvoiceObserver::class);

    }
}
