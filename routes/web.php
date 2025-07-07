<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/strive-week', function () {
    return view('strive-week');
})->name('strive-week');

Route::get('/elevate-week', function () {
    return view('elevate-week');
})->name('elevate-week');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

// Admin routes (super admin only)
Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Camp management
    Route::resource('camps', App\Http\Controllers\Admin\CampManagementController::class);
    Route::get('camps/trashed', [App\Http\Controllers\Admin\CampManagementController::class, 'trashed'])->name('camps.trashed');
    Route::patch('camps/{id}/restore', [App\Http\Controllers\Admin\CampManagementController::class, 'restore'])->name('camps.restore');
    Route::delete('camps/{id}/force-delete', [App\Http\Controllers\Admin\CampManagementController::class, 'forceDelete'])->name('camps.force-delete');
    
    // User management
    Route::resource('users', App\Http\Controllers\Admin\UserManagementController::class);
    Route::get('users/trashed', [App\Http\Controllers\Admin\UserManagementController::class, 'trashed'])->name('users.trashed');
    Route::patch('users/{id}/restore', [App\Http\Controllers\Admin\UserManagementController::class, 'restore'])->name('users.restore');
    Route::delete('users/{id}/force-delete', [App\Http\Controllers\Admin\UserManagementController::class, 'forceDelete'])->name('users.force-delete');
    
    // Role management
    Route::resource('roles', App\Http\Controllers\Admin\RoleManagementController::class);
    Route::get('roles/trashed', [App\Http\Controllers\Admin\RoleManagementController::class, 'trashed'])->name('roles.trashed');
    Route::patch('roles/{id}/restore', [App\Http\Controllers\Admin\RoleManagementController::class, 'restore'])->name('roles.restore');
    Route::delete('roles/{id}/force-delete', [App\Http\Controllers\Admin\RoleManagementController::class, 'forceDelete'])->name('roles.force-delete');
    
    // Permission management
    Route::resource('permissions', App\Http\Controllers\Admin\PermissionManagementController::class);
    Route::get('permissions/trashed', [App\Http\Controllers\Admin\PermissionManagementController::class, 'trashed'])->name('permissions.trashed');
    Route::patch('permissions/{id}/restore', [App\Http\Controllers\Admin\PermissionManagementController::class, 'restore'])->name('permissions.restore');
    Route::delete('permissions/{id}/force-delete', [App\Http\Controllers\Admin\PermissionManagementController::class, 'forceDelete'])->name('permissions.force-delete');
});

// Camp dashboard routes (for users with camp access)
Route::middleware(['auth'])->prefix('camps')->name('camps.')->group(function () {
    Route::get('{camp}/dashboard', [App\Http\Controllers\CampController::class, 'dashboard'])->name('dashboard');
    Route::get('{camp}/staff', [App\Http\Controllers\CampController::class, 'staff'])->name('staff');
    Route::get('{camp}/activities', [App\Http\Controllers\CampController::class, 'activities'])->name('activities');
    Route::get('{camp}/settings', [App\Http\Controllers\CampController::class, 'settings'])->name('settings');
    Route::delete('{camp}/staff/{user}', [App\Http\Controllers\CampController::class, 'removeStaff'])->name('remove-staff');
    
    // Camp instance routes
    Route::get('{camp}/instances/create', [App\Http\Controllers\CampInstanceController::class, 'create'])->name('instances.create');
    Route::post('{camp}/instances', [App\Http\Controllers\CampInstanceController::class, 'store'])->name('instances.store');
    Route::get('{camp}/instances/{instance}/edit', [App\Http\Controllers\CampInstanceController::class, 'edit'])->name('instances.edit');
    Route::put('{camp}/instances/{instance}', [App\Http\Controllers\CampInstanceController::class, 'update'])->name('instances.update');
    Route::delete('{camp}/instances/{instance}', [App\Http\Controllers\CampInstanceController::class, 'destroy'])->name('instances.destroy');
    Route::get('{camp}/instances/{instance}', [App\Http\Controllers\CampInstanceController::class, 'show'])->name('instances.show');
});

Route::get('/api/frontpage-images', function () {
    $imagePath = public_path('FrontPage');
    $images = [];
    
    if (is_dir($imagePath)) {
        $files = scandir($imagePath);
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    $images[] = '/FrontPage/' . $file;
                }
            }
        }
    }
    
    return response()->json($images);
});

require __DIR__.'/auth.php';
