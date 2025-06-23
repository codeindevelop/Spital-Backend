<?php

namespace Modules\Eshop\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Eshop\Database\Seeders\Settings\EshopProductSettingsSeeder;
use Modules\Eshop\Database\Seeders\Settings\EshopGeneralSettingsTableSeeder;

class EshopDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // General Settings
        $this->call([EshopGeneralSettingsTableSeeder::class]);

        // Product Setting
        $this->call([EshopProductSettingsSeeder::class]);
    }
}
