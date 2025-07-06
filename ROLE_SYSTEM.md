# Role-Based Access Control System for Camp LUJO-KISMIF

## Overview

This document describes the comprehensive role-based access control (RBAC) system implemented for the Camp LUJO-KISMIF management application. The system provides flexible, granular control over user permissions and access levels.

## Database Structure

### Tables

1. **`roles`** - Defines user roles
   - `id` (Primary Key)
   - `name` (Unique role identifier)
   - `display_name` (Human-readable role name)
   - `description` (Role description)
   - `is_admin` (Boolean flag for admin roles)
   - `created_at`, `updated_at` (Timestamps)

2. **`permissions`** - Defines specific permissions/actions
   - `id` (Primary Key)
   - `name` (Unique permission identifier)
   - `display_name` (Human-readable permission name)
   - `description` (Permission description)
   - `group` (Permission grouping for organization)
   - `created_at`, `updated_at` (Timestamps)

3. **`role_permissions`** - Many-to-many relationship between roles and permissions
   - `id` (Primary Key)
   - `role_id` (Foreign Key to roles)
   - `permission_id` (Foreign Key to permissions)
   - `created_at`, `updated_at` (Timestamps)

4. **`user_roles`** - Many-to-many relationship between users and roles
   - `id` (Primary Key)
   - `user_id` (Foreign Key to users)
   - `role_id` (Foreign Key to roles)
   - `created_at`, `updated_at` (Timestamps)

5. **`camps`** - Defines different camp sessions
   - `id` (Primary Key)
   - `name` (Unique camp identifier)
   - `display_name` (Human-readable camp name)
   - `description` (Camp description)
   - `start_date` (Camp start date)
   - `end_date` (Camp end date)
   - `is_active` (Boolean flag for active camps)
   - `max_capacity` (Maximum number of campers)
   - `price` (Camp registration price)
   - `age_range` (Target age range)
   - `created_at`, `updated_at` (Timestamps)

6. **`user_camp_assignments`** - Links users to specific camps with roles
   - `id` (Primary Key)
   - `user_id` (Foreign Key to users)
   - `camp_id` (Foreign Key to camps)
   - `role_id` (Foreign Key to roles)
   - `position` (Specific position/title)
   - `notes` (Additional notes)
   - `is_primary` (Boolean flag for primary assignment)
   - `created_at`, `updated_at` (Timestamps)

## Predefined Roles

### Admin-Level Roles

1. **Super Administrator** (`super_admin`)
   - Full system access
   - Can manage all users, roles, and permissions
   - Highest level of access

2. **Camp Director** (`camp_director`)
   - Full camp management access
   - Can manage campers, staff, sessions, and registrations
   - Can view and create reports
   - Can manage users and assign roles
   - Can modify system settings

3. **Board Member** (`board_member`)
   - Oversight and reporting access
   - Can view all camp data
   - Can create and export reports
   - Can view users and roles
   - Can view system settings

### Staff-Level Roles

4. **Camp Staff** (`camp_staff`)
   - Limited management access
   - Can view and edit campers
   - Can view staff information
   - Can view sessions
   - Can manage registrations
   - Can view reports

5. **Youth Minister** (`youth_minister`)
   - Camper management access
   - Can create and edit campers
   - Can view sessions
   - Can manage registrations
   - Can view reports

### External Roles

6. **Church Representative** (`church_representative`)
   - Limited access for church representatives
   - Can view campers and sessions
   - Can create and view registrations
   - Can view reports

7. **Parent** (`parent`)
   - Access to children's information
   - Can view campers (their children)
   - Can view sessions
   - Can create and view registrations

8. **Camper** (`camper`)
   - Limited personal access
   - Can view sessions
   - Can view their own registrations

## Permission Groups

### Campers
- `view_campers` - View camper information
- `create_campers` - Create new camper records
- `edit_campers` - Edit camper information
- `delete_campers` - Delete camper records

### Staff
- `view_staff` - View staff information
- `create_staff` - Create new staff records
- `edit_staff` - Edit staff information
- `delete_staff` - Delete staff records

### Sessions
- `view_sessions` - View camp sessions
- `create_sessions` - Create new camp sessions
- `edit_sessions` - Edit camp sessions
- `delete_sessions` - Delete camp sessions

### Registrations
- `view_registrations` - View camper registrations
- `create_registrations` - Create new registrations
- `edit_registrations` - Edit registrations
- `delete_registrations` - Delete registrations

### Reports
- `view_reports` - View camp reports
- `create_reports` - Create new reports
- `export_reports` - Export reports to various formats

### Users
- `view_users` - View user accounts
- `create_users` - Create new user accounts
- `edit_users` - Edit user accounts
- `delete_users` - Delete user accounts

### Roles
- `view_roles` - View user roles
- `create_roles` - Create new roles
- `edit_roles` - Edit roles
- `delete_roles` - Delete roles
- `assign_roles` - Assign roles to users

### System
- `view_settings` - View system settings
- `edit_settings` - Edit system settings

## Camp-Specific Access Control

The system now supports camp-specific role assignments, allowing staff and directors to be assigned to specific camps (Super Week, Strive Week, etc.) with limited access to only their assigned camps.

### Key Features

1. **Camp Assignments** - Users can be assigned to specific camps with specific roles
2. **Camp-Scoped Permissions** - Permissions are checked within the context of specific camps
3. **Primary Assignments** - Users can have a primary camp assignment
4. **Position Tracking** - Track specific positions within camps (e.g., "Head Counselor", "Kitchen Staff")

### Predefined Camps

- **Super Week** - High school summer camp (9th-12th Grade)
- **Strive Week** - Middle school summer camp (6th-8th Grade)
- **Elevate Week** - Leadership development camp (10th-12th Grade)
- **Jump Week** - Adventure and outdoor activities camp (7th-12th Grade)
- **Day Camp** - Day camp for elementary students (1st-4th Grade)
- **Family Camp** - Family-oriented camp experience (All Ages)
- **Winter Retreat** - Winter weekend retreat (6th-12th Grade)

## Usage Examples

### In Controllers

```php
// Check if user has a specific role
if ($user->hasRole('camp_director')) {
    // Allow access
}

// Check if user has any of multiple roles
if ($user->hasAnyRole(['camp_director', 'board_member'])) {
    // Allow access
}

// Check if user has a specific permission
if ($user->hasPermission('create_campers')) {
    // Allow access
}

// Check if user is an admin
if ($user->isAdmin()) {
    // Allow admin access
}

// Camp-specific permission checks
if ($user->hasPermissionInCamp('view_campers', $campId)) {
    // User can view campers for this specific camp
}

// Check if user is assigned to a camp
if ($user->isAssignedToCamp($campId)) {
    // User is assigned to this camp
}

// Check if user has a role in a specific camp
if ($user->hasRoleInCamp('camp_director', $campId)) {
    // User is a director for this specific camp
}

// Get user's primary camp
$primaryCamp = $user->primaryCamp();

// Get all camps where user has a specific permission
$campsWithPermission = $user->getCampsWithPermission('view_registrations');
```

### In Routes

```php
// Protect routes with role middleware
Route::middleware(['auth', 'role:camp_director'])->group(function () {
    Route::get('/campers/create', [CamperController::class, 'create']);
});

// Protect routes with permission middleware
Route::middleware(['auth', 'permission:create_campers'])->group(function () {
    Route::post('/campers', [CamperController::class, 'store']);
});

// Multiple roles
Route::middleware(['auth', 'role:camp_director,board_member'])->group(function () {
    Route::get('/reports', [ReportController::class, 'index']);
});

// Camp-specific access control
Route::middleware(['auth', 'camp.access:view_campers'])->group(function () {
    Route::get('/camps/{camp}/campers', [CampController::class, 'campers']);
});

// Camp-specific permission check
Route::middleware(['auth', 'camp.access:view_registrations'])->group(function () {
    Route::get('/camps/{camp}/registrations', [CampController::class, 'registrations']);
});
```

### In Blade Templates

```blade
{{-- Check for specific role --}}
@role('camp_director')
    <div>Camp Director Content</div>
@endrole

{{-- Check for any of multiple roles --}}
@anyrole(['camp_director', 'board_member'])
    <div>Admin Content</div>
@endanyrole

{{-- Check for specific permission --}}
@permission('create_campers')
    <a href="/campers/create">Add New Camper</a>
@endpermission

{{-- Check for admin status --}}
@admin
    <div>Admin Only Content</div>
@endadmin

{{-- Camp-specific access --}}
@campaccess($camp->id)
    <div>Camp-specific content for {{ $camp->display_name }}</div>
@endcampaccess

{{-- Camp-specific permission --}}
@campPermission($camp->id, 'view_campers')
    <a href="/camps/{{ $camp->id }}/campers">View Campers</a>
@endcampPermission
```

### Managing Roles and Permissions

```php
// Assign a role to a user
$user->assignRole($role);

// Remove a role from a user
$user->removeRole($role);

// Sync roles (replace all existing roles)
$user->syncRoles([$role1->id, $role2->id]);

// Assign a permission to a role
$role->assignPermission($permission);

// Remove a permission from a role
$role->removePermission($permission);

// Camp assignments
$user->assignToCamp($camp, $role, [
    'position' => 'Head Counselor',
    'is_primary' => true,
]);

// Remove user from camp
$user->removeFromCamp($campId);

// Get user's camp assignments
$assignments = $user->campAssignments;

// Get user's assigned camps
$camps = $user->assignedCamps;
```

## Setup Instructions

1. **Run Migrations**
   ```bash
   php artisan migrate
   ```

2. **Seed the Database**
   ```bash
   php artisan db:seed
   ```

3. **Create Your First Admin User**
   ```bash
   php artisan tinker
   ```
   ```php
   $user = \App\Models\User::create([
       'name' => 'Admin User',
       'email' => 'admin@camp.com',
       'password' => Hash::make('password'),
   ]);
   
   $superAdminRole = \App\Models\Role::where('name', 'super_admin')->first();
   $user->assignRole($superAdminRole);
   ```

## Security Considerations

1. **Always check permissions at the controller level** - Don't rely solely on UI hiding
2. **Use middleware for route protection** - This provides server-side security
3. **Validate permissions in API endpoints** - Ensure proper authorization
4. **Log permission checks** - Monitor access patterns
5. **Regular role audits** - Review and update role assignments

## Extending the System

### Adding New Permissions

1. Add the permission to the `RolePermissionSeeder`
2. Run the seeder: `php artisan db:seed --class=RolePermissionSeeder`

### Adding New Roles

1. Create the role in the `RolePermissionSeeder`
2. Assign appropriate permissions
3. Run the seeder

### Custom Permission Logic

You can extend the User model with custom permission methods:

```php
public function canManageCampers(): bool
{
    return $this->hasAnyPermission(['create_campers', 'edit_campers', 'delete_campers']);
}
```

## Best Practices

1. **Principle of Least Privilege** - Only grant necessary permissions
2. **Role Hierarchy** - Design roles with clear hierarchy
3. **Permission Granularity** - Use specific permissions rather than broad ones
4. **Regular Reviews** - Periodically review and update permissions
5. **Documentation** - Keep role and permission documentation updated
6. **Testing** - Test permission checks thoroughly

## Troubleshooting

### Common Issues

1. **Permission not working** - Check if the permission exists and is assigned to the user's role
2. **Role not working** - Verify the role exists and is assigned to the user
3. **Middleware not working** - Ensure middleware is properly registered in `bootstrap/app.php`

### Debug Commands

```bash
# Check user roles
php artisan tinker
$user = \App\Models\User::find(1);
$user->roles->pluck('name');

# Check user permissions
$user->roles->flatMap->permissions->pluck('name')->unique();

# Check if user has specific permission
$user->hasPermission('create_campers');
``` 