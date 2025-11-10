<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CamperInformationSnapshot extends Model
{
    use HasFactory;

    protected $fillable = [
        'camper_id',
        'year',
        'data',
        'form_version',
        'captured_at',
        'captured_by_user_id',
        'ip_address',
        'user_agent',
        'data_hash',
    ];

    protected $casts = [
        'data' => 'array',
        'captured_at' => 'datetime',
    ];

    public function camper(): BelongsTo
    {
        return $this->belongsTo(Camper::class);
    }

    public function capturedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'captured_by_user_id');
    }
}
