<?php

namespace Modules\Auth\App\Http\Controllers\user\logout;


use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Auth\App\Events\UserLogoutEvent;



class UserLogoutController extends Controller
{


    /**
     * Logout user (Revoke the token)
     *
     * @param  Request  $request
     * @return JsonResponse [string] message
     */
    public function logout(Request $request): JsonResponse
    {
        $user = Auth::user();
        $ip = $request->getClientIp();


        $request->user()->token()->revoke();

        UserLogoutEvent::dispatch($user,$ip);

        return response()->json([
            'message' => 'Successfully logged out'
        ], 200);
    }

}
