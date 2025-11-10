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
        Schema::create('agreements', function (Blueprint $table) {
            $table->id();
            $table->string('type')->index();
            $table->string('title');
            $table->string('slug')->unique();
            $table->unsignedSmallInteger('year')->index();
            $table->string('version')->default('v1');
            $table->longText('content')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        Schema::create('parent_agreement_signatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('agreement_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('year')->index();
            $table->string('typed_name');
            $table->timestamp('signed_at')->index();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'agreement_id', 'year'], 'parent_agreement_unique');
        });

        Schema::create('camper_agreement_signatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('camper_id')->constrained()->cascadeOnDelete();
            $table->foreignId('agreement_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('year')->index();
            $table->string('typed_name');
            $table->timestamp('signed_at')->index();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->unique(['camper_id', 'agreement_id', 'year'], 'camper_agreement_unique');
        });

        Schema::create('camper_information_snapshots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('camper_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('year')->index();
            $table->foreignId('parent_agreement_signature_id')->nullable();
            $table->json('data');
            $table->string('form_version')->nullable();
            $table->timestamp('captured_at')->index();
            $table->foreignId('captured_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['camper_id', 'year'], 'camper_information_year_unique');
            $table->foreign('parent_agreement_signature_id', 'fk_info_parent_sig')
                ->references('id')
                ->on('parent_agreement_signatures')
                ->nullOnDelete();
        });

        Schema::create('camper_medical_snapshots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('camper_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('year')->index();
            $table->foreignId('camper_agreement_signature_id')->nullable();
            $table->json('data');
            $table->string('form_version')->nullable();
            $table->timestamp('captured_at')->index();
            $table->foreignId('captured_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['camper_id', 'year'], 'camper_medical_year_unique');
            $table->foreign('camper_agreement_signature_id', 'fk_med_camper_sig')
                ->references('id')
                ->on('camper_agreement_signatures')
                ->nullOnDelete();
        });

        Schema::table('enrollments', function (Blueprint $table) {
            $table->foreignId('information_snapshot_id')
                ->nullable()
                ->after('camper_id')
                ->constrained('camper_information_snapshots')
                ->nullOnDelete();
            $table->foreignId('medical_snapshot_id')
                ->nullable()
                ->after('information_snapshot_id')
                ->constrained('camper_medical_snapshots')
                ->nullOnDelete();
            $table->foreignId('parent_signature_id')
                ->nullable()
                ->after('medical_snapshot_id')
                ->constrained('parent_agreement_signatures')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('parent_signature_id');
            $table->dropConstrainedForeignId('medical_snapshot_id');
            $table->dropConstrainedForeignId('information_snapshot_id');
        });

        Schema::dropIfExists('camper_medical_snapshots');
        Schema::dropIfExists('camper_information_snapshots');
        Schema::dropIfExists('camper_agreement_signatures');
        Schema::dropIfExists('parent_agreement_signatures');
        Schema::dropIfExists('agreements');
    }
};

