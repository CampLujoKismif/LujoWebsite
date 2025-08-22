<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class FamilyUser extends Pivot
{
    use SoftDeletes;

    protected $table = 'family_users';

    protected $fillable = [
        'family_id',
        'user_id',
        'role_in_family',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the family for this relationship.
     */
    public function family()
    {
        return $this->belongsTo(Family::class);
    }

    /**
     * Get the user for this relationship.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
