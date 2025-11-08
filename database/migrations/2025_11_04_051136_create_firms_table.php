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
        // Migration: create_firms_table.php
        Schema::create('firms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profile_id')->constrained()->onDelete('cascade');

            // Registration Info
            $table->boolean('is_registered')->default(false);
            $table->string('firm_crd')->nullable();
            $table->string('individual_crd')->nullable();

            // AUM (using bigInteger for larger amounts)
            $table->bigInteger('firm_aum')->nullable();

            // Address
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();

            // If not registered
            $table->text('explanation_if_not_registered')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('firms');
    }
};
