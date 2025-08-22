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
        Schema::create('form_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_template_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['text', 'textarea', 'date', 'select', 'checkbox', 'file', 'signature', 'email', 'phone', 'number', 'radio']);
            $table->string('label');
            $table->json('options_json')->nullable();
            $table->boolean('required')->default(false);
            $table->integer('sort')->default(0);
            $table->string('validation_rules')->nullable();
            $table->text('help_text')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_fields');
    }
};
