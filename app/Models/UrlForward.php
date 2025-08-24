<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UrlForward extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'internal_url',
        'external_url',
        'title',
        'description',
        'is_active',
        'click_count',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'click_count' => 'integer',
    ];

    /**
     * Get the user who created this URL forward.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Increment the click count for this URL forward.
     */
    public function incrementClickCount(): void
    {
        $this->increment('click_count');
    }

    /**
     * Scope to get only active URL forwards.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the full internal URL with domain.
     */
    public function getFullInternalUrlAttribute(): string
    {
        return url($this->internal_url);
    }

    /**
     * Validate that the internal URL is properly formatted.
     */
    public static function validateInternalUrl(string $url): bool
    {
        // Remove leading slash if present
        $url = ltrim($url, '/');
        
        // Check if URL is not empty and doesn't contain invalid characters
        return !empty($url) && preg_match('/^[a-zA-Z0-9\/\-_]+$/', $url);
    }

    /**
     * Validate that the external URL is properly formatted.
     */
    public static function validateExternalUrl(string $url): bool
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }
}
