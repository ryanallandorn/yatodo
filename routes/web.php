<?php

// routes/web.php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Controllers
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\DocumentationController;
use Laravel\Jetstream\Http\Controllers\Inertia\UserProfileController;

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


    // Projects
    Route::resource('projects', ProjectController::class)
    ->except(['show']); // Exclude the default 'show' route
   // Route::post('projects/datatable', [ProjectController::class, 'getDatatableData'])->name('projects.datatable');
    // Route::get('projects/{project}/view', [ProjectController::class, 'show'])->name('projects.view');
    // Route::get('projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    // Use a single route for both the main project view and tab views
    Route::get('projects/{project}/{view?}', [ProjectController::class, 'show'])->name('projects.show');



    // Tasks
    Route::resource('tasks', taskController::class);
    //Route::post('tasks/datatable', [taskController::class, 'getDatatableData'])->name('tasks.datatable');
    Route::get('tasks/{task}/view', [taskController::class, 'show'])->name('tasks.view');
    Route::get('tasks/{task}/edit', [taskController::class, 'edit'])->name('tasks.edit');

    // Users
    Route::resource('users', UserController::class);
    

    // Notes
    Route::resource('notes', NoteController::class);
    

    // Route::get('todos', Todos::class);

    // Route::get('posts', ShowPosts::class);
    // Route::get('notes', ShowNotes::class);
    // Route::get('notes/create', CreateNote::class);


    // // Define the profile route with session handling
    // Route::get('/user/profile', function (Request $request) {
    //     $controller = new UserProfileController();

    //     // Fetch the sessions and add an ID if missing
    //     $sessions = $controller->sessions($request)->map(function ($session, $index) {
    //         $session->id = $session->id ?? $index; // Add ID if missing
    //         return $session;
    //     });

    //     // Render the Inertia view with the sessions
    //     return Inertia::render('Profile/Show', [
    //         'sessions' => $sessions,
    //     ]);
    // })->name('profile.show');


    Route::prefix('documentation')->name('documentation.')->group(function () {
        Route::get('/', [DocumentationController::class, 'index'])->name('index');
        Route::get('/{topic}', [DocumentationController::class, 'show'])->name('show');
    });


});