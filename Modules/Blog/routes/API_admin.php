<?php

use Illuminate\Support\Facades\Route;
use Modules\Blog\App\Http\Controllers\Admin\AdminPostCategoryController;
use Modules\Blog\App\Http\Controllers\Admin\AdminPostCommentController;
use Modules\Blog\App\Http\Controllers\Admin\AdminPostController;


Route::prefix('v1/admin/blog')->group(function () {
    Route::middleware('auth:api')->group(function () {
        // پست‌ها
        Route::get('posts/all', [AdminPostController::class, 'getAllPosts']);
        Route::get('posts/{id}', [AdminPostController::class, 'getPostById']);
        Route::post('posts/create', [AdminPostController::class, 'createPost']);
        Route::put('posts/{id}', [AdminPostController::class, 'updatePost']);
        Route::delete('posts/{id}', [AdminPostController::class, 'deletePost']);
        Route::post('posts/demo', [AdminPostController::class, 'generateDemoPosts']);

        // دسته‌بندی‌ها
        Route::get('categories/all', [AdminPostCategoryController::class, 'getAllCategories']);
        Route::get('categories/{id}', [AdminPostCategoryController::class, 'getCategoryById']);
        Route::post('categories/create', [AdminPostCategoryController::class, 'createCategory']);
        Route::put('categories/{id}', [AdminPostCategoryController::class, 'updateCategory']);
        Route::delete('categories/{id}', [AdminPostCategoryController::class, 'deleteCategory']);

        // کامنت‌ها
        Route::get('comments/post/{postId}', [AdminPostCommentController::class, 'getCommentsByPostId']);
        Route::post('comments/create', [AdminPostCommentController::class, 'createComment']);
        Route::put('comments/{id}', [AdminPostCommentController::class, 'updateComment']);
        Route::delete('comments/{id}', [AdminPostCommentController::class, 'deleteComment']);
    });
});
