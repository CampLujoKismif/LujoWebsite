<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CamperAgreementSignature extends Model
{
    use HasFactory;

    protected $fillable = [
        'camper_id',
        'agreement_id',
        'year',
        'typed_name',
        'signed_at',
        'ip_address',
        'user_agent',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'signed_at' => 'datetime',
    ];

    public function agreement(): BelongsTo
    {
        return $this->belongsTo(Agreement::class);
    }

    public function camper(): BelongsTo
    {
        return $this->belongsTo(Camper::class);
    }
}
