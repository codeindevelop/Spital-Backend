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
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Access to Admin Rea
        Permission::create(['guard_name' => 'api', 'name' => 'super_admin_area:access']);


        Permission::create(['guard_name' => 'api', 'name' => 'admin_area:access']);


        // Manage Users permissions
        Permission::create(['guard_name' => 'api', 'name' => 'view all users']);

        Permission::create(['guard_name' => 'api', 'name' => 'users:view']);
        Permission::create(['guard_name' => 'api', 'name' => 'users:create']);
        Permission::create(['guard_name' => 'api', 'name' => 'users:edit']);
        Permission::create(['guard_name' => 'api', 'name' => 'users:delete']);
        Permission::create(['guard_name' => 'api', 'name' => 'users:suspend']);


        // Manage Role permissions
        Permission::create(['guard_name' => 'api', 'name' => 'roles:view']);
        Permission::create(['guard_name' => 'api', 'name' => 'roles:create']);
        Permission::create(['guard_name' => 'api', 'name' => 'roles:edit']);
        Permission::create(['guard_name' => 'api', 'name' => 'roles:delete']);

        Permission::create(['guard_name' => 'api', 'name' => 'permissions:view']);
        Permission::create(['guard_name' => 'api', 'name' => 'permissions:create']);
        Permission::create(['guard_name' => 'api', 'name' => 'permissions:edit']);
        Permission::create(['guard_name' => 'api', 'name' => 'permissions:delete']);


        // Blog Permission
        Permission::create(['guard_name' => 'api', 'name' => 'post:view']);
        Permission::create(['guard_name' => 'api', 'name' => 'post:create']);
        Permission::create(['guard_name' => 'api', 'name' => 'post:edit']);
        Permission::create(['guard_name' => 'api', 'name' => 'post:delete']);


        // Server Permission
        Permission::create(['guard_name' => 'api', 'name' => 'server:view']);
        Permission::create(['guard_name' => 'api', 'name' => 'server:create']);
        Permission::create(['guard_name' => 'api', 'name' => 'server:edit']);
        Permission::create(['guard_name' => 'api', 'name' => 'server:delete']);

        // Host Permission
        Permission::create(['guard_name' => 'api', 'name' => 'host:view']);
        Permission::create(['guard_name' => 'api', 'name' => 'host:create']);
        Permission::create(['guard_name' => 'api', 'name' => 'host:edit']);
        Permission::create(['guard_name' => 'api', 'name' => 'host:delete']);

        // Project Permission
        Permission::create(['guard_name' => 'api', 'name' => 'project:view']);
        Permission::create(['guard_name' => 'api', 'name' => 'project:create']);
        Permission::create(['guard_name' => 'api', 'name' => 'project:edit']);
        Permission::create(['guard_name' => 'api', 'name' => 'project:delete']);

        // v Todos Permission
        Permission::create(['guard_name' => 'api', 'name' => 'all_todos:view']);
        Permission::create(['guard_name' => 'api', 'name' => 'todo:view']);
        Permission::create(['guard_name' => 'api', 'name' => 'todo:create']);
        Permission::create(['guard_name' => 'api', 'name' => 'todo:edit']);
        Permission::create(['guard_name' => 'api', 'name' => 'todo:delete']);

        // Source codes   Permission
        Permission::create(['guard_name' => 'api', 'name' => 'all_sources:view']);
        Permission::create(['guard_name' => 'api', 'name' => 'source:view']);
        Permission::create(['guard_name' => 'api', 'name' => 'source:create']);
        Permission::create(['guard_name' => 'api', 'name' => 'source:edit']);
        Permission::create(['guard_name' => 'api', 'name' => 'source:delete']);

        // Tickets Permission
        Permission::create(['guard_name' => 'api', 'name' => 'support_department:edit']);
        Permission::create(['guard_name' => 'api', 'name' => 'all_tickets:view']);
        Permission::create(['guard_name' => 'api', 'name' => 'ticket:view']);
        Permission::create(['guard_name' => 'api', 'name' => 'ticket:create']);
        Permission::create(['guard_name' => 'api', 'name' => 'ticket:edit']);
        Permission::create(['guard_name' => 'api', 'name' => 'ticket:response']);
        Permission::create(['guard_name' => 'api', 'name' => 'ticket:delete']);

        // contact request Permission
        Permission::create(['guard_name' => 'api', 'name' => 'contact_req:view']);
        Permission::create(['guard_name' => 'api', 'name' => 'contact_req:create']);
        Permission::create(['guard_name' => 'api', 'name' => 'contact_req:edit']);
        Permission::create(['guard_name' => 'api', 'name' => 'contact_req:delete']);

        // Payment - transactions Permission
        Permission::create(['guard_name' => 'api', 'name' => 'all_transactions:view']);
        Permission::create(['guard_name' => 'api', 'name' => 'transaction:view']);
        Permission::create(['guard_name' => 'api', 'name' => 'transaction:create']);
        Permission::create(['guard_name' => 'api', 'name' => 'transaction:edit']);
        Permission::create(['guard_name' => 'api', 'name' => 'transaction:delete']);

        // Payment - Invoice Permission
        Permission::create(['guard_name' => 'api', 'name' => 'all_invoices:view']);
        Permission::create(['guard_name' => 'api', 'name' => 'invoice:view']);
        Permission::create(['guard_name' => 'api', 'name' => 'invoice:create']);
        Permission::create(['guard_name' => 'api', 'name' => 'invoice:edit']);
        Permission::create(['guard_name' => 'api', 'name' => 'invoice:delete']);

        // Payment - Deposit Permission
        Permission::create(['guard_name' => 'api', 'name' => 'all_deposits:view']);
        Permission::create(['guard_name' => 'api', 'name' => 'deposit:view']);
        Permission::create(['guard_name' => 'api', 'name' => 'deposit:create']);
        Permission::create(['guard_name' => 'api', 'name' => 'deposit:edit']);
        Permission::create(['guard_name' => 'api', 'name' => 'deposit:delete']);

        // Payment - discount Permission
        Permission::create(['guard_name' => 'api', 'name' => 'all_discount:view']);
        Permission::create(['guard_name' => 'api', 'name' => 'discount:view']);
        Permission::create(['guard_name' => 'api', 'name' => 'discount:create']);
        Permission::create(['guard_name' => 'api', 'name' => 'discount:edit']);
        Permission::create(['guard_name' => 'api', 'name' => 'discount:delete']);

        // Settings Permission
        Permission::create(['guard_name' => 'api', 'name' => 'view settings']);
        Permission::create(['guard_name' => 'api', 'name' => 'create settings']);
        Permission::create(['guard_name' => 'api', 'name' => 'edit settings']);
        Permission::create(['guard_name' => 'api', 'name' => 'check settings']);
        Permission::create(['guard_name' => 'api', 'name' => 'delete settings']);


        // website Permission
        Permission::create(['guard_name' => 'api', 'name' => 'maintenance:edit']);


        // Order Permission
        Permission::create(['guard_name' => 'api', 'name' => 'all_orders:view']);
        Permission::create(['guard_name' => 'api', 'name' => 'order:view']);
        Permission::create(['guard_name' => 'api', 'name' => 'order:create']);
        Permission::create(['guard_name' => 'api', 'name' => 'order:edit']);
        Permission::create(['guard_name' => 'api', 'name' => 'order:delete']);

        // Employment Permission
        Permission::create(['guard_name' => 'api', 'name' => 'employer:view']);
        Permission::create(['guard_name' => 'api', 'name' => 'employer:create']);
        Permission::create(['guard_name' => 'api', 'name' => 'employer:edit']);
        Permission::create(['guard_name' => 'api', 'name' => 'employer:delete']);

        // Training Category Permission
        Permission::create(['guard_name' => 'api', 'name' => 'course_category:view']);
        Permission::create(['guard_name' => 'api', 'name' => 'course_category:create']);
        Permission::create(['guard_name' => 'api', 'name' => 'course_category:edit']);
        Permission::create(['guard_name' => 'api', 'name' => 'course_category:delete']);

        // Training Course Permission
        Permission::create(['guard_name' => 'api', 'name' => 'course:view']);
        Permission::create(['guard_name' => 'api', 'name' => 'course:create']);
        Permission::create(['guard_name' => 'api', 'name' => 'course:edit']);
        Permission::create(['guard_name' => 'api', 'name' => 'course:delete']);

        // Instructor Category Permission
        Permission::create(['guard_name' => 'api', 'name' => 'instructor:view']);
        Permission::create(['guard_name' => 'api', 'name' => 'instructor:create']);
        Permission::create(['guard_name' => 'api', 'name' => 'instructor:edit']);
        Permission::create(['guard_name' => 'api', 'name' => 'instructor:delete']);

        // Contracts Permission
        Permission::create(['guard_name' => 'api', 'name' => 'all_contracts:view']);
        Permission::create(['guard_name' => 'api', 'name' => 'contract:view']);
        Permission::create(['guard_name' => 'api', 'name' => 'contract:create']);
        Permission::create(['guard_name' => 'api', 'name' => 'contract:edit']);
        Permission::create(['guard_name' => 'api', 'name' => 'contract:delete']);


        Permission::create([
            'guard_name' => 'api', 'name' => 'user_panel:access'
        ]);


        // Sign Permissions To Roles
        $SuperAdminRole = Role::create(['guard_name' => 'api', 'name' => 'super-admin']);
        $SuperAdminRole->givePermissionTo('admin_area:access');
        // Users Permissions
        $SuperAdminRole->givePermissionTo('view all users');
        $SuperAdminRole->givePermissionTo('users:view');
        $SuperAdminRole->givePermissionTo('users:create');
        $SuperAdminRole->givePermissionTo('users:edit');
        $SuperAdminRole->givePermissionTo('users:delete');
        $SuperAdminRole->givePermissionTo('users:suspend');

        // Course Category Permissions
        $SuperAdminRole->givePermissionTo('course_category:view');
        $SuperAdminRole->givePermissionTo('course_category:create');
        $SuperAdminRole->givePermissionTo('course_category:edit');
        $SuperAdminRole->givePermissionTo('course_category:delete');

        // Course Permissions
        $SuperAdminRole->givePermissionTo('course:view');
        $SuperAdminRole->givePermissionTo('course:create');
        $SuperAdminRole->givePermissionTo('course:edit');
        $SuperAdminRole->givePermissionTo('course:delete');

        // Instructor Permissions
        $SuperAdminRole->givePermissionTo('instructor:view');
        $SuperAdminRole->givePermissionTo('instructor:create');
        $SuperAdminRole->givePermissionTo('instructor:edit');
        $SuperAdminRole->givePermissionTo('instructor:delete');


        // Sign permissions to system Admin
        $SystemManagerRole = Role::create(['guard_name' => 'api', 'name' => 'admin']);
        $SystemManagerRole->givePermissionTo('admin_area:access');

        $SystemManagerRole->givePermissionTo('view all users');
        $SystemManagerRole->givePermissionTo('users:view');
        $SystemManagerRole->givePermissionTo('users:create');
        $SystemManagerRole->givePermissionTo('users:edit');
        $SystemManagerRole->givePermissionTo('users:delete');
        $SystemManagerRole->givePermissionTo('users:suspend');

        // Course Category Permissions
        $SystemManagerRole->givePermissionTo('course_category:view');
        $SystemManagerRole->givePermissionTo('course_category:create');
        $SystemManagerRole->givePermissionTo('course_category:edit');
        $SystemManagerRole->givePermissionTo('course_category:delete');

        // Course Permissions
        $SystemManagerRole->givePermissionTo('course:view');
        $SystemManagerRole->givePermissionTo('course:create');
        $SystemManagerRole->givePermissionTo('course:edit');
        $SystemManagerRole->givePermissionTo('course:delete');

        // Instructor Permissions
        $SystemManagerRole->givePermissionTo('instructor:view');
        $SystemManagerRole->givePermissionTo('instructor:create');
        $SystemManagerRole->givePermissionTo('instructor:edit');
        $SystemManagerRole->givePermissionTo('instructor:delete');

        // Create Business Owner Role
        $BusinessOwnerRole = Role::create(['guard_name' => 'api', 'name' => 'business-owner']);
        $BusinessOwnerRole->givePermissionTo('user_panel:access');


        // Role For access to panel and ( Verified) mobile or email
        $UserRole = Role::create(['guard_name' => 'api', 'name' => 'regular-user']);
        $UserRole->givePermissionTo('user_panel:access');


        // Role for Suspended User
        Role::create(['guard_name' => 'api', 'name' => 'suspended']);


    }
}
