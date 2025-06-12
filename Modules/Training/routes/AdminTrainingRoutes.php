<?php


use Modules\Files\App\Http\Controllers\v1\admin\crm\PreRegisterUserController;
use Modules\Training\App\Http\Controllers\admin\instructor\InstructorController;


use Modules\Files\App\Http\Controllers\v1\admin\skill\AdminSkillCategoryController;
use Modules\Files\App\Http\Controllers\v1\admin\skill\AdminSkillController;
use Illuminate\Support\Facades\Route;



/*
|--------------------------------------------------------------------------
| Authentication API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Authentication API routes for your application.
*/


Route::prefix('v1/admin/training')->group(function () {




    Route::middleware("auth:api")->group(function () {


        Route::get('skills-category', [AdminSkillCategoryController::class, 'getAllSkillsCategory']);
        Route::post('new-skill-category', [AdminSkillCategoryController::class, 'createSkillCategory']);
        Route::put('edit-skill-category/{id}', [AdminSkillCategoryController::class, 'editSkillCategory']);
        Route::get('skills', [AdminSkillController::class, 'getAllSkills']);
        Route::post('new-skill', [AdminSkillController::class, 'createSkill']);
        Route::put('edit-skill/{id}', [AdminSkillController::class, 'editSkill']);

        // user pre register
        Route::post('preregister', [PreRegisterUserController::class, 'SubmiteUserDataInCrm']);

        Route::post('preregisterok', [PreRegisterUserController::class, 'SubmiteUserPleaseRegister']);

        Route::post('send-classtime', [PreRegisterUserController::class, 'sendClassTimeToUser']);

        Route::post('send-submit-money', [PreRegisterUserController::class, 'sendSubmitMoney']);

        Route::post('register-complete', [PreRegisterUserController::class, 'registerCompleteWaiting']);
    });
});
