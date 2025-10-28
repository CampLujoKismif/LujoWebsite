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
        Schema::table('rental_reservations', function (Blueprint $table) {
            $table->decimal('amount_paid', 10, 2)->default(0)->after('final_amount');
            $table->enum('payment_status', ['unpaid', 'partial', 'paid', 'refunded'])->default('unpaid')->after('amount_paid');
            $table->string('payment_method')->nullable()->after('payment_status');
            $table->timestamp('payment_date')->nullable()->after('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rental_reservations', function (Blueprint $table) {
            $table->dropColumn(['amount_paid', 'payment_status', 'payment_method', 'payment_date']);
        });
    }
};

