<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // <-- Tambahkan ini

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
        // Paksa Waktu Indonesia Barat (WIB)
        config(['app.timezone' => 'Asia/Jakarta']);
        date_default_timezone_set('Asia/Jakarta');

        // Set locale Indonesia untuk Carbon
        \Carbon\Carbon::setLocale('id');

        // Paksa HTTPS jika di Production (Railway)
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}