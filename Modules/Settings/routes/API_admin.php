<?php


use Illuminate\Support\Facades\Route;
use Modules\Settings\App\Http\Controllers\Admin\System\General\Admin\Blog\AdminBlogSettingController;
use Modules\Settings\App\Http\Controllers\Admin\Eshop\GetEshopGeneralSettingsController;
use Modules\Settings\App\Http\Controllers\Admin\Eshop\UpdateEshopGeneralSettingsController;
use Modules\Settings\App\Http\Controllers\Admin\System\General\Admin\Seo\SeoGeneralSettingController;
use Modules\Settings\App\Http\Controllers\Admin\System\General\Admin\Seo\SeoRepresentationSettingController;

use Modules\Settings\App\Http\Controllers\Admin\System\General\GeneralSettingController;


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

            // System Settings
            Route::prefix('system')->group(function () {

                // Eshop General Settings
                Route::get('general',
                    [GeneralSettingController::class, 'show'])->name('admin.system.general_settings.show');
                Route::patch('general',
                    [GeneralSettingController::class, 'update'])->name('admin.system.general_settings.update');


            });

            // Eshop Settings
            Route::prefix('eshop')->group(function () {

                // Eshop General Settings
                Route::get('general',
                    GetEshopGeneralSettingsController::class)->name('admin.eshop.settings.get');
                Route::put('general',
                    UpdateEshopGeneralSettingsController::class)->name('admin.eshop.settings.update');


            });

            // Blog Settings
            Route::prefix('blog')->group(function () {
                Route::get('general', [AdminBlogSettingController::class, 'getSettings']);
                Route::put('general', [AdminBlogSettingController::class, 'updateSettings']);

            });


        });
    });
});


