<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'camper_id',
        'camp_instance_id',
        'path',
        'label',
        'description',
        'file_size',
        'mime_type',
        'uploaded_by_user_id',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the camper for this document.
     */
    public function camper(): BelongsTo
    {
        return $this->belongsTo(Camper::class);
    }

    /**
     * Get the camp instance for this document.
     */
    public function campInstance(): BelongsTo
    {
        return $this->belongsTo(CampInstance::class);
    }

    /**
     * Get the camp for this document.
     */
    public function camp(): BelongsTo
    {
        return $this->campInstance->camp();
    }

    /**
     * Get the family for this document.
     */
    public function family(): BelongsTo
    {
        return $this->camper->family();
    }

    /**
     * Get the user who uploaded this document.
     */
    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by_user_id');
    }

    /**
     * Get the file URL.
     */
    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->path);
    }

    /**
     * Get the file name.
     */
    public function getFileNameAttribute(): string
    {
        return basename($this->path);
    }

    /**
     * Get the file extension.
     */
    public function getExtensionAttribute(): string
    {
        return pathinfo($this->path, PATHINFO_EXTENSION);
    }

    /**
     * Get the formatted file size.
     */
    public function getFormattedFileSizeAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Check if this is an image file.
     */
    public function isImage(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
    }

    /**
     * Check if this is a PDF file.
     */
    public function isPdf(): bool
    {
        return $this->mime_type === 'application/pdf';
    }

    /**
     * Get the file type icon.
     */
    public function getFileTypeIconAttribute(): string
    {
        if ($this->isImage()) {
            return 'image';
        }

        if ($this->isPdf()) {
            return 'file-text';
        }

        return 'file';
    }

    /**
     * Scope to filter documents by camper.
     */
    public function scopeForCamper($query, $camperId)
    {
        return $query->where('camper_id', $camperId);
    }

    /**
     * Scope to filter documents by camp instance.
     */
    public function scopeForCampInstance($query, $campInstanceId)
    {
        return $query->where('camp_instance_id', $campInstanceId);
    }

    /**
     * Scope to filter documents by user.
     */
    public function scopeUploadedBy($query, $userId)
    {
        return $query->where('uploaded_by_user_id', $userId);
    }
}
