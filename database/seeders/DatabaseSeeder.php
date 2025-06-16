<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Auth\Database\Seeders\AuthDatabaseSeeder;
use Modules\Blog\Database\Seeders\BlogDatabaseSeeder;
use Modules\Leads\Database\Seeders\LeadsDatabaseSeeder;
use Modules\Localization\Database\Seeders\LocalizationDatabaseSeeder;
use Modules\RolePermission\Database\Seeders\RolePermissionDatabaseSeeder;
use Modules\Settings\Database\Seeders\SettingsDatabaseSeeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {


        // Localization Seeder Like Languages and Countries etc...
        $this->call(LocalizationDatabaseSeeder::class);

        // Role and Permission Seeder Base File
        $this->call(RolePermissionDatabaseSeeder::class);


        // Settings Module seeder
        $this->call(SettingsDatabaseSeeder::class);

        // User And Superadmin Default Seeder
        $this->call(AuthDatabaseSeeder::class);

        // Lead requirement Seeder
        $this->call(LeadsDatabaseSeeder::class);


//        $this->call(BlogDatabaseSeeder::class);
    }
}
