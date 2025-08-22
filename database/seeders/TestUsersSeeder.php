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
        // Create a camp manager user
        $manager = User::where('email', 'manager@lujo.com')->first();
        if (!$manager) {
            $manager = User::create([
                'name' => 'Camp Manager',
                'email' => 'manager@lujo.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);
        }

        $managerRole = Role::where('name', 'camp-manager')->first();
        if ($managerRole) {
            $manager->assignRole($managerRole);
        }

        // Note: Camp assignment will be handled through the admin interface
        // The manager role is sufficient for now

        // Create a parent user
        $parent = User::where('email', 'parent@lujo.com')->first();
        if (!$parent) {
            $parent = User::create([
                'name' => 'Test Parent',
                'email' => 'parent@lujo.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);
        }

        $parentRole = Role::where('name', 'parent')->first();
        if ($parentRole) {
            $parent->assignRole($parentRole);
        }

        // Create a family for the parent
        $family = Family::create([
            'name' => 'Smith Family',
            'owner_user_id' => $parent->id,
            'phone' => '555-123-4567',
            'address' => '123 Main St',
            'city' => 'Anytown',
            'state' => 'TX',
            'zip_code' => '12345',
        ]);

        // Add the parent to their family
        $family->users()->attach($parent->id, ['role_in_family' => 'parent']);

        // Create some campers for the family
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

        $this->command->info('Test users created:');
        $this->command->info('Manager: manager@lujo.com / password');
        $this->command->info('Parent: parent@lujo.com / password');
        $this->command->info('Admin: admin@lujo.com / password (from SuperAdminSeeder)');
    }
}