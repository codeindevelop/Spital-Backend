<?php

use Illuminate\Support\Facades\Route;

use Modules\Blog\App\Http\Controllers\User\UserPostController;

Route::prefix('v1/user/blog')->group(function () {
    Route::middleware('auth:api')->group(function () {

        // کامنت‌ها
        Route::post('comments/create', [UserPostController::class, 'createComment']);

        // Create Question
        Route::post('posts/questions/create', [UserPostController::class, 'createQuestion']);

        // Bookmark Post
        Route::post('posts/{postId}/bookmark', [UserPostController::class, 'bookmarkPost']);
        Route::post('posts/{postId}/unbookmark', [UserPostController::class, 'unbookmarkPost']);

        // Like Post
        Route::post('posts/{postId}/like', [UserPostController::class, 'likePost']);
        Route::post('posts/{postId}/unlike', [UserPostController::class, 'unlikePost']);

    });
});
