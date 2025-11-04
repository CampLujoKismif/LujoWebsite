<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampSessionDetailTemplate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'camp_id',
        'theme_description',
        'schedule_data',
        'additional_info',
        'theme_photos',
        'meta_description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'schedule_data' => 'array',
        'theme_photos' => 'array',
    ];

    /**
     * Get the camp for this template.
     */
    public function camp(): BelongsTo
    {
        return $this->belongsTo(Camp::class);
    }

    /**
     * Get the template for a specific camp.
     *
     * @param int $campId
     * @return self|null
     */
    public static function getForCamp(int $campId): ?self
    {
        return static::where('camp_id', $campId)->first();
    }

    /**
     * Get or create the template for a specific camp.
     *
     * @param int $campId
     * @return self
     */
    public static function getOrCreateForCamp(int $campId): self
    {
        return static::firstOrCreate(
            ['camp_id' => $campId],
            [
                'theme_description' => null,
                'schedule_data' => null,
                'additional_info' => null,
                'theme_photos' => null,
                'meta_description' => null,
            ]
        );
    }

    /**
     * Copy this template's data to a camp instance.
     *
     * @param CampInstance $campInstance
     * @return void
     */
    public function copyToSession(CampInstance $campInstance): void
    {
        $campInstance->update([
            'theme_description' => $this->theme_description,
            'schedule_data' => $this->schedule_data,
            'additional_info' => $this->additional_info,
        ]);

        // Note: theme_photos might be handled separately if they need different paths
        // For now, we'll copy them as-is
        if ($this->theme_photos) {
            $campInstance->update(['theme_photos' => $this->theme_photos]);
        }
    }
}
