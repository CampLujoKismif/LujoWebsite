<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class FamilyContact extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'family_id',
        'name',
        'relation',
        'home_phone',
        'cell_phone',
        'work_phone',
        'email',
        'address',
        'city',
        'state',
        'zip',
        'authorized_pickup',
        'is_primary',
    ];

    protected $casts = [
        'authorized_pickup' => 'boolean',
        'is_primary' => 'boolean',
    ];

    public function family(): BelongsTo
    {
        return $this->belongsTo(Family::class);
    }
}

