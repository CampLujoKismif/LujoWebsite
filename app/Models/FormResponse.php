<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormResponse extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'form_template_id',
        'camper_id',
        'enrollment_id',
        'submitted_by_user_id',
        'submitted_at',
        'is_complete',
        'notes',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'is_complete' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the form template for this response.
     */
    public function formTemplate(): BelongsTo
    {
        return $this->belongsTo(FormTemplate::class);
    }

    /**
     * Get the camper for this response.
     */
    public function camper(): BelongsTo
    {
        return $this->belongsTo(Camper::class);
    }

    /**
     * Get the enrollment for this response.
     */
    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    /**
     * Get the user who submitted this response.
     */
    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by_user_id');
    }

    /**
     * Get the answers for this response.
     */
    public function answers(): HasMany
    {
        return $this->hasMany(FormAnswer::class);
    }

    /**
     * Get the family for this response.
     */
    public function family(): BelongsTo
    {
        return $this->camper->family();
    }

    /**
     * Check if this response is submitted.
     */
    public function isSubmitted(): bool
    {
        return !is_null($this->submitted_at);
    }

    /**
     * Check if this response is complete.
     */
    public function isComplete(): bool
    {
        if ($this->is_complete) {
            return true;
        }

        // Check if all required fields are answered
        $requiredFieldIds = $this->formTemplate->requiredFields()->pluck('id');
        $answeredFieldIds = $this->answers()
            ->whereIn('form_field_id', $requiredFieldIds)
            ->whereNotNull('value_text')
            ->pluck('form_field_id');

        return $requiredFieldIds->diff($answeredFieldIds)->isEmpty();
    }

    /**
     * Get the answer for a specific field.
     */
    public function getAnswerForField(FormField $field): ?FormAnswer
    {
        return $this->answers()->where('form_field_id', $field->id)->first();
    }

    /**
     * Get the value for a specific field.
     */
    public function getValueForField(FormField $field): mixed
    {
        $answer = $this->getAnswerForField($field);
        
        if (!$answer) {
            return null;
        }

        return $answer->value_text ?? $answer->value_json;
    }

    /**
     * Mark this response as submitted.
     */
    public function markAsSubmitted(): void
    {
        $this->update([
            'submitted_at' => now(),
            'is_complete' => $this->isComplete(),
        ]);
    }

    /**
     * Scope to filter submitted responses.
     */
    public function scopeSubmitted($query)
    {
        return $query->whereNotNull('submitted_at');
    }

    /**
     * Scope to filter incomplete responses.
     */
    public function scopeIncomplete($query)
    {
        return $query->where('is_complete', false);
    }

    /**
     * Scope to filter complete responses.
     */
    public function scopeComplete($query)
    {
        return $query->where('is_complete', true);
    }

    /**
     * Scope to filter responses by camper.
     */
    public function scopeForCamper($query, $camperId)
    {
        return $query->where('camper_id', $camperId);
    }

    /**
     * Scope to filter responses by enrollment.
     */
    public function scopeForEnrollment($query, $enrollmentId)
    {
        return $query->where('enrollment_id', $enrollmentId);
    }
}
