<?php


use Illuminate\Support\Facades\Route;
use Modules\Settings\App\Http\Controllers\Admin\Seo\SeoGeneralSettingController;
use Modules\Settings\App\Http\Controllers\Admin\Seo\SeoRepresentationSettingController;


Route::prefix('v1/admin')->group(function () {


    Route::middleware("auth:api")->group(function () {

        Route::prefix('setting')->group(function () {

            // SEO Settings
            Route::prefix('seo')->group(function () {
                // Seo General Settings
                Route::get('general', [SeoGeneralSettingController::class, 'getSettings']);
                Route::post('general', [SeoGeneralSettingController::class, 'updateSettings']);

                // Seo Representation Settings
                Route::get('representation', [SeoRepresentationSettingController::class, 'getSettings']);
                Route::post('representation', [SeoRepresentationSettingController::class, 'updateSettings']);




            });
        });
    });
});


