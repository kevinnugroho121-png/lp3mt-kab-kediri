<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Guru;
use App\Observers\GuruObserver;

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
        // Tempel baris ini di paling bawah dalam fungsi boot
        \App\Models\Guru::observe(\App\Observers\GuruObserver::class);
    }
}
