<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Camp;
use App\Models\Family;
use App\Models\Camper;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class TestUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting TestUsersSeeder...');

        // Create a camp manager user
        $manager = User::where('email', 'manager@lujo.com')->first();
        if (!$manager) {
            $manager = User::create([
                'name' => 'Camp Manager',
                'email' => 'manager@lujo.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);
            $this->command->info('Created manager user: manager@lujo.com');
        } else {
            $this->command->info('Manager user already exists: manager@lujo.com');
        }

        // Assign camp-manager role
        $managerRole = Role::where('name', 'camp-manager')->first();
        if ($managerRole) {
            if (!$manager->hasRole('camp-manager')) {
                $manager->assignRole($managerRole);
                $this->command->info('Assigned camp-manager role to manager user');
            } else {
                $this->command->info('Manager user already has camp-manager role');
            }
        } else {
            $this->command->error('camp-manager role not found! Make sure RolePermissionSeeder has been run.');
        }

        // Create a parent user
        $parent = User::where('email', 'parent@lujo.com')->first();
        if (!$parent) {
            $parent = User::create([
                'name' => 'Test Parent',
                'email' => 'parent@lujo.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);
            $this->command->info('Created parent user: parent@lujo.com');
        } else {
            $this->command->info('Parent user already exists: parent@lujo.com');
        }

        // Assign parent role
        $parentRole = Role::where('name', 'parent')->first();
        if ($parentRole) {
            if (!$parent->hasRole('parent')) {
                $parent->assignRole($parentRole);
                $this->command->info('Assigned parent role to parent user');
            } else {
                $this->command->info('Parent user already has parent role');
            }
        } else {
            $this->command->error('parent role not found! Make sure RolePermissionSeeder has been run.');
        }

        // Create a family for the parent
        $family = Family::where('owner_user_id', $parent->id)->first();
        if (!$family) {
            $family = Family::create([
                'name' => 'Smith Family',
                'owner_user_id' => $parent->id,
                'phone' => '555-123-4567',
                'address' => '123 Main St',
                'city' => 'Anytown',
                'state' => 'TX',
                'zip_code' => '12345',
            ]);
            $this->command->info('Created family for parent user');
        } else {
            $this->command->info('Family already exists for parent user');
        }

        // Add the parent to their family
        if (!$family->users()->where('user_id', $parent->id)->exists()) {
            $family->users()->attach($parent->id, ['role_in_family' => 'parent']);
            $this->command->info('Added parent to family');
        } else {
            $this->command->info('Parent already in family');
        }

        // Create some campers for the family
        $existingCampers = Camper::where('family_id', $family->id)->count();
        if ($existingCampers == 0) {
            Camper::create([
                'family_id' => $family->id,
                'first_name' => 'John',
                'last_name' => 'Smith',
                'date_of_birth' => now()->subYears(12),
                'gender' => 'male',
                'grade' => 6,
                'school' => 'Anytown Elementary',
            ]);

            Camper::create([
                'family_id' => $family->id,
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'date_of_birth' => now()->subYears(10),
                'gender' => 'female',
                'grade' => 4,
                'school' => 'Anytown Elementary',
                'allergies' => json_encode(['Peanuts', 'Shellfish']),
                'medical_conditions' => json_encode(['Asthma']),
            ]);
            $this->command->info('Created campers for family');
        } else {
            $this->command->info('Campers already exist for family');
        }

        // Verify the users have the correct roles
        $this->command->info('Verifying user roles...');
        $manager->refresh();
        $parent->refresh();
        
        $this->command->info('Manager roles: ' . implode(', ', $manager->getRoleNames()->toArray()));
        $this->command->info('Parent roles: ' . implode(', ', $parent->getRoleNames()->toArray()));

        $this->command->info('Test users setup complete:');
        $this->command->info('Manager: manager@lujo.com / password');
        $this->command->info('Parent: parent@lujo.com / password');
        $this->command->info('Admin: admin@lujo.com / password (from SuperAdminSeeder)');
    }
}