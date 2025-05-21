<?php

namespace Modules\Leads\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Leads\Database\Seeders\LeadSource\LeadSourcesSeeder;
use Modules\Leads\Database\Seeders\LeadStatus\LeadsStatusSeeder;

class LeadsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lead Sources Seeder
        $this->call([LeadSourcesSeeder::class]);

        // Lead Status Seeder
        $this->call([LeadsStatusSeeder::class]);
    }
}
