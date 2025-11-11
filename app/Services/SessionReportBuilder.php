<?php

namespace App\Services;

use App\Models\CampInstance;
use App\Models\Enrollment;
use Illuminate\Support\Str;

class SessionReportBuilder
{
    /**
     * Build the data array required for the manager session report.
     *
     * @return array|null
     */
    public static function build(CampInstance $campInstance): ?array
    {
        $campInstance->loadMissing('camp');

        $enrollments = Enrollment::where('camp_instance_id', $campInstance->id)
            ->with([
                'camper.family.contacts',
                'camper.family.owner',
                'camper',
                'informationSnapshot',
                'medicalSnapshot',
                'discountCode',
            ])
            ->get()
            ->sortBy(function ($enrollment) {
                $camper = $enrollment->camper;

                return Str::lower(
                    trim(($camper->last_name ?? '') . ' ' . ($camper->first_name ?? ''))
                );
            })
            ->values();

        if ($enrollments->isEmpty()) {
            return null;
        }

        $summary = [
            'total' => $enrollments->count(),
            'confirmed' => $enrollments->where('status', 'confirmed')->count(),
            'pending' => $enrollments->where('status', 'pending')->count(),
            'waitlisted' => $enrollments->where('status', 'waitlisted')->count(),
            'cancelled' => $enrollments->where('status', 'cancelled')->count(),
        ];

        return [
            'campInstance' => $campInstance,
            'camp' => $campInstance->camp,
            'enrollments' => $enrollments,
            'summary' => $summary,
            'generatedAt' => now(),
        ];
    }
}


