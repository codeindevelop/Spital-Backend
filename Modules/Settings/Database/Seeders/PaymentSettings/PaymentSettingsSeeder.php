<?php

namespace Modules\Settings\Database\Seeders\PaymentSettings;


use Illuminate\Database\Seeder;
use Modules\Settings\App\Models\PaymentGateway;


class PaymentSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        //Seed Auth Setting

        PaymentGateway::create([
            "gateway_name" => 'زیبال',
            "merchant_id" => env('ZIBAL_MERCHANT_ID'),
            "request_url" => 'https://gateway.zibal.ir/v1/request',
            "start_url" => 'https://gateway.zibal.ir/start',
            "verify_url" => 'https://gateway.zibal.ir/v1/verify',
            "inquiry_url" => 'https://gateway.zibal.ir/v1/inquiry',
            "call_back_url" => env('PAYMENT_CALL_BACK_URL'),
            "active" => true,

        ]);


    }
}
