<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('camper_id')->constrained()->cascadeOnDelete();
            $table->json('allergies')->nullable();
            $table->json('medications')->nullable();
            $table->string('physician_name')->nullable();
            $table->string('physician_phone')->nullable();
            $table->string('policy_number')->nullable();
            $table->string('insurance_provider')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->string('emergency_contact_relationship')->nullable();
            $table->json('medical_conditions')->nullable();
            $table->json('dietary_restrictions')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('last_updated_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_records');
    }
};
