<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'ziggy' => fn () => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
            'appName' => config('app.name'),
            'csrfToken' => csrf_token(),
            'auth.user' => fn () => $request->user()
                ? $request->user()->only('id', 'name', 'email')
                : null,
            'flash' => [
                'message' => fn () => $request->session()->get('message')
            ],
            'translations' => function () {
                return [
                    'Manage Account' => __('messages.Manage Account'),
                    'Profile' => __('messages.Profile'),
                    'API Tokens' => __('messages.API Tokens'),
                    // Add other translation keys as needed
                ];
            },
        ];
    }
}
