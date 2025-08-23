<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ensure only one active session per camp
        $campsWithMultipleActiveSessions = DB::table('camp_instances')
            ->where('is_active', true)
            ->groupBy('camp_id')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('camp_id');

        foreach ($campsWithMultipleActiveSessions as $campId) {
            // Get all active sessions for this camp, keep the most recent one
            $sessionsToDeactivate = DB::table('camp_instances')
                ->where('camp_id', $campId)
                ->where('is_active', true)
                ->orderBy('created_at', 'desc')
                ->skip(1)
                ->pluck('id');

            if ($sessionsToDeactivate->isNotEmpty()) {
                DB::table('camp_instances')
                    ->whereIn('id', $sessionsToDeactivate)
                    ->update(['is_active' => false]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No index to drop since we're using application-level enforcement
    }
};
