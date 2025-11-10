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
            if (!Schema::hasColumn('camper_information_snapshots', 'ip_address')) {
                $table->string('ip_address')->nullable()->after('captured_by_user_id');
            }

            if (!Schema::hasColumn('camper_information_snapshots', 'user_agent')) {
                $table->string('user_agent')->nullable()->after('ip_address');
            }

            if (!Schema::hasColumn('camper_information_snapshots', 'data_hash')) {
                $table->char('data_hash', 64)->nullable()->after('user_agent');
            }
        });

        Schema::table('camper_medical_snapshots', function (Blueprint $table) {
            if (!Schema::hasColumn('camper_medical_snapshots', 'ip_address')) {
                $table->string('ip_address')->nullable()->after('captured_by_user_id');
            }

            if (!Schema::hasColumn('camper_medical_snapshots', 'user_agent')) {
                $table->string('user_agent')->nullable()->after('ip_address');
            }

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
            if (Schema::hasColumn('camper_information_snapshots', 'user_agent')) {
                $table->dropColumn('user_agent');
            }
            if (Schema::hasColumn('camper_information_snapshots', 'ip_address')) {
                $table->dropColumn('ip_address');
            }
        });

        Schema::table('camper_medical_snapshots', function (Blueprint $table) {
            if (Schema::hasColumn('camper_medical_snapshots', 'data_hash')) {
                $table->dropColumn('data_hash');
            }
            if (Schema::hasColumn('camper_medical_snapshots', 'user_agent')) {
                $table->dropColumn('user_agent');
            }
            if (Schema::hasColumn('camper_medical_snapshots', 'ip_address')) {
                $table->dropColumn('ip_address');
            }
        });
    }
};

