<?php

namespace Modules\Auth\App\Emails\login;


use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Modules\User\App\Models\User;

class UserLoginMail extends Mailable
{
    use Queueable, SerializesModels;

    protected  $user;
    protected  $ip;
    /**
     * Create a new message instance.
     */
    public function __construct(User $user,$ip)

    {

        $this->user =  $user;
        $this->ip =  $ip;
        $this->date = Verta(Carbon::now())->format('j-n-Y');
        $this->time = Verta(Carbon::now())->format('H:i');
    }


    public function build(): UserLoginMail
    {
        return $this->view('emails.auth.login')->subject(env('LOGIN_EMAIL_TO_USER_SUBJECT'))->with([
            'first_name' => $this->user->first_name,
            'date' => $this->date,
            'time' => $this->time,
            'ip' => $this->ip,
        ]);
    }
}
