<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\UserController;

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


/**
 * Routes for user authentication.
 */
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);


Route::middleware('auth:api')->group(function () {

    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);

    Route::get('users', [UserController::class, 'index']);
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::post('users', [UserController::class, 'store']);
    Route::put('users/{id}', [UserController::class, 'update']);
    Route::delete('users/{id}', [UserController::class, 'destroy']);

    Route::put('/users/{id}/role', [UserController::class, 'updateUserRole']);
    Route::post('/users/{id}/restore', [UserController::class, 'restore']);
    Route::post('/users/{id}/force-delete', [UserController::class, 'forceDeleteUser']);


    Route::post('/tasks', [TaskController::class, 'store']);
    Route::get('/tasks', [TaskController::class, 'index']);
    Route::get('/tasks/{id}', [TaskController::class, 'show']);
    Route::post('tasks/{id}/assign', [TaskController::class, 'assign']);
    Route::put('tasks/{id}/status', [TaskController::class, 'updateStatus']);
    Route::put('/tasks/{id}', [TaskController::class, 'update']);
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);
    Route::put('/tasks/{id}/restore', [TaskController::class, 'restore']);
    Route::delete('/tasks/{id}/force-delete', [TaskController::class, 'forceDelete']);


});


