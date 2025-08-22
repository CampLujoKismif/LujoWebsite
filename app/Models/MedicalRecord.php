<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicalRecord extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'camper_id',
        'allergies',
        'medications',
        'physician_name',
        'physician_phone',
        'policy_number',
        'insurance_provider',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
        'medical_conditions',
        'dietary_restrictions',
        'notes',
        'last_updated_by_user_id',
    ];

    protected $casts = [
        'allergies' => 'array',
        'medications' => 'array',
        'medical_conditions' => 'array',
        'dietary_restrictions' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the camper for this medical record.
     */
    public function camper(): BelongsTo
    {
        return $this->belongsTo(Camper::class);
    }

    /**
     * Get the family for this medical record.
     */
    public function family(): BelongsTo
    {
        return $this->camper->family();
    }

    /**
     * Get the user who last updated this record.
     */
    public function lastUpdatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'last_updated_by_user_id');
    }

    /**
     * Check if this medical record has any alerts.
     */
    public function hasAlerts(): bool
    {
        return !empty($this->allergies) || 
               !empty($this->medications) || 
               !empty($this->medical_conditions) ||
               !empty($this->dietary_restrictions);
    }

    /**
     * Get all medical alerts as a formatted string.
     */
    public function getAlertsSummaryAttribute(): string
    {
        $alerts = [];

        if (!empty($this->allergies)) {
            $alerts[] = 'Allergies: ' . implode(', ', $this->allergies);
        }

        if (!empty($this->medications)) {
            $alerts[] = 'Medications: ' . implode(', ', $this->medications);
        }

        if (!empty($this->medical_conditions)) {
            $alerts[] = 'Conditions: ' . implode(', ', $this->medical_conditions);
        }

        if (!empty($this->dietary_restrictions)) {
            $alerts[] = 'Dietary: ' . implode(', ', $this->dietary_restrictions);
        }

        return implode('; ', $alerts);
    }

    /**
     * Get the allergies as a formatted string.
     */
    public function getAllergiesDisplayAttribute(): string
    {
        return is_array($this->allergies) ? implode(', ', $this->allergies) : '';
    }

    /**
     * Get the medications as a formatted string.
     */
    public function getMedicationsDisplayAttribute(): string
    {
        return is_array($this->medications) ? implode(', ', $this->medications) : '';
    }

    /**
     * Get the medical conditions as a formatted string.
     */
    public function getMedicalConditionsDisplayAttribute(): string
    {
        return is_array($this->medical_conditions) ? implode(', ', $this->medical_conditions) : '';
    }

    /**
     * Get the dietary restrictions as a formatted string.
     */
    public function getDietaryRestrictionsDisplayAttribute(): string
    {
        return is_array($this->dietary_restrictions) ? implode(', ', $this->dietary_restrictions) : '';
    }

    /**
     * Check if the camper has allergies.
     */
    public function hasAllergies(): bool
    {
        return !empty($this->allergies);
    }

    /**
     * Check if the camper takes medications.
     */
    public function takesMedications(): bool
    {
        return !empty($this->medications);
    }

    /**
     * Check if the camper has medical conditions.
     */
    public function hasMedicalConditions(): bool
    {
        return !empty($this->medical_conditions);
    }

    /**
     * Check if the camper has dietary restrictions.
     */
    public function hasDietaryRestrictions(): bool
    {
        return !empty($this->dietary_restrictions);
    }
}
