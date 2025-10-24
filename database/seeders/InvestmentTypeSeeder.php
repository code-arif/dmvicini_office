<?php

namespace Database\Seeders;

use App\Models\InvestmentTypes;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class InvestmentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $investmentTypes = [
            [
                'name' => 'Short-Term Investment',
                'description' => 'Investments held for less than one year, focused on liquidity and capital preservation (e.g., treasury bills, money market funds).'
            ],
            [
                'name' => 'Long-Term Investment',
                'description' => 'Assets held for several years to generate higher returns through compounding or growth (e.g., stocks, real estate).'
            ],
            [
                'name' => 'Fixed Income Investment',
                'description' => 'Investments offering regular interest payments and fixed maturity (e.g., government or corporate bonds).'
            ],
            [
                'name' => 'Equity Investment',
                'description' => 'Ownership in a company through the purchase of shares, providing potential capital appreciation and dividends.'
            ],
            [
                'name' => 'Alternative Investment',
                'description' => 'Non-traditional assets such as hedge funds, private equity, venture capital, and commodities.'
            ],
            [
                'name' => 'Real Estate Investment',
                'description' => 'Investing in residential, commercial, or industrial properties for rental income or long-term appreciation.'
            ],
            [
                'name' => 'Retirement Investment',
                'description' => 'Long-term savings and portfolio plans designed to provide income post-retirement (e.g., pension funds, annuities).'
            ],
            [
                'name' => 'Sustainable Investment',
                'description' => 'Investments focused on environmental, social, and governance (ESG) principles for responsible growth.'
            ],
            [
                'name' => 'Speculative Investment',
                'description' => 'High-risk investments aiming for significant returns (e.g., crypto, penny stocks, or startup equity).'
            ],
            [
                'name' => 'Income Investment',
                'description' => 'Assets generating steady cash flow, such as dividend-paying stocks or rental properties.'
            ],
        ];

        foreach ($investmentTypes as $investmentType) {
            InvestmentTypes::create($investmentType);
        }
    }
}
