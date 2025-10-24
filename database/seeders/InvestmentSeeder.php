<?php

namespace Database\Seeders;

use App\Models\Investment;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class InvestmentSeeder extends Seeder
{
    public function run(): void
    {
        $investments = [
            [
                'title' => 'Manhattan Commercial Tower Fund',
                'asset_class_id' => 5, // Real Estate
                'investment_type_id' => 2, // Long-Term Investment
                'investments_strategy_id' => 9, // Buy and Hold Strategy
                'term' => '10 Years',
                'min_investment' => '$250,000',
                'targeted_irr' => '12%',
                'targeted_eps' => '7.5%',
                'thumbnail' => 'https://cdn.pixabay.com/photo/2017/08/30/07/56/money-2696228_1280.jpg',
                'summary' => 'A premium commercial office redevelopment in downtown Manhattan targeting long-term capital appreciation.',
                'country' => 'USA',
                'city' => 'New York',
                'state' => 'New York',
                'address' => '350 5th Avenue, New York, NY',
                'latitude' => 40.748817,
                'longitude' => -73.985428,
                'banker_phone' => '+1 212 555 0199',
                'banker_email' => 'invest@nycapital.com',
                'status' => 'active',
                'sponsor' => 'NY Capital Partners',
                'fund_name' => 'Manhattan Office Equity Fund',
                'target_equity' => 15000000,
                'target_raise' => 25000000,
                'launch_date' => '2025-01-01',
                'close_date' => '2026-12-31',
                'property_type' => 'Commercial',
                'unit_count' => 12,
                'market_overview' => 'NYC commercial real estate remains a resilient asset class with high rental demand.'
            ],
            [
                'title' => 'Solar Energy Yield Fund II',
                'asset_class_id' => 31, // Renewable Energy Assets
                'investment_type_id' => 4, // Equity Investment
                'investments_strategy_id' => 1, // Growth Investing
                'term' => '8 Years',
                'min_investment' => '$100,000',
                'targeted_irr' => '15%',
                'targeted_eps' => '10%',
                'thumbnail' => 'https://cdn.pixabay.com/photo/2017/08/30/07/56/money-2696228_1280.jpg',
                'summary' => 'Portfolio of solar farms across California and Nevada generating stable renewable energy income.',
                'country' => 'USA',
                'city' => 'Los Angeles',
                'state' => 'California',
                'address' => '2200 Green Valley Blvd, Los Angeles, CA',
                'latitude' => 34.052235,
                'longitude' => -118.243683,
                'banker_phone' => '+1 310 555 0142',
                'banker_email' => 'solar@greenfunds.com',
                'status' => 'active',
                'sponsor' => 'GreenFunds Capital',
                'fund_name' => 'Solar Energy Yield Fund II',
                'target_equity' => 10000000,
                'target_raise' => 18000000,
                'launch_date' => '2025-02-01',
                'close_date' => '2026-02-01',
                'property_type' => 'Renewable Energy',
                'unit_count' => 8,
                'market_overview' => 'Solar demand is projected to grow 25% annually in western states.'
            ],
            [
                'title' => 'European Infrastructure Growth Fund',
                'asset_class_id' => 32, // Infrastructure Funds
                'investment_type_id' => 2,
                'investments_strategy_id' => 10, // Tactical Asset Allocation
                'term' => '12 Years',
                'min_investment' => '€500,000',
                'targeted_irr' => '13%',
                'targeted_eps' => '8%',
                'thumbnail' => 'https://cdn.pixabay.com/photo/2017/08/30/07/56/money-2696228_1280.jpg',
                'summary' => 'Investing in transportation, energy, and digital infrastructure across key EU markets.',
                'country' => 'Germany',
                'city' => 'Berlin',
                'state' => 'Berlin',
                'address' => 'Potsdamer Platz, Berlin, Germany',
                'latitude' => 52.509665,
                'longitude' => 13.375,
                'banker_phone' => '+49 30 555 8821',
                'banker_email' => 'infra@growthfund.eu',
                'status' => 'active',
                'sponsor' => 'EuroInfra Partners',
                'fund_name' => 'European Infrastructure Growth Fund',
                'target_equity' => 25000000,
                'target_raise' => 50000000,
                'launch_date' => '2024-11-15',
                'close_date' => '2026-03-30',
                'property_type' => 'Infrastructure',
                'unit_count' => 15,
                'market_overview' => 'Europe’s infrastructure market benefits from ESG-aligned projects and government support.'
            ],
            [
                'title' => 'Blockchain Innovation Fund',
                'asset_class_id' => 10, // Cryptocurrencies
                'investment_type_id' => 5, // Alternative Investment
                'investments_strategy_id' => 1, // Growth Investing
                'term' => '5 Years',
                'min_investment' => '$50,000',
                'targeted_irr' => '25%',
                'targeted_eps' => '15%',
                'thumbnail' => 'https://cdn.pixabay.com/photo/2017/08/30/07/56/money-2696228_1280.jpg',
                'summary' => 'Early-stage investment in blockchain startups focused on DeFi, Web3, and AI integrations.',
                'country' => 'Singapore',
                'city' => 'Singapore',
                'state' => null,
                'address' => '8 Marina View, Singapore',
                'latitude' => 1.286920,
                'longitude' => 103.854570,
                'banker_phone' => '+65 6222 4488',
                'banker_email' => 'crypto@innovatefund.sg',
                'status' => 'active',
                'sponsor' => 'Digital Future Ventures',
                'fund_name' => 'Blockchain Innovation Fund',
                'target_equity' => 2000000,
                'target_raise' => 8000000,
                'launch_date' => '2025-04-01',
                'close_date' => '2025-12-31',
                'property_type' => 'Tech Venture',
                'unit_count' => 20,
                'market_overview' => 'Global blockchain adoption continues to rise, especially in fintech and data security.'
            ],
            [
                'title' => 'Asia Pacific Healthcare REIT',
                'asset_class_id' => 11, // REITs
                'investment_type_id' => 4,
                'investments_strategy_id' => 3,
                'term' => '10 Years',
                'min_investment' => '$150,000',
                'targeted_irr' => '10%',
                'targeted_eps' => '6%',
                'thumbnail' => 'https://cdn.pixabay.com/photo/2017/08/30/07/56/money-2696228_1280.jpg',
                'summary' => 'A diversified portfolio of hospitals and senior living facilities across Asia-Pacific.',
                'country' => 'Australia',
                'city' => 'Sydney',
                'state' => 'New South Wales',
                'address' => '200 George Street, Sydney',
                'latitude' => -33.8688,
                'longitude' => 151.2093,
                'banker_phone' => '+61 2 5550 9012',
                'banker_email' => 'info@apacreit.com.au',
                'status' => 'active',
                'sponsor' => 'APAC REIT Group',
                'fund_name' => 'Asia Pacific Healthcare REIT',
                'target_equity' => 18000000,
                'target_raise' => 30000000,
                'launch_date' => '2024-12-01',
                'close_date' => '2026-01-15',
                'property_type' => 'Healthcare Real Estate',
                'unit_count' => 9,
                'market_overview' => 'Aging demographics in Asia drive high healthcare property demand.'
            ],
            // ... (You’ll continue similarly for 95 more entries)
        ];

        // Generate additional sample investments using pattern data
        for ($i = 6; $i <= 100; $i++) {
            $investments[] = [
                'title' => 'Global Investment Opportunity ' . $i,
                'asset_class_id' => rand(1, 100),
                'investment_type_id' => rand(1, 10),
                'investments_strategy_id' => rand(1, 10),
                'term' => rand(3, 15) . ' Years',
                'min_investment' => '$' . number_format(rand(10000, 250000), 0),
                'targeted_irr' => rand(8, 25) . '%',
                'targeted_eps' => rand(4, 15) . '%',
                'thumbnail' => 'https://cdn.pixabay.com/photo/2017/08/30/07/56/money-2696228_1280.jpg',
                'summary' => 'Diversified investment across multiple sectors offering stable returns and growth opportunities.',
                'country' => fake()->country(),
                'city' => fake()->city(),
                'state' => fake()->state(),
                'address' => fake()->address(),
                'latitude' => fake()->latitude(),
                'longitude' => fake()->longitude(),
                'banker_phone' => '+1 800 ' . rand(100, 999) . ' ' . rand(1000, 9999),
                'banker_email' => 'banker' . $i . '@example.com',
                'status' => fake()->randomElement(['draft', 'active', 'closed']),
                'sponsor' => fake()->company(),
                'fund_name' => 'Fund ' . Str::random(5),
                'target_equity' => rand(1000000, 20000000),
                'target_raise' => rand(2000000, 50000000),
                'launch_date' => fake()->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
                'close_date' => fake()->dateTimeBetween('now', '+2 years')->format('Y-m-d'),
                'property_type' => fake()->randomElement(['Residential', 'Commercial', 'Industrial', 'Technology', 'Energy']),
                'unit_count' => rand(5, 40),
                'market_overview' => fake()->sentence(12),
            ];
        }

        foreach ($investments as $investment) {
            Investment::create($investment);
        }
    }
}
