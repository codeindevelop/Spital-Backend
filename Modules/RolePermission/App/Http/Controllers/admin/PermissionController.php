<?php

namespace Modules\RolePermission\App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    // view all permissions
    public function index(): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();

        // if user have permission to view all permissions
        if ($user->can('permissions:view')) {

            $permissions = Permission::all();
            $Count = $permissions->count();

            return response()->json([
                'permissions' => $permissions,
                'permissionsCount' => $Count
            ], 200);
        } else {
            return response()->json([
                'message' => 'you dont have permission to view this'
            ], 401);
        }
    }

    public function getUsersByPermission(): \Illuminate\Http\JsonResponse
    {

        $user = Auth::user();

        // if user have permission to users:permissions:view
        if ($user->can('permissions:view')) {

            $permissions = User::with('permissions')->get();

            return response()->json([
                'user_permissions' => $permissions
            ], 200);
        } else {
            return response()->json([
                'message' => 'you dont have permission to view this'
            ], 401);
        }
    }


    public function getUserPermissionById($id): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();

        // if user have permission to users:permissions:view
        if ($user->can('permissions:view')) {


            $tagret_user = User::findOrFail($id);

            $permissions = $tagret_user->getAllPermissions();

            return response()->json([
                'Permissions' => $permissions
            ]);
        } else {
            return response()->json([
                'message' => 'you dont have permission to view this'
            ], 401);
        }
    }


    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [

            'permission_name' => ['required'],

        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first(), 'status' => false], 500);
        } else {

            if ($user->can('permissions:create')) {

                Permission::create(['name' => $request->permission_name]);

                return response()->json([
                    'message' => 'Permission Has ben created!'
                ], 201);
            } else {
                return response()->json([
                    'message' => 'You don have permission to create a Permission!'
                ], 401);
            }
        }
    }


    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();
        $permission = Permission::findOrFail($id);

        if ($user->can('permissions:delete')) {
            $permission->delete();

            return response()->json(['message' => 'Permission has ben Deleted!']);
        } else {
            return response()->json([
                'message' => 'You don have permission to Delete Permission!'
            ], 500);
        }
    }
}
