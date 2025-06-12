<?php

namespace Modules\Auth\App\Http\Controllers\user\SignupEmail;

use Modules\Files\App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;


use Modules\Auth\App\Services\SignupByEmailService;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;


class SignupByEmailController extends Controller
{

    private SignupByEmailService $signupByEmailService;

    public function __construct(SignupByEmailService $signupByEmailService)
    {
        $this->signupByEmailService = $signupByEmailService;
    }

    public function signupUser(Request $request): JsonResponse
    {

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed|max:255',
        ]);

        // If Validator has been Failed
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first()
            ], 422);
        }

        // Get Client IP
        $ip =  $request->getClientIp();


        // Send Validated Data to Service
        $response = $this->signupByEmailService->signup($request->all() + ['ip' => $ip]);

        return response()->json([
            'data' => [
                'message' => 'User registered successfully. Please check your email for verification.',
//                'user' => $response['user'],
                'accessToken' => $response['accessToken'],
            ],

        ], Response::HTTP_CREATED);

    }


}
