<?php

namespace Modules\Auth\App\Http\Controllers\user\Profile;


use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    // get user profile
    public function profile(): JsonResponse
    {
        $user = Auth::user();


        if ($user) {


            return response()->json([
                'data' => [
//                'id' => $user->id,
                    'user' => $user,
                    'verifyInfo' => $user->verifyInfo,
                    'personalInfos' => $user->personalInfos,


                    'roles' => $user->getRoleNames(),
                    'permissions' => $user->getAllPermissions()->pluck('name')
                ]
            ], 200);
        } else {
            return response()->json([
                'data' => [
                    'message' => "user has not exist",
                ]
            ], 404);
        }
    }

}
