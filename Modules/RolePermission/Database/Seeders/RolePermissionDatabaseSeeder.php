<?php

namespace Modules\RolePermission\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\RolePermission\Database\Seeders\PermissionAndRole\PermissionsAndRolesSeeder;


class RolePermissionDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //  call permission And Roles Default seeder
        $this->call(PermissionsAndRolesSeeder::class);
    }
}
