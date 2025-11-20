<?php

namespace App\Http\Controllers\Web\Backend\Investment;

use App\Models\Investment;
use Illuminate\Http\Request;
use App\Models\InvestmentHighlight;
use App\Http\Controllers\Controller;

class InvestmentHightlightController extends Controller
{
    /**
     * Update investment higlight
     */
    public function storeOrUpdateHighlight(Request $request, $investment_id)
    {
        $investment = Investment::findOrFail($investment_id);

        $validated = $request->validate([
            'overview'                    => 'nullable|string',
            'targeted_irr'                => 'nullable|string',
            'tax_doc'                     => 'nullable|string',
            'investor_waterfall'          => 'nullable|string',
            'promoted_interest'           => 'nullable|string',
            'asset_management_fee'        => 'nullable|string',
            'organizational_and_offering_fee' => 'nullable|string',
            'acquisition_fee'             => 'nullable|string',
            'disposition_fee'             => 'nullable|string',
            'fund_administration_fee'     => 'nullable|string',
        ]);

        $highlight = InvestmentHighlight::updateOrCreate(
            ['investment_id' => $investment->id],
            $validated
        );

        return response()->json([
            'success' => true,
            'message' => 'Highlights saved!',
            'highlight' => $highlight
        ]);
    }
}
