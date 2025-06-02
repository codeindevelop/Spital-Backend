<?php

namespace Modules\Auth\App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\Auth\App\Services\AdminUserService;
use Modules\RolePermission\App\Models\Role;
use Modules\User\App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthController extends Controller
{
    private AdminUserService $adminUserService;

    public function __construct(AdminUserService $adminUserService)
    {
        $this->adminUserService = $adminUserService;
    }

    // Login Admins
    public function adminLogin(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'max:255'],
            'remember_me' => ['boolean'],
            '2fa_code' => ['sometimes', 'numeric'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $credentials = $request->only('email', 'password');
        if (!Auth::attempt($credentials)) {
            return response()->json(['error' => 'Invalid email or password.'], 401);
        }

        $user = Auth::user();
        if (!$user->can('admin_area:access')) {
            return response()->json(['error' => 'You cannot access the admin area.'], 403);
        }

        $tokenResult = $user->createToken('accessToken');
        $token = $tokenResult->token;
        $token->expires_at = $request->remember_me
            ? now()->addWeeks(1)
            : now()->addSeconds(config('auth.password_timeout', 3600));
        $token->save();

        $ip = $request->getClientIp();
        // TODO: Dispatch UserLogedinByEmailEvent if needed
        // UserLogedinByEmailEvent::dispatch($user, $ip);

        return response()->json([
            'data' => [
                'accessToken' => $tokenResult->accessToken,
            ],
        ], Response::HTTP_OK);
    }

    // Get Admin Profile
    public function adminProfile(): JsonResponse
    {
        $user = Auth::user();
        if (!$user->can('admin_area:access')) {
            return response()->json(['error' => 'You cannot access this area.'], 403);
        }

        $role = $user->getRoleNames();
        $permissions = $user->getAllPermissions();

        return response()->json([
            'data' => [
                'user' => $user->load('personalInfo', 'verify', 'roles', 'permissions'),
                'role' => $role,
                'permissions' => $permissions,
            ],
        ], Response::HTTP_OK);
    }

    // Create User by Admin
    public function adminCreateUser(Request $request): JsonResponse
    {
        $operator = Auth::user();
        if (!$operator->can('users:create')) {
            return response()->json(['error' => 'You do not have permission to create users.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'user_name' => ['required', 'nullable', 'max:255', 'unique:user_personal_infos,user_name'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'mobile_number' => ['required', 'string', 'max:20', 'unique:users,mobile_number'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'gender' => ['nullable', 'string', 'in:male,female,other'],
            'national_id' => ['nullable', 'string', 'max:20'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'home_address' => ['nullable', 'string', 'max:500'],
            'passport_number' => ['nullable', 'string', 'max:50'],
            'shenasname_number' => ['nullable', 'string', 'max:50'],
            'mellicard_number' => ['nullable', 'string', 'max:50'],
            'active' => ['boolean'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $ip = $request->getClientIp();
        $response = $this->adminUserService->createUser($request->all() + ['ip' => $ip, 'operator_id' => $operator->id]);

        return response()->json([
            'data' => [
                'message' => 'User created successfully.',
                'user' => $response['user'],
                'accessToken' => $response['accessToken'],
            ],
        ], Response::HTTP_CREATED);
    }

    // Update User
    public function update(Request $request, $id): JsonResponse
    {
        $operator = Auth::user();
        if (!$operator->can('users:edit')) {
            return response()->json(['error' => 'You do not have permission to edit users.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'first_name' => ['sometimes', 'string', 'max:255'],
            'last_name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'email', 'max:255', 'unique:users,email,' . $id . ',id'],
            'mobile_number' => ['sometimes', 'string', 'max:20', 'unique:users,mobile_number,' . $id . ',id'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'gender' => ['nullable', 'string', 'in:male,female,other'],
            'national_id' => ['nullable', 'string', 'max:20'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'home_address' => ['nullable', 'string', 'max:500'],
            'active' => ['boolean'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $user = $this->adminUserService->updateUser($id, $request->all(), $operator->id);

        return response()->json([
            'data' => [
                'message' => 'User updated successfully.',
                'user' => $user,
            ],
        ], Response::HTTP_OK);
    }

    // Suspend/Activate User
    public function suspendUser(Request $request, $id): JsonResponse
    {
        $operator = Auth::user();
        if (!$operator->can('users:suspend')) {
            return response()->json(['error' => 'You do not have permission to suspend users.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'active' => ['required', 'boolean'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $user = $this->adminUserService->suspendUser($id, $request->active, $operator->id);

        return response()->json([
            'data' => [
                'message' => $user->active ? 'User activated successfully.' : 'User suspended successfully.',
                'user' => $user,
            ],
        ], Response::HTTP_OK);
    }

    // Verify User
    public function verifyUser(Request $request, $id): JsonResponse
    {
        $operator = Auth::user();
        if (!$operator->can('users:verify')) {
            return response()->json(['error' => 'You do not have permission to verify users.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'status' => ['required', 'string', 'in:verified,pending,rejected'],
            'reject_status' => ['nullable', 'string', 'max:255', 'required_if:status,rejected'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $verify = $this->adminUserService->verifyUser($id, $request->all(), $operator->id);

        return response()->json([
            'data' => [
                'message' => 'User verification status updated successfully.',
                'verify' => $verify,
            ],
        ], Response::HTTP_OK);
    }

    // Get All Users
    public function getAllUsers(): JsonResponse
    {
        $operator = Auth::user();
        if (!$operator->can('users:view')) {
            return response()->json(['error' => 'You do not have permission to view users.'], 403);
        }

        $users = $this->adminUserService->getAllUsers();

        return response()->json([
            'data' => [
                'users' => $users,
                'usersCount' => $users->count(),
            ],
        ], Response::HTTP_OK);
    }

    // Get User By ID
    public function getUserById(Request $request): JsonResponse
    {
        $operator = Auth::user();
        if (!$operator->can('users:view')) {
            return response()->json(['error' => 'You do not have permission to view users.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'user_id' => ['required', 'uuid', 'exists:users,id'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $user = $this->adminUserService->getUserById($request->user_id);

        return response()->json([
            'data' => [
                'user' => $user,
            ],
        ], Response::HTTP_OK);
    }

    // Get Trashed Users
    public function getTrashedUsers(): JsonResponse
    {
        $operator = Auth::user();
        if (!$operator->can('users:view')) {
            return response()->json(['error' => 'You do not have permission to view trashed users.'], 403);
        }

        $users = $this->adminUserService->getTrashedUsers();

        return response()->json([
            'data' => [
                'users' => $users,
                'trashedUsersCount' => $users->count(),
            ],
        ], Response::HTTP_OK);
    }

    // Restore Trashed User
    public function restoreTrashedUsers($id): JsonResponse
    {
        $operator = Auth::user();
        if (!$operator->can('users:restore')) {
            return response()->json(['error' => 'You do not have permission to restore users.'], 403);
        }

        $user = $this->adminUserService->restoreUser($id, $operator->id);

        return response()->json([
            'data' => [
                'message' => 'User restored successfully.',
                'user' => $user,
            ],
        ], Response::HTTP_OK);
    }

    // Delete User
    public function destroy($id): JsonResponse
    {
        $operator = Auth::user();
        if (!$operator->can('users:delete')) {
            return response()->json(['error' => 'You do not have permission to delete users.'], 403);
        }

        $this->adminUserService->deleteUser($id, $operator->id);

        return response()->json([
            'data' => [
                'message' => 'User deleted successfully.',
            ],
        ], Response::HTTP_OK);
    }
}
