<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = [
            // Camper permissions
            ['name' => 'view_campers', 'display_name' => 'View Campers', 'description' => 'Can view camper information', 'group' => 'campers'],
            ['name' => 'create_campers', 'display_name' => 'Create Campers', 'description' => 'Can create new camper records', 'group' => 'campers'],
            ['name' => 'edit_campers', 'display_name' => 'Edit Campers', 'description' => 'Can edit camper information', 'group' => 'campers'],
            ['name' => 'delete_campers', 'display_name' => 'Delete Campers', 'description' => 'Can delete camper records', 'group' => 'campers'],
            
            // Staff permissions
            ['name' => 'view_staff', 'display_name' => 'View Staff', 'description' => 'Can view staff information', 'group' => 'staff'],
            ['name' => 'create_staff', 'display_name' => 'Create Staff', 'description' => 'Can create new staff records', 'group' => 'staff'],
            ['name' => 'edit_staff', 'display_name' => 'Edit Staff', 'description' => 'Can edit staff information', 'group' => 'staff'],
            ['name' => 'delete_staff', 'display_name' => 'Delete Staff', 'description' => 'Can delete staff records', 'group' => 'staff'],
            
            // Session permissions
            ['name' => 'view_sessions', 'display_name' => 'View Sessions', 'description' => 'Can view camp sessions', 'group' => 'sessions'],
            ['name' => 'create_sessions', 'display_name' => 'Create Sessions', 'description' => 'Can create new camp sessions', 'group' => 'sessions'],
            ['name' => 'edit_sessions', 'display_name' => 'Edit Sessions', 'description' => 'Can edit camp sessions', 'group' => 'sessions'],
            ['name' => 'delete_sessions', 'display_name' => 'Delete Sessions', 'description' => 'Can delete camp sessions', 'group' => 'sessions'],
            
            // Registration permissions
            ['name' => 'view_registrations', 'display_name' => 'View Registrations', 'description' => 'Can view camper registrations', 'group' => 'registrations'],
            ['name' => 'create_registrations', 'display_name' => 'Create Registrations', 'description' => 'Can create new registrations', 'group' => 'registrations'],
            ['name' => 'edit_registrations', 'display_name' => 'Edit Registrations', 'description' => 'Can edit registrations', 'group' => 'registrations'],
            ['name' => 'delete_registrations', 'display_name' => 'Delete Registrations', 'description' => 'Can delete registrations', 'group' => 'registrations'],
            
            // Report permissions
            ['name' => 'view_reports', 'display_name' => 'View Reports', 'description' => 'Can view camp reports', 'group' => 'reports'],
            ['name' => 'create_reports', 'display_name' => 'Create Reports', 'description' => 'Can create new reports', 'group' => 'reports'],
            ['name' => 'export_reports', 'display_name' => 'Export Reports', 'description' => 'Can export reports to various formats', 'group' => 'reports'],
            
            // User management permissions
            ['name' => 'view_users', 'display_name' => 'View Users', 'description' => 'Can view user accounts', 'group' => 'users'],
            ['name' => 'create_users', 'display_name' => 'Create Users', 'description' => 'Can create new user accounts', 'group' => 'users'],
            ['name' => 'edit_users', 'display_name' => 'Edit Users', 'description' => 'Can edit user accounts', 'group' => 'users'],
            ['name' => 'delete_users', 'display_name' => 'Delete Users', 'description' => 'Can delete user accounts', 'group' => 'users'],
            
            // Role management permissions
            ['name' => 'view_roles', 'display_name' => 'View Roles', 'description' => 'Can view user roles', 'group' => 'roles'],
            ['name' => 'create_roles', 'display_name' => 'Create Roles', 'description' => 'Can create new roles', 'group' => 'roles'],
            ['name' => 'edit_roles', 'display_name' => 'Edit Roles', 'description' => 'Can edit roles', 'group' => 'roles'],
            ['name' => 'delete_roles', 'display_name' => 'Delete Roles', 'description' => 'Can delete roles', 'group' => 'roles'],
            ['name' => 'assign_roles', 'display_name' => 'Assign Roles', 'description' => 'Can assign roles to users', 'group' => 'roles'],
            
            // System permissions
            ['name' => 'view_settings', 'display_name' => 'View Settings', 'description' => 'Can view system settings', 'group' => 'system'],
            ['name' => 'edit_settings', 'display_name' => 'Edit Settings', 'description' => 'Can edit system settings', 'group' => 'system'],
        ];

        foreach ($permissions as $permissionData) {
            Permission::create($permissionData);
        }

        // Create roles
        $roles = [
            [
                'name' => 'super_admin',
                'display_name' => 'Super Administrator',
                'description' => 'Full system access - highest level administrator',
                'is_admin' => true,
                'permissions' => Permission::all()->pluck('name')->toArray(),
            ],
            [
                'name' => 'camp_director',
                'display_name' => 'Camp Director',
                'description' => 'Camp director with full camp management access',
                'is_admin' => true,
                'permissions' => [
                    'view_campers', 'create_campers', 'edit_campers', 'delete_campers',
                    'view_staff', 'create_staff', 'edit_staff', 'delete_staff',
                    'view_sessions', 'create_sessions', 'edit_sessions', 'delete_sessions',
                    'view_registrations', 'create_registrations', 'edit_registrations', 'delete_registrations',
                    'view_reports', 'create_reports', 'export_reports',
                    'view_users', 'create_users', 'edit_users',
                    'view_roles', 'assign_roles',
                    'view_settings', 'edit_settings',
                ],
            ],
            [
                'name' => 'board_member',
                'display_name' => 'Board Member',
                'description' => 'Board member with oversight and reporting access',
                'is_admin' => true,
                'permissions' => [
                    'view_campers', 'view_staff', 'view_sessions', 'view_registrations',
                    'view_reports', 'create_reports', 'export_reports',
                    'view_users', 'view_roles',
                    'view_settings',
                ],
            ],
            [
                'name' => 'camp_staff',
                'display_name' => 'Camp Staff',
                'description' => 'Camp staff member with limited management access',
                'is_admin' => false,
                'permissions' => [
                    'view_campers', 'edit_campers',
                    'view_staff',
                    'view_sessions',
                    'view_registrations', 'create_registrations', 'edit_registrations',
                    'view_reports',
                ],
            ],
            [
                'name' => 'youth_minister',
                'display_name' => 'Youth Minister',
                'description' => 'Youth minister with camper management access',
                'is_admin' => false,
                'permissions' => [
                    'view_campers', 'create_campers', 'edit_campers',
                    'view_sessions',
                    'view_registrations', 'create_registrations', 'edit_registrations',
                    'view_reports',
                ],
            ],
            [
                'name' => 'church_representative',
                'display_name' => 'Church Representative',
                'description' => 'Church representative with limited access',
                'is_admin' => false,
                'permissions' => [
                    'view_campers',
                    'view_sessions',
                    'view_registrations', 'create_registrations',
                    'view_reports',
                ],
            ],
            [
                'name' => 'parent',
                'display_name' => 'Parent',
                'description' => 'Parent with access to their children\'s information',
                'is_admin' => false,
                'permissions' => [
                    'view_campers',
                    'view_sessions',
                    'view_registrations', 'create_registrations',
                ],
            ],
            [
                'name' => 'camper',
                'display_name' => 'Camper',
                'description' => 'Camper with limited personal access',
                'is_admin' => false,
                'permissions' => [
                    'view_sessions',
                    'view_registrations',
                ],
            ],
        ];

        foreach ($roles as $roleData) {
            $permissions = $roleData['permissions'];
            unset($roleData['permissions']);
            
            $role = Role::create($roleData);
            
            // Assign permissions to role
            $permissionModels = Permission::whereIn('name', $permissions)->get();
            $role->permissions()->attach($permissionModels);
        }
    }
} 