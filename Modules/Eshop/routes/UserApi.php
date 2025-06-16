<?php


use Illuminate\Support\Facades\Route;


Route::prefix('v1/user/eshop')->group(function () {
    Route::middleware('auth:api')->group(function () {
        // پست‌ها
//        Route::get('/posts/all', GetAllPostsController::class)->name('admin.posts.all');

    });
});
