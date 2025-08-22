<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormAnswer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'form_response_id',
        'form_field_id',
        'value_text',
        'value_json',
        'file_path',
    ];

    protected $casts = [
        'value_json' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the form response for this answer.
     */
    public function formResponse(): BelongsTo
    {
        return $this->belongsTo(FormResponse::class);
    }

    /**
     * Get the form field for this answer.
     */
    public function formField(): BelongsTo
    {
        return $this->belongsTo(FormField::class);
    }

    /**
     * Get the camper for this answer.
     */
    public function camper(): BelongsTo
    {
        return $this->formResponse->camper();
    }

    /**
     * Get the enrollment for this answer.
     */
    public function enrollment(): BelongsTo
    {
        return $this->formResponse->enrollment();
    }

    /**
     * Get the display value for this answer.
     */
    public function getDisplayValueAttribute(): string
    {
        if ($this->formField->isFileUpload() && $this->file_path) {
            return 'File uploaded: ' . basename($this->file_path);
        }

        if ($this->formField->isSelect() && $this->value_json) {
            return is_array($this->value_json) ? implode(', ', $this->value_json) : $this->value_json;
        }

        return $this->value_text ?? '';
    }

    /**
     * Check if this answer has a value.
     */
    public function hasValue(): bool
    {
        return !empty($this->value_text) || 
               !empty($this->value_json) || 
               !empty($this->file_path);
    }

    /**
     * Get the file URL if this is a file upload.
     */
    public function getFileUrlAttribute(): ?string
    {
        if (!$this->file_path) {
            return null;
        }

        return asset('storage/' . $this->file_path);
    }

    /**
     * Check if this answer is for a file upload field.
     */
    public function isFileUpload(): bool
    {
        return $this->formField->isFileUpload();
    }

    /**
     * Check if this answer is for a select field.
     */
    public function isSelect(): bool
    {
        return $this->formField->isSelect();
    }

    /**
     * Get the value as an array (for select fields).
     */
    public function getValueArrayAttribute(): array
    {
        if (is_array($this->value_json)) {
            return $this->value_json;
        }

        if (is_string($this->value_json)) {
            return [$this->value_json];
        }

        return [];
    }
}
