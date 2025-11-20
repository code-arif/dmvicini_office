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

            // Pending Approval Users (email verified but not approved by admin)
            $pendingUsers = User::with(['profile', 'accessRequests'])
                ->whereNotNull('email_verified_at')
                ->where('access_level', 'provisional')
                ->where('is_active', false)
                ->where('role', 'user')
                ->latest('created_at')
                ->limit(10)
                ->get()
                ->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'full_name' => ($user->profile->first_name ?? '') . ' ' . ($user->profile->last_name ?? ''),
                        'email' => $user->email,
                        'registered_date' => $user->created_at->format('M d, Y'),
                        'registered_date_full' => $user->created_at->format('F d, Y h:i A'),
                        'avatar' => $user->avatar ? asset($user->avatar) : null,
                        'firm_name' => $user->profile->firm_name ?? 'N/A',
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
                    'pending_users' => $pendingUsers,
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
     * Get user details for approval modal
     */
    public function getUserDetails($id)
    {
        try {
            $user = User::with([
                'profile',
                'profile.firm',
                'accessRequest',
                'complianceAcknowledgment'
            ])->find($id);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            $profile = $user->profile;
            $firm = $profile->firm ?? null;
            $compliance = $user->complianceAcknowledgment;

            $data = [
                'id' => $user->id,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at ? $user->email_verified_at->format('M d, Y h:i A') : null,
                'registered_at' => $user->created_at->format('M d, Y h:i A'),
                'access_level' => $user->access_level,
                'is_active' => $user->is_active,
                'avatar' => $user->avatar ? asset($user->avatar) : null,

                // Profile Info
                'first_name' => $profile->first_name ?? null,
                'last_name' => $profile->last_name ?? null,
                'full_name' => ($profile->first_name ?? '') . ' ' . ($profile->last_name ?? ''),
                'title' => $profile->title ?? null,
                'firm_name' => $profile->firm_name ?? null,
                'phone' => $profile->phone ?? null,
                'country' => $profile->country ?? null,
                'investor_type' => $profile->investor_type ?? null,
                'investor_type_other' => $profile->investor_type_other ?? null,

                // Firm Info
                'firm_info' => $firm ? [
                    'is_registered' => $firm->is_registered,
                    'firm_crd' => $firm->firm_crd,
                    'individual_crd' => $firm->individual_crd,
                    'firm_aum' => $firm->firm_aum ? '$' . number_format($firm->firm_aum, 0) : null,
                    'address' => $firm->address,
                    'city' => $firm->city,
                    'state' => $firm->state,
                    'zip' => $firm->zip,
                    'explanation_if_not_registered' => $firm->explanation_if_not_registered,
                ] : null,

                // Compliance Info
                'compliance' => $compliance ? [
                    'terms_agreed' => $compliance->terms_agreed,
                    'terms_agreed_at' => $compliance->terms_agreed_at ? $compliance->terms_agreed_at->format('M d, Y h:i A') : null,
                    'privacy_agreed' => $compliance->privacy_agreed,
                    'privacy_agreed_at' => $compliance->privacy_agreed_at ? $compliance->privacy_agreed_at->format('M d, Y h:i A') : null,
                    'investor_acknowledgment' => $compliance->investor_acknowledgment,
                    'investor_acknowledgment_at' => $compliance->investor_acknowledgment_at ? $compliance->investor_acknowledgment_at->format('M d, Y h:i A') : null,
                    'confidentiality_agreed' => $compliance->confidentiality_agreed,
                    'confidentiality_agreed_at' => $compliance->confidentiality_agreed_at ? $compliance->confidentiality_agreed_at->format('M d, Y h:i A') : null,
                    'marketing_opt_in' => $compliance->marketing_opt_in,
                    'ip_address' => $compliance->ip_address,
                ] : null,

                // Access Request Info
                'access_request' => $user->accessRequest ? [
                    'status' => $user->accessRequest->status,
                    'verification_type' => $user->accessRequest->verification_type,
                    'verifier_document' => $user->accessRequest->verifier_document ? asset($user->accessRequest->verifier_document) : null,
                    'admin_notes' => $user->accessRequest->admin_notes,
                ] : null,
            ];

            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'User details retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load user details',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Approve user access
     */
    public function approveUser(Request $request, $id)
    {
        try {
            $user = User::with('accessRequest')->find($id);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            // Update user status
            $user->update([
                'access_level' => 'full',
                'is_active' => true,
            ]);

            // Update access request if exists
            if ($user->accessRequest) {
                $user->accessRequest->update([
                    'status' => 'approved',
                    'verified_at' => now(),
                    'verified_by' => auth()->id(),
                    'admin_notes' => $request->input('notes', null),
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'User approved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve user',
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
}
