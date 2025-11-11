<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;

class Camper extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'family_id',
        'first_name',
        'last_name',
        'date_of_birth',
        'biological_gender',
        'date_of_baptism',
        'phone_number',
        'email',
        'school',
        'photo_path',
        'allergies',
        'medical_conditions',
        'medications',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
        'notes',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'date_of_baptism' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $hidden = [
        'grade',
        't_shirt_size',
    ];

    /**
     * Normalize list-style health attributes before persisting.
     */
    protected function normalizeHealthListInput(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        if (is_string($value)) {
            $value = trim($value);

            if ($value === '') {
                return null;
            }

            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $value = $decoded;
            } else {
                $value = preg_split('/[\r\n,;]+/', $value);
            }
        } elseif ($value instanceof \Illuminate\Support\Collection) {
            $value = $value->all();
        }

        if (!is_array($value)) {
            $value = [$value];
        }

        $normalized = array_values(array_filter(array_map(static function ($item) {
            if (is_string($item)) {
                $item = trim($item);
            }

            return ($item === null || $item === '') ? null : $item;
        }, $value), static fn ($item) => $item !== null));

        if (empty($normalized)) {
            return null;
        }

        return json_encode($normalized, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Convert stored JSON back into a human-readable string.
     */
    protected function formatHealthListOutput(?string $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        $decoded = json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            if (!is_array($decoded)) {
                $decoded = [$decoded];
            }

            $decoded = array_values(array_filter(array_map(static function ($item) {
                return is_string($item) ? trim($item) : $item;
            }, $decoded), static fn ($item) => $item !== null && $item !== ''));

            if (empty($decoded)) {
                return null;
            }

            return implode(', ', array_map(static function ($item) {
                return is_scalar($item) ? (string) $item : json_encode($item);
            }, $decoded));
        }

        return trim($value);
    }

    /**
     * Convert stored JSON into an array.
     */
    protected function healthListToArray(?string $value): array
    {
        if ($value === null || $value === '') {
            return [];
        }

        $decoded = json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $decoded = is_array($decoded) ? $decoded : [$decoded];

            return array_values(array_filter(array_map(static function ($item) {
                return is_string($item) ? trim($item) : $item;
            }, $decoded), static fn ($item) => $item !== null && $item !== ''));
        }

        return array_values(array_filter(array_map(static fn ($item) => trim($item), preg_split('/[\r\n,;]+/', $value) ?: []), static fn ($item) => $item !== ''));
    }

    public function setAllergiesAttribute(mixed $value): void
    {
        $this->attributes['allergies'] = $this->normalizeHealthListInput($value);
    }

    public function getAllergiesAttribute(?string $value): ?string
    {
        return $this->formatHealthListOutput($value);
    }

    public function getAllergiesListAttribute(): array
    {
        return $this->healthListToArray($this->attributes['allergies'] ?? null);
    }

    public function setMedicalConditionsAttribute(mixed $value): void
    {
        $this->attributes['medical_conditions'] = $this->normalizeHealthListInput($value);
    }

    public function getMedicalConditionsAttribute(?string $value): ?string
    {
        return $this->formatHealthListOutput($value);
    }

    public function getMedicalConditionsListAttribute(): array
    {
        return $this->healthListToArray($this->attributes['medical_conditions'] ?? null);
    }

    public function setMedicationsAttribute(mixed $value): void
    {
        $this->attributes['medications'] = $this->normalizeHealthListInput($value);
    }

    public function getMedicationsAttribute(?string $value): ?string
    {
        return $this->formatHealthListOutput($value);
    }

    public function getMedicationsListAttribute(): array
    {
        return $this->healthListToArray($this->attributes['medications'] ?? null);
    }

    /**
     * Get the biological gender options.
     */
    public static function getBiologicalGenderOptions(): array
    {
        return [
            'Male' => 'Male',
            'Female' => 'Female',
        ];
    }

    /**
     * Get the biological gender options for validation.
     */
    public static function getBiologicalGenderValues(): array
    {
        return array_keys(self::getBiologicalGenderOptions());
    }

    /**
     * Get the t-shirt size options.
     */
    public static function getTShirtSizeOptions(): array
    {
        return [
            'XS' => 'XS',
            'S' => 'S',
            'M' => 'M',
            'L' => 'L',
            'XL' => 'XL',
            'XXL' => 'XXL',
            'Youth XS' => 'Youth XS',
            'Youth S' => 'Youth S',
            'Youth M' => 'Youth M',
            'Youth L' => 'Youth L',
            'Youth XL' => 'Youth XL',
        ];
    }

    /**
     * Get the photo URL.
     */
    public function getPhotoUrlAttribute(): ?string
    {
        if (!$this->photo_path) {
            return null;
        }
        
        return \Illuminate\Support\Facades\Storage::disk('public')->url($this->photo_path);
    }

    /**
     * Get the family for this camper.
     */
    public function family(): BelongsTo
    {
        return $this->belongsTo(Family::class);
    }

    /**
     * Get the enrollments for this camper.
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Get the medical record for this camper.
     */
    public function medicalRecord(): HasOne
    {
        return $this->hasOne(MedicalRecord::class);
    }

    /**
     * Get the form responses for this camper.
     */
    public function formResponses(): HasMany
    {
        return $this->hasMany(FormResponse::class);
    }

    /**
     * Get the documents for this camper.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function agreementSignatures(): HasMany
    {
        return $this->hasMany(CamperAgreementSignature::class);
    }

    public function informationSnapshots(): HasMany
    {
        return $this->hasMany(CamperInformationSnapshot::class);
    }

    public function medicalSnapshots(): HasMany
    {
        return $this->hasMany(CamperMedicalSnapshot::class);
    }

    /**
     * Get the notes for this camper.
     */
    public function notes(): HasMany
    {
        return $this->hasMany(Note::class, 'notable_id')
            ->where('notable_type', Camper::class);
    }

    /**
     * Get the full name of the camper.
     */
    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Get the age of the camper.
     */
    public function getAgeAttribute(): int
    {
        return $this->date_of_birth ? $this->date_of_birth->age : 0;
    }

    /**
     * Get the current grade level.
     */
    public function getCurrentGradeAttribute(): ?int
    {
        if (!$this->date_of_birth) {
            return null;
        }

        $age = $this->age;
        $currentYear = now()->year;
        $schoolYearStart = $currentYear - 5; // Assuming school starts at age 5

        return $age >= 5 ? min(12, $age - 5) : null;
    }

    /**
     * Check if the camper has any medical alerts.
     */
    public function hasMedicalAlerts(): bool
    {
        return !empty($this->allergies) || 
               !empty($this->medical_conditions) || 
               !empty($this->medications);
    }

    public function latestInformationSnapshot(?int $year = null): ?CamperInformationSnapshot
    {
        $year = $year ?? now()->year;

        return $this->informationSnapshots()
            ->where('year', '<=', $year)
            ->orderByDesc('year')
            ->first();
    }

    public function informationData(?int $year = null): ?array
    {
        return $this->latestInformationSnapshot($year)?->data;
    }

    public function gradeForYear(?int $year = null)
    {
        return Arr::get($this->informationData($year), 'camper.grade');
    }

    public function tShirtSizeForYear(?int $year = null)
    {
        return Arr::get($this->informationData($year), 'camper.t_shirt_size');
    }

    /**
     * Get active enrollments for this camper.
     */
    public function activeEnrollments()
    {
        return $this->enrollments()
            ->whereIn('status', ['pending', 'confirmed', 'waitlisted'])
            ->with('campSession.camp');
    }

    /**
     * Check if the camper is enrolled in a specific camp session.
     */
    public function isEnrolledIn(CampInstance $campInstance): bool
    {
        return $this->enrollments()
            ->where('camp_instance_id', $campInstance->id)
            ->whereIn('status', ['pending', 'confirmed', 'waitlisted'])
            ->exists();
    }
}
