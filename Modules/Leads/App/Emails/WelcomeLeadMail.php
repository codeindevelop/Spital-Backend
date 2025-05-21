<?php

namespace Modules\Leads\App\Emails;


use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\Leads\App\Models\Lead;

class WelcomeLeadMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected Lead $lead;


    /**
     * Create a new message instance.
     */
    public function __construct(Lead $lead)
    {

        $this->lead = $lead;
        $this->date = Verta(Carbon::now())->format('j-n-Y');
        $this->time = Verta(Carbon::now())->format('H:i');
    }


    public function build(): WelcomeLeadMail
    {
        return $this->view('emails.crm.welcome_lead')->subject(env('WELCOME_LEAD_MAIL_SUBJECT'))->with([
            'first_name' => $this->lead->first_name,
            'date' => $this->date,
            'time' => $this->time,

        ]);
    }
}
