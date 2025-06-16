<?php

namespace Modules\Settings\Database\Seeders\Seo\TelegramBotSettings;


use Illuminate\Database\Seeder;
use Modules\Settings\App\Models\Eshop\TelegramBotsetting;


class TelegramBotSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        //Seed Telegram Bot Setting

        TelegramBotsetting::create([
            "bot_api_tokent" => '000000',
            "active" => true,


        ]);


    }
}
