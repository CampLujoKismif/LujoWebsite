<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if the super admin user already exists
        $existingUser = User::where('email', 'pettijohnj@gmail.com')->first();
        
        if ($existingUser) {
            $this->command->info('Super admin user already exists. Skipping creation.');
            return;
        }

        // Create the super admin user
        $superAdmin = User::create([
            'name' => 'Jarred Pettijohn',
            'email' => 'pettijohnj@gmail.com',
            'password' => Hash::make('rootroot'),
            'email_verified_at' => now(), // Mark email as verified
        ]);

        // Get the super admin role
        $superAdminRole = Role::where('name', 'super_admin')->first();
        
        if ($superAdminRole) {
            $superAdmin->assignRole($superAdminRole);
            $this->command->info('Super admin user created successfully:');
            $this->command->info('Name: Jarred Pettijohn');
            $this->command->info('Email: pettijohnj@gmail.com');
            $this->command->info('Password: rootroot');
            $this->command->info('Role: Super Admin');
        } else {
            $this->command->error('Super admin role not found. Please run RolePermissionSeeder first.');
        }
    }
}
