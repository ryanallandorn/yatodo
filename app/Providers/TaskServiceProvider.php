<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class TaskServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        // Register task-specific services or bindings if necessary
        $this->app->singleton('TaskService', function ($app) {
            return new \App\Services\TaskService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        // // Load task-specific routes into existing API routes
        // Route::middleware('api')
        //     ->prefix('api')
        //     ->group(function () {
        //         $this->loadRoutesFrom(__DIR__.'/../Routes/api/tasks.php');
        //     });
    }
}
