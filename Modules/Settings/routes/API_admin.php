<?php


use Illuminate\Support\Facades\Route;
use Modules\Settings\App\Http\Controllers\Admin\Seo\SeoGeneralSettingController;


Route::prefix('v1/admin')->group(function () {


    Route::middleware("auth:api")->group(function () {
        Route::put('general', [SeoGeneralSettingController::class, 'updateSettings']);


        Route::prefix('setting')->group(function () {

            // SEO Settings
            Route::prefix('seo')->group(function () {
                Route::get('general', [SeoGeneralSettingController::class, 'getSettings']);
                Route::post('general', [SeoGeneralSettingController::class, 'updateSettings']);

            });

        });

    });
});


