<?php

use App\Models\TaxStrategy;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // Set order for existing records
        $strategies = TaxStrategy::orderBy('id', 'ASC')->get();

        foreach ($strategies as $index => $strategy) {
            $strategy->order = $index + 1;
            $strategy->save();
        }
    }

    public function down(): void
    {
        // Reset all orders to 0
        TaxStrategy::query()->update(['order' => 0]);
    }
};
