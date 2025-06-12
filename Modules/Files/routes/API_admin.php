<?php

use Illuminate\Support\Facades\Route;
use Modules\Files\App\Http\Controllers\UploadController;


Route::prefix('v1/admin/file')->group(function () {


    Route::middleware("auth:api")->group(function () {
        Route::post('/upload-seo-image', [UploadController::class, 'uploadSeoImage'])->middleware('auth:api');


    });
});
