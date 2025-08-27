<?php

namespace App\Http\Controllers\Web\Backend\Investment;

use App\Http\Controllers\Controller;
use App\Models\InvestmentHighlight;
use Illuminate\Http\Request;

class InvestmentHightlightController extends Controller
{
    // get investment highlight
    public function edit($investment_id)
    {
        $highlight = InvestmentHighlight::where('investment_id', $investment_id)->first();

        if (!$highlight) {
            return response()->json([
                'success' => false,
                'message' => 'Investment highlight not found.',
                'data'    => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Investment highlight fetched successfully.',
            'data'    => $highlight,
        ], 200);
    }

    //store highlight
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'investment_id' => 'required|exists:investments,id',
            'overview' => 'nullable|string',
            'targeted_returns_key.*' => 'nullable|string',
            'targeted_returns_value.*' => 'nullable|string',
            'fees_key.*' => 'nullable|string',
            'fees_value.*' => 'nullable|string',
        ]);

        // Prepare targeted_returns JSON
        $targeted_returns = [];
        if ($request->has('targeted_returns_key') && $request->has('targeted_returns_value')) {
            foreach ($request->targeted_returns_key as $i => $key) {
                if ($key && isset($request->targeted_returns_value[$i])) {
                    $targeted_returns[$key] = $request->targeted_returns_value[$i];
                }
            }
        }

        // Prepare fees JSON
        $fees = [];
        if ($request->has('fees_key') && $request->has('fees_value')) {
            foreach ($request->fees_key as $i => $key) {
                if ($key && isset($request->fees_value[$i])) {
                    $fees[$key] = $request->fees_value[$i];
                }
            }
        }

        // Check if highlight exists for this investment
        $highlight = InvestmentHighlight::updateOrCreate(
            ['investment_id' => $request->investment_id],
            [
                'overview' => $request->overview,
                'targeted_returns' => json_encode($request->targeted_returns),
                'fees' => json_encode($request->fees),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Investment highlight saved successfully.',
        ]);
    }
}
