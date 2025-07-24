<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Xendit\Xendit;

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

    public function boot()
    {
        Xendit::setApiKey(env('XENDIT_API_KEY'));
    }
}
