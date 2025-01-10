<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ApiController; // Include additional controllers as needed

// Public routes
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']); // Optional: for user registration

// Protected routes (requires authentication)
Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
    
    // Example of additional protected routes
    Route::get('user-profile', [ApiController::class, 'userProfile']);
    Route::get('data', [ApiController::class, 'getData']); // Replace with actual methods
    Route::get('/getallleads', [ApiController::class, 'getAllLeads']);
    Route::get('/getnotinterested', [ApiController::class, 'getnotinterested']);
});
