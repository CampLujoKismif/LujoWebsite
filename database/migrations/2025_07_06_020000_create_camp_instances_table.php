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
        Schema::create('camp_instances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('camp_id')->constrained('camps')->onDelete('cascade');
            $table->year('year');
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->text('theme_description')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('max_capacity')->nullable();
            $table->decimal('price', 8, 2)->nullable();
            $table->unsignedTinyInteger('age_from')->nullable();
            $table->unsignedTinyInteger('age_to')->nullable();
            $table->unsignedTinyInteger('grade_from')->nullable();
            $table->unsignedTinyInteger('grade_to')->nullable();
            $table->date('registration_open_date')->nullable();
            $table->date('registration_close_date')->nullable();
            $table->json('theme_photos')->nullable();
            $table->json('schedule_data')->nullable();
            $table->json('additional_info')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->unique(['camp_id', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('camp_instances');
    }
}; 