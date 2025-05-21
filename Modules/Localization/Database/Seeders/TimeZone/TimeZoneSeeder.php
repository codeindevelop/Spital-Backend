<?php

namespace Modules\Localization\Database\Seeders\TimeZone;

use Carbon\Carbon;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Localization\App\Models\TimeZone;

class TimeZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $data = json_decode(file_get_contents(__DIR__.'/timezones.json'), true);

        foreach ($data as $tzs) {
            foreach ($tzs as $offset => $timezones) {
                foreach ($timezones as $timezone) {
                    TimeZone::create([

                        'label' => $timezone['label'],
                        'value' => $timezone['value'],
                        'offset' => $offset,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),

                    ]);
                }
            }
        }
    }
}
