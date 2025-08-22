<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Camper extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'family_id',
        'first_name',
        'last_name',
        'date_of_birth',
        'gender',
        'grade',
        'school',
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
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

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
