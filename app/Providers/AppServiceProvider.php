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
        // [OTOMATISASI NGROK - ANTI EFEK SAMPING]
        // Sistem hanya akan mengunci URL jika mendeteksi header kiriman dari terowongan Ngrok
        if (isset($_SERVER['HTTP_X_FORWARDED_HOST']) || isset($_SERVER['HTTP_X_ORIGINAL_HOST'])) {
            
            if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
                \URL::forceScheme('https');
            }
            
            if (isset($_SERVER['HTTP_X_ORIGINAL_HOST'])) {
                \URL::forceRootUrl('https://' . $_SERVER['HTTP_X_ORIGINAL_HOST']);
            } else {
                \URL::forceRootUrl('https://' . $_SERVER['HTTP_X_FORWARDED_HOST']);
            }
            
        }
        
        Guru::observe(GuruObserver::class);
        
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            header("ngrok-skip-browser-warning: 1");
        }
    }
}
