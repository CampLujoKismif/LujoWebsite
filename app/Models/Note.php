<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Note extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'notable_type',
        'notable_id',
        'user_id',
        'body',
        'is_private',
        'is_urgent',
    ];

    protected $casts = [
        'is_private' => 'boolean',
        'is_urgent' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the user who created this note.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent model that this note belongs to.
     */
    public function notable()
    {
        return $this->morphTo();
    }

    /**
     * Get the camper if this note is for a camper.
     */
    public function camper()
    {
        if ($this->notable_type === Camper::class) {
            return $this->notable;
        }
        return null;
    }

    /**
     * Get the enrollment if this note is for an enrollment.
     */
    public function enrollment()
    {
        if ($this->notable_type === Enrollment::class) {
            return $this->notable;
        }
        return null;
    }

    /**
     * Get the family if this note is for a family.
     */
    public function family()
    {
        if ($this->notable_type === Family::class) {
            return $this->notable;
        }
        return null;
    }

    /**
     * Get the camp instance if this note is for a camp instance.
     */
    public function campInstance()
    {
        if ($this->notable_type === CampInstance::class) {
            return $this->notable;
        }
        return null;
    }

    /**
     * Get the camp if this note is for a camp.
     */
    public function camp()
    {
        if ($this->notable_type === Camp::class) {
            return $this->notable;
        }
        return null;
    }

    /**
     * Get the notable type display name.
     */
    public function getNotableTypeDisplayAttribute(): string
    {
        return match($this->notable_type) {
            Camper::class => 'Camper',
            Enrollment::class => 'Enrollment',
            Family::class => 'Family',
            CampInstance::class => 'Camp Session',
            Camp::class => 'Camp',
            default => class_basename($this->notable_type),
        };
    }

    /**
     * Get the notable name.
     */
    public function getNotableNameAttribute(): string
    {
        if (!$this->notable) {
            return 'Unknown';
        }

        return match($this->notable_type) {
            Camper::class => $this->notable->full_name,
            Enrollment::class => $this->notable->camper->full_name . ' - ' . $this->notable->campInstance->display_name,
            Family::class => $this->notable->name,
            CampInstance::class => $this->notable->display_name,
            Camp::class => $this->notable->display_name,
            default => 'Unknown',
        };
    }

    /**
     * Get the truncated body for display.
     */
    public function getTruncatedBodyAttribute(): string
    {
        return \Str::limit($this->body, 100);
    }

    /**
     * Scope to filter private notes.
     */
    public function scopePrivate($query)
    {
        return $query->where('is_private', true);
    }

    /**
     * Scope to filter public notes.
     */
    public function scopePublic($query)
    {
        return $query->where('is_private', false);
    }

    /**
     * Scope to filter urgent notes.
     */
    public function scopeUrgent($query)
    {
        return $query->where('is_urgent', true);
    }

    /**
     * Scope to filter notes by user.
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to filter notes by notable type.
     */
    public function scopeByNotableType($query, $type)
    {
        return $query->where('notable_type', $type);
    }

    /**
     * Scope to filter notes by notable ID.
     */
    public function scopeByNotableId($query, $id)
    {
        return $query->where('notable_id', $id);
    }
}
