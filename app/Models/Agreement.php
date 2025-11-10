<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Agreement extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'title',
        'slug',
        'year',
        'version',
        'content',
        'is_active',
        'published_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function parentSignatures(): HasMany
    {
        return $this->hasMany(ParentAgreementSignature::class);
    }

    public function camperSignatures(): HasMany
    {
        return $this->hasMany(CamperAgreementSignature::class);
    }
}
