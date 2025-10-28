<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Investment;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Models\InvestmentDocument;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    use ApiResponse;

    /**
     * Get dashboard statistics (API)
     */
    public function index(Request $request)
    {
        try {
            // Total Invested Amount (sum of all min_investment or target_raise)
            $totalInvested = Investment::where('status', '!=', 'draft')
                ->sum(DB::raw('CAST(REPLACE(REPLACE(min_investment, ",", ""), "$", "") AS DECIMAL(20,2))'));

            // Total Returns (calculated from targeted_irr)
            $totalReturns = Investment::where('status', '!=', 'draft')
                ->get()
                ->sum(function ($investment) {
                    $minInvestment = (float) str_replace([',', '$'], '', $investment->min_investment ?? 0);
                    $irrPercent = (float) str_replace(['%', ' '], '', $investment->targeted_irr ?? 0);
                    return ($minInvestment * $irrPercent) / 100;
                });

            // Active Deals Count
            $activeDeals = Investment::where('status', 'active')->count();

            // Average ROI (average of all targeted_irr)
            $avgROI = Investment::where('status', '!=', 'draft')
                ->whereNotNull('targeted_irr')
                ->get()
                ->avg(function ($investment) {
                    return (float) str_replace(['%', ' '], '', $investment->targeted_irr ?? 0);
                });

            // Approved Deals (active and completed)
            $approvedDeals = Investment::with(['assetClass', 'investmentType', 'strategy'])
                ->whereIn('status', ['active', 'closed'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
                ->map(function ($investment) {
                    $minInvestment = (float) str_replace([',', '$'], '', $investment->min_investment ?? 0);
                    $irrPercent = (float) str_replace(['%', ' '], '', $investment->targeted_irr ?? 0);

                    return [
                        'id' => $investment->id,
                        'name' => $investment->title,
                        'type' => $investment->assetClass->name ?? 'N/A',
                        'investment' => '$' . number_format($minInvestment, 0),
                        'roi' => ($irrPercent >= 0 ? '+' : '') . number_format($irrPercent, 1) . '%',
                        'roi_raw' => $irrPercent,
                        'status' => ucfirst($investment->status),
                        'thumbnail' => $investment->thumbnail ? asset($investment->thumbnail) : null,
                        'asset_class' => $investment->assetClass->name ?? 'Real Estate',
                        'fund_name' => $investment->fund_name,
                        'sponsor' => $investment->sponsor,
                    ];
                });

            // KYC Documents
            $kycDocuments = InvestmentDocument::with('investment')
                ->latest()
                ->limit(4)
                ->get()
                ->map(function ($doc) {
                    return [
                        'id' => $doc->id,
                        'name' => $doc->name ?? 'Document ' . $doc->id,
                        'file_path' => $doc->file_path ? asset($doc->file_path) : null,
                        'investment_title' => $doc->investment->title ?? 'N/A',
                        'created_at' => $doc->created_at->format('M d, Y'),
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => [
                    'total_invested' => [
                        'value' => '$' . number_format($totalInvested, 0),
                        'raw' => $totalInvested,
                    ],
                    'total_returns' => [
                        'value' => '$' . number_format($totalReturns, 0),
                        'raw' => $totalReturns,
                    ],
                    'active_deals' => [
                        'value' => $activeDeals,
                        'raw' => $activeDeals,
                    ],
                    'average_roi' => [
                        'value' => number_format($avgROI, 1) . '%',
                        'raw' => $avgROI,
                    ],
                    'approved_deals' => $approvedDeals,
                    'kyc_documents' => $kycDocuments,
                ],
                'message' => 'Dashboard statistics retrieved successfully'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * apprived deals
     */
    public function approvedDeals(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        try {
            // Approved Deals (active and completed)
            $investments = Investment::with(['assetClass', 'investmentType', 'strategy'])
                ->whereIn('status', ['active', 'closed'])
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);

            // Transform each investment
            $approvedDeals = $investments->getCollection()->map(function ($investment) {
                $minInvestment = (float) str_replace([',', '$'], '', $investment->min_investment ?? 0);
                $irrPercent = (float) str_replace(['%', ' '], '', $investment->targeted_irr ?? 0);

                return [
                    'id'            => $investment->id,
                    'name'          => $investment->title,
                    'type'          => $investment->assetClass->name ?? 'N/A',
                    'investment'    => '$' . number_format($minInvestment, 0),
                    'roi'           => ($irrPercent >= 0 ? '+' : '') . number_format($irrPercent, 1) . '%',
                    'roi_raw'       => $irrPercent,
                    'status'        => ucfirst($investment->status),
                    'thumbnail'     => $investment->thumbnail ? asset($investment->thumbnail) : null,
                    'asset_class'   => $investment->assetClass->name ?? 'Real Estate',
                    'fund_name'     => $investment->fund_name,
                    'sponsor'       => $investment->sponsor,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'approved_deals' => $approvedDeals,
                    'pagination' => [
                        'total'         => $investments->total(),
                        'current_page'  => $investments->currentPage(),
                        'last_page'     => $investments->lastPage(),
                        'per_page'      => $investments->perPage(),
                    ],
                ],
                'message' => 'Approved deals retrieved successfully'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load deals',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
