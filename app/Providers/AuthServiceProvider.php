<?php

namespace App\Providers;

use App\Models\Camp;
use App\Models\CampInstance;
use App\Models\Camper;
use App\Models\Enrollment;
use App\Models\FormTemplate;
use App\Models\MedicalRecord;
use App\Policies\CampInstancePolicy;
use App\Policies\CampPolicy;
use App\Policies\CamperPolicy;
use App\Policies\EnrollmentPolicy;
use App\Policies\FormTemplatePolicy;
use App\Policies\MedicalRecordPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Camp::class => CampPolicy::class,
        CampInstance::class => CampInstancePolicy::class,
        Camper::class => CamperPolicy::class,
        Enrollment::class => EnrollmentPolicy::class,
        MedicalRecord::class => MedicalRecordPolicy::class,
        FormTemplate::class => FormTemplatePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
