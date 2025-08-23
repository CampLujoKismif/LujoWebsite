<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles and permissions first
        $this->call([
            RolePermissionSeeder::class,
            CampSeeder::class,
            CampInstanceSeeder::class,
            SuperAdminSeeder::class,
            TestUsersSeeder::class,
        ]);

        // User::factory(10)->create();

        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Assign super admin role to test user
        $superAdminRole = \App\Models\Role::where('name', 'super_admin')->first();
        if ($superAdminRole) {
            $user->assignRole($superAdminRole);
        }

        // Create some example camp assignments for demonstration
        $this->createExampleCampAssignments();
    }

    private function createExampleCampAssignments(): void
    {
        // Get camps and roles
        $superWeek = \App\Models\Camp::where('name', 'super_week')->first();
        $striveWeek = \App\Models\Camp::where('name', 'strive_week')->first();
        $campDirectorRole = \App\Models\Role::where('name', 'camp_director')->first();
        $campStaffRole = \App\Models\Role::where('name', 'camp_staff')->first();

        if ($superWeek && $campDirectorRole) {
            // Create a camp director for Super Week
            $director = \App\Models\User::factory()->create([
                'name' => 'Super Week Director',
                'email' => 'director@superweek.com',
            ]);
            $director->assignRole($campDirectorRole);
            $director->assignToCamp($superWeek, $campDirectorRole, [
                'position' => 'Camp Director',
                'is_primary' => true,
            ]);
        }

        if ($striveWeek && $campStaffRole) {
            // Create a staff member for Strive Week
            $staff = \App\Models\User::factory()->create([
                'name' => 'Strive Week Staff',
                'email' => 'staff@striveweek.com',
            ]);
            $staff->assignRole($campStaffRole);
            $staff->assignToCamp($striveWeek, $campStaffRole, [
                'position' => 'Head Counselor',
                'is_primary' => true,
            ]);
        }
    }
}
