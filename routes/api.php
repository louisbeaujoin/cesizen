<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\BreathingApiController;
use App\Http\Controllers\Api\InformationApiController;

Route::post('/login', [AuthApiController::class, 'login']);
Route::post('/register', [AuthApiController::class, 'register']);
Route::get('/exercises', [BreathingApiController::class, 'index']);
Route::get('/information', [InformationApiController::class, 'index']);
Route::get('/information/{slug}', [InformationApiController::class, 'show']);

Route::middleware('api.token')->group(function () {
    Route::post('/logout', [AuthApiController::class, 'logout']);
    Route::get('/profile', [AuthApiController::class, 'profile']);
    Route::put('/profile', [AuthApiController::class, 'updateProfile']);
    Route::put('/profile/password', [AuthApiController::class, 'updatePassword']);
    Route::post('/sessions', [BreathingApiController::class, 'saveSession']);
});
