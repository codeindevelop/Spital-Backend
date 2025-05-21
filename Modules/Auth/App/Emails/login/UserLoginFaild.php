<?php

namespace App\Mail\Auth;

use Modules\User\App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserLoginFaild extends Mailable
{
    use Queueable, SerializesModels;

    protected  $targetUser;
    protected  $ip;
    /**
     * Create a new message instance.
     */
    public function __construct(User $targetUser, $ip)

    {

        $this->targetUser =  $targetUser;
        $this->ip =  $ip;
        $this->date = Verta(Carbon::now())->format('j-n-Y');
        $this->time = Verta(Carbon::now())->format('H:i');
    }


    public function build()
    {
        return $this->view('emails.auth.loginfaild')->subject(env('LOGIN_FAILD_EMAIL_TO_USER_SUBJECT'))->with([
            'first_name' => $this->targetUser->first_name,
            'date' => $this->date,
            'time' => $this->time,
            'ip' => $this->ip,
        ]);
    }
}
