<?php

namespace Modules\Leads\Database\Seeders\LeadSource;

use Illuminate\Database\Seeder;

use Modules\Leads\App\Models\LeadSources;

class LeadSourcesSeeder extends Seeder
{
    public function run(): void
    {
        $Sources = [
            ['source_name' => 'دیوار', 'active' => true],
            ['source_name' => 'شیپور', 'active' => true],
            ['source_name' => 'اینستاگرام', 'active' => true],
            ['source_name' => 'فیس بوک', 'active' => true],
            ['source_name' => 'یوتیوب', 'active' => true],
            ['source_name' => 'تویتر', 'active' => true],
            ['source_name' => 'ایمیل مارکتینگ', 'active' => true],
            ['source_name' => 'وب سایت', 'active' => true],
            ['source_name' => 'تبلیغات گوگل', 'active' => true],
            ['source_name' => 'سرچ گوگل', 'active' => true],
            ['source_name' => 'معرفی آشنا', 'active' => true],

        ];

        foreach ($Sources as $Source) {
            LeadSources::create($Source);
        }
    }
}
