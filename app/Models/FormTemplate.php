<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormTemplate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'scope',
        'camp_instance_id',
        'name',
        'description',
        'is_active',
        'requires_annual_completion',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'requires_annual_completion' => 'boolean',
        'sort_order' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the camp instance for this form template.
     */
    public function campInstance(): BelongsTo
    {
        return $this->belongsTo(CampInstance::class);
    }

    /**
     * Get the camp for this form template.
     */
    public function camp(): BelongsTo
    {
        return $this->campInstance->camp();
    }

    /**
     * Get the fields for this form template.
     */
    public function fields(): HasMany
    {
        return $this->hasMany(FormField::class)->orderBy('sort');
    }

    /**
     * Get the form responses for this template.
     */
    public function formResponses(): HasMany
    {
        return $this->hasMany(FormResponse::class);
    }

    /**
     * Check if this is a global form template.
     */
    public function isGlobal(): bool
    {
        return $this->scope === 'global';
    }

    /**
     * Check if this is a camp session specific form template.
     */
    public function isCampSession(): bool
    {
        return $this->scope === 'camp_session';
    }

    /**
     * Get the scope display name.
     */
    public function getScopeDisplayAttribute(): string
    {
        return match($this->scope) {
            'global' => 'Global',
            'camp_session' => 'Camp Session',
            default => ucfirst($this->scope),
        };
    }

    /**
     * Scope to filter active form templates.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter global form templates.
     */
    public function scopeGlobal($query)
    {
        return $query->where('scope', 'global');
    }

    /**
     * Scope to filter camp session form templates.
     */
    public function scopeCampSession($query)
    {
        return $query->where('scope', 'camp_session');
    }

    /**
     * Scope to filter form templates by camp instance.
     */
    public function scopeForCampInstance($query, $campInstanceId)
    {
        return $query->where(function ($q) use ($campInstanceId) {
            $q->where('scope', 'global')
              ->orWhere(function ($subQ) use ($campInstanceId) {
                  $subQ->where('scope', 'camp_session')
                       ->where('camp_instance_id', $campInstanceId);
              });
        });
    }

    /**
     * Get the required fields for this template.
     */
    public function requiredFields()
    {
        return $this->fields()->where('required', true);
    }

    /**
     * Check if all required fields are filled for a specific camper.
     */
    public function isCompleteForCamper(Camper $camper): bool
    {
        $response = $this->formResponses()
            ->where('camper_id', $camper->id)
            ->latest()
            ->first();

        if (!$response) {
            return false;
        }

        $requiredFieldIds = $this->requiredFields()->pluck('id');
        $answeredFieldIds = $response->answers()
            ->whereIn('form_field_id', $requiredFieldIds)
            ->whereNotNull('value_text')
            ->pluck('form_field_id');

        return $requiredFieldIds->diff($answeredFieldIds)->isEmpty();
    }
}
