<?php

namespace Modules\RolePermission\Database\Seeders\PermissionAndRole;

use Illuminate\Database\Seeder;
use Modules\RolePermission\App\Models\Permission;
use Modules\RolePermission\App\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionsAndRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \Exception
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Load JSON file
        $jsonPath = __DIR__.'/permissions_and_roles.json';
        if (!file_exists($jsonPath)) {
            throw new \Exception('Permissions and roles JSON file not found at: '.$jsonPath);
        }

        $data = json_decode(file_get_contents($jsonPath), true);

        // Create Permissions
        foreach ($data['permissions'] as $permission) {
            Permission::firstOrCreate([
                'guard_name' => $permission['guard_name'],
                'name' => $permission['name'],
            ]);
        }

        // Create Roles and Assign Permissions
        foreach ($data['roles'] as $roleData) {
            $role = Role::firstOrCreate([
                'guard_name' => $roleData['guard_name'],
                'name' => $roleData['name'],
            ]);

            if (!empty($roleData['permissions'])) {
                $role->syncPermissions($roleData['permissions']);
            }
        }
    }
}
