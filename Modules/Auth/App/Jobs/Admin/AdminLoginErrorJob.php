<?php

namespace Modules\Auth\App\Jobs\Admin;

//use App\Mail\Auth\AdminLoginFailedMail;
use Modules\Auth\App\Emails\Admin\login\AdminLoginFailedMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

use Modules\User\App\Models\User;


class AdminLoginErrorJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public User $user;
    public string $ip;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, string $ip)
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

        activity()
            ->causedByAnonymous()
            ->withProperties([
                'email' => $user->email,

            ])
            ->log('gabl email');

        // Try To Send Mail to User
        Mail::to($user->email)->send(new AdminLoginFailedMail($user, $ip));

    }
}
