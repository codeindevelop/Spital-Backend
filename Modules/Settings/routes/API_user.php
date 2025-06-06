<?php


use Illuminate\Support\Facades\Route;
use Modules\Auth\App\Http\Controllers\user\LoginEmail\UserLoginByEmailController;
use Modules\Auth\App\Http\Controllers\user\logout\UserLogoutController;
use Modules\Auth\App\Http\Controllers\user\Profile\UserProfileController;
use Modules\Auth\App\Http\Controllers\user\SignupEmail\SignupByEmailController;
use Modules\Auth\App\Http\Controllers\user\SignupSocial\SocialAuthController;
use Modules\Auth\App\Http\Controllers\user\VerifyMobile\UserVerifyMobileController;


Route::prefix('v1/user')->group(function () {

    Route::post('/signup-email', [SignupByEmailController::class, 'signupUser']);

    // User Login Route
    Route::post('/login-email', [UserLoginByEmailController::class, 'login']);

    // User Signup Route
//    Route::post('/signup', [SignupController::class, 'register']);

//    Route::post('check-username', [CheckExistsController::class, 'checkUserNameExist']);
//    Route::post('check-email', [CheckExistsController::class, 'checkUserEmailExist']);
//    Route::post('check-mobile', [CheckExistsController::class, 'checkUserMobileExist']);


    // Auth login and register with Google
    Route::get('/google/redirect', [SocialAuthController::class, 'redirectToGoogle'])->name('google.redirect');
    Route::get(
        '/google/callback',
        [SocialAuthController::class, 'handleGoogleCallback']
    )->name('google.callback');


    Route::middleware("auth:api")->group(function () {

//        Route::get('signup/activation/{token}', [UserSignupController::class, 'emailSignupActive']);

        Route::post('/mobile-otp', [UserVerifyMobileController::class, 'setUserMobileNumber']);
        Route::post('/verify-otp', [UserVerifyMobileController::class, 'verifyMobileOTP']);
        Route::post('/get-code-again', [UserVerifyMobileController::class, 'getMobileConfirmCodeAgain']);

        // Get User Profile
        Route::get('profile', [UserProfileController::class, 'profile']);


        // User Logout
        Route::post('logout', [UserLogoutController::class, 'logout']);
    });

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


