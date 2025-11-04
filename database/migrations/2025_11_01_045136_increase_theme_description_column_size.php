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
        // Increase theme_description size in camp_instances table
        Schema::table('camp_instances', function (Blueprint $table) {
            $table->longText('theme_description')->nullable()->change();
        });

        // Increase theme_description and additional_info size in camp_session_detail_templates table
        Schema::table('camp_session_detail_templates', function (Blueprint $table) {
            $table->longText('theme_description')->nullable()->change();
            $table->longText('additional_info')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to text() (64KB limit)
        Schema::table('camp_instances', function (Blueprint $table) {
            $table->text('theme_description')->nullable()->change();
        });

        Schema::table('camp_session_detail_templates', function (Blueprint $table) {
            $table->text('theme_description')->nullable()->change();
            $table->text('additional_info')->nullable()->change();
        });
    }
};
