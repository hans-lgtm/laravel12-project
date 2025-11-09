<?php

namespace App\Providers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\URL;
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
        // Paksa semua URL jadi HTTPS di production (hilangkan warning "tidak aman")
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        // Buat symbolic link storage otomatis jika belum ada
        if (!file_exists(public_path('storage'))) {
            Artisan::call('storage:link');
        }
    }
}
