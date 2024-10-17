<?php

// app/Http/Middleware/InertiaBodyClasses.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InertiaBodyClasses
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Get the current route name
        $routeName = $request->route()?->getName();

        // Set default body classes
        $bodyClasses = 'font-sans antialiased FCUK'; 

        // Define body classes based on the route

        $authClasses = 'd-flex align-items-center py-4 bg-body-tertiary guest auth';

        //ray($routeName);

        switch ($routeName) {
            case 'login':
                $bodyClasses = $authClasses;
                break;
            case 'register':
                $bodyClasses = $authClasses;
                break;
            case 'dashboard':
                $bodyClasses = 'dashboard-page-class';
                break;
            default:
                $bodyClasses = 'font-sans antialiased';
        }

        // Share the body classes with Inertia
        Inertia::share('bodyClasses', $bodyClasses);

        return $next($request);
    }
}
