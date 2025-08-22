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
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('camp_instance_id')->constrained()->cascadeOnDelete();
            $table->foreignId('camper_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['pending', 'confirmed', 'waitlisted', 'cancelled'])->default('pending');
            $table->integer('balance_cents')->default(0);
            $table->integer('amount_paid_cents')->default(0);
            $table->boolean('forms_complete')->default(false);
            $table->timestamp('enrolled_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['camp_instance_id', 'camper_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
