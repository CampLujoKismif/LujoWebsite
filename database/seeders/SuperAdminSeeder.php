<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if the super admin user already exists
        $existingUser = User::where('email', 'admin@lujo.com')->first();
        
        if ($existingUser) {
            $this->command->info('Super admin user already exists. Skipping creation.');
            return;
        }

        // Create the super admin user
        $superAdmin = User::create([
            'name' => 'System Administrator',
            'email' => 'admin@lujo.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(), // Mark email as verified
        ]);

        // Get the system admin role
        $systemAdminRole = Role::where('name', 'system-admin')->first();
        
        if ($systemAdminRole) {
            $superAdmin->assignRole($systemAdminRole);
            $this->command->info('Super admin user created successfully:');
            $this->command->info('Name: System Administrator');
            $this->command->info('Email: admin@lujo.com');
            $this->command->info('Password: password');
            $this->command->info('Role: System Admin');
        } else {
            $this->command->error('System admin role not found. Please run RolePermissionSeeder first.');
        }
    }
}
