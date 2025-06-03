<?php

namespace Modules\RolePermission\App\Http\Controllers\admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\User\App\Models\User;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    // view all roles in system
    public function index(): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();

        // if user have permission to roles:view
        if ($user->can('roles:view')) {

            $roles = Role::all();
            $roleCount = $roles->count();

            return response()->json([
                'roles' => $roles,
                'rolesCount' => $roleCount
            ], 200);

            // if don have permission
        } else {

            return response()->json([
                'message' => 'you dont have permission to roles:view'
            ], 401);
        }
    }


    // view all users by roles
    public function getUsersByRoles(): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();

        // if user have permission to roles:view
        if ($user->can('roles:view')) {

            $userRoles = User::with('roles')->get();

            return response()->json([
                'userRoles' => $userRoles
            ]);
        } else {

            return response()->json([
                'message' => 'you dont have permission to roles:view'
            ], 401);
        }
    }

    public function getRolesPermission(): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();

        // if user have permission to roles:view
        if ($user->can('roles:view')) {

            $role_permissions = Role::with('permissions')->get();
            $roleCounts = $role_permissions->count();


            return response()->json([
                'role_permissions' => $role_permissions,
                'rolesCount' => $roleCounts

            ]);
        } else {

            return response()->json([
                'message' => 'you dont have permission to view this'
            ], 401);
        }
    }

    public function getRolesPermissionsById($id): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();

        // if user have permission to roles:view
        if ($user->can('roles:view')) {


            $targetRole = Role::where('id', $id)->with('permissions')->get();

            return response()->json([
                'role_permissions' => $targetRole
            ]);
        } else {

            return response()->json([
                'message' => 'you dont have permission to view this'
            ], 401);
        }
    }


    public function assignRoleToUser(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [

            'user_id' => ['required'],
            'rolenames' => ['required'],

        ]);


        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first(), 'status' => false], 500);
        } else {

            if ($user->can('roles:edit')) {

                // Give user id by request
                $userid = User::findOrFail($request->user_id);

                $userid->assignRole($request->rolenames);

                return response()->json([
                    'message' => 'Role Has ben assigned to user'
                ], 201);
            } else {
                return response()->json([
                    'message' => 'You don have permission to this'
                ], 401);
            }
        }
    }

    public function removeRoleToUser(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [

            'user_id' => ['required'],
            'rolename' => ['required'],

        ]);


        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first(), 'status' => false], 500);
        } else {

            if ($user->can('roles:edit')) {

                // Give user id by request
                $userid = User::findOrFail($request->user_id);

                $userid->removeRole($request->rolename);

                return response()->json([
                    'message' => 'Role Has ben Removed to user'
                ], 201);
            } else {
                return response()->json([
                    'message' => 'You don have permission to this'
                ], 401);
            }
        }
    }


    // create new role
    public function store(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [

            'role_name' => ['required'],

        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first(), 'status' => false], 500);
        } else {

            if ($user->can('roles:create')) {

                Role::create(['guard_name' => 'api', 'name' => $request->role_name]);

                return response()->json([
                    'message' => 'Role Has ben created!'
                ], 201);
            } else {
                return response()->json([
                    'message' => 'You don have permission to create a Role!'
                ], 401);
            }
        }
    }

    // Role Give A permission By ID
    public function roleGivPermission(Request $request): \Illuminate\Http\JsonResponse
    {

        $user = Auth::user();


        $validator = Validator::make($request->all(), [

            'permission_id' => ['required'],
            'role_id' => ['required'],

        ]);

        $role_id = $request->role_id;
        $permission = $request->permission;


        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first(), 'status' => false], 500);
        } else {

            if ($user->can('roles:edit')) {

                $role = Role::findById($role_id);

                $role->givePermissionTo($permission);

                return response()->json([
                    'message' => 'Role Has Ben Updated'
                ], 201);
            } else {
                return response()->json([
                    'message' => 'You don have permission to Update Role Permissions'
                ], 500);
            }
        }
    }

    // Role Revoke A permission By ID
    public function roleRevokePermission(Request $request): \Illuminate\Http\JsonResponse
    {

        $user = Auth::user();


        $validator = Validator::make($request->all(), [

            'permission_id' => ['required'],
            'role_id' => ['required'],

        ]);

        $role_id = $request->role_id;
        $permission = $request->permission_id;


        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first(), 'status' => false], 500);
        } else {

            if ($user->can('roles:edit')) {

                $role = Role::findById($role_id);

                $role->revokePermissionTo($permission);

                return response()->json([
                    'message' => 'Permission Has Ben revoked from Role'
                ], 201);
            } else {
                return response()->json([
                    'message' => 'You don have permission to Update Role Permissions'
                ], 500);
            }
        }
    }

    // delete role
    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();
        $role = Role::findOrFail($id);

        if ($user->can('roles:delete')) {
            $role->delete();

            return response()->json(['message' => 'Role has ben Deleted!']);
        } else {
            return response()->json([
                'message' => 'You don have permission to Delete Role!'
            ], 401);
        }
    }
}
