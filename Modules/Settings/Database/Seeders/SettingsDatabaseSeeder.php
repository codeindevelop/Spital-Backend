<?php

namespace Modules\Settings\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Settings\Database\Seeders\Seo\AuthSettings\AuthSettingsSeeder;
use Modules\Settings\Database\Seeders\Seo\EmailSettings\EmailSettingsSeeder;
use Modules\Settings\Database\Seeders\Seo\GeneralSettings\GeneralSettingsSeeder;
use Modules\Settings\Database\Seeders\Seo\PaymentGatewaySettings\PaymentGatewaysSettingsSeeder;
use Modules\Settings\Database\Seeders\Seo\SeoGeneralSettingsSeeder;

use Modules\Settings\Database\Seeders\Seo\SeoRepresentationSettingSeeder;
use Modules\Settings\Database\Seeders\Seo\SmsSettings\SmsSettingsSeeder;
use Modules\Settings\Database\Seeders\Seo\SocialSettings\SocialSettingsSeeder;
use Modules\Settings\Database\Seeders\Seo\TelegramBotSettings\TelegramBotSettingsSeeder;

class SettingsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // General Settings Seed Data
//        $this->call(GeneralSettingsSeeder::class);
//
//
//        //  Auth Settings Seed Data
//        $this->call(AuthSettingsSeeder::class);
//
//        //   security Settings Seed Data
//
//        //   Payment Gateway Settings Seed Data
//        $this->call(PaymentGatewaysSettingsSeeder::class);
//        //   Payment Settings Seed Data
//
//        //   SMS Settings Seed Data
//        $this->call(SmsSettingsSeeder::class);
//
//        //   Email Settings Seed Data
//        $this->call(EmailSettingsSeeder::class);
//
//        //   Robots Settings Seed Data
//        $this->call(TelegramBotSettingsSeeder::class);
//
//        //   social Settings Seed Data
//        $this->call(SocialSettingsSeeder::class);


        //   SEO Settings Seed Data
        $this->call(SeoGeneralSettingsSeeder::class);
        $this->call(SeoRepresentationSettingSeeder::class);


    }
}
