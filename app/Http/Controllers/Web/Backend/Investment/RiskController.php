<?php

namespace App\Http\Controllers\Web\Backend\Investment;

use App\Http\Controllers\Controller;
use App\Models\InvestmentRisk;
use Illuminate\Http\Request;

class RiskController extends Controller
{
    // Get investment risk
    public function edit($investment_id)
    {
        $risk = InvestmentRisk::where('investment_id', $investment_id)->first();

        if (!$risk) {
            return response()->json([
                'success' => false,
                'message' => 'Investment risk not found.',
                'data'    => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Investment risk fetched successfully.',
            'data'    => $risk,
        ], 200);
    }

    // Store or update risk
    public function store(Request $request)
    {
        $request->validate([
            'investment_id' => 'required|exists:investments,id',
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'risk_level'    => 'required|in:low,medium,high',
        ]);

        // Check if risk exists for this investment
        $risk = InvestmentRisk::updateOrCreate(
            ['investment_id' => $request->investment_id],
            [
                'title'       => $request->title,
                'description' => $request->description,
                'risk_level'  => $request->risk_level,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Investment risk saved successfully.',
            'data'    => $risk,
        ]);
    }
}
