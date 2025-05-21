<?php

namespace Modules\Auth\App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Modules\Auth\App\Emails\login\UserLoginMail;
use Modules\Auth\App\Emails\logout\UserLogoutMail;
use Modules\User\App\Models\User;



class UserLogoutJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public User $user;
    public string $ip;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user , string $ip)
    {
        //
        $this->user = $user;
        $this->ip = $ip;

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
        $ip = $this->ip;

        // Try To Send Mail to User
        Mail::to($user->email)->send(new UserLogoutMail($user, $ip));

    }
}
