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
        // First, deactivate all but one active session if multiple exist
        $activeSessions = DB::table('camp_instances')
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();

        if ($activeSessions->count() > 1) {
            // Keep the most recently created active session, deactivate the rest
            $sessionsToDeactivate = $activeSessions->skip(1);
            foreach ($sessionsToDeactivate as $session) {
                DB::table('camp_instances')
                    ->where('id', $session->id)
                    ->update(['is_active' => false]);
            }
        }

        // For MySQL, we'll enforce the single active session constraint at the application level
        // since MySQL doesn't support partial unique indexes with WHERE clauses
        // The constraint is enforced in the CampInstance model's boot method
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No index to drop since we're using application-level enforcement
    }
};
