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
        Schema::create('investment_highlights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('investment_id')->constrained()->cascadeOnDelete();
            $table->longText('overview')->nullable();
            $table->string('targeted_irr')->nullable();
            $table->string('tax_doc')->nullable();
            $table->text('investor_waterfall')->nullable();
            $table->text('promoted_interest')->nullable();
            $table->string('asset_management_fee')->nullable();
            $table->string('organizational_and_offering_fee')->nullable();
            $table->string('acquisition_fee')->nullable();
            $table->string('disposition_fee')->nullable();
            $table->string('fund_administration_fee')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investment_highlights');
    }
};
