<?php

namespace App\Http\Controllers\Web\Backend;

use App\Models\Post;
use App\Models\User;
use App\Models\Event;
use App\Models\Venue;
use App\Models\Investment;
use Illuminate\Http\Request;
use App\Models\InvestmentDocument;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Display the dashboard view.
     */
    public function index()
    {
        return view('backend.layouts.dashboard');
    }


    /**
     * Get dashboard statistics (API)
     */
    public function getStats(Request $request)
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
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get investment details for modal/popup
     */
    public function getInvestmentDetails($id)
    {
        try {
            $investment = Investment::with([
                'assetClass',
                'investmentType',
                'strategy',
                'highlight',
                'documents',
                'risks',
            ])->find($id);

            if (!$investment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Investment not found'
                ], 404);
            }

            $data = [
                'id' => $investment->id,
                'title' => $investment->title,
                'summary' => $investment->summary,
                'thumbnail' => $investment->thumbnail ? asset($investment->thumbnail) : null,

                // Financial
                'min_investment' => $investment->min_investment,
                'targeted_irr' => $investment->targeted_irr,
                'targeted_eps' => $investment->targeted_eps,
                'term' => $investment->term,
                'target_equity' => $investment->target_equity ? '$' . number_format($investment->target_equity, 0) : null,
                'target_raise' => $investment->target_raise ? '$' . number_format($investment->target_raise, 0) : null,

                // Categories
                'asset_class' => $investment->assetClass->name ?? null,
                'investment_type' => $investment->investmentType->name ?? null,
                'strategy' => $investment->strategy->name ?? null,

                // Additional Info
                'sponsor' => $investment->sponsor,
                'fund_name' => $investment->fund_name,
                'property_type' => $investment->property_type,
                'unit_count' => $investment->unit_count,
                'market_overview' => $investment->market_overview,

                // Dates
                'launch_date' => $investment->launch_date,
                'close_date' => $investment->close_date,

                // Location
                'country' => $investment->country,
                'state' => $investment->state,
                'city' => $investment->city,
                'address' => $investment->address,

                // Contact
                'banker_phone' => $investment->banker_phone,
                'banker_email' => $investment->banker_email,

                // Status
                'status' => ucfirst($investment->status),

                // Relations
                'highlight' => $investment->highlight,
                'documents' => $investment->documents->map(function ($doc) {
                    return [
                        'id' => $doc->id,
                        'name' => $doc->name,
                        'file_path' => $doc->file_path ? asset($doc->file_path) : null,
                    ];
                }),
                'risks' => $investment->risks,
            ];

            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Investment details retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load investment details',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get chart data for dashboard
     */
    public function getChartData(Request $request)
    {
        try {
            $period = $request->get('period', 'month'); // day, week, month, year

            // Investment trend over time
            $investmentTrend = Investment::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->where('status', '!=', 'draft')
                ->groupBy('date')
                ->orderBy('date', 'desc')
                ->limit(30)
                ->get();

            // Investment by asset class
            $byAssetClass = Investment::selectRaw('asset_class_id, COUNT(*) as count')
                ->with('assetClass')
                ->where('status', '!=', 'draft')
                ->whereNotNull('asset_class_id')
                ->groupBy('asset_class_id')
                ->get()
                ->map(function ($item) {
                    return [
                        'name' => $item->assetClass->name ?? 'Unknown',
                        'count' => $item->count,
                    ];
                });

            // Investment by status
            $byStatus = Investment::selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->get()
                ->map(function ($item) {
                    return [
                        'status' => ucfirst($item->status),
                        'count' => $item->count,
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => [
                    'investment_trend' => $investmentTrend,
                    'by_asset_class' => $byAssetClass,
                    'by_status' => $byStatus,
                ],
                'message' => 'Chart data retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load chart data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
