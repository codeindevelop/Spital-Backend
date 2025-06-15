<?php

use Illuminate\Support\Facades\Route;

use Modules\Blog\App\Http\Controllers\User\Comments\CreateCommentController;
use Modules\Blog\App\Http\Controllers\User\Posts\BookmarkPostController;
use Modules\Blog\App\Http\Controllers\User\Posts\LikePostController;
use Modules\Blog\App\Http\Controllers\User\Posts\UnbookmarkPostController;
use Modules\Blog\App\Http\Controllers\User\Posts\UnlikePostController;
use Modules\Blog\App\Http\Controllers\User\Questions\CreateQuestionController;
use Modules\Blog\App\Http\Controllers\User\UserPostController;

Route::prefix('v1/user/blog')->group(function () {
    Route::middleware('auth:api')->group(function () {

        // کامنت‌ها
        Route::post('/comments/create', CreateCommentController::class)->name('user.comments.create');

        // Create Question
        Route::post('/questions/create', CreateQuestionController::class)->name('user.questions.create');

        // Bookmark Post
        Route::post('/posts/{postId}/bookmark', BookmarkPostController::class)->name('user.posts.bookmark');
        Route::delete('/posts/{postId}/bookmark', UnbookmarkPostController::class)->name('user.posts.unbookmark');

        // Like Post
        Route::post('/posts/{postId}/like', LikePostController::class)->name('user.posts.like');
        Route::delete('/posts/{postId}/like', UnlikePostController::class)->name('user.posts.unlike');


    });
});
