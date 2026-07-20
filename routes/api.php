<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WeatherController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::prefix('posts')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [PostController::class, 'index']);
    Route::post('/', [PostController::class, 'store']);
    Route::get('/{post}', [PostController::class, 'show']);
    Route::patch('/{post}', [PostController::class, 'update']);
    Route::delete('/{post}', [PostController::class, 'destroy']);
});

Route::prefix('users')->middleware('auth:sanctum')->group(function () {
    Route::get('/{user}', [UserController::class, 'show']);
});

Route::get('/weathers', [WeatherController::class, 'index']);
