<?php

namespace Modules\User\App\Http\Controllers\user;


use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\User\App\Services\GetUserProfileService;
use Symfony\Component\HttpFoundation\Response;

class UserProfileController extends Controller
{

    // get user profile
    public function profile(): JsonResponse
    {

        $userData = new GetUserProfileService();
        $user = $userData->getUser();

        return response()->json([
            'user' => $user['user'],
            'role' => $user['role'],
        ], Response::HTTP_OK);
    }
}
