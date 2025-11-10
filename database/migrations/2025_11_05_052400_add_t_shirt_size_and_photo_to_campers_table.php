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
        Schema::table('campers', function (Blueprint $table) {
            $table->string('t_shirt_size')->nullable()->after('school');
            $table->string('photo_path')->nullable()->after('t_shirt_size');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campers', function (Blueprint $table) {
            $table->dropColumn(['t_shirt_size', 'photo_path']);
        });
    }
};
