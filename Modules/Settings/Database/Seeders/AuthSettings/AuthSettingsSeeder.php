<?php

namespace Modules\Settings\Database\Seeders\Seo\AuthSettings;


use Illuminate\Database\Seeder;
use Modules\Settings\App\Models\AuthSetting;


class AuthSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        //Seed Auth Setting

        AuthSetting::create([
            "users_can_login" => true,
            "users_can_signup" => true,
            "admins_can_login" => true,
            "admins_can_signup" => true,
            "users_sigup_type" => 'email',
            "users_login_type" => 'email',
            "admins_sigup_type" => 'email',
            "admins_login_type" => 'email',
            "enable_reset_password" => true,

        ]);


    }
}
