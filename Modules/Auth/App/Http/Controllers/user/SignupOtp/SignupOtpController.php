<?php

namespace Modules\Auth\App\Http\Controllers\user\SignupOtp;

use Modules\Files\App\Http\Controllers\Controller;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\User\App\Models\User;
use Modules\User\App\Models\UserVerify;

class SignupOtpController extends Controller
{

    public function setUserMobileNumber(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();

        if ($user) {

            $validator = Validator::make($request->all(), [
                'mobile_number' => ['required', 'unique:users'],
            ]);

            // Check Validator Error
            if ($validator->fails()) {
                $MobileNumbelExist = User::where('mobile_number', $request->mobile_number)->first();

                // If user Mobile number Exist
                if ($MobileNumbelExist) {

                    return response()->json([
                        'error' => 'Mobile Number has Exist'
                    ], 401);
                }
            }
            // Create Random Code for user
            $code = str_pad(mt_rand(625, 999999), 6, '753', STR_PAD_LEFT);

            // save token and new mobile number in DB for validate
            $user->update([
                'mobile_verify_token' => $code,
                'mobile_number' => $request->mobile_number
            ]);

            // set log for user
            activity()
                ->withProperties(['user_id' => $user->id])
                ->log('send new otp code for verify mobile');

            $apikey = env('SMS_API_KEY');
            $client = new \GuzzleHttp\Client([
                'headers' => ['Content-Type' => 'application/json', 'Authorization' => "AccessKey {$apikey}"]
            ]);

            // Values to send
            $patternValues = [
                "code" => $code,
            ];

            // Begin Post sms
            $client->post(
                env('SEND_SMS_SERVER'),
                ['body' => json_encode(
                    [
                        'pattern_code' => env('SIGNUP_SMS_PAT_CODE'),
                        'originator' => env('SEND_SMS_NUMBER'),
                        'recipient' => $user->mobile_number,
                        'values' => $patternValues,
                    ]
                )]
            );

            return response()->json([
                'message' => 'code has ben created'
            ], 200);
        } else {
            return response()->json([
                'message' => 'user auth faild !'
            ], 402);
        }
    }


    // Verify OTP Code
    public function verifyMobileOTP(Request $request): \Illuminate\Http\JsonResponse
    {

        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'error' => 'please login'
            ], 401);
        }
        $validator = Validator::make($request->all(), [
            'mobile_verify_token' => ['required'],
        ]);

        // Check Validator Error
        if ($validator->fails()) {


            return response()->json([
                'error' => 'mobile verify token is require'
            ], 401);
        }


        $token = User::where('mobile_verify_token', $request->mobile_verify_token)->first();
        // If mobile token has exist youser has ben verified
        if ($token) {



            $token->update([
                'mobile_verify_token' => null,
            ]);

            // update verify table for this user
            $verifyInfo = UserVerify::where('user_id', $user->id)->first();

            $$verifyInfo->update([
                'mobile_verified_at' => Carbon::now(),
                'verify_mobile_number' => true,
            ]);


            // set log for user
            activity()
                ->withProperties(['user_id' => $user->id, 'ip' => $request->ip()])
                ->log('user verify by mobile');



            return response()->json([
                'message' => 'Mobile is verify successfull'
            ], 201);


            // if mobile token has ben exipired
        } else {
            return response()->json([
                'message' => 'your code has expired or wrong!'
            ], 400);
        }
    }



    // Get OTP Code Again
    public function getMobileConfirmCodeAgain(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'mobile_number' => ['required']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'err' => $validator->errors()->first()
            ], 400);
        }

        $mobile_exist = User::where('mobile_number', $request->mobile_number)->first();

        if ($mobile_exist) {
            // Check User by Mobile number
            $user = User::where('mobile_number', $request->mobile_number)->first();

            // Create Random Code for user
            $code = str_pad(mt_rand(625, 999999), 6, '753', STR_PAD_LEFT);

            // save token in DB for validate
            $user->update([
                'mobile_verify_token' => $code
            ]);

            // set log for user
            activity()
                ->withProperties(['user_id' => $user->id])
                ->log('send new otp code for verify mobile');

            $apikey = env('SMS_API_KEY');
            $client = new \GuzzleHttp\Client([
                'headers' => ['Content-Type' => 'application/json', 'Authorization' => "AccessKey {$apikey}"]
            ]);

            // Values to send
            $patternValues = [
                "code" => $code,
            ];

            // Begin Post sms
            $client->post(
                env('SEND_SMS_SERVER'),
                ['body' => json_encode(
                    [
                        'pattern_code' => env('SIGNUP_SMS_PAT_CODE'),
                        'originator' => env('SEND_SMS_NUMBER'),
                        'recipient' => $user->mobile_number,
                        'values' => $patternValues,
                    ]
                )]
            );

            return response()->json([
                'message' => 'code has ben created'
            ], 200);
        } else {
            // if user mobile number is not exist
            return response()->json([
                'message' => 'mobile number has not existed in system'
            ], 402);
        }
    }
}
