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
            // Add new fields
            $table->date('date_of_baptism')->nullable()->after('date_of_birth');
            $table->string('phone_number')->nullable()->after('date_of_baptism');
            $table->string('email')->nullable()->after('phone_number');
            
            // Drop the old gender column
            $table->dropColumn('gender');
        });

        Schema::table('campers', function (Blueprint $table) {
            // Add the new biological_gender column with restricted options
            $table->enum('biological_gender', ['Male', 'Female'])->nullable()->after('date_of_birth');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campers', function (Blueprint $table) {
            // Drop new fields
            $table->dropColumn(['date_of_baptism', 'phone_number', 'email', 'biological_gender']);
            
            // Restore the old gender column
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('date_of_birth');
        });
    }
};
