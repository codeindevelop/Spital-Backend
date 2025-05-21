<?php

namespace Modules\Auth\Database\Seeders\Admin;



use Illuminate\Database\Seeder;
use Modules\RolePermission\App\Models\Role;
use Modules\User\App\Models\User;


class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        //create super Admin

        $admin = User::create([
            "user_name" => env('ADMIN_USER_NAME'),
            "first_name" => env('ADMIN_FIRST_NAME'),
            "last_name" => env('ADMIN_LAST_NAME'),
            "email" => env('ADMIN_EMAIL'),
            "mobile_number" => env('ADMIN_MOBILE'),
            "password" => bcrypt(env('ADMIN_PASSWORD')),
            "active" => true,

        ]);

        $role = Role::findByName('admin', 'api');
        $admin->assignRole($role);

//        activity()
//            ->causedBy($superAdmin)
//            ->event('create-user')
//            ->withProperties(['user' => $superAdmin])
//            ->log('create super admin');
    }
}
