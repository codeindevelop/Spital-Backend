<?php

namespace Modules\Auth\App\Http\Controllers\admin\auth;

use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Modules\Auth\App\Events\UserLogedinByEmailEvent;
use Modules\User\App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class AdminAuthController extends Controller
{
    // Login Admins
    public function adminLogin(Request $request): JsonResponse
    {

        // Validate Request Data
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'max:255'],
            'remember_me' => ['boolean'],
            '2fa_code' => ['sometimes', 'numeric'],
        ]);

        // If Validator has Error :: return the error
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first()
            ], 422);


            // If Validator has Not Error
        } else {
            $emailExist = User::where('email', $request->email)->first();

            // If user has not Signup return Error
            if (!$emailExist) {
                return response()->json([
                    'error' => 'user not exist with this email!'
                ], 422);
            }


            $credentials = request(['email', 'password']);
            if (!Auth::attempt($credentials)) {
                return response()->json(['error' => 'user or pass is wrong or whatever.'], 401);
            }

            $user = Auth::user();

            if ($user->can('admin_area:access')) {

                $tokenResult = $user->createToken('accessToken');
                $token = $tokenResult->token;

                if ($request->remember_me) {
                    $token->expires_at = Carbon::now()->addWeeks(1);
                } else {
                    $token->expires_at = Carbon::now()->addSeconds(config("auth.password_timeout"));
                }
                $token->save();

                $ip = $request->getClientIp();


                // Call Dispatch User login Actions
                UserLogedinByEmailEvent::dispatch($user, $ip);

                return response()->json([
                    'data' => [
                        'accessToken' => $tokenResult->accessToken,

                    ]
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'error' => 'you can not access to this area'
                ], 401);
            }
        }
    }


    // get user profile
    public function adminProfile(): JsonResponse
    {
        $user = Auth::user();
        if ($user->can('mother_area:access')) {

            $role = $user->getRoleNames();
            $permissions = $user->getAllPermissions();

            return response()->json([
                'user' => $user,
                'role' => $role,
                'permissions' => $permissions,
            ]);
        } else {

            return response()->json([
                'error' => 'you can not access to this area'
            ], 402);
        }
    }


    // Create user by admin
    public function createUser(Request $request): JsonResponse
    {

        $operator = Auth::user();
        if ($operator->can('users:create')) {

            $validate = Validator::make($request->all(), [

                'first_name' => ['required', 'string'],
                'last_name' => ['required', 'string'],
                'mobile_number' => ['string', 'unique:users'],
                'email' => ['email', 'max:255', 'unique:users'],
                'password' => ['required', 'confirmed'],
            ]);
            if ($validate->fails()) {
                // Check user email has not exist
                $emailExist = User::where('email', $request->email)->first();

                if ($emailExist) {
                    return response()->json(
                        [
                            'error' => 'email has Exist'
                        ],
                        401
                    );
                }

                // check user has registered or not by mobile number
                $checkMobileNumber = User::where('mobile_number', $request->mobile_number)->first();


                // If user has Existed
                if ($checkMobileNumber) {
                    // If user Email Exist
                    return response()->json([
                        'error' => 'mobile_number has Exist'
                    ], 401);
                }
            }

            $user = new User([

                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'mobile_number' => $request->mobile_number,
                'email' => $request->email,
                'email_verify_token' => Str::random(60),
                'password' => bcrypt($request->password),
                "father_name" => $request->father_name,
                'melli_code' => $request->melli_code,
                'shenasname_no' => $request->shenasname_no,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'phone_number' => $request->phone_number,
                'home_address' => $request->home_address,
                'profile_pic' => $request->profile_pic,
                'image_mellicard' => $request->image_mellicard,
                'image_shenasname' => $request->image_shenasname,
                'image_passport' => $request->image_passport,
                'image_selfi' => $request->image_selfi,
                'active' => $request->active,
                'status' => 'ایجاد توسط'.$operator->first_name,


            ]);

            $random_password_code = $request->password;

            $user->save();

//            ProcessCreateUserByOperator::dispatch($user, $random_password_code, $user, $operator);


            $user->assignRole('registred-user');

            return response()->json([
                'message' => 'User has ben Created successful!',
                'operator_id' => $user->id,
                'user' => $user,

            ], Response::HTTP_CREATED);


            // If admin dos not permission to create user
        } else {
            return response()->json([
                'message' => 'You don have permission  '
            ], 402);
        }
    }

    // Update user
    public function update(Request $request, $id): JsonResponse
    {
        $user = Auth::user();


        if ($user->can('users:edit')) {
            $targetUser = User::findOrFail($id);

            $targetUser->update([
                'first_name' => $request->has('first_name') ? $request->first_name : $targetUser->first_name,
                'last_name' => $request->has('last_name') ? $request->last_name : $targetUser->last_name,
                'email' => $request->has('email') ? $request->email : $targetUser->email,
                'mobile_number' => $request->has('mobile_number') ? $request->mobile_number : $targetUser->mobile_number,
                // 'active' => $request->active,
                'password' => $request->password != "" ? bcrypt($request->password) : $targetUser->password
            ]);

            return response()->json([
                'message' => 'User has ben Updated!',
                'updated_user' => $targetUser
            ], 202);
        } else {
            return response()->json([
                'message' => 'you dont have permission',

            ], 402);
        }
    }

    // Update user
    public function suspendUser(Request $request, $id): JsonResponse
    {
        $user = Auth::user();


        if ($user->can('susbend user')) {
            $targetUser = User::findOrFail($id);

            $targetUser->update([
                // 'first_name' => $request->has('first_name') ? $request->first_name : $targetUser->first_name,
                // 'last_name' => $request->has('last_name') ? $request->last_name : $targetUser->last_name,
                // 'email' => $request->has('email') ? $request->email : $targetUser->email,
                // 'mobile_number' => $request->has('mobile_number') ? $request->mobile_number : $targetUser->mobile_number,
                'active' => $request->active,
                // 'password' => $request->password != "" ? bcrypt($request->password) : $targetUser->password
            ]);

            return response()->json([
                'message' => 'User has ben susbended!',
                'updated_user' => $targetUser
            ], 202);
        } else {
            return response()->json([
                'message' => 'you dont have permission',

            ], 402);
        }
    }


    // Get All !not trashed users List
    public function getAllUsers(): JsonResponse
    {
        $user = Auth::user();


        if ($user->can('view all users')) {


            $users = User::with('personalInfos')->with('roles')->get();
            $usersCount = $users->count();


            return response()->json([
                'data' => [
                    'users' =>  $users,
                    'usersCounts' => $usersCount,
                ]
            ], 200);
        } else {
            return response()->json([
                'message' => 'You don have permission to see all Users'
            ], 402);
        }
    }

    // Get User By Id
    public function getUserById(Request $request): JsonResponse
    {
        $user = Auth::user();

        if ($user->can('users:view')) {
            $targetUser = User::where('id',
                $request->user_id)->with('personalInfos')->with('roles')->with('permissions')->get();

            return response()->json([
                'data' => [
                    'user' => $targetUser
                ]
            ], 200);
        } else {
            return response()->json([
                'message' => 'You don have permission'
            ], 402);
        }
    }

    // Get All trashed users List
    public function getTrashedUsers(): JsonResponse
    {
        $user = Auth::user();

        // Check Deleted Users
        $users = User::onlyTrashed()->get();

        if ($user->can('susbend users')) {

            $trashedUsersCount = $users->count();

            return response()->json([
                'users' => $users,
                'trashedUsersCount' => $trashedUsersCount,
            ], 200);
        } else {
            return response()->json([
                'message' => 'You don have permission to see Trashed Users'
            ], 500);
        }
    }

    // restore trashed user by ID
    public function restoreTrashedUsers($id): JsonResponse
    {
        $user = Auth::user();

        // Check Deleted User
        $targetUser = User::withTrashed()->find($id);

        if ($user->can('susbend user')) {

            $targetUser->restore();

            return response()->json([
                'message' => 'user has ben restored successful'
            ], 200);
        } else {
            return response()->json([
                'message' => 'You dont have permission'
            ], 500);
        }
    }

    // Delete User By Id
    public function destroy($id): JsonResponse
    {
        $user = Auth::user();

        if ($user->can('users:delete')) {
            $targeted = User::findOrFail($id);
            $targeted->delete();

            return response()->json(['message' => 'User has ben Deleted!'], 200);
        } else {
            return response()->json([
                'message' => 'You don have permission '
            ], 402);
        }
    }


}
