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
        Schema::table('camps', function (Blueprint $table) {
            $table->dropColumn(['age_from', 'age_to', 'grade_from', 'grade_to']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('camps', function (Blueprint $table) {
            $table->unsignedTinyInteger('age_from')->nullable()->after('price');
            $table->unsignedTinyInteger('age_to')->nullable()->after('age_from');
            $table->unsignedTinyInteger('grade_from')->nullable()->after('age_to');
            $table->unsignedTinyInteger('grade_to')->nullable()->after('grade_from');
        });
    }
};
