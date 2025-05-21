<?php

namespace Modules\Auth\App\Emails\Signup;


use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Hekmatinasser\Verta\Verta;
use Modules\User\App\Models\User;
use Modules\User\App\Models\UserVerify;

/**
 * @property mixed $ip
 */
class UserSignup extends Mailable
{
    use Queueable, SerializesModels;

    protected User $user;

    private string $date;
    private UserVerify $userVerify;
    private string $activation_token;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {

        $this->user = $user;
        $this->activation_token = UserVerify::where('user_id', $user->id)->first()->email_verify_token;
        $this->date = Verta(Carbon::now())->format('H:i | Y-n-j');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): static
    {
        return $this->view('emails.auth.signup')->subject(env('SIGNUP_EMAIL_TO_USER_SUBJECT'))->with([
            'first_name' => $this->user->first_name,
            'activation_token' => $this->activation_token,
        ]);
    }
}
