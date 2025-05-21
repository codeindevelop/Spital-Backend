<?php


use Modules\Training\App\Http\Controllers\admin\trainPath\AdminPathController;
use Illuminate\Support\Facades\Route;
use Modules\Training\App\Http\Controllers\admin\course\CourseEpisodeAdminController;
use Modules\Training\App\Http\Controllers\admin\course\CourseFilesAdminController;
use Modules\Training\App\Http\Controllers\admin\course\CoursesAdminController;
use Modules\Training\App\Http\Controllers\admin\course\CoursesCategoryAdminController;
use Modules\Training\App\Http\Controllers\admin\course\CourseSeasonsAdminController;
use Modules\Training\App\Http\Controllers\admin\course\CourseVideoAdminController;
use Modules\Training\App\Http\Controllers\admin\instructor\InstructorController;


/*
|--------------------------------------------------------------------------
| Authentication API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Authentication API routes for your application.
*/


Route::prefix('v1/admin/training')->group(function () {


    Route::middleware("auth:api")->group(function () {


        //  instructor by admin
        Route::post('register-instructor', [InstructorController::class, 'RegisterInstructor']);
        Route::get('all-instructors', [InstructorController::class, 'GetAllInstructor']);
        Route::get('instructor/{id}', [InstructorController::class, 'getById']);

        /* -------------------------------------------------------------------------- */
        /*                           Course Category Routes                           */
        /* -------------------------------------------------------------------------- */

        //  course category by admin
        Route::get('all-course-category', [CoursesCategoryAdminController::class, 'index']);
        Route::get('category/{id}', [CoursesCategoryAdminController::class, 'show']);
        Route::put('category/{id}', [CoursesCategoryAdminController::class, 'updateCourseCategory']);
        Route::delete('category/{id}', [CoursesCategoryAdminController::class, 'deleteCourseCategory']);
        Route::post('create-course-category', [CoursesCategoryAdminController::class, 'createCourseCategory']);


        /* -------------------------------------------------------------------------- */
        /*                               Courses Routes                               */
        /* -------------------------------------------------------------------------- */
        Route::get('all-courses', [CoursesAdminController::class, 'index']);
        Route::post('create-course', [CoursesAdminController::class, 'createNewCourse']);
        Route::get('course/{id}', [CoursesAdminController::class, 'show']);
        Route::put('edit-course/{id}', [CoursesAdminController::class, 'updateCourse']);


        /* -------------------------------------------------------------------------- */
        /*                            Course Seasons Route                            */
        /* -------------------------------------------------------------------------- */

        Route::get('all-seasons', [CourseSeasonsAdminController::class, 'index']);
        Route::get('get-season/{id}', [CourseSeasonsAdminController::class, 'show']);
        Route::post('create-season', [CourseSeasonsAdminController::class, 'createCourseSeason']);
        Route::put('edit-season/{id}', [CourseSeasonsAdminController::class, 'editCourseSeason']);

        /* -------------------------------------------------------------------------- */
        /*                            Course Episode Route                            */
        /* -------------------------------------------------------------------------- */

        Route::get('all-episodes', [CourseEpisodeAdminController::class, 'index']);
        Route::get('get-episode/{id}', [CourseEpisodeAdminController::class, 'show']);
        Route::post('create-episode', [CourseEpisodeAdminController::class, 'createCourseEpisode']);
        Route::put('edit-episode/{id}', [CourseEpisodeAdminController::class, 'editCourseEpisode']);

        /* -------------------------------------------------------------------------- */
        /*                            Course Videos  Route                            */
        /* -------------------------------------------------------------------------- */

        Route::get('all-videos', [CourseVideoAdminController::class, 'index']);
        Route::get('get-video/{id}', [CourseVideoAdminController::class, 'show']);
        Route::post('create-video', [CourseVideoAdminController::class, 'createCourseVideo']);
        Route::put('edit-video/{id}', [CourseVideoAdminController::class, 'editCourseVideo']);

        /* -------------------------------------------------------------------------- */
        /*                            Course Files  Route                            */
        /* -------------------------------------------------------------------------- */

        Route::get('all-files', [CourseFilesAdminController::class, 'index']);
        Route::get('get-file/{id}', [CourseFilesAdminController::class, 'show']);
        Route::post('create-file', [CourseFilesAdminController::class, 'createCourseFile']);
        Route::put('edit-file/{id}', [CourseFilesAdminController::class, 'editCourseFile']);



        /* -------------------------------------------------------------------------- */
        /*                            Course Paths  Route                             */
        /* -------------------------------------------------------------------------- */

        Route::get('path/all', [AdminPathController::class, 'getAllPaths']);
        Route::get('path/show/{id}', [AdminPathController::class, 'getPathById']);
        Route::put('path/edit/{id}', [AdminPathController::class, 'editPathById']);
        Route::post('path/new', [AdminPathController::class, 'createPath']);
    });
});
