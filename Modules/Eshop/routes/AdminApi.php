<?php


use Illuminate\Support\Facades\Route;
use Modules\Eshop\App\Http\Controllers\Admin\Settings\General\GetEshopGeneralSettingsController;
use Modules\Eshop\App\Http\Controllers\Admin\Settings\General\UpdateEshopGeneralSettingsController;
use Modules\Eshop\App\Http\Controllers\Admin\Settings\Product\GetEshopProductSettingController;
use Modules\Eshop\App\Http\Controllers\Admin\Settings\Product\UpdateEshopProductSettingController;


Route::prefix('v1/admin/eshop')->group(function () {
    Route::middleware('auth:api')->group(function () {


        // Settings
        Route::prefix('setting')->group(function () {
            // Eshop General Settings
            Route::get('general',
                GetEshopGeneralSettingsController::class);
            Route::put('general',
                UpdateEshopGeneralSettingsController::class);


            // Product Setting
            Route::get('product', GetEshopProductSettingController::class)->name('eshop.settings.product.get');
            Route::put('product',
                UpdateEshopProductSettingController::class)->name('eshop.settings.product.update')->middleware('auth:api');
        });


    });
});
