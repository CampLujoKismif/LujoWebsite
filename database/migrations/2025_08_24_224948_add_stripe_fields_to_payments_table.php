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
        Schema::table('payments', function (Blueprint $table) {
            $table->string('stripe_payment_intent_id')->nullable()->after('reference');
            $table->string('stripe_charge_id')->nullable()->after('stripe_payment_intent_id');
            $table->enum('status', ['pending', 'processing', 'succeeded', 'failed', 'cancelled'])->default('pending')->after('stripe_charge_id');
            $table->json('stripe_metadata')->nullable()->after('status');
            $table->timestamp('processed_at')->nullable()->after('stripe_metadata');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn([
                'stripe_payment_intent_id',
                'stripe_charge_id',
                'status',
                'stripe_metadata',
                'processed_at'
            ]);
        });
    }
};
