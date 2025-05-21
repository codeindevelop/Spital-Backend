<?php

namespace Modules\Settings\Database\Seeders\GeneralSettings;


use Illuminate\Database\Seeder;
use Modules\Settings\App\Models\GeneralSetting;


class GeneralSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        //create General Setting

        GeneralSetting::create([
            "portal_name" => 'ابریکد',
            "portal_desc" => 'مارکت تخصصی برنامه نویسی',
            "time_zone" => 295,
            "maintenance_mode" => false,
            "signup_type" => "email",
            "user_panel_url" => env("USERS_PANEL_URL"),

        ]);


    }
}
