<?php

namespace Modules\Settings\Database\Seeders\Seo\EmailSettings;


use Illuminate\Database\Seeder;
use Modules\Settings\App\Models\EmailSetting;


class EmailSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        //Seed Email Setting

        EmailSetting::create([
            "send_email_company" => 'parsweb-server',
            "send_email_host" => 'caspian.pws-dns.net',
            "email_protocol" => 'smtp',
            "email_encryption" => 'ssl',
            "send_email_port" => '465',
            "send_email_name" => 'ابریکد',
            "send_email_url" => 'noreply@abrecode.com',
            "smtp_username" => 'noreply@abrecode.com',
            "smtp_password" => 'xvMB(U8Mp$lq',

        ]);

        EmailSetting::create([
            "send_email_company" => 'parsweb-server',
            "send_email_host" => 'caspian.pws-dns.net',
            "email_protocol" => 'smtp',
            "email_encryption" => 'ssl',
            "send_email_port" => '1025',
            "send_email_name" => 'باکسیکد',
            "send_email_url" => 'info@abrecode.com',
            "smtp_username" => 'info@abrecode.com',
            "smtp_password" => 'ZNZ_=}w,X-4D',

        ]);

        EmailSetting::create([
            "send_email_company" => 'parsweb-server',
            "send_email_host" => 'caspian.pws-dns.net',
            "email_protocol" => 'smtp',
            "email_encryption" => 'ssl',
            "send_email_port" => '1025',
            "send_email_name" => 'باکسیکد',
            "send_email_url" => 'support@abrecode.com',
            "smtp_username" => 'support@abrecode.com',
            "smtp_password" => '#F.$w{+]!{lP',

        ]);
        EmailSetting::create([
            "send_email_company" => 'parsweb-server',
            "send_email_host" => 'caspian.pws-dns.net',
            "email_protocol" => 'smtp',
            "email_encryption" => 'ssl',
            "send_email_port" => '1025',
            "send_email_name" => 'باکسیکد',
            "send_email_url" => 'sales@abrecode.com',
            "smtp_username" => 'sales@abrecode.com',
            "smtp_password" => 'Op[6bQJ4!0W1',

        ]);


    }
}
