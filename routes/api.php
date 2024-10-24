<?php

// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// use App\Http\Controllers\ProjectController;
// use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('get')->group(function () {

    Route::get('/users', [UserController::class, 'apiGet'])->name('api.get.users');
    // Route::get('/tasks', [TaskController::class, 'apiGet'])->name('api.get.tasks');
    // Route::get('/projects', [ProjectController::class, 'apiGet'])->name('api.get.projects');
    // Route::get('/tasks', [TaskController::class, 'apiGet'])->name('api.get.tasks');

    // Route::post('/get/tasks', [TaskController::class, 'store']);
    // Route::get('/get/tasks/{task}', [TaskController::class, 'show']);
    // Route::put('/get/tasks/{task}', [TaskController::class, 'update']);
    // Route::delete('/get/tasks/{task}', [TaskController::class, 'destroy']);
});