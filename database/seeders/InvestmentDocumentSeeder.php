<?php

namespace Database\Seeders;

use App\Models\Investment;
use Illuminate\Database\Seeder;
use App\Models\InvestmentDocument;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class InvestmentDocumentSeeder extends Seeder
{
    public function run(): void
    {
        $documentTypes = [
            'Investment Brochure',
            'Offering Memorandum (PPM)',
            'Financial Summary',
            'Market Analysis Report',
            'Legal Agreement',
            'Due Diligence Report',
            'Investor Presentation',
            'Subscription Document',
            'Annual Performance Report',
            'Environmental Impact Study',
        ];

        $investments = Investment::all();

        foreach ($investments as $investment) {
            // Each investment gets 2–4 random documents
            $count = rand(2, 4);
            $selectedDocs = collect($documentTypes)->random($count);

            foreach ($selectedDocs as $docName) {
                InvestmentDocument::create([
                    'investment_id' => $investment->id,
                    'name' => $docName,
                    'file_path' => 'uploads/investments/' . $investment->id . '/' . str_replace(' ', '_', strtolower($docName)) . '.pdf',
                ]);
            }
        }
    }
}
