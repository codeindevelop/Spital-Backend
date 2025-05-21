<?php

namespace Modules\Auth\App\Emails\Signup;

use Modules\User\App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ActiveUser extends Mailable
{
    use Queueable, SerializesModels;

    private User $user;
    private string $date;
    private string $time;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)

    {

        $this->user =  $user;
        $this->date = Verta(Carbon::now())->format('j-n-Y');
        $this->time = Verta(Carbon::now())->format('H:i');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): static
    {
        return $this->view('emails.auth.activesignup')->subject(env('VERIFY_EMAIL_TO_USER_SUBJECT'))->with([
            'first_name' => $this->user->first_name,
            'date' => $this->date,
            'time' => $this->time,

        ]);
    }
}
