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
        if (is_dir('/tmp') || PHP_OS_FAMILY !== 'Windows') {
            try {
                $dbPath = config('database.connections.sqlite.database');
                if ($dbPath && is_string($dbPath) && !file_exists($dbPath)) {
                    @touch($dbPath);
                }
                if (!\Illuminate\Support\Facades\Schema::hasTable('balance_packages')) {
                    \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
                    \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
                }
            } catch (\Throwable $e) {
                // Fail-safe migration check
            }
        }
    }



}
