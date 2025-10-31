<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    try {
        $campInstances = \App\Models\CampInstance::with('camp')
            ->where('is_active', true)
            ->orderBy('start_date')
            ->get();
        
        return view('home', compact('campInstances'));
    } catch (\Exception $e) {
        // If there's an error, pass an empty collection
        $campInstances = collect();
        return view('home', compact('campInstances'));
    }
})->name('home');

Route::get('/strive-week', function () {
    return view('strive-week');
})->name('strive-week');

Route::get('/elevate-week', function () {
    return view('elevate-week');
})->name('elevate-week');

Route::get('/camp-sessions', function () {
    return view('public.camp-sessions');
})->name('camp-sessions.index');

Route::get('/camp-sessions/{instance}', [App\Http\Controllers\PublicCampController::class, 'showSession'])->name('camp-sessions.show');

Route::get('/rentals', function () {
    return view('rentals');
})->name('rentals');

// Dashboard routes with role-based routing
Route::middleware(['auth', 'verified', 'require.password.change'])->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', App\Livewire\Dashboard\Index::class)->name('home');
    
    // Admin Dashboard
    Route::middleware(['role:system-admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', App\Livewire\Admin\Dashboard::class)->name('index');
        Route::get('/users', App\Livewire\Admin\UserManagement::class)->name('users');
        Route::get('/roles', App\Livewire\Admin\RoleManagement::class)->name('roles');
        Route::get('/camps', App\Livewire\Admin\CampManagement::class)->name('camps');
        Route::get('/church-congregations', App\Livewire\Admin\ChurchCongregationManagement::class)->name('church-congregations');
        Route::get('/form-templates', App\Livewire\Admin\FormTemplateManagement::class)->name('form-templates');
        Route::get('/form-responses', App\Livewire\Admin\FormResponseManagement::class)->name('form-responses');
        Route::get('/enrollments', App\Livewire\Admin\EnrollmentManagement::class)->name('enrollments');
        Route::get('/url-forwards', App\Livewire\Admin\UrlForwardManagement::class)->name('url-forwards');
        Route::get('/error-logs', App\Livewire\Admin\ErrorLogViewer::class)->name('error-logs');
        Route::get('/rentals', App\Livewire\Admin\RentalManagement::class)->name('rentals');
        Route::get('/discount-codes', App\Livewire\Admin\DiscountCodeManagement::class)->name('discount-codes');
        Route::get('/email-templates', App\Livewire\Admin\EmailTemplateManagement::class)->name('email-templates');
    });
    
    // Rental Admin Dashboard (for rental-admin role)
    Route::middleware(['role:rental-admin'])->prefix('rental-admin')->name('rental-admin.')->group(function () {
        Route::get('/', App\Livewire\Admin\RentalManagement::class)->name('index');
    });
    
    // Manager Dashboard
    Route::middleware(['role:camp-manager,system-admin'])->prefix('manager')->name('manager.')->group(function () {
        Route::get('/', App\Livewire\Manager\Dashboard::class)->name('index');
        Route::get('/enrollments', App\Livewire\Manager\EnrollmentManagement::class)->name('enrollments');
        Route::get('/sessions', App\Livewire\Manager\CampSessionManagement::class)->name('sessions');
    });
    
    // Parent Portal
                Route::middleware(['role:parent,system-admin'])->prefix('parent')->name('parent.')->group(function () {
                Route::get('/', App\Livewire\ParentPortal\Dashboard::class)->name('index');
                Route::get('/families', App\Livewire\ParentPortal\FamilyManagement::class)->name('families');
                Route::get('/campers', App\Livewire\ParentPortal\CamperManagement::class)->name('campers');
                Route::get('/enrollments', App\Livewire\ParentPortal\EnrollmentManagement::class)->name('enrollments');
                Route::get('/payments', App\Livewire\ParentPortal\PaymentProcessing::class)->name('payments');
                Route::get('/register/{campInstance}', App\Livewire\ParentPortal\CampRegistration::class)->name('register');
                Route::get('/medical-records', App\Livewire\ParentPortal\MedicalRecords::class)->name('medical-records');
                Route::get('/forms', App\Livewire\ParentPortal\FormFilling::class)->name('forms');
                Route::get('/families/{family}', App\Livewire\ParentPortal\FamilyDetails::class)->name('family-details');
                Route::get('/families/{family}/attachments', App\Livewire\ParentPortal\FamilyAttachments::class)->name('family-attachments');
                Route::get('/families/{family}/insurance', App\Livewire\ParentPortal\FamilyInsuranceInfo::class)->name('family-insurance');
                Route::get('/families/{family}/congregation', App\Livewire\ParentPortal\FamilyCongregationInfo::class)->name('family-congregation');
            });
});

Route::middleware(['auth', 'require.password.change'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

// Admin routes (system admin only)
Route::middleware(['auth', 'require.password.change', 'role:system-admin'])->prefix('admin')->name('admin.')->group(function () {
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
    
    // URL Forward management
    Route::resource('url-forwards', App\Http\Controllers\Admin\UrlForwardManagementController::class);
    Route::get('url-forwards/trashed', [App\Http\Controllers\Admin\UrlForwardManagementController::class, 'trashed'])->name('url-forwards.trashed');
    Route::patch('url-forwards/{id}/restore', [App\Http\Controllers\Admin\UrlForwardManagementController::class, 'restore'])->name('url-forwards.restore');
    Route::delete('url-forwards/{id}/force-delete', [App\Http\Controllers\Admin\UrlForwardManagementController::class, 'forceDelete'])->name('url-forwards.force-delete');
    Route::patch('url-forwards/{urlForward}/toggle-status', [App\Http\Controllers\Admin\UrlForwardManagementController::class, 'toggleStatus'])->name('url-forwards.toggle-status');
    
    // Church Congregation management
    Route::resource('church-congregations', App\Http\Controllers\Admin\ChurchCongregationController::class);
    Route::get('church-congregations/trashed', [App\Http\Controllers\Admin\ChurchCongregationController::class, 'trashed'])->name('church-congregations.trashed');
    Route::patch('church-congregations/{id}/restore', [App\Http\Controllers\Admin\ChurchCongregationController::class, 'restore'])->name('church-congregations.restore');
    Route::delete('church-congregations/{id}/force-delete', [App\Http\Controllers\Admin\ChurchCongregationController::class, 'forceDelete'])->name('church-congregations.force-delete');
    Route::get('api/congregations', [App\Http\Controllers\Admin\ChurchCongregationController::class, 'getCongregations'])->name('api.congregations');
    
    // Rental admin API routes (route is in admin group, so path is relative to /admin)
    Route::get('api/rental/availability/{year}/{month}', [App\Http\Controllers\Api\RentalController::class, 'getAdminAvailability'])->name('api.rental.admin.availability');
});

// Camp dashboard routes (for users with camp access)
Route::middleware(['auth', 'require.password.change'])->prefix('camps')->name('camps.')->group(function () {
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

// Stripe webhook route
Route::post('/stripe/webhook', [App\Http\Controllers\StripeWebhookController::class, 'handleWebhook'])->name('cashier.webhook');

// URL Forwarding - this should be the very last route to catch any unmatched URLs
Route::get('{internalUrl}', [App\Http\Controllers\UrlForwardController::class, 'forward'])
    ->where('internalUrl', '[a-zA-Z0-9\/\-_]+')
    ->name('url.forward');
