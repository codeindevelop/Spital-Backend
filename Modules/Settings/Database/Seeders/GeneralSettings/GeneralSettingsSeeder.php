<?php

namespace Modules\Settings\Database\Seeders\GeneralSettings;

use Carbon\Carbon;
use Illuminate\Database\Seeder;

use Modules\Localization\App\Models\countries\Country;
use Modules\Localization\App\Models\Language;
use Modules\Localization\App\Models\TimeZone;

use Illuminate\Support\Str;
use Modules\Settings\App\Models\System\GeneralSetting;

class GeneralSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // دریافت اولین رکورد از جداول مرتبط
        $timezone = TimeZone::where('value', 'Asia/Tehran')->firstOrFail();
        $language = Language::where('code', 'fa')->firstOrFail();
        $country = Country::where('iso', 'IR')->firstOrFail();

        GeneralSetting::create([
            'id' => Str::uuid(),
            'timezone_id' => $timezone->id,
            'language_id' => $language->id,
            'country_id' => $country->id,
            'site_name' => 'وب سایت من',
            'site_desc' => 'وب سایت مدیریت شده توسط اسپیتال',
            'maintenance_mode' => false,
            'user_panel_url' => 'https://panel.example.com',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
