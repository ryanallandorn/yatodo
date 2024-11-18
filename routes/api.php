<?php

// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\PersonalAccessToken;

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\SessionController;

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

Route::delete('/sessions/{id}', [SessionController::class, 'destroy'])->name('session.destroy');

Route::get('/api-tokens/{token}', function ($token) {
    try {
        // Basic validation
        if (!$token) {
            return response()->json(['error' => 'Token ID required'], 400);
        }

        $tokenModel = \App\Models\PersonalAccessToken::findOrFail($token);
        
        // Only return minimal necessary information
        return response()->json([
            'id' => $tokenModel->id,
            'name' => $tokenModel->name,
            'abilities' => $tokenModel->abilities,
            'last_used_at' => $tokenModel->last_used_at
        ]);

    } catch (\Exception $e) {
        return response()->json(['error' => 'Token not found'], 404);
    }
})->name('api-tokens.show');

Route::prefix('get')->group(function () {

    // Users
    Route::get('/users', [UserController::class, 'apiGetMultiple'])->name('api.get.users');

    // Projects
    Route::get('/projects', [ProjectController::class, 'apiGetMultiple'])->name('api.get.projects');
    Route::get('/projects/{id}', [ProjectController::class, 'apiGetSingle'])->name('api.get.project');

    // Tasks
    Route::get('/tasks', [TaskController::class, 'apiGetMultiple'])->name('api.get.tasks');
    Route::get('/task/{id}', [TaskController::class, 'apiGetSingle'])->name('api.get.task');

    // Notes
    Route::get('/notes', [NoteController::class, 'apiGetMultiple'])->name('api.get.notes');
    Route::get('/note/{id}', [NoteController::class, 'apiGetSingle'])->name('api.get.note');
    // Documentation


    // Route::post('/get/tasks', [TaskController::class, 'store']);
    // Route::get('/get/tasks/{task}', [TaskController::class, 'show']);
    // Route::put('/get/tasks/{task}', [TaskController::class, 'update']);
    // Route::delete('/get/tasks/{task}', [TaskController::class, 'destroy']);



});

Route::prefix('put')->group(function () {

    // mayb ehave array look on base / controler /task/ TaskCongtrolelr

    //
    Route::put('/task/{task}', [TaskController::class, 'update'])->name('api.update.task');
    Route::put('/task/{task}/{fieldName}', [TaskController::class, 'updateFieldSingle'])
    ->name('api.update.taskFieldSingle');

    //
    Route::put('/note/{note}', [NoteController::class, 'update'])->name('api.update.note');
    Route::put('/note/{note}/{fieldName}', [NoteController::class, 'updateFieldSingle'])
    ->name('api.update.noteFieldSingle');

});