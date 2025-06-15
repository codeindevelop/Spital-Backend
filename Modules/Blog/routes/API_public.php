<?php

use Illuminate\Support\Facades\Route;
use Modules\Blog\App\Http\Controllers\Public\Categories\GetAllCategoriesController;
use Modules\Blog\App\Http\Controllers\Public\Categories\GetCategoryBySlugController;
use Modules\Blog\App\Http\Controllers\Public\Comments\GetCommentsByPostIdController;
use Modules\Blog\App\Http\Controllers\Public\Posts\GetAllPostsController;
use Modules\Blog\App\Http\Controllers\Public\Posts\GetPostByIdController;
use Modules\Blog\App\Http\Controllers\Public\Posts\GetPostBySlugController;
use Modules\Blog\App\Http\Controllers\Public\Posts\GetTrendingPostsController;
use Modules\Blog\App\Http\Controllers\Public\Posts\RedirectShortLinkController;
use Modules\Blog\App\Http\Controllers\Public\PublicPostController;

Route::prefix('v1/public/blog')->group(function () {
    // پست‌ها
    Route::get('/posts/all', GetAllPostsController::class)->name('public.posts.all');
    Route::get('/posts/id/{slug}', GetPostByIdController::class)->name('public.posts.by_id');
    Route::get('/posts/{slug}', GetPostBySlugController::class)->name('public.posts.by_slug');
    Route::get('/s/{code}', RedirectShortLinkController::class)->name('public.posts.short_link');
    Route::get('posts/trending', [GetTrendingPostsController::class, 'getTrendingPosts']);

    // دسته‌بندی‌ها
    Route::get('/categories/all', GetAllCategoriesController::class)->name('public.categories.all');
    Route::get('/categories/{slug}', GetCategoryBySlugController::class)->name('public.categories.by_slug');

    // کامنت‌ها
    Route::get('comments/post/{postId}', [PublicPostController::class, 'getCommentsByPostId']);


    Route::get('/comments/post/{postId}', GetCommentsByPostIdController::class)->name('public.comments.by_post');


});
