<?php


use Illuminate\Support\Facades\Route;
use Modules\Eshop\App\Http\Controllers\Admin\Settings\General\GetEshopGeneralSettingsController;
use Modules\Eshop\App\Http\Controllers\Admin\Settings\General\UpdateEshopGeneralSettingsController;


Route::prefix('v1/admin/eshop')->group(function () {
    Route::middleware('auth:api')->group(function () {


        // Settings
        Route::prefix('setting')->group(function () {
            Route::get('general',
                GetEshopGeneralSettingsController::class);
            // Eshop General Settings
            Route::put('general',
                UpdateEshopGeneralSettingsController::class);
        });


    });
});
