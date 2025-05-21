<?php


use Illuminate\Support\Facades\Route;


use Modules\Auth\App\Http\Controllers\admin\auth\VerificationController;
use Modules\Leads\App\Http\Controllers\admin\LeadController;
use Modules\Leads\App\Http\Controllers\admin\LeadSourceController;
use Modules\Leads\App\Http\Controllers\admin\LeadStatusController;


Route::prefix('v1/admin/lead')->group(function () {


    Route::middleware("auth:api")->group(function () {

        // Lead Source
        Route::post('source/add', [LeadSourceController::class, 'createSource']);
        Route::get('source/list', [LeadSourceController::class, 'allSources']);

        // Lead Status Route
        Route::post('status/add', [LeadStatusController::class, 'createStatus']);
        Route::get('status/list', [LeadStatusController::class, 'allStatus']);

        // Lead Routes
        Route::post('add', [LeadController::class, 'createLead']);
        Route::get('list', [LeadController::class, 'allLeads']);
        Route::post('convert', [LeadController::class, 'convertLeadToUser']);

        // Change USer access to panel and classes
        Route::post('change-access', [VerificationController::class, 'changeUserVerifyMod']);
    });
});
