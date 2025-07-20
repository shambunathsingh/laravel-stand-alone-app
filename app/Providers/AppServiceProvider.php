<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

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
        // Dynamically load views and migrations from all modules
        $modulesPath = app_path('Modules');

        foreach (glob($modulesPath . '/*') as $modulePath) {
            $moduleName = basename($modulePath);

            // Register views
            $viewsPath = $modulePath . '/Views';
            if (is_dir($viewsPath)) {
                $this->loadViewsFrom($viewsPath, $moduleName);
            }

            // Register migrations
            $migrationsPath = $modulePath . '/Database/Migrations';
            if (is_dir($migrationsPath)) {
                $this->loadMigrationsFrom($migrationsPath);
            }
        }
    }
}
