<?php

namespace Modules\Localization\Database\Seeders\Translation\Email\Auth\Fa;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Localization\App\Models\EmailTranslation;
use Modules\Localization\App\Models\Language;

class FaEmailTranslationSeeder extends Seeder
{
    public function run(): void
    {

        $translations = [
            [
                'name' => 'signup_verify_email',
                'subject' => 'تایید حساب کاربری',
                'language_id' => '2',
                'content' => ' به خانواده آکوپیلا خوش آمدید ، ثبت نام شما با موفقیت
            در سامانه سایت انجام گردیده
            ، جهت فعالسازی حساب کاربری خود لطفا روی گزینه فعالسازی حساب کاربری کلیک نمایید',


            ], [
                'name' => 'signup_verify_email',
                'subject' => 'User Email Verification',
                'language_id' => '1',
                'content' => 'Welcome to the Aqopila family, your registration has been successfully
completed in the site system
, to activate your account, please click on the Activate Account option',


            ],


        ];

        foreach ($translations as $translation) {
            EmailTranslation::create($translation);
        }


    }
}
