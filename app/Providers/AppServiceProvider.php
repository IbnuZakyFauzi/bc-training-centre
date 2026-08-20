<?php

namespace App\Providers;

use App\Models\OjtLogbook;
use App\Observers\OjtLogbookObserver;
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
        OjtLogbook::observe(OjtLogbookObserver::class);
    }
}
