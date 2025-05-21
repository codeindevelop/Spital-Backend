<?php

namespace Modules\User\App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;



class GetUserProfileJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

//    private SendOtpEmailService $SendOtpEmailService;


    public function __construct()
    {

    }

    public function handle(): void
    {

        Log::info("TempUser Message Received From tempUserSignupTopic successfully!");

    }
}
