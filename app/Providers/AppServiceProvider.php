<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // <--- 1. ADD THIS LINE HERE

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
        // 2. ADD THIS BLOCK HERE
        if($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
