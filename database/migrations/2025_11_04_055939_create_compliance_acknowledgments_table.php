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
        // Migration: create_compliance_acknowledgments_table.php
        Schema::create('compliance_acknowledgments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Required Checkboxes
            $table->boolean('terms_agreed')->default(false);
            $table->timestamp('terms_agreed_at')->nullable();

            $table->boolean('privacy_agreed')->default(false);
            $table->timestamp('privacy_agreed_at')->nullable();

            $table->boolean('investor_acknowledgment')->default(false);
            $table->timestamp('investor_acknowledgment_at')->nullable();

            $table->boolean('confidentiality_agreed')->default(false);
            $table->timestamp('confidentiality_agreed_at')->nullable();

            // Optional Marketing
            $table->boolean('marketing_opt_in')->default(false);
            $table->timestamp('marketing_opt_in_at')->nullable();

            // Audit Trail
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compliance_acknowledgments');
    }
};
