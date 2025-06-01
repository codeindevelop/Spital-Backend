<?php

namespace Modules\Auth\App\Http\Controllers\admin\auth;

use App\Http\Controllers\Controller;
use App\Jobs\User\ProcessCreateUserByOperator;
use Modules\User\App\Models\Portal\PortalUser;
use Modules\User\App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UsersAuthController extends Controller
{

    // Create user by admin
    public function createUser(Request $request): \Illuminate\Http\JsonResponse
    {

        $operator = Auth::user();

        if ($operator->can('users:create')) {

            $validate = Validator::make($request->all(), [
                'first_name' => ['required', 'string'],
                'last_name' => ['required', 'string'],
                'user_group_id' => ['required'],
                'portal_id' => ['required'],
                'mobile_number' => ['string', 'unique:users'],
                'email' => ['email', 'max:255', 'unique:users'],
                'create_password' => ['required'],
                'send_verify_email' => ['required'],
                'send_welcome_sms' => ['required'],
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

            // if register type by user email

            $password = $request->password;

            // create random password by request
            if ($request->create_password == true) {
                $password = Str::random(8);
            }

            $user = new User([
                'group_id' =>  $request->user_group_id,
                'user_name' => $request->user_name,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'mobile_number' => $request->mobile_number,
                'email_verify_token' => Str::random(60),
                'password' => bcrypt($password),
                'gender' => $request->gender,
                'active' => $request->active,
            ]);

            $user->save();
            $user->assignRole('registred-user');


            $SendVerifyEmail = $request->send_verify_email;
            $SendWelcomeSms = $request->send_welcome_sms;

            ProcessCreateUserByOperator::dispatchAfterResponse($user, $password, $operator, $SendVerifyEmail, $SendWelcomeSms);


            return response()->json([
                'message' => 'User has ben Created successful!',
                'user' => $user,
            ], 201);





            // If admin dos not permission to create user
        } else {
            return response()->json([
                'message' => 'You don have permission'
            ], 402);
        }
    }
}
