<?php

use Illuminate\Support\Facades\Route;
use Modules\ProjectManager\App\Http\Controllers\ProjectManagerController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group([], function () {
    Route::resource('projectmanager', ProjectManagerController::class)->names('projectmanager');
});
