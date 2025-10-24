<?php

namespace Database\Seeders;

use App\Models\Investment;
use App\Models\InvestmentRisk;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class InvestmentRiskSeeder extends Seeder
{
    public function run(): void
    {
        $risks = [
            [
                'title' => 'Market Volatility',
                'description' => 'The investment’s performance may fluctuate due to market conditions, economic changes, or geopolitical factors.',
                'risk_level' => 'high',
            ],
            [
                'title' => 'Liquidity Risk',
                'description' => 'Limited ability to quickly sell or exit the investment without a significant loss in value.',
                'risk_level' => 'medium',
            ],
            [
                'title' => 'Interest Rate Risk',
                'description' => 'Rising interest rates could impact borrowing costs or reduce demand for the underlying asset.',
                'risk_level' => 'medium',
            ],
            [
                'title' => 'Operational Risk',
                'description' => 'Potential losses due to poor management decisions, system failures, or human errors.',
                'risk_level' => 'low',
            ],
            [
                'title' => 'Regulatory Risk',
                'description' => 'Changes in laws, tax policies, or government regulations could negatively affect returns.',
                'risk_level' => 'medium',
            ],
            [
                'title' => 'Currency Risk',
                'description' => 'Exposure to foreign exchange rate fluctuations for international investments.',
                'risk_level' => 'high',
            ],
            [
                'title' => 'Environmental Risk',
                'description' => 'Potential impact from environmental changes or non-compliance with sustainability regulations.',
                'risk_level' => 'low',
            ],
            [
                'title' => 'Credit Risk',
                'description' => 'Risk that borrowers or counterparties may default on their financial obligations.',
                'risk_level' => 'high',
            ],
        ];

        $investments = Investment::all();

        foreach ($investments as $investment) {
            // Assign 2–4 random risk factors to each investment
            $randomRisks = collect($risks)->random(rand(2, 4));

            foreach ($randomRisks as $risk) {
                InvestmentRisk::create([
                    'investment_id' => $investment->id,
                    'title' => $risk['title'],
                    'description' => $risk['description'],
                    'risk_level' => $risk['risk_level'],
                ]);
            }
        }
    }
}
