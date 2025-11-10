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
        Schema::create('family_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('family_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('name');
            $table->string('relation')->nullable();
            $table->string('home_phone')->nullable();
            $table->string('cell_phone')->nullable();
            $table->string('work_phone')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state', 50)->nullable();
            $table->string('zip', 20)->nullable();
            $table->boolean('authorized_pickup')->default(false);
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_contacts');
    }
};

