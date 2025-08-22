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
        // Create permissions
        $permissions = [
            // Camp permissions
            'view_camps',
            'create_camps',
            'edit_camps',
            'delete_camps',
            
            // Camp Instance permissions
            'view_camp_instances',
            'create_camp_instances',
            'edit_camp_instances',
            'delete_camp_instances',
            
            // Camper permissions
            'view_campers',
            'create_campers',
            'edit_campers',
            'delete_campers',
            
            // Enrollment permissions
            'view_enrollments',
            'create_enrollments',
            'edit_enrollments',
            'delete_enrollments',
            
            // Medical Record permissions
            'view_medical_records',
            'create_medical_records',
            'edit_medical_records',
            'delete_medical_records',
            
            // Form Template permissions
            'view_form_templates',
            'create_form_templates',
            'edit_form_templates',
            'delete_form_templates',
            
            // User management permissions
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',
            
            // Report permissions
            'view_reports',
            'create_reports',
            'export_reports',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles
        $systemAdmin = Role::create(['name' => 'system-admin']);
        $campManager = Role::create(['name' => 'camp-manager']);
        $parent = Role::create(['name' => 'parent']);

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