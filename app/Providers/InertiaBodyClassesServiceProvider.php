<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Illuminate\Http\Request;

class InertiaBodyClassesServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Typically left empty unless binding services
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Inertia::share([
            'bodyClasses' => function (Request $request) {
                $routeName = $request->route()?->getName();

                // Default body classes
                $bodyClasses = 'font-sans antialiased'; 

                // Define body classes based on the route
                $authClasses = 'd-flex align-items-center py-4 bg-body-tertiary guest auth';

                switch ($routeName) {
                    case 'login':
                    case 'register':
                        $bodyClasses = $authClasses;
                        break;
                    case 'dashboard':
                        $bodyClasses = 'dashboard-page-class';
                        break;
                    default:
                        $bodyClasses = 'font-sans antialiased';
                }

                return $bodyClasses;
            },
            // Add more shared data related to body classes if needed
        ]);
    }
}
