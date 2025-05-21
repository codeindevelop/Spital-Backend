<?php

namespace Modules\Settings\Database\Seeders\SmsSettings;


use Illuminate\Database\Seeder;
use Modules\Settings\App\Models\SmsSetting;


class SmsSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        //Seed SMS Setting

        SmsSetting::create([
            "send_sms_company" => 'فراز اس ام اس',
            "send_sms_server" => env('SEND_SMS_SERVER'),
            "send_sms_number" => env('SEND_SMS_NUMBER'),
            "receive_sms_number" => env('RECIVE_SMS_NUMBER'),
            "api_key" => env('SMS_API_KEY'),
            "active" => true,

        ]);


    }
}
