<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\Manager\SessionReportPreviewController;

Route::get('/', function () {
    try {
        $campInstances = \App\Models\CampInstance::with('camp')
            ->orderBy('start_date')
            ->get();

        $activeCampSessions = $campInstances->where('is_active', true);

        return view('home', compact('campInstances', 'activeCampSessions'));
    } catch (\Exception $e) {
        // If there's an error, pass an empty collection
        $campInstances = collect();
        $activeCampSessions = collect();
        return view('home', compact('campInstances', 'activeCampSessions'));
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

// Public Registration API Routes (accessible without auth for initial steps)
Route::prefix('api/public-registration')->name('api.public-registration.')->group(function () {
    Route::get('/check-auth', [App\Http\Controllers\Api\PublicRegistrationController::class, 'checkAuth']);
    Route::post('/login', [App\Http\Controllers\Api\PublicRegistrationController::class, 'login']);
    Route::post('/register', [App\Http\Controllers\Api\PublicRegistrationController::class, 'register']);
    Route::get('/camp-instance/{id}', [App\Http\Controllers\Api\PublicRegistrationController::class, 'getCampInstance']);
    
    // Routes that require authentication
    Route::middleware('auth')->group(function () {
        Route::get('/user-data', [App\Http\Controllers\Api\PublicRegistrationController::class, 'getUserData']);
        Route::post('/family', [App\Http\Controllers\Api\PublicRegistrationController::class, 'updateFamily']);
        Route::post('/camper', [App\Http\Controllers\Api\PublicRegistrationController::class, 'saveCamper']);
        Route::delete('/camper/{id}', [App\Http\Controllers\Api\PublicRegistrationController::class, 'deleteCamper']);
        Route::patch('/camper/{id}/restore', [App\Http\Controllers\Api\PublicRegistrationController::class, 'restoreCamper']);
        Route::post('/discounts/validate', [App\Http\Controllers\Api\PublicRegistrationController::class, 'validateDiscountCode']);
        Route::post('/enrollments', [App\Http\Controllers\Api\PublicRegistrationController::class, 'createEnrollments']);
        Route::get('/annual-status', [App\Http\Controllers\Api\AnnualComplianceController::class, 'status']);
        Route::post('/annual-confirmation', [App\Http\Controllers\Api\AnnualComplianceController::class, 'submit']);
        Route::get('/forms', [App\Http\Controllers\Api\AnnualFormController::class, 'show']);
        Route::post('/forms', [App\Http\Controllers\Api\AnnualFormController::class, 'store']);
        Route::post('/create-payment-intent', [App\Http\Controllers\Api\PublicRegistrationController::class, 'createPaymentIntent']);
        Route::post('/confirm-payment', [App\Http\Controllers\Api\PublicRegistrationController::class, 'confirmPayment']);
    });
});

// Dashboard routes with role-based routing
Route::middleware(['auth', 'verified', 'require.password.change', 'require.onboarding'])->prefix('dashboard')->name('dashboard.')->group(function () {
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
        Route::get('/reports/sessions/{campInstance}/preview', SessionReportPreviewController::class)->name('reports.sessions.preview');
    });
    
    // Parent Portal
                Route::middleware(['role:parent,system-admin'])->prefix('parent')->name('parent.')->group(function () {
                Route::get('/', App\Livewire\ParentPortal\Dashboard::class)->name('index');
                Route::get('/payments', App\Livewire\ParentPortal\PaymentProcessing::class)->name('payments');
                Route::get('/register/{campInstance}', App\Livewire\ParentPortal\CampRegistration::class)->name('register');
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
    
    // Camp session template management
    Route::get('session-templates', [App\Http\Controllers\Admin\CampSessionTemplateController::class, 'index'])->name('session-templates.index');
    Route::get('camps/{camp}/session-template', [App\Http\Controllers\Admin\CampSessionTemplateController::class, 'show'])->name('camps.session-template');
    Route::put('camps/{camp}/session-template', [App\Http\Controllers\Admin\CampSessionTemplateController::class, 'update'])->name('camps.session-template.update');
    
    // User management
    Route::resource('users', App\Http\Controllers\Admin\UserManagementController::class);
    Route::get('users/trashed', [App\Http\Controllers\Admin\UserManagementController::class, 'trashed'])->name('users.trashed');
    Route::patch('users/{id}/restore', [App\Http\Controllers\Admin\UserManagementController::class, 'restore'])->name('users.restore');
    Route::delete('users/{id}/force-delete', [App\Http\Controllers\Admin\UserManagementController::class, 'forceDelete'])->name('users.force-delete');
    Route::post('users/{user}/resend-verification', [App\Http\Controllers\Admin\UserManagementController::class, 'resendVerification'])->name('users.resend-verification');
    
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

// Image upload for session templates and session details (accessible to admins and camp managers)
Route::middleware(['auth', 'require.password.change', 'role:system-admin,camp-manager'])->group(function () {
    Route::post('admin/session-templates/upload-image', [App\Http\Controllers\Admin\CampSessionTemplateController::class, 'uploadImage'])->name('session-templates.upload-image');
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
