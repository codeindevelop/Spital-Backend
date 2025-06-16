<?php

namespace Modules\Settings\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Settings\Database\Seeders\EshopSettings\General\EshopGeneralSettingsTableSeeder;
use Modules\Settings\Database\Seeders\GeneralSettings\GeneralSettingsSeeder;
use Modules\Settings\Database\Seeders\Seo\SeoGeneralSettingsSeeder;
use Modules\Settings\Database\Seeders\Seo\SeoRepresentationSettingSeeder;

class SettingsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // System General Settings
        $this->call(GeneralSettingsSeeder::class);


        // Eshop General Settings
        $this->call(EshopGeneralSettingsTableSeeder::class);


        //   SEO Settings Seed Data
        $this->call(SeoGeneralSettingsSeeder::class);
        $this->call(SeoRepresentationSettingSeeder::class);


    }
}
