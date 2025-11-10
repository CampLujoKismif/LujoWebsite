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
        Schema::table('camper_information_snapshots', function (Blueprint $table) {
            if (!Schema::hasColumn('camper_information_snapshots', 'data_hash')) {
                $table->char('data_hash', 64)->nullable()->after('user_agent');
            }
        });

        Schema::table('camper_medical_snapshots', function (Blueprint $table) {
            if (!Schema::hasColumn('camper_medical_snapshots', 'data_hash')) {
                $table->char('data_hash', 64)->nullable()->after('user_agent');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('camper_information_snapshots', function (Blueprint $table) {
            if (Schema::hasColumn('camper_information_snapshots', 'data_hash')) {
                $table->dropColumn('data_hash');
            }
        });

        Schema::table('camper_medical_snapshots', function (Blueprint $table) {
            if (Schema::hasColumn('camper_medical_snapshots', 'data_hash')) {
                $table->dropColumn('data_hash');
            }
        });
    }
};


