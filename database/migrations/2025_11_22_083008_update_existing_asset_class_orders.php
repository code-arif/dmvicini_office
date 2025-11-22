<?php

use App\Models\AssetClass;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Set order for existing records
        $classes = AssetClass::orderBy('id', 'ASC')->get();

        foreach ($classes as $index => $class) {
            $class->order = $index + 1;
            $class->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reset all orders to 0
        AssetClass::query()->update(['order' => 0]);
    }
};
