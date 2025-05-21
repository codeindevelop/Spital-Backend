<?php

namespace Modules\Auth\Database\Seeders\SuperAdmin;



use Illuminate\Database\Seeder;
use Modules\RolePermission\App\Models\Role;
use Modules\User\App\Models\User;


class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        //create super Admin

        $superAdmin = User::create([
            "user_name" => env('SUPER_ADMIN_USER_NAME'),
            "first_name" => env('SUPER_ADMIN_FIRST_NAME'),
            "last_name" => env('SUPER_ADMIN_LAST_NAME'),
            "email" => env('SUPER_ADMIN_EMAIL'),
            "mobile_number" => env('SUPER_ADMIN_MOBILE'),
            "password" => bcrypt(env('SUPER_ADMIN_PASSWORD')),
            "active" => true,

        ]);

        $role = Role::findByName('super-admin', 'api');
        $superAdmin->assignRole($role);

//        activity()
//            ->causedBy($superAdmin)
//            ->event('create-user')
//            ->withProperties(['user' => $superAdmin])
//            ->log('create super admin');
    }
}
