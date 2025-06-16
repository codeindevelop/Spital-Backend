<?php

namespace Modules\Settings\Database\Seeders\Seo\SocialSettings;


use Illuminate\Database\Seeder;
use Modules\Settings\App\Models\Eshop\SocialSetting;


class SocialSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        //Seed Social Setting

        SocialSetting::create([
            'instagram' => '',
            'telegram' => '',
            'whatsapp' => '',
            'whatsapp_business' => '',
            'facebook_user' => '',
            'facebook_page' => '',
            'twitter' => '',
            'skype' => '',
            'youtube' => '',
            'aparat' => '',
            'tiktok' => '',
            'pintrest' => '',
            'linkdin' => '',
            'dribbble' => '',
            'snapchat' => '',


        ]);


    }
}
