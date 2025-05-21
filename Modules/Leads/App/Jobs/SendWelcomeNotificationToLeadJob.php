<?php

namespace Modules\Leads\App\Jobs;

use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Modules\Leads\App\Emails\WelcomeLeadMail;
use Modules\Leads\App\Models\Lead;


class SendWelcomeNotificationToLeadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Lead $lead;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Lead $lead)
    {
        //
        $this->lead = $lead;

    }


    public function handle(): void
    {

        // Try To Load Lead data
        $lead = $this->lead;

        // If send email has been checked
        if ($lead->send_welcome_email) {
            // Try To Send Welcome Mail to Lead
            Mail::to($lead->email)->send(new WelcomeLeadMail($lead));
        }


        // If send SMS has been checked
        if ($lead->send_welcome_sms) {


            $apikey = env('SMS_API_KEY');
            $client = new Client([
                'headers' => ['Content-Type' => 'application/json', 'Authorization' => "AccessKey {$apikey}"]
            ]);

            // Values to send
            $patternValues = [
                "first_name" => $lead->first_name,
            ];

            // Begin Post sms
            $client->post(
                env('SEND_SMS_SERVER'),
                [
                    'body' => json_encode(
                        [
                            'pattern_code' => env('CREATE_LEAD_WELCOME_CODE'),
                            'originator' => env('SEND_SMS_NUMBER'),
                            'recipient' => $lead->mobile_number,
                            'values' => $patternValues,
                        ]
                    )
                ]
            );

        }


    }
}
