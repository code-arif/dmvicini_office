<?php

namespace Database\Seeders;

use App\Models\InvestmentStrategy;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class InvestmentStrategySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $strategies = [
            [
                'name' => 'Growth Investing',
                'description' => 'Focuses on companies with strong potential for future earnings and revenue growth, even if their current valuations are high.'
            ],
            [
                'name' => 'Value Investing',
                'description' => 'Targets undervalued companies that are trading below their intrinsic value, expecting their price to rise over time.'
            ],
            [
                'name' => 'Income Investing',
                'description' => 'Aims to generate regular income through dividends, interest payments, or rental income rather than capital appreciation.'
            ],
            [
                'name' => 'Index Investing',
                'description' => 'Invests in index funds or ETFs that mirror major market indices such as the S&P 500 for broad market exposure.'
            ],
            [
                'name' => 'Dividend Investing',
                'description' => 'Focuses on companies with a consistent history of paying and increasing dividends over time.'
            ],
            [
                'name' => 'Momentum Investing',
                'description' => 'Buys assets that have shown strong recent performance, with the expectation that the trend will continue.'
            ],
            [
                'name' => 'Contrarian Investing',
                'description' => 'Invests against prevailing market trends by buying undervalued assets that are currently out of favor.'
            ],
            [
                'name' => 'Asset Allocation Strategy',
                'description' => 'Balances investments across various asset classes such as stocks, bonds, and real estate to manage risk and reward.'
            ],
            [
                'name' => 'Buy and Hold Strategy',
                'description' => 'Involves long-term ownership of investments regardless of short-term market fluctuations.'
            ],
            [
                'name' => 'Tactical Asset Allocation',
                'description' => 'Actively adjusts portfolio weights to capitalize on short-term market opportunities while maintaining long-term goals.'
            ],
        ];


        foreach ($strategies as $strategie) {
            InvestmentStrategy::create($strategie);
        }
    }
}
