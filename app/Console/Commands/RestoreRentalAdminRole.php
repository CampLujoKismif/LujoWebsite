<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Role;
use App\Models\Permission;

class RestoreRentalAdminRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'role:restore-rental-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restore the rental-admin role if it was accidentally modified or deleted';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking rental-admin role...');

        // Find or create the role
        $role = Role::withTrashed()->where('name', 'rental-admin')->first();

        if ($role && $role->trashed()) {
            $this->info('Found soft-deleted rental-admin role. Restoring...');
            $role->restore();
        } elseif (!$role) {
            $this->info('Creating rental-admin role...');
            $role = Role::create([
                'name' => 'rental-admin',
                'display_name' => 'Rental Administrator',
                'description' => 'Manages facility rentals and reservations',
                'is_admin' => true,
            ]);
        }

        // Update role properties to ensure they're correct
        $role->update([
            'display_name' => 'Rental Administrator',
            'description' => 'Manages facility rentals and reservations',
            'is_admin' => true,
        ]);

        // Assign all required permissions
        $requiredPermissions = [
            'view_rentals',
            'create_rentals',
            'edit_rentals',
            'delete_rentals',
            'manage_rental_pricing',
            'manage_rental_discounts',
            'process_rental_refunds',
            'view_rental_analytics',
            'view_reports',
            'create_reports',
            'export_reports',
        ];

        $this->info('Assigning permissions to rental-admin role...');
        
        $permissions = Permission::whereIn('name', $requiredPermissions)->get();
        
        if ($permissions->count() < count($requiredPermissions)) {
            $missing = array_diff($requiredPermissions, $permissions->pluck('name')->toArray());
            $this->warn('Some permissions are missing: ' . implode(', ', $missing));
        }

        // Sync permissions using the role_permissions relationship
        $role->permissions()->sync($permissions->pluck('id')->toArray());

        $this->info("✓ Role '{$role->display_name}' (name: {$role->name}) has been restored/updated");
        $this->info("✓ {$permissions->count()} permissions assigned");
        
        // Show which permissions were assigned
        $this->info("Permissions: " . $permissions->pluck('name')->implode(', '));

        return Command::SUCCESS;
    }
}
