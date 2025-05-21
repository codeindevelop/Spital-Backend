<?php

namespace Modules\Auth\App\Http\Controllers\user\LoginEmail;


use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Modules\Auth\App\Emails\login\UserLoginMail;
use Modules\Auth\App\Events\UserLogedinByEmailEvent;
use Modules\User\App\Models\User;


class UserLoginByEmailController extends Controller
{


    // Login User By Email
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean',
        ]);

        // Check User Has Existed in DB
        $targetUser = User::where('email', $request->email)->first();
        if ($targetUser === null) {
            return response()->json(['message' => 'User Dos-not Existed'], 401);
        }

        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Password has wrong'], 401);
        }

        $user = Auth::user();


        $tokenResult = $user->createToken('Login Token');
        $token = $tokenResult->token;
        $ip = $request->getClientIp();

        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        } else {
            $token->expires_at = Carbon::now()->addSeconds(config("auth.password_timeout"));
        }
        $token->save();

        // Dispatch Event
        UserLogedinByEmailEvent::dispatch($user, $ip);



        return response()->json([
            'data' => [
                'access_token' => $tokenResult->accessToken,
                'expires_at' => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString(),
            ]
        ]);
    }

}
