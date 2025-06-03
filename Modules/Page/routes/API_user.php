<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Page\App\Http\Controllers\Admin\PageController;

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

Route::prefix('v1/page')->group(function () {

    Route::get('/get-by-link/{path?}', [PageController::class, 'getPageByPath'])->where('path', '.*');

});
