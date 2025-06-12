<?php

namespace Modules\Auth\App\Http\Controllers\admin\auth;

use Modules\Files\App\Http\Controllers\Controller;
use App\Jobs\Auth\ProcessUserVerify;
use App\Jobs\verification\ProcessUserActived;
use Modules\User\App\Models\User;
use Modules\User\App\Models\UserVerify;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class VerificationController extends Controller
{

    // Change User access to panel by admin
    public function changeUserVerifyMod(Request $request)
    {
        $admin = Auth::user();
        $user = User::where('id', $request->user_id)->first();
        $validator = Validator::make($request->all(), [

            'user_id' => ['required'],
            'action' => ['required'],

        ]);


        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first(), 'status' => false], 400);
        } else {
            if ($admin->can('active user')) {

                // if admin verified user
                if ($request->action == 'verify') {

                    $verifyInfo = UserVerify::where('user_id', $user->id)->first();



                    // Assign Role for user
                    $user->removeRole('regular-user');
                    $user->removeRole('under-review-user');
                    $user->removeRole('under-review-user');
                    $user->assignRole('verified-user');





                    $verifyInfo->update([
                        'reject_status' => null,
                        'reject_dateTime' => null,
                        'verify_dateTime' => Carbon::now(),
                        'status' => 'verified',
                    ]);
                    ProcessUserVerify::dispatchAfterResponse($user, $verifyInfo,$admin);

                    return response()->json([
                        [
                            'data' => [
                                'message' => 'Successfully user verify'
                            ]
                        ],

                    ], 201);
                }

                if (
                    $request->action == 'reject'
                ) {
                    $verifyInfo = UserVerify::where('user_id', $user->id)->first();

                    $verifyInfo->update([
                        'reject_status' => $request->reject_status,
                        'reject_dateTime' => Carbon::now(),
                        'status' => 'reject',
                    ]);





                    ProcessUserVerify::dispatchAfterResponse($user, $verifyInfo,$admin);

                    return response()->json([
                        [
                            'data' => [
                                'message' => 'user has ben rejected'
                            ]
                        ],

                    ], 201);
                }
            }
        }
    }
}
