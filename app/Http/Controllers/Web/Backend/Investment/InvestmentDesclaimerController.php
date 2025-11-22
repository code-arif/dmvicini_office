<?php

namespace App\Http\Controllers\Web\Backend\Investment;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\InvestmentDisclaimer;

class InvestmentDesclaimerController extends Controller
{
    /**
     * Store or Update disclaimer (single disclaimer per investment)
     */
    public function storeOrUpdateDisclaimer(Request $request, $investment_id)
    {
        $request->validate([
            'description' => 'required|string'
        ]);

        $disclaimer = InvestmentDisclaimer::updateOrCreate(
            ['investment_id' => $investment_id],
            ['description' => $request->description]
        );

        return response()->json([
            'success' => true,
            'disclaimer' => $disclaimer,
            'message' => 'Disclaimer saved successfully!'
        ]);
    }
}
