<?php

namespace Modules\Auth\App\Http\Controllers\user\ForgotPassword;

use Modules\Files\App\Http\Controllers\Controller;
use App\Jobs\Password\ProcessPasswordResetReq;
use App\Jobs\Password\ProcessPasswordResetSuc;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Modules\User\App\Models\User;
use Modules\User\App\Models\PasswordReset;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class PasswordResetController extends Controller
{
    /**
     * Create token password reset
     *
     * @param  [string] email
     * @return [string] message
     */
    public function create(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
        ]);
        $user = User::where('email', $request->email)->first();
        if (!$user)
            return response()->json([
                'data' => [
                    'erro' => 'We cant find a user with that e-mail address.'
                ]

            ], 404);
        $passwordReset = PasswordReset::updateOrCreate(
            [
                'email' => $user->email,
                'token' => Str::random(60),
            ]
        );


        if ($user && $passwordReset) {

            $ip = $request->ip();
            $token = $passwordReset->token;
            // dispatch process password req job
            ProcessPasswordResetReq::dispatchAfterResponse($user, $ip, $token);


            return response()->json([
                'data' => [
                    'message' => 'We have e-mailed your password reset link!'
                ]

            ], 200);
        }
    }

    /**
     * Find token password reset
     *
     * @param  [string] $token
     * @return [string] message
     * @return [json] passwordReset object
     */
    public function find($token)
    {
        $passwordToken = PasswordReset::where('token', $token)
            ->first();
        if (!$passwordToken)
            return response()->json([
                'data' => [
                    'message' => 'This password reset token is invalid.'
                ]

            ], 404);
        if (Carbon::parse($passwordToken->updated_at)->addMinutes(720)->isPast()) {
            $passwordToken->delete();
            return response()->json([
                'data' => [
                    'message' => 'This password reset token is invalid.'
                ]

            ], 404);
        }
        return response()->json([
            'data' => [
                'passwordToken' => $passwordToken
            ]

        ], 200);
    }

    /**
     * Reset password
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @param  [string] token
     * @return [string] message
     * @return [json] user object
     */
    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string|confirmed',
            'token' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => [
                    'err' => $validator->errors()->first()
                ]

            ], 400);
        }




        $passwordReset = PasswordReset::where([
            ['token', $request->token],
            ['email', $request->email]
        ])->first();

        $user = User::where('email', $passwordReset->email)->first();

        // if password most be unused before on the last dates
        if ($user->last_password == $request->password && $user->password == $request->password) {
            return response()->json([
                'data' => [
                    'err' => 'you most be select unused password on the last dates'
                ]
            ], 400);
        }



        if (!$passwordReset)
            return response()->json([
                'data' => [
                    'message' => 'This password reset token is invalid.'
                ]
            ], 404);
        if (!$user)

            // set log for user
            activity()
                ->withProperties(['user_id' => $request->email])
                ->log('err to find user for change password');

        return response()->json([
            'data' => [
                'message' => 'We cant find a user with that e-mail address.'
            ]

        ], 404);
        $user->password = bcrypt($request->password);
        $user->save();
        $passwordReset->delete();

        // Dispatch password reset suc Job
        ProcessPasswordResetSuc::dispatchAfterResponse($user);



        return response()->json([
            'data' => [
                'massage' => 'You are changed your password successful.'
            ]

        ], 202);
    }
}
