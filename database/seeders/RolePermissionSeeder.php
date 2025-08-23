<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions with display names
        $permissions = [
            // Camp permissions
            ['name' => 'view_camps', 'display_name' => 'View Camps', 'group' => 'camps'],
            ['name' => 'create_camps', 'display_name' => 'Create Camps', 'group' => 'camps'],
            ['name' => 'edit_camps', 'display_name' => 'Edit Camps', 'group' => 'camps'],
            ['name' => 'delete_camps', 'display_name' => 'Delete Camps', 'group' => 'camps'],
            
            // Camp Instance permissions
            ['name' => 'view_camp_instances', 'display_name' => 'View Camp Instances', 'group' => 'camp_instances'],
            ['name' => 'create_camp_instances', 'display_name' => 'Create Camp Instances', 'group' => 'camp_instances'],
            ['name' => 'edit_camp_instances', 'display_name' => 'Edit Camp Instances', 'group' => 'camp_instances'],
            ['name' => 'delete_camp_instances', 'display_name' => 'Delete Camp Instances', 'group' => 'camp_instances'],
            
            // Camper permissions
            ['name' => 'view_campers', 'display_name' => 'View Campers', 'group' => 'campers'],
            ['name' => 'create_campers', 'display_name' => 'Create Campers', 'group' => 'campers'],
            ['name' => 'edit_campers', 'display_name' => 'Edit Campers', 'group' => 'campers'],
            ['name' => 'delete_campers', 'display_name' => 'Delete Campers', 'group' => 'campers'],
            
            // Enrollment permissions
            ['name' => 'view_enrollments', 'display_name' => 'View Enrollments', 'group' => 'enrollments'],
            ['name' => 'create_enrollments', 'display_name' => 'Create Enrollments', 'group' => 'enrollments'],
            ['name' => 'edit_enrollments', 'display_name' => 'Edit Enrollments', 'group' => 'enrollments'],
            ['name' => 'delete_enrollments', 'display_name' => 'Delete Enrollments', 'group' => 'enrollments'],
            
            // Medical Record permissions
            ['name' => 'view_medical_records', 'display_name' => 'View Medical Records', 'group' => 'medical_records'],
            ['name' => 'create_medical_records', 'display_name' => 'Create Medical Records', 'group' => 'medical_records'],
            ['name' => 'edit_medical_records', 'display_name' => 'Edit Medical Records', 'group' => 'medical_records'],
            ['name' => 'delete_medical_records', 'display_name' => 'Delete Medical Records', 'group' => 'medical_records'],
            
            // Form Template permissions
            ['name' => 'view_form_templates', 'display_name' => 'View Form Templates', 'group' => 'form_templates'],
            ['name' => 'create_form_templates', 'display_name' => 'Create Form Templates', 'group' => 'form_templates'],
            ['name' => 'edit_form_templates', 'display_name' => 'Edit Form Templates', 'group' => 'form_templates'],
            ['name' => 'delete_form_templates', 'display_name' => 'Delete Form Templates', 'group' => 'form_templates'],
            
            // User management permissions
            ['name' => 'view_users', 'display_name' => 'View Users', 'group' => 'users'],
            ['name' => 'create_users', 'display_name' => 'Create Users', 'group' => 'users'],
            ['name' => 'edit_users', 'display_name' => 'Edit Users', 'group' => 'users'],
            ['name' => 'delete_users', 'display_name' => 'Delete Users', 'group' => 'users'],
            
            // Report permissions
            ['name' => 'view_reports', 'display_name' => 'View Reports', 'group' => 'reports'],
            ['name' => 'create_reports', 'display_name' => 'Create Reports', 'group' => 'reports'],
            ['name' => 'export_reports', 'display_name' => 'Export Reports', 'group' => 'reports'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        // Create roles with display names
        $systemAdmin = Role::create([
            'name' => 'system-admin',
            'display_name' => 'System Administrator',
            'description' => 'Full system access with all permissions',
            'is_admin' => true
        ]);
        $campManager = Role::create([
            'name' => 'camp-manager',
            'display_name' => 'Camp Manager',
            'description' => 'Manages camp operations and staff',
            'is_admin' => false
        ]);
        $parent = Role::create([
            'name' => 'parent',
            'display_name' => 'Parent',
            'description' => 'Parent with access to their children\'s information',
            'is_admin' => false
        ]);

        // Assign permissions to system admin (all permissions)
        $systemAdmin->givePermissionTo(Permission::all());

        // Assign permissions to camp manager
        $campManager->givePermissionTo([
            'view_camps',
            'view_camp_instances',
            'create_camp_instances',
            'edit_camp_instances',
            'view_campers',
            'edit_campers',
            'view_enrollments',
            'create_enrollments',
            'edit_enrollments',
            'delete_enrollments',
            'view_medical_records',
            'edit_medical_records',
            'view_form_templates',
            'create_form_templates',
            'edit_form_templates',
            'delete_form_templates',
            'view_reports',
            'create_reports',
            'export_reports',
        ]);

        // Assign permissions to parent
        $parent->givePermissionTo([
            'view_camp_instances',
            'view_campers',
            'create_campers',
            'edit_campers',
            'view_enrollments',
            'create_enrollments',
            'edit_enrollments',
            'view_medical_records',
            'create_medical_records',
            'edit_medical_records',
            'view_form_templates',
        ]);
    }
} 