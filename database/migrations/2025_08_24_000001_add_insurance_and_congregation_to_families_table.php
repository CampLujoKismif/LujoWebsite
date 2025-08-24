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
        Schema::table('families', function (Blueprint $table) {
            // Insurance information
            $table->string('insurance_provider')->nullable()->after('emergency_contact_relationship');
            $table->string('insurance_policy_number')->nullable()->after('insurance_provider');
            $table->string('insurance_group_number')->nullable()->after('insurance_policy_number');
            $table->string('insurance_phone')->nullable()->after('insurance_group_number');
            
            // Home congregation
            $table->foreignId('home_congregation_id')->nullable()->after('insurance_phone')->constrained('church_congregations')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('families', function (Blueprint $table) {
            $table->dropForeign(['home_congregation_id']);
            $table->dropColumn([
                'insurance_provider',
                'insurance_policy_number',
                'insurance_group_number',
                'insurance_phone',
                'home_congregation_id'
            ]);
        });
    }
};
