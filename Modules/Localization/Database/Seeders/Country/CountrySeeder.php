<?php

namespace Modules\Localization\Database\Seeders\Country;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $csvFile = __DIR__.'/countries.csv';
        $countries = [];

        // خواندن فایل CSV
        if (($handle = fopen($csvFile, 'r')) !== false) {
            // رد شدن از هدر
            fgetcsv($handle);

            // خواندن خطوط
            while (($data = fgetcsv($handle)) !== false) {
                $countries[] = [
                    'id' => Str::uuid(),
                    'iso' => $data[0],
                    'name' => $data[1],
                    'nickname' => $data[2],
                    'iso3' => $data[3] ?: null,
                    'numcode' => $data[4] ? (int) $data[4] : null,
                    'phonecode' => (int) $data[5],
                ];
            }
            fclose($handle);
        }

        // وارد کردن داده‌ها به دیتابیس
        DB::table('countries')->insert($countries);
    }
}
