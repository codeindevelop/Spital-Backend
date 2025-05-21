<?php

namespace Modules\Localization\Database\Seeders;

use Modules\Localization\Database\Seeders\Country\CountrySeeder;
use Illuminate\Database\Seeder;

use Modules\Localization\Database\Seeders\Language\LanguageSeeder;
use Modules\Localization\Database\Seeders\TimeZone\TimeZoneSeeder;
use Modules\Localization\Database\Seeders\Translation\Email\Auth\Fa\FaEmailTranslationSeeder;

class LocalizationDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call(CountrySeeder::class);



        $this->call(TimeZoneSeeder::class);

        $this->call(LanguageSeeder::class);

        // Persian Email Translation For Auth
//        $this->call(FaEmailTranslationSeeder::class);
    }
}
