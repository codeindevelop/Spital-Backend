<?php

use Illuminate\Support\Facades\Route;

use Modules\Blog\App\Http\Controllers\User\UserPostController;

Route::prefix('v1/user/blog')->group(function () {
    Route::middleware('auth:api')->group(function () {

        // کامنت‌ها
        Route::post('comments/create', [UserPostController::class, 'createComment']);

    });
});
