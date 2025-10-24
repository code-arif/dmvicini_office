<?php

namespace Database\Seeders;

use App\Models\Investment;
use Illuminate\Database\Seeder;
use App\Models\InvestmentHighlight;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class InvestmentHighlightSeeder extends Seeder
{
    public function run(): void
    {
        $investments = Investment::all();

        foreach ($investments as $investment) {
            InvestmentHighlight::create([
                'investment_id' => $investment->id,
                'overview' => fake()->paragraph(5, true) . ' This investment aims to provide stable income and capital appreciation through diversified asset allocation.',

                'targeted_returns' => json_encode([
                    'IRR' => fake()->numberBetween(8, 20) . '%',
                    'CashYield' => fake()->numberBetween(4, 12) . '%',
                    'EquityMultiple' => 'x' . fake()->randomFloat(2, 1.2, 3.0),
                ]),

                'fees' => json_encode([
                    'ManagementFee' => '$' . fake()->numberBetween(500, 5000),
                    'PerformanceFee' => fake()->numberBetween(10, 25) . '%',
                    'AcquisitionFee' => '$' . fake()->numberBetween(1000, 10000),
                ]),
            ]);
        }
    }
}
