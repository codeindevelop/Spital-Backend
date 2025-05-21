<?php

namespace Modules\Auth\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Auth\Database\Seeders\Admin\AdminSeeder;
use Modules\Auth\Database\Seeders\SuperAdmin\SuperAdminSeeder;


class AuthDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //  Superadmin Seeder
        $this->call(SuperAdminSeeder::class);
        $this->call(AdminSeeder::class);
    }
}
