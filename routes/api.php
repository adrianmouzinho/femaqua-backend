<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ToolController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('/register', 'register');
        Route::post('/login', 'login');
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::apiResource('tools', ToolController::class)->except(['create', 'edit']);
    });
});
