<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Auth\App\Http\Controllers\admin\auth\AdminAuthController;
use Modules\Auth\App\Http\Controllers\user\SignupEmail\SignupByEmailController;


Route::prefix('v1/admin')->group(function () {
//
//    Route::post('/signup-email', [SignupByEmailController::class, 'signupUser']);

    // User Login Route
    Route::post('login-managers', [AdminAuthController::class, 'adminLogin']);

    // User Signup Route
//    Route::post('/signup', [SignupController::class, 'register']);

//    Route::post('check-username', [CheckExistsController::class, 'checkUserNameExist']);
//    Route::post('check-email', [CheckExistsController::class, 'checkUserEmailExist']);
//    Route::post('check-mobile', [CheckExistsController::class, 'checkUserMobileExist']);


    // Auth login and register with Google
//    Route::get('/google/redirect', [SocialAuthController::class, 'redirectToGoogle'])->name('google.redirect');
//    Route::get(
//        '/google/callback',
//        [SocialAuthController::class, 'handleGoogleCallback']
//    )->name('google.callback');


//    Route::middleware("auth:api")->group(function () {
//
//        Route::get('signup/activation/{token}', [UserSignupController::class, 'emailSignupActive']);
//
//        Route::post('otp', [OtpAuthController::class, 'setUserMobileNumber']);
//        Route::post('verify-otp', [OtpAuthController::class, 'verifyMobileOTP']);
//        Route::post('get-code-again', [OtpAuthController::class, 'getMobileConfirmCodeAgain']);
//
//        // Get User Profile
//        Route::get('profile', [UserProfileController::class, 'profile']);
//
//        // Get User Verify-Info
//        Route::get('verify-info', [UserVerifyController::class, 'getVerifyStatus']);
//
//        // verify user info
//        Route::put('verify-step-two', [UserVerifyController::class, 'verifyStepTwo']);
//
//        // verify user three
//        Route::put('verify-step-three', [UserVerifyController::class, 'verifyStepThree']);
//
//        // verify user three
//        Route::put('verify-step-four', [UserVerifyController::class, 'verifyStepFour']);
//
//        // User Logout
//        Route::post('logout', [UserLoginController::class, 'logout']);
//    });

    //  Forgot password route group
//    Route::group([
//        'middleware' => 'api',
//        'prefix' => 'password'
//    ], function () {
//        Route::post('create', [PasswordResetController::class, 'create']);
//        Route::get('find/{token}', [PasswordResetController::class, 'find']);
//        Route::post('reset', [PasswordResetController::class, 'reset']);
//    });

});


