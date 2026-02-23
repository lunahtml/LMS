<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\AuthController;

Route::get('/health', function() {
    return response()->json(['status' => 'ok', 'message' => 'API is working']);
});

// Публичные маршруты
Route::post('/login', [AuthController::class, 'login']);

// Публичные курсы (для теста)
Route::get('/courses', [CourseController::class, 'index']);
Route::get('/courses/{id}', [CourseController::class, 'show']);

// Защищенные маршруты
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
});