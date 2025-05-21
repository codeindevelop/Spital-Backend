<?php

namespace Modules\Auth\App\Http\Controllers\user\SignupSocial;

use App\Http\Controllers\Controller;
use Modules\User\App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;


class SocialAuthController extends Controller
{

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();

    }


    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();
        $user = User::where('email', $googleUser->email)->first();

        if ($user) {
            $tokenResult = $user->createToken('Login Token');
            $token = $tokenResult->token;
            $token->save();

            return response()->json([
                'data' => [
                    'access_token' => $tokenResult->accessToken,
                    'user' => $user
                ],
            ]);
        }
        if (!$user) {

            $user = User::create([
                'first_name' => $googleUser->name, 'email' => $googleUser->email,
                'password' => Hash::make(rand(100000, 999999))
            ]);
            Auth::login($user);
            $tokenResult = $user->createToken('Login Token');
            $token = $tokenResult->token;
            $token->save();
            return response()->json([
                'access_token' => $tokenResult->accessToken,
                'user' => $user
            ], 200);
        }
    }
}
