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

class UserEmailVerifyed extends Mailable
{
    use Queueable, SerializesModels;

    protected  $user;
    /**
     * Create a new message instance.
     */
    public function __construct(User $user)

    {

        $this->user =  $user;
        $this->date = Verta(Carbon::now())->format('j-n-Y');
        $this->time = Verta(Carbon::now())->format('H:i');
    }

    /**
     * Get the message envelope.
     */
    public function build()
    {
        return $this->view('emails.auth.activesignup')->subject(env('VERIFY_EMAIL_TO_USER_SUBJECT'))->with([
            'first_name' => $this->user->first_name,
            'date' => $this->date,
            'time' => $this->time,
        ]);
    }
}
