<?php

use Illuminate\Support\Facades\Route;
use Modules\Blog\App\Http\Controllers\Public\PublicPostController;

Route::prefix('v1/public/blog')->group(function () {
    // پست‌ها
    Route::get('posts/all', [PublicPostController::class, 'getAllPosts']);
    Route::get('posts/{id}', [PublicPostController::class, 'getPostByID']);
    Route::get('/s/{code}', [PublicPostController::class, 'redirectShortLink']);
    Route::get('posts/trending', [PublicPostController::class, 'getTrendingPosts']);

    // دسته‌بندی‌ها
    Route::get('categories/all', [PublicPostController::class, 'getAllCategories']);
    Route::get('categories/{slug}', [PublicPostController::class, 'getCategoryBySlug']);

    // کامنت‌ها
    Route::get('comments/post/{postId}', [PublicPostController::class, 'getCommentsByPostId']);



});
