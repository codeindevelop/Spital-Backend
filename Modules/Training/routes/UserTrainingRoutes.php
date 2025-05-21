<?php


use Modules\Training\App\Http\Controllers\user\course\CourseCategoryUserController;
use Modules\Training\App\Http\Controllers\user\course\CourseSeasonsUserController;
use Modules\Training\App\Http\Controllers\user\course\CourseUserController;
use Modules\Training\App\Http\Controllers\user\trainingPath\PathController;
use Illuminate\Support\Facades\Route;



/*
|--------------------------------------------------------------------------
| Authentication API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Authentication API routes for your application.
*/


Route::prefix('v1/user/training')->group(function () {

    /* -------------------------------------------------------------------------- */
    /*                           course Category Routes                           */
    /* -------------------------------------------------------------------------- */
    Route::get('all-course-category', [CourseCategoryUserController::class, 'index']);


    /* -------------------------------------------------------------------------- */
    /*                               Courses Routes                               */
    /* -------------------------------------------------------------------------- */
    Route::get('all-courses', [CourseUserController::class, 'index']);
    Route::get('course/{id}', [CourseUserController::class, 'show']);
    Route::get('category-course/{id}', [CourseUserController::class, 'getCategoryCourses']);

    /* -------------------------------------------------------------------------- */
    /*                            Course Seasons Routes                           */
    /* -------------------------------------------------------------------------- */
    Route::get('all-seasons', [CourseSeasonsUserController::class, 'index']);



    /* -------------------------------------------------------------------------- */
    /*                            Course Paths  Route                             */
    /* -------------------------------------------------------------------------- */

    Route::get('path/all', [PathController::class, 'getAllPaths']);
    Route::get('path/show/{id}', [PathController::class, 'getPathById']);


});
