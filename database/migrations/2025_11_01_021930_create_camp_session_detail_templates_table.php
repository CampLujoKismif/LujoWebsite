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
        Schema::create('camp_session_detail_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('camp_id')->constrained('camps')->onDelete('cascade');
            $table->text('theme_description')->nullable();
            $table->json('schedule_data')->nullable(); // Daily schedule with time/activity pairs
            $table->text('additional_info')->nullable(); // Additional information section
            $table->json('theme_photos')->nullable(); // Array of photo URLs/paths
            $table->string('meta_description')->nullable(); // SEO meta description
            $table->timestamps();

            // One template per camp
            $table->unique('camp_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('camp_session_detail_templates');
    }
};
