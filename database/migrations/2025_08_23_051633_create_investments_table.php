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
            $table->foreignId('asset_class_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('investment_type_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('investments_strategy_id')->nullable()->constrained('investment_strategies')->nullOnDelete();

            $table->string('term')->nullable();
            $table->string('min_investment')->nullable();
            $table->string('targeted_irr')->nullable();
            $table->string('targeted_eps')->nullable();
            $table->string('thumbnail')->nullable();
            $table->longText('summary')->nullable();

            //location
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('address')->nullable();
            $table->text('map_url')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->string('banker_phone')->nullable();
            $table->string('banker_email')->nullable();
            $table->enum('status', ['draft', 'active', 'closed'])->default('draft');

            // spreadsheet file upload
            $table->string('sponsor')->nullable();
            $table->string('fund_name')->nullable();
            $table->decimal('target_equity', 20, 2)->nullable();
            $table->decimal('target_raise', 20, 2)->nullable();
            $table->date('launch_date')->nullable();
            $table->date('close_date')->nullable();
            $table->string('property_type')->nullable();
            $table->integer('unit_count')->nullable();
            $table->text('market_overview')->nullable();

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
