<?php

use Modules\Localization\App\Http\Controllers\admin\LocalizationController;
use Illuminate\Support\Facades\Route;

/*
    |--------------------------------------------------------------------------
    | API Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register API routes for your application. These
    | routes are loaded by the RouteServiceProvider within a group which
    | is assigned the "api" middleware group. Enjoy building your API!
    |
*/


Route::prefix('v1/admin/localization')->group(function () {

    Route::middleware("auth:api")->group(function () {
        Route::get('/country/all', [LocalizationController::class, 'allCountries']);

    });
});
