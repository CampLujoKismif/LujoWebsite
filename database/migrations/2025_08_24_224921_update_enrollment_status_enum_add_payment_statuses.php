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
        // First, we need to drop the existing enum constraint and recreate it
        DB::statement("ALTER TABLE enrollments MODIFY COLUMN status ENUM('pending', 'confirmed', 'waitlisted', 'cancelled', 'registered_awaiting_payment', 'registered_fully_paid') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to the original enum values
        DB::statement("ALTER TABLE enrollments MODIFY COLUMN status ENUM('pending', 'confirmed', 'waitlisted', 'cancelled') DEFAULT 'pending'");
    }
};
