<?php

namespace Modules\Settings\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Settings\Database\Seeders\AuthSettings\AuthSettingsSeeder;
use Modules\Settings\Database\Seeders\EmailSettings\EmailSettingsSeeder;
use Modules\Settings\Database\Seeders\GeneralSettings\GeneralSettingsSeeder;
use Modules\Settings\Database\Seeders\PaymentGatewaySettings\PaymentGatewaysSettingsSeeder;
use Modules\Settings\Database\Seeders\SmsSettings\SmsSettingsSeeder;
use Modules\Settings\Database\Seeders\SocialSettings\SocialSettingsSeeder;
use Modules\Settings\Database\Seeders\TelegramBotSettings\TelegramBotSettingsSeeder;

class SettingsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // General Settings Seed Data
        $this->call(GeneralSettingsSeeder::class);

        //  Auth Settings Seed Data
        $this->call(AuthSettingsSeeder::class);

        //   security Settings Seed Data

        //   Payment Gateway Settings Seed Data
        $this->call(PaymentGatewaysSettingsSeeder::class);
        //   Payment Settings Seed Data

        //   SMS Settings Seed Data
        $this->call(SmsSettingsSeeder::class);

        //   Email Settings Seed Data
        $this->call(EmailSettingsSeeder::class);

        //   Robots Settings Seed Data
        $this->call(TelegramBotSettingsSeeder::class);

        //   social Settings Seed Data
        $this->call(SocialSettingsSeeder::class);


    }
}
