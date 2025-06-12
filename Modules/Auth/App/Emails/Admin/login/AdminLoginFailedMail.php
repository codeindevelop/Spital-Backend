<?php

namespace Modules\Auth\App\Emails\Admin\login;

use Modules\User\App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Hekmatinasser\Verta\Verta;

class AdminLoginFailedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public User $targetUser;
    public string $ip;
    public string $date;
    public string $time;

    public function __construct(User $targetUser, string $ip)
    {
        $this->targetUser = $targetUser;
        $this->ip = $ip;
        $this->date = Verta::now()->format('j-n-Y');
        $this->time = Verta::now()->format('H:i');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: env('LOGIN_FAILED_EMAIL_TO_USER_SUBJECT', 'هشدار: تلاش ناموفق برای ورود به حساب شما'),
        );
    }

    public function content(): Content
    {
        // استفاده از رابطه لودشده
        $firstName = $this->targetUser->personalInfo ? $this->targetUser->personalInfo->first_name : 'کاربر گرامی';


        return new Content(

            view: 'emails.admin.auth.adminLoginFailed',
            with: [
                'first_name' => $firstName,
                'targetUser' => $this->targetUser,
                'date' => $this->date,
                'time' => $this->time,
                'ip' => $this->ip,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
