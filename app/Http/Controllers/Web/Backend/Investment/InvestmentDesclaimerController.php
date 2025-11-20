<?php

namespace App\Http\Controllers\Web\Backend\Investment;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\InvestmentDisclaimer;

class InvestmentDesclaimerController extends Controller
{
    // store investment desclaimer
    public function storeDisclaimer(Request $request, $investment_id)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $disclaimer = InvestmentDisclaimer::create([
            'investment_id' => $investment_id,
            'title'         => $validated['title'],
            'description'   => $validated['description'],
        ]);

        return response()->json(['success' => true, 'disclaimer' => $disclaimer]);
    }

}
