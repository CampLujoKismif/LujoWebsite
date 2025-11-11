<?php

namespace App\Services;

use App\Models\Agreement;
use App\Models\Camper;
use App\Models\CamperAgreementSignature;
use App\Models\CamperInformationSnapshot;
use App\Models\CamperMedicalSnapshot;
use App\Models\Enrollment;
use App\Models\MedicalRecord;
use App\Models\ParentAgreementSignature;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class AnnualComplianceService
{
    public function __construct(
        protected ?array $config = null
    ) {
        $this->config = $config ?? config('annual_forms', []);
    }

    public function currentYear(): int
    {
        return (int) ($this->config['default_year'] ?? now()->year);
    }

    protected function resolveYear(?int $year = null): int
    {
        return $year ?? $this->currentYear();
    }

    public function parentAgreement(?int $year = null): Agreement
    {
        return $this->ensureAgreement('parent_guardian', Arr::get($this->config, 'parent_agreement', []), $year);
    }

    public function camperAgreement(?int $year = null): Agreement
    {
        return $this->ensureAgreement('camper_conduct', Arr::get($this->config, 'camper_agreement', []), $year);
    }

    public function camperAgreementDefinition(?int $year = null): array
    {
        $agreement = $this->camperAgreement($year);

        return [
            'id' => $agreement->id,
            'title' => $agreement->title,
            'content' => $agreement->content,
            'version' => $agreement->version,
        ];
    }

    protected function ensureAgreement(string $type, array $definition, ?int $year = null): Agreement
    {
        $year = $this->resolveYear($year);
        $baseSlug = $definition['slug'] ?? $type;
        $slug = sprintf('%s-%d', $baseSlug, $year);

        return Agreement::updateOrCreate(
            ['slug' => $slug],
            [
                'type' => $type,
                'title' => $definition['title'] ?? ucfirst(str_replace('_', ' ', $type)),
                'content' => $definition['content'] ?? '',
                'is_active' => true,
                'year' => $year,
                'version' => $definition['version'] ?? 1,
                'published_at' => now(),
            ]
        );
    }

    public function parentSignatureStatus(User $user, ?int $year = null): array
    {
        $year = $this->resolveYear($year);
        $agreement = $this->parentAgreement($year);

        $signature = ParentAgreementSignature::where('user_id', $user->id)
            ->where('agreement_id', $agreement->id)
            ->where('year', $year)
            ->first();

        return [
            'required' => true,
            'signed' => (bool) $signature,
            'signed_at' => optional($signature?->signed_at)?->toIso8601String(),
            'typed_name' => $signature?->typed_name,
            'agreement' => [
                'id' => $agreement->id,
                'title' => $agreement->title,
                'content' => $agreement->content,
                'version' => $agreement->version,
            ],
        ];
    }

    public function camperStatuses(User $user, ?int $year = null): array
    {
        $year = $this->resolveYear($year);
        $agreement = $this->camperAgreement($year);

        /** @var Collection<int, Camper> $campers */
        $campers = $user->accessibleCampers()
            ->with([
                'medicalRecord',
                'informationSnapshots',
                'medicalSnapshots',
                'agreementSignatures' => function ($query) use ($agreement) {
                    $query->where('agreement_id', $agreement->id);
                },
            ])->get();

        return $campers->map(function (Camper $camper) use ($year) {
            $infoSnapshot = $camper->informationSnapshots
                ->firstWhere('year', $year)
                ?? $camper->informationSnapshots
                    ->where('year', '<', $year)
                    ->sortByDesc('year')
                    ->first();

            $medicalSnapshot = $camper->medicalSnapshots
                ->firstWhere('year', $year)
                ?? $camper->medicalSnapshots
                    ->where('year', '<', $year)
                    ->sortByDesc('year')
                    ->first();

            $signature = $camper->agreementSignatures
                ->firstWhere('year', $year)
                ?? $camper->agreementSignatures
                    ->where('year', '<', $year)
                    ->sortByDesc('year')
                    ->first();

            return [
                'camper_id' => $camper->id,
                'camper_name' => $camper->full_name,
                'information_snapshot_id' => $infoSnapshot?->id,
                'medical_snapshot_id' => $medicalSnapshot?->id,
                'camper_signature_id' => $signature?->id,
                'signed' => (bool) ($infoSnapshot && $medicalSnapshot && $signature),
                'signed_at' => optional($signature?->signed_at)?->toIso8601String(),
                'typed_name' => $signature?->typed_name,
            ];
        })->values()->all();
    }

    public function captureAnnualForms(User $user, array $payload, string $ip = null, string $userAgent = null, ?int $year = null): array
    {
        $year = $this->resolveYear($year);
        $parentAgreement = $this->parentAgreement($year);
        $camperAgreement = $this->camperAgreement($year);
        $informationVersion = $this->config['information_form_version'] ?? "{$year}.1";
        $medicalVersion = $this->config['medical_form_version'] ?? "{$year}.1";

        return DB::transaction(function () use (
            $user,
            $payload,
            $year,
            $parentAgreement,
            $camperAgreement,
            $informationVersion,
            $medicalVersion,
            $ip,
            $userAgent
        ) {
            $timestamp = now();

            // Parent signature
            $parentSignature = ParentAgreementSignature::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'agreement_id' => $parentAgreement->id,
                    'year' => $year,
                ],
                [
                    'typed_name' => Arr::get($payload, 'parent.typed_name'),
                    'signed_at' => $timestamp,
                    'ip_address' => $ip,
                    'user_agent' => $userAgent,
                    'metadata' => [
                        'affirmations' => Arr::get($payload, 'parent.affirmations', []),
                    ],
                ]
            );

            $camperResponses = [];

            $campersPayload = Arr::get($payload, 'campers', []);
            $campers = $user->accessibleCampers()->with('medicalRecord', 'family.users')->get()->keyBy('id');

            foreach ($campersPayload as $camperInput) {
                $camperId = Arr::get($camperInput, 'camper_id');
                /** @var Camper|null $camper */
                $camper = $campers->get($camperId);

                if (!$camper) {
                    continue;
                }

                $informationSnapshot = $this->ensureInformationSnapshot($camper, $year, $informationVersion, $timestamp, $user->id, $ip, $userAgent);
                $medicalSnapshot = $this->ensureMedicalSnapshot($camper, $year, $medicalVersion, $timestamp, $user->id, $ip, $userAgent);

                $camperSignature = CamperAgreementSignature::updateOrCreate(
                    [
                        'camper_id' => $camper->id,
                        'agreement_id' => $camperAgreement->id,
                        'year' => $year,
                    ],
                    [
                        'typed_name' => Arr::get($camperInput, 'typed_name'),
                        'signed_at' => $timestamp,
                        'ip_address' => $ip,
                        'user_agent' => $userAgent,
                        'metadata' => [
                            'affirmations' => Arr::get($camperInput, 'affirmations', []),
                        ],
                    ]
                );

                $camperResponses[] = [
                    'camper_id' => $camper->id,
                    'information_snapshot_id' => $informationSnapshot?->id,
                    'medical_snapshot_id' => $medicalSnapshot?->id,
                    'camper_signature_id' => $camperSignature->id,
                ];
            }

            return [
                'parent_signature_id' => $parentSignature->id,
                'campers' => $camperResponses,
            ];
        });
    }

    protected function ensureInformationSnapshot(
        Camper $camper,
        int $year,
        string $version,
        $timestamp,
        ?int $userId,
        ?string $ip,
        ?string $userAgent
    ): CamperInformationSnapshot {
        $snapshot = CamperInformationSnapshot::firstOrNew([
            'camper_id' => $camper->id,
            'year' => $year,
        ]);

        if (!$snapshot->exists || empty($snapshot->data)) {
            $infoData = $this->defaultInformationFormData($camper, $year);
            $snapshot->fill([
                'form_version' => $version,
                'data' => $infoData,
                'data_hash' => hash('sha256', json_encode($infoData)),
            ]);
        }

        $snapshot->captured_at = $timestamp;
        $snapshot->captured_by_user_id = $userId;
        $snapshot->ip_address = $ip;
        $snapshot->user_agent = $userAgent;
        $snapshot->save();

        return $snapshot;
    }

    protected function ensureMedicalSnapshot(
        Camper $camper,
        int $year,
        string $version,
        $timestamp,
        ?int $userId,
        ?string $ip,
        ?string $userAgent
    ): CamperMedicalSnapshot {
        $snapshot = CamperMedicalSnapshot::firstOrNew([
            'camper_id' => $camper->id,
            'year' => $year,
        ]);

        if (!$snapshot->exists || empty($snapshot->data)) {
            $medicalData = $this->defaultMedicalFormData($camper);
            $snapshot->fill([
                'form_version' => $version,
                'data' => $medicalData,
                'data_hash' => hash('sha256', json_encode($medicalData)),
            ]);
        }

        $snapshot->captured_at = $timestamp;
        $snapshot->captured_by_user_id = $userId;
        $snapshot->ip_address = $ip;
        $snapshot->user_agent = $userAgent;
        $snapshot->save();

        return $snapshot;
    }

    public function getCamperForms(User $user, array $camperIds, ?int $year = null): array
    {
        $year = $this->resolveYear($year);
        $campers = $user->accessibleCampers()
            ->with(['family.owner', 'medicalRecord', 'informationSnapshots', 'medicalSnapshots'])
            ->whereIn('id', $camperIds)
            ->get()
            ->keyBy('id');

        $previousYear = $year - 1;

        return $camperIds
            ? array_values(array_filter(array_map(function ($camperId) use ($campers, $year, $previousYear) {
                /** @var Camper|null $camper */
                $camper = $campers->get($camperId);
                if (!$camper) {
                    return null;
                }

                $informationData = $this->resolveSnapshotData($camper->informationSnapshots, $year, $previousYear)
                    ?? $this->defaultInformationFormData($camper, $year);

                $medicalData = $this->resolveSnapshotData($camper->medicalSnapshots, $year, $previousYear)
                    ?? $this->defaultMedicalFormData($camper);

                return [
                    'camper_id' => $camper->id,
                    'camper_name' => $camper->full_name,
                    'information' => $informationData,
                    'medical' => $medicalData,
                ];
            }, $camperIds)))
            : [];
    }

    public function storeCamperForms(User $user, array $forms, string $ip = null, string $userAgent = null, ?int $year = null): array
    {
        $year = $this->resolveYear($year);
        $informationVersion = $this->config['information_form_version'] ?? "{$year}.1";
        $medicalVersion = $this->config['medical_form_version'] ?? "{$year}.1";
        $timestamp = now();

        $campers = $user->accessibleCampers()
            ->with(['family.owner', 'medicalRecord'])
            ->whereIn('id', array_column($forms, 'camper_id'))
            ->get()
            ->keyBy('id');

        $responses = [];

        DB::transaction(function () use (
            $forms,
            $campers,
            $timestamp,
            $year,
            $informationVersion,
            $medicalVersion,
            $ip,
            $userAgent,
            $user,
            &$responses
        ) {
            foreach ($forms as $form) {
                $camperId = Arr::get($form, 'camper_id');
                /** @var Camper|null $camper */
                $camper = $campers->get($camperId);

                if (!$camper) {
                    continue;
                }

                $informationSnapshot = null;
                $medicalSnapshot = null;

                $informationInput = $this->normalizeInformationInput($camper, Arr::get($form, 'information', []), $year);
                $medicalInput = $this->normalizeMedicalInput($camper, Arr::get($form, 'medical', []));

                // Update camper core fields
                $camper->fill(array_filter([
                    'date_of_birth' => Arr::get($informationInput, 'camper.date_of_birth'),
                    'phone_number' => Arr::get($informationInput, 'camper.home_phone'),
                    'email' => Arr::get($informationInput, 'camper.email'),
                ], fn ($value) => !is_null($value)));
                $camper->save();

                // Medical record update
                $medicalRecord = $camper->medicalRecord ?: new MedicalRecord(['camper_id' => $camper->id]);
                $medicalRecord->fill(array_filter([
                    'physician_name' => Arr::get($medicalInput, 'medical.physician_name'),
                    'physician_phone' => Arr::get($medicalInput, 'medical.physician_phone'),
                    'insurance_provider' => Arr::get($medicalInput, 'insurance.company'),
                    'policy_number' => Arr::get($medicalInput, 'insurance.policy_number'),
                    'emergency_contact_name' => Arr::get($medicalInput, 'emergency_contact.name'),
                    'emergency_contact_phone' => Arr::get($medicalInput, 'emergency_contact.phone'),
                    'emergency_contact_relationship' => Arr::get($medicalInput, 'emergency_contact.relationship'),
                    'allergies' => Arr::get($medicalInput, 'medical.allergies'),
                    'medications' => Arr::get($medicalInput, 'medical.medications'),
                    'medical_conditions' => Arr::get($medicalInput, 'medical.conditions'),
                    'notes' => Arr::get($medicalInput, 'medical.notes'),
                ], fn ($value) => !is_null($value)));
                $medicalRecord->save();

                $informationSnapshot = CamperInformationSnapshot::updateOrCreate(
                    [
                        'camper_id' => $camper->id,
                        'year' => $year,
                    ],
                    [
                        'form_version' => $informationVersion,
                        'data' => $informationInput,
                        'captured_at' => $timestamp,
                        'captured_by_user_id' => $user->id,
                        'ip_address' => $ip,
                        'user_agent' => $userAgent,
                        'data_hash' => hash('sha256', json_encode($informationInput)),
                    ]
                );

                $medicalSnapshot = CamperMedicalSnapshot::updateOrCreate(
                    [
                        'camper_id' => $camper->id,
                        'year' => $year,
                    ],
                    [
                        'form_version' => $medicalVersion,
                        'data' => $medicalInput,
                        'captured_at' => $timestamp,
                        'captured_by_user_id' => $user->id,
                        'ip_address' => $ip,
                        'user_agent' => $userAgent,
                        'data_hash' => hash('sha256', json_encode($medicalInput)),
                    ]
                );

                $responses[] = [
                    'camper_id' => $camper->id,
                    'information_snapshot_id' => $informationSnapshot?->id,
                    'medical_snapshot_id' => $medicalSnapshot?->id,
                ];
            }
        });

        return $responses;
    }

    protected function resolveSnapshotData($snapshots, int $currentYear, int $previousYear): ?array
    {
        $current = $snapshots->firstWhere('year', $currentYear);
        if ($current && !empty($current->data)) {
            return $current->data;
        }

        $previous = $snapshots
            ->where('year', '<', $currentYear)
            ->sortByDesc('year')
            ->first();

        return $previous?->data;
    }

    protected function buildInformationSnapshotData(Camper $camper, User $user): array
    {
        $data = $this->defaultInformationFormData($camper, $this->currentYear());
        $data['snapshot_version'] = $this->config['information_form_version'] ?? "{$this->currentYear()}.1";
        $data['captured_by'] = [
            'user_id' => $user->id,
            'name' => $user->name,
        ];

        return $data;
    }

    protected function buildMedicalSnapshotData(Camper $camper, User $user): array
    {
        $data = $this->defaultMedicalFormData($camper);
        $data['snapshot_version'] = $this->config['medical_form_version'] ?? "{$this->currentYear()}.1";
        $data['camper_id'] = $camper->id;
        $data['captured_by'] = [
            'user_id' => $user->id,
            'name' => $user->name,
        ];

        return $data;
    }

    public function attachSnapshotsToEnrollment(Enrollment $enrollment): void
    {
        $year = $this->currentYear();
        $parentAgreement = $this->parentAgreement();
        $camperAgreement = $this->camperAgreement();

        $parentSignature = ParentAgreementSignature::where('user_id', $enrollment->family()->first()->owner_user_id ?? null)
            ->where('agreement_id', $parentAgreement->id)
            ->where('year', $year)
            ->first();

        $informationSnapshot = CamperInformationSnapshot::where('camper_id', $enrollment->camper_id)
            ->where('year', $year)
            ->first();

        $medicalSnapshot = CamperMedicalSnapshot::where('camper_id', $enrollment->camper_id)
            ->where('year', $year)
            ->first();

        $enrollment->update([
            'parent_signature_id' => $parentSignature?->id,
            'information_snapshot_id' => $informationSnapshot?->id,
            'medical_snapshot_id' => $medicalSnapshot?->id,
        ]);
    }

    protected function defaultInformationFormData(Camper $camper, ?int $year = null): array
    {
        $year = $this->resolveYear($year);
        $family = $camper->family;
        $medical = $camper->medicalRecord;
        $informationData = $camper->informationData($year) ?? $camper->informationData();

        $dateOfBirth = $camper->date_of_birth ? $camper->date_of_birth->toDateString() : null;
        $age = $camper->date_of_birth ? $camper->date_of_birth->age : null;
        $grade = Arr::get($informationData, 'camper.grade');
        $tShirtSize = Arr::get($informationData, 'camper.t_shirt_size');

        if (is_null($grade)) {
            $grade = $camper->getAttribute('grade');
        }

        if (is_null($tShirtSize)) {
            $tShirtSize = $camper->getAttribute('t_shirt_size');
        }

        return [
            'camper' => [
                'first_name' => $camper->first_name ?? '',
                'last_name' => $camper->last_name ?? '',
                'date_of_birth' => $dateOfBirth,
                'age' => $age,
                'grade' => $grade ?? '',
                'sex' => $camper->biological_gender ?? '',
                'address' => $family?->address ?? '',
                'city' => $family?->city ?? '',
                'state' => $family?->state ?? '',
                'zip' => $family?->zip_code ?? '',
                'home_phone' => $camper->phone_number ?? $family?->phone ?? '',
                'alternate_phone' => '',
                'email' => $camper->email ?? '',
                'alternate_email' => '',
                'home_church' => $family?->home_church ?? '',
                'parent_marital_status' => '',
                'lives_with' => '',
                't_shirt_size' => $tShirtSize ?? '',
            ],
            'emergency_contact' => [
                'name' => $family?->emergency_contact_name ?? $medical?->emergency_contact_name ?? '',
                'relation' => $family?->emergency_contact_relationship ?? $medical?->emergency_contact_relationship ?? '',
                'address' => $family?->address ?? '',
                'city' => $family?->city ?? '',
                'state' => $family?->state ?? '',
                'zip' => $family?->zip_code ?? '',
                'home_phone' => $family?->emergency_contact_phone ?? $medical?->emergency_contact_phone ?? '',
                'work_phone' => '',
                'cell_phone' => '',
                'authorized_pickup' => true,
            ],
        ];
    }

    protected function defaultMedicalFormData(Camper $camper): array
    {
        $medical = $camper->medicalRecord;
        $family = $camper->family;

        return [
            'medical' => [
                'life_health_issues' => '',
                'medications_prescribed' => '',
                'otc_medications' => '',
                'conditions' => $medical?->medical_conditions ?? [],
                'notes' => $medical?->notes ?? '',
                'medications' => $medical?->medications ?? [],
                'allergies' => $medical?->allergies ?? [],
                'life_changes' => '',
                'physician_name' => $medical?->physician_name ?? '',
                'physician_phone' => $medical?->physician_phone ?? '',
                'tetanus_date' => '',
            ],
            'otc_permissions' => [
                'pain' => false,
                'skin' => false,
                'cuts' => false,
                'stomach' => false,
                'allergies' => false,
            ],
            'emergency_contact' => [
                'name' => $medical?->emergency_contact_name ?? $family?->emergency_contact_name ?? '',
                'relationship' => $medical?->emergency_contact_relationship ?? $family?->emergency_contact_relationship ?? '',
                'address' => $family?->address ?? '',
                'city' => $family?->city ?? '',
                'state' => $family?->state ?? '',
                'zip' => $family?->zip_code ?? '',
                'phone' => $medical?->emergency_contact_phone ?? $family?->emergency_contact_phone ?? '',
                'all_hours_phone' => '',
            ],
            'insurance' => [
                'insured_name' => $family?->insurance_insured_name ?? '',
                'company' => $medical?->insurance_provider ?? $family?->insurance_provider ?? '',
                'address' => '',
                'city' => '',
                'state' => '',
                'zip' => '',
                'phone' => $medical?->insurance_phone ?? '',
                'policy_number' => $medical?->insurance_policy_number ?? $family?->insurance_policy_number ?? '',
                'group_number' => $medical?->insurance_group_number ?? '',
            ],
        ];
    }

    protected function normalizeInformationInput(Camper $camper, array $input, ?int $year = null): array
    {
        $year = $this->resolveYear($year);
        $defaults = $this->defaultInformationFormData($camper, $year);
        $camperInput = array_merge($defaults['camper'], Arr::get($input, 'camper', []));
        $emergencyInput = array_merge($defaults['emergency_contact'], Arr::get($input, 'emergency_contact', []));

        $camperInput['date_of_birth'] = $camperInput['date_of_birth'] ?: ($camper->date_of_birth ? $camper->date_of_birth->toDateString() : null);
        $camperInput['age'] = $camperInput['age'] ?: ($camper->date_of_birth ? $camper->date_of_birth->age : null);
        if ($camperInput['grade'] === null || $camperInput['grade'] === '') {
            $camperInput['grade'] = $camper->gradeForYear($year) ?? $camper->getAttribute('grade');
        }
        if ($camperInput['t_shirt_size'] === null || $camperInput['t_shirt_size'] === '') {
            $camperInput['t_shirt_size'] = $camper->tShirtSizeForYear($year) ?? $camper->getAttribute('t_shirt_size');
        }

        return [
            'camper' => $camperInput,
            'emergency_contact' => $emergencyInput,
        ];
    }

    protected function normalizeMedicalInput(Camper $camper, array $input): array
    {
        $defaults = $this->defaultMedicalFormData($camper);

        $medicalInput = array_merge($defaults['medical'], Arr::get($input, 'medical', []));
        $medicalInput['medications'] = $this->toStringArray(Arr::get($medicalInput, 'medications'));
        $medicalInput['allergies'] = $this->toStringArray(Arr::get($medicalInput, 'allergies'));

        $otcInput = array_merge($defaults['otc_permissions'], Arr::get($input, 'otc_permissions', []));
        foreach ($otcInput as $key => $value) {
            $otcInput[$key] = (bool) $value;
        }

        $emergency = array_merge($defaults['emergency_contact'], Arr::get($input, 'emergency_contact', []));
        $insurance = array_merge($defaults['insurance'], Arr::get($input, 'insurance', []));

        return [
            'medical' => $medicalInput,
            'otc_permissions' => $otcInput,
            'emergency_contact' => $emergency,
            'insurance' => $insurance,
        ];
    }

    protected function toStringArray(mixed $value): array
    {
        if (is_array($value)) {
            return array_values(array_filter(array_map(
                fn ($entry) => is_string($entry) ? trim($entry) : (string) $entry,
                $value
            ), fn ($entry) => $entry !== ''));
        }

        if (is_string($value) && trim($value) !== '') {
            return array_map('trim', preg_split('/\r\n|[\r\n]/', $value));
        }

        return [];
    }
}

