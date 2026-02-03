<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Contracts\ImageOptimizationServiceInterface;
use App\Services\ImageOptimizationService;

class ImageOptimizationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register the ImageOptimizationService as scoped
        // This means a new instance will be created for each request
        $this->app->scoped(
            ImageOptimizationServiceInterface::class,
            ImageOptimizationService::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
