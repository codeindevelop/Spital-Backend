<?php

use Illuminate\Support\Facades\Route;
use Modules\Blog\App\Http\Controllers\Admin\AdminPostCommentController;
use Modules\Blog\App\Http\Controllers\Admin\Categories\CreateCategoryController;
use Modules\Blog\App\Http\Controllers\Admin\Categories\DeleteCategoryController;
use Modules\Blog\App\Http\Controllers\Admin\Categories\GetAllCategoriesController;
use Modules\Blog\App\Http\Controllers\Admin\Categories\GetCategoryByIdController;
use Modules\Blog\App\Http\Controllers\Admin\Categories\UpdateCategoryController;
use Modules\Blog\App\Http\Controllers\Admin\Posts\CreatePostController;
use Modules\Blog\App\Http\Controllers\Admin\Posts\DeletePostController;
use Modules\Blog\App\Http\Controllers\Admin\Posts\GenerateDemoPostsController;
use Modules\Blog\App\Http\Controllers\Admin\Posts\GetAllPostsController;
use Modules\Blog\App\Http\Controllers\Admin\Posts\GetPostByIdController;
use Modules\Blog\App\Http\Controllers\Admin\Posts\GetPostBySlugController;
use Modules\Blog\App\Http\Controllers\Admin\Posts\UpdatePostController;


Route::prefix('v1/admin/blog')->group(function () {
    Route::middleware('auth:api')->group(function () {
        // پست‌ها
        Route::get('/posts/all', GetAllPostsController::class)->name('admin.posts.all');
        Route::get('/posts/{id}', GetPostByIdController::class)->name('admin.posts.show');
        Route::get('/posts/slug/{slug}', GetPostBySlugController::class)->name('admin.posts.slug');
        Route::post('/posts/create', CreatePostController::class)->name('admin.posts.create');
        Route::put('/posts/{id}', UpdatePostController::class)->name('admin.posts.update');
        Route::delete('/posts/{id}', DeletePostController::class)->name('admin.posts.delete');
        Route::post('/posts/demo', GenerateDemoPostsController::class)->name('admin.posts.demo');

        // دسته‌بندی‌ها
        Route::get('/categories/all', GetAllCategoriesController::class)->name('admin.categories.all');
        Route::get('/categories/{id}', GetCategoryByIdController::class)->name('admin.categories.show');
        Route::post('/categories/create', CreateCategoryController::class)->name('admin.categories.create');
        Route::put('/categories/{id}', UpdateCategoryController::class)->name('admin.categories.update');
        Route::delete('/categories/{id}', DeleteCategoryController::class)->name('admin.categories.delete');

        // کامنت‌ها
        Route::get('comments/post/{postId}', [AdminPostCommentController::class, 'getCommentsByPostId']);
        Route::post('comments/create', [AdminPostCommentController::class, 'createComment']);
        Route::put('comments/{id}', [AdminPostCommentController::class, 'updateComment']);
        Route::delete('comments/{id}', [AdminPostCommentController::class, 'deleteComment']);
    });
});
