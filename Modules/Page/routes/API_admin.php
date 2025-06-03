<?php

use Illuminate\Support\Facades\Route;
use Modules\Page\App\Http\Controllers\Admin\PageController;


Route::prefix('v1/admin/page')->group(function () {


    Route::middleware("auth:api")->group(function () {
        Route::get('all', [PageController::class, 'getAllPages']);
        Route::get('/get-by-id/{id}', [PageController::class, 'getAllPage']);

//        Route::get('/get-by-link/{slug}', [PageController::class, 'getPageBySlug']);
        Route::post('create', [PageController::class, 'createPage']);
        Route::put('{id}', [PageController::class, 'updatePage']);
        Route::delete('{id}', [PageController::class, 'deletePage']);

    });
});
