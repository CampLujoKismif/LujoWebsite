<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormField extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'form_template_id',
        'type',
        'label',
        'options_json',
        'required',
        'sort',
        'validation_rules',
        'help_text',
    ];

    protected $casts = [
        'options_json' => 'array',
        'required' => 'boolean',
        'sort' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the form template for this field.
     */
    public function formTemplate(): BelongsTo
    {
        return $this->belongsTo(FormTemplate::class);
    }

    /**
     * Get the form answers for this field.
     */
    public function answers(): HasMany
    {
        return $this->hasMany(FormAnswer::class);
    }

    /**
     * Get the field type display name.
     */
    public function getTypeDisplayAttribute(): string
    {
        return match($this->type) {
            'text' => 'Text Input',
            'textarea' => 'Text Area',
            'date' => 'Date',
            'select' => 'Dropdown',
            'checkbox' => 'Checkbox',
            'file' => 'File Upload',
            'signature' => 'Signature',
            'email' => 'Email',
            'phone' => 'Phone Number',
            'number' => 'Number',
            'radio' => 'Radio Buttons',
            default => ucfirst($this->type),
        };
    }

    /**
     * Check if this field is a file upload field.
     */
    public function isFileUpload(): bool
    {
        return in_array($this->type, ['file', 'signature']);
    }

    /**
     * Check if this field is a select field.
     */
    public function isSelect(): bool
    {
        return in_array($this->type, ['select', 'radio']);
    }

    /**
     * Check if this field is a text input.
     */
    public function isTextInput(): bool
    {
        return in_array($this->type, ['text', 'email', 'phone', 'number']);
    }

    /**
     * Get the options for select/radio fields.
     */
    public function getOptionsAttribute(): array
    {
        return $this->options_json ?? [];
    }

    /**
     * Get the validation rules as an array.
     */
    public function getValidationRulesArrayAttribute(): array
    {
        if (empty($this->validation_rules)) {
            return [];
        }

        return explode('|', $this->validation_rules);
    }

    /**
     * Get the HTML input type for this field.
     */
    public function getHtmlTypeAttribute(): string
    {
        return match($this->type) {
            'email' => 'email',
            'phone' => 'tel',
            'number' => 'number',
            'date' => 'date',
            'text' => 'text',
            'textarea' => 'textarea',
            'file' => 'file',
            'checkbox' => 'checkbox',
            'radio' => 'radio',
            'select' => 'select',
            'signature' => 'file',
            default => 'text',
        };
    }

    /**
     * Scope to filter required fields.
     */
    public function scopeRequired($query)
    {
        return $query->where('required', true);
    }

    /**
     * Scope to filter optional fields.
     */
    public function scopeOptional($query)
    {
        return $query->where('required', false);
    }

    /**
     * Scope to filter by field type.
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to order by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort');
    }
}
