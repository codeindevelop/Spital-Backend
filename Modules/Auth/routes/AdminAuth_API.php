<?php


use Illuminate\Support\Facades\Route;
use Modules\Auth\App\Http\Controllers\admin\auth\AdminAuthController;
use Modules\Auth\App\Http\Controllers\admin\auth\UsersAuthController;
use Modules\Auth\App\Http\Controllers\admin\auth\VerificationController;
use Modules\RolePermission\App\Http\Controllers\admin\PermissionController;
use Modules\RolePermission\App\Http\Controllers\admin\RoleController;


/*
|--------------------------------------------------------------------------
| Authentication API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Authentication API routes for your application.
*/


Route::prefix('v1/admin')->group(function () {



    Route::middleware("auth:api")->group(function () {



        Route::get('users', [AdminAuthController::class, 'getAllUsers']);
        Route::post('create-user', [UsersAuthController::class, 'createUser']);
        Route::get('trashed-users', [AdminAuthController::class, 'getTrashedUsers']);
        Route::put('trashed-user/{id}', [AdminAuthController::class, 'restoreTrashedUsers']);
        Route::post('getuser', [AdminAuthController::class, 'getUserById']);
        Route::delete('user/{id}', [AdminAuthController::class, 'destroy']);


        Route::post('update-profile', [AdminAuthController::class, 'update']);


        Route::post('verify-user', [VerificationController::class, 'changeUserVerifyMod']);


        // Roles Routes API
        Route::get('roles', [RoleController::class, 'index']);
        Route::post('role', [RoleController::class, 'store']);
        Route::get('role-permission/{id}', [RoleController::class, 'getRolesPermissionsById']);
        Route::delete('role/{id}', [RoleController::class, 'destroy']);
        Route::put('user-role/{id}', [RoleController::class, 'changeUserRole']);
        Route::post('role-giv-permission', [RoleController::class, 'roleGivPermission']);
        Route::post('role-revoke-permission', [RoleController::class, 'roleRevokePermission']);
        Route::get('user-roles', [RoleController::class, 'getUsersByRoles']);
        Route::post('role-to-user', [RoleController::class, 'assignRoleToUser']);
        Route::post('role-remove-user', [RoleController::class, 'removeRoleToUser']);
        Route::get('role-permissions', [RoleController::class, 'getRolesPermission']);

        // Permissions Routes API
        Route::get('permissions', [PermissionController::class, 'index']);
        Route::post('permission', [PermissionController::class, 'store']);
        Route::delete('permission/{id}', [PermissionController::class, 'destroy']);
        Route::get('user-permissions', [PermissionController::class, 'getUsersByPermission']);
        Route::get('get-user-permission/{id}', [PermissionController::class, 'getUserPermissionById']);

        // Change USer access to panel and classes
        Route::post('change-access', [VerificationController::class, 'changeUserVerifyMod']);
    });
});
