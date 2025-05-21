<?php

namespace Modules\Leads\App\Jobs;

use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\User\App\Models\User;


class SendWelcomeLeadConvertToUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public User $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        //
        $this->user = $user;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {

        // Try To Load Lead data
        $user = $this->user;


        $apikey = env('SMS_API_KEY');
        $client = new Client([
            'headers' => ['Content-Type' => 'application/json', 'Authorization' => "AccessKey {$apikey}"]
        ]);

        // Values to send
        $patternValues = [
            "first_name" => $user->first_name,
        ];

        // Begin Post sms
        $client->post(
            env('SEND_SMS_SERVER'),
            [
                'body' => json_encode(
                    [
                        'pattern_code' => env('ANSWER_IN_CHAT_SMS_PAT_CODE'),
                        'originator' => env('SEND_SMS_NUMBER'),
                        'recipient' => $user->mobile_number,
                        'values' => $patternValues,
                    ]
                )
            ]
        );

    }


}
