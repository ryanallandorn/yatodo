<?php

// routes/web.php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


use App\Http\Controllers\DocumentationController;

// Authenticated routes
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');









    Route::prefix('documentation')->name('documentation.')->group(function () {
        Route::get('/', [DocumentationController::class, 'index'])->name('index');
        Route::get('/{topic}', [DocumentationController::class, 'show'])->name('show');
    });


});