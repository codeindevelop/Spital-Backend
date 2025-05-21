<?php

namespace Modules\Leads\Database\Seeders\LeadStatus;

use Illuminate\Database\Seeder;

use Modules\Leads\App\Models\LeadStatus;


class LeadsStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            ['status_name' => 'جدید', 'active' => true],
            ['status_name' => 'تماس گرفته شده', 'active' => true],
            ['status_name' => 'درحال بررسی', 'active' => true],
            ['status_name' => 'در حال انجام', 'active' => true],
            ['status_name' => 'مشتری', 'active' => true],

        ];

        foreach ($statuses as $status) {
            LeadStatus::create($status);
        }
    }
}
