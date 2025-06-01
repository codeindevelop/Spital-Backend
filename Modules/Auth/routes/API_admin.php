<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Auth\App\Http\Controllers\admin\auth\AdminAuthController;
use Modules\Auth\App\Http\Controllers\user\SignupEmail\SignupByEmailController;


Route::prefix('v1/admin')->group(function () {

    Route::post('login-managers', [AdminAuthController::class, 'adminLogin']);
    Route::get('profile', [AdminAuthController::class, 'adminProfile']);
    Route::post('create-user', [AdminAuthController::class, 'adminCreateUser']);
    Route::put('users/{id}', [AdminAuthController::class, 'update']);
    Route::put('users/{id}/suspend', [AdminAuthController::class, 'suspendUser']);
    Route::put('users/{id}/verify', [AdminAuthController::class, 'verifyUser']);
    Route::get('users', [AdminAuthController::class, 'getAllUsers']);
    Route::get('users/{id}', [AdminAuthController::class, 'getUserById']);
    Route::get('users/trashed', [AdminAuthController::class, 'getTrashedUsers']);
    Route::post('users/{id}/restore', [AdminAuthController::class, 'restoreTrashedUsers']);
    Route::delete('users/{id}', [AdminAuthController::class, 'destroy']);


});


