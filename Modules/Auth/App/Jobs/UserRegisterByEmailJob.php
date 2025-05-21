<?php

namespace Modules\Auth\App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Modules\Auth\App\Emails\Signup\UserSignup;
use Modules\User\App\Models\User;
use Modules\User\App\Models\UserVerify;


class UserRegisterByEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public User $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        //
        $this->user = $user;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {

        // Try To Send Activation Email
        $user = $this->user;

        // Try To Send Mail to User
        Mail::to($user->email)->send(new UserSignup($user));

    }
}
