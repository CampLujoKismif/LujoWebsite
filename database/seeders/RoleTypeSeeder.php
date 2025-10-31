<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Set all existing roles to web_access type (for roles that don't have a type set yet)
        Role::withoutGlobalScopes()
            ->where(function($query) {
                $query->whereNull('type')->orWhere('type', '');
            })
            ->update(['type' => 'web_access']);
        
        // Create missing camp roles as web access roles
        $campRoles = [
            [
                'name' => 'camp_director',
                'display_name' => 'Camp Director',
                'description' => 'Full camp management access - can manage campers, staff, sessions, and registrations',
                'is_admin' => true,
                'type' => 'web_access'
            ],
            [
                'name' => 'camp_staff',
                'display_name' => 'Camp Staff',
                'description' => 'Camp staff member with limited management access',
                'is_admin' => false,
                'type' => 'web_access'
            ],
            [
                'name' => 'youth_minister',
                'display_name' => 'Youth Minister',
                'description' => 'Youth minister with camper management access',
                'is_admin' => false,
                'type' => 'web_access'
            ],
            [
                'name' => 'board_member',
                'display_name' => 'Board Member',
                'description' => 'Board member with oversight and reporting access',
                'is_admin' => true,
                'type' => 'web_access'
            ],
            [
                'name' => 'church_representative',
                'display_name' => 'Church Representative',
                'description' => 'Church representative with limited access',
                'is_admin' => false,
                'type' => 'web_access'
            ],
            [
                'name' => 'camper',
                'display_name' => 'Camper',
                'description' => 'Camper with limited personal access',
                'is_admin' => false,
                'type' => 'web_access'
            ],
        ];
        
        foreach ($campRoles as $roleData) {
            $role = Role::firstOrCreate(
                ['name' => $roleData['name']],
                $roleData
            );
            
            // Update existing role if it didn't have a type
            if ($role->type !== $roleData['type']) {
                $role->update(['type' => $roleData['type']]);
            }
        }
        
        echo "Role types initialized successfully.\n";
    }
}

