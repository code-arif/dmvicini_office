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
        Schema::create('investments', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('asset_class_id')->constrained()->cascadeOnDelete();
            $table->foreignId('investment_type_id')->constrained()->cascadeOnDelete();
            $table->foreignId('investments_strategy_id')->nullable()->constrained('investment_strategies')->nullOnDelete();
            $table->foreignId('location_id')->nullable()->constrained()->nullOnDelete();
            $table->string('term')->nullable();
            $table->string('min_investment')->nullable();
            $table->string('targeted_irr')->nullable();
            $table->string('thumbnail')->nullable();
            $table->longText('summary')->nullable();
            $table->string('banker_phone')->nullable();
            $table->string('banker_email')->nullable();
            $table->enum('status', ['draft', 'active', 'closed'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investments');
    }
};
