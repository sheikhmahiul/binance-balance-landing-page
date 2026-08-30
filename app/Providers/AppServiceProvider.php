<?php

namespace App\Providers;

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
        if (is_dir('/tmp') || isset($_ENV['VERCEL']) || getenv('VERCEL') || isset($_SERVER['VERCEL'])) {
            try {
                if (!\Illuminate\Support\Facades\Schema::hasTable('balance_packages')) {
                    \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
                    \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
                }
            } catch (\Throwable $e) {
                // Ignore migration errors if already initialized
            }
        }
    }


}
