<?php

namespace Modules\Leads\App\Listeners;


use Modules\Auth\App\Events\LeadConvertToUserEvent;
use Modules\Auth\App\Events\UserRegisteredEvent;
use Modules\Auth\App\Jobs\UserRegisterByEmailJob;
use Modules\Leads\App\Jobs\SendWelcomeLeadConvertToUserJob;
use Modules\User\App\Models\UserPersonalInfo;


class ConvertLeadToUserListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(LeadConvertToUserEvent $event): void
    {

        $user = $event->user;

        $userPersonalInfo = new UserPersonalInfo([
            'user_id' => $user->id,
            'account_type' => null,
            'nationality' => null,
            'father_name' => null,
            'gender' => null,
            'date_of_birth' => null,
            'shenasname_no' => null,
            'mellicode' => null,
            'passport_number' => null,
            'country_id' => 101,
            'home_address' => null,
            'phone_number' => null,
            'image_mellicard' => null,
            'image_shenasname' => null,
            'image_passport' => null,
            'verified' => false,
            'status' => 'تایید نشده',
            'user_verified_at' => null,
        ]);


        // Save User Personal Info
        $user->personalInfos()->save($userPersonalInfo);

        // create new wallet for user
//        $wallet = new Wallet([
//            'user_id' => $user->id,
//            'usd_balance' => 0,
//            'irt_balance' => 0,
//            'cad_balance' => 0,
//            'pound_balance' => 0,
//            'btc_balance' => 0,
//            'usdt_balance' => 0,
//            'eth_balance' => 0,
//            'bch_balance' => 0,
//            'dash_balance' => 0,
//            'ltc_balance' => 0,
//            'imc_balance' => 0,
//            'usdterc20_balance' => 0,
//        ]);
//        $wallet->save();


        // Assign Registered registered-user Role
        $user->assignRole('regular-user');

//        Mail::to($user->email)->send(new UserSignup($user));

        // Dispatch Jobs After user register for send OTP code Email --- Produce message to Kafka
        SendWelcomeLeadConvertToUserJob::dispatchAfterResponse($user);


    }
}
