<?php

use App\Models\InvestmentTypes;
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
        // Set order for existing records
        $types = InvestmentTypes::orderBy('id', 'ASC')->get();

        foreach ($types as $index => $type) {
            $types->order = $index + 1;
            $types->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reset all orders to 0
        InvestmentTypes::query()->update(['order' => 0]);
    }
};
