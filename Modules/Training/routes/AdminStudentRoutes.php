<?php


use Modules\Training\App\Http\Controllers\admin\student\StudentAdminController;
use Modules\Training\App\Http\Controllers\admin\student\StudentGroupAdminController;
use Modules\Training\App\Http\Controllers\admin\student\StudentLevelAdminController;
use Illuminate\Support\Facades\Route;



/*
|--------------------------------------------------------------------------
| Authentication API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Authentication API routes for your application.
*/


Route::prefix('v1/admin/student')->group(function () {




    Route::middleware("auth:api")->group(function () {

        /* -------------------------------------------------------------------------- */
        /*                            student groups route                            */
        /* -------------------------------------------------------------------------- */
        // get student group
        Route::get('student-groups', [StudentGroupAdminController::class, 'index']);

        // get student group by id
        Route::get('student-group/{id}', [StudentGroupAdminController::class, 'show']);

        // student_group:create
        Route::post('student-group', [StudentGroupAdminController::class, 'createStudentGroup']);

        // student_group:edit
        Route::put('edit-student-group/{id}', [StudentGroupAdminController::class, 'updateStudentGroup']);

        // student_group:delete
        Route::delete('delete-student-group/{id}', [StudentGroupAdminController::class, 'deleteStudentGroup']);

        /* -------------------------------------------------------------------------- */
        /*                            student level route                            */
        /* -------------------------------------------------------------------------- */
        // get student level
        Route::get('student-levels', [StudentLevelAdminController::class, 'index']);

        // get student level by id
        Route::get('student-level/{id}', [StudentLevelAdminController::class, 'show']);

        // student:create level
        Route::post('student-level', [StudentLevelAdminController::class, 'createStudentLevel']);

        // student:edit level
        Route::put('edit-student-level/{id}', [StudentLevelAdminController::class, 'updateStudentLevel']);

        // student:delete level
        Route::delete('delete-student-level/{id}', [StudentLevelAdminController::class, 'deleteStudentLevel']);


        /* -------------------------------------------------------------------------- */
        /*                               student routes                               */
        /* -------------------------------------------------------------------------- */

        // student:create by admin
        Route::post('signup-student', [StudentAdminController::class, 'createStudentByOperator']);

        Route::post('edit-student', [StudentAdminController::class, 'editStudentById']);

        Route::delete('delete-student', [StudentAdminController::class, 'deleteStudentById']);

        // Get all students
        Route::get('all-students', [StudentAdminController::class, 'getAllStudentsList']);

        Route::post('student', [StudentAdminController::class, 'getStudentById']);
    });
});
