<?php

namespace App\Http\Controllers\Api;

use App\Models\AssetClass;
use App\Models\Investment;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Models\InvestmentTypes;
use App\Models\InvestmentStrategy;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\InvestmentResource;

class InvestmentController extends Controller
{
    use ApiResponse;

    //investment list
    public function investmentList(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        $query = Investment::with(['assetClass', 'investmentType', 'strategy', 'highlight'])->where('status', 'active')->latest('id');

        // Search by title
        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        // Filter by asset class
        if ($request->filled('asset_class_id')) {
            $query->where('asset_class_id', $request->asset_class_id);
        }

        // Filter by investment type
        if ($request->filled('investment_type_id')) {
            $query->where('investment_type_id', $request->investment_type_id);
        }

        // Filter by strategy
        if ($request->filled('strategy_id')) {
            $query->where('investments_strategy_id', $request->strategy_id);
        }

        // Location-based filters
        if ($request->filled('country')) {
            $query->where('country', 'like', '%' . $request->country . '%');
        }

        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        // Date filters (existing)
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        // ============================================
        // NEW: Minimum Investment Range Filter
        // ============================================
        if ($request->filled('min_investment_from') || $request->filled('min_investment_to')) {
            $query->where(function ($q) use ($request) {
                $minFrom = $request->input('min_investment_from');
                $minTo = $request->input('min_investment_to');

                // Remove currency symbols and commas, then convert to number
                if ($minFrom !== null) {
                    $q->whereRaw("CAST(REPLACE(REPLACE(REPLACE(min_investment, '$', ''), ',', ''), ' ', '') AS DECIMAL(20,2)) >= ?", [$minFrom]);
                }

                if ($minTo !== null) {
                    $q->whereRaw("CAST(REPLACE(REPLACE(REPLACE(min_investment, '$', ''), ',', ''), ' ', '') AS DECIMAL(20,2)) <= ?", [$minTo]);
                }
            });
        }


        // ============================================
        // NEW: Year-based Filter (Dynamic)
        // ============================================
        if ($request->filled('years')) {
            $years = (int) $request->input('years');

            if ($years > 0) {
                $startDate = now()->subYears($years);
                $query->where('created_at', '>=', $startDate);
            }
        }

        // Alternative: Predefined year options (optional)
        if ($request->filled('year_range')) {
            $yearRanges = [
                'last_1_year' => 1,
                'last_2_years' => 2,
                'last_3_years' => 3,
                'last_5_years' => 5,
                'last_10_years' => 10,
                'all_time' => null, // No filter
            ];

            if (isset($yearRanges[$request->year_range]) && $yearRanges[$request->year_range] !== null) {
                $years = $yearRanges[$request->year_range];
                $startDate = now()->subYears($years);
                $query->where('created_at', '>=', $startDate);
            }
        }


        // ============================================
        // NEW: Sort options
        // ============================================
        $sortBy = $request->input('sort_by', 'created_at'); // created_at, min_investment, targeted_irr
        $sortOrder = $request->input('sort_order', 'desc'); // asc, desc

        // Validate sort_by to prevent SQL injection
        $allowedSortFields = ['created_at', 'min_investment', 'targeted_irr', 'title', 'launch_date'];
        if (!in_array($sortBy, $allowedSortFields)) {
            $sortBy = 'created_at';
        }

        // Special handling for min_investment sorting (since it's stored as string)
        if ($sortBy === 'min_investment') {
            $query->orderByRaw("CAST(REPLACE(REPLACE(REPLACE(min_investment, '$', ''), ',', ''), ' ', '') AS DECIMAL(20,2)) {$sortOrder}");
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        $investments = $query->paginate($perPage);

        $data = $investments->map(function ($item) {
            return [
                'id'              => $item->id,
                'title'           => $item->title,
                'term'            => $item->term,
                'min_investment'  => $item->min_investment,
                'targeted_irr'    => $item->highlight?->targeted_irr,
                'p_strategy'      => optional($item->strategy)->name,
                'asset_class'     => optional($item->assetClass)->name,
                'investment_type' => optional($item->investmentType)->name,
                'country'         => $item->country ?? null,
                'city'            => $item->city ?? null,
                'state'           => $item->state ?? null,
                'address'          => $item->address ?? null,
                'thumbnail'       => $item->mountain_image ? url($item->mountain_image) : null,
                'property_type'   => $item->property_type,
                'launch_date'     => $item->launch_date,
                'close_date'      => $item->close_date,
                'created_at'      => $item->created_at->format('Y-m-d'),
                'years_old'       => $item->created_at->diffInYears(now()),
            ];
        });

        return $this->success([
            'investments' => $data,
            'pagination' => [
                'total' => $investments->total(),
                'current_page' => $investments->currentPage(),
                'last_page' => $investments->lastPage(),
                'per_page' => $investments->perPage(),
            ],
            'filters_applied' => [
                'title' => $request->input('title'),
                'asset_class_id' => $request->input('asset_class_id'),
                'investment_type_id' => $request->input('investment_type_id'),
                'strategy_id' => $request->input('strategy_id'),
                'country' => $request->input('country'),
                'city' => $request->input('city'),
                'min_investment_from' => $request->input('min_investment_from'),
                'min_investment_to' => $request->input('min_investment_to'),
                'investment_range' => $request->input('investment_range'),
                'years' => $request->input('years'),
                'year_range' => $request->input('year_range'),
                'launch_year' => $request->input('launch_year'),
                'sort_by' => $request->input('sort_by', 'created_at'),
                'sort_order' => $request->input('sort_order', 'desc'),
            ],
        ], 'Investments retrieved successfully');
    }

    // investment details
    public function show($id)
    {
        $investment = Investment::with([
            'assetClass',
            'investmentType',
            'strategy',
            'highlight',
            'documents',
            'disclaimers',
            'tax_strategies',
            'images'
        ])->find($id);

        // return $investment;exit();

        if (!$investment) {
            return $this->error('Investment not found', 404);
        }

        return $this->success(new InvestmentResource($investment), 'Investment details retrieved successfully');
    }

    // get invesment strategy
    public function investmentStrategy()
    {
        $class = InvestmentStrategy::select('id', 'name')->get();
        return $this->success($class, 'Investment Strategies Retrieved Successfully');
    }

    // country list
    public function filterData()
    {
        $countries = DB::table('investments')->select('country')->get();
        $cities = DB::table('investments')->select('city')->get();
        $class = AssetClass::select('id', 'name')->get();
        $types = InvestmentTypes::select('id', 'name')->get();
        $strategy = InvestmentStrategy::select('id', 'name')->get();

        return $this->success(
            [
                'countries' => $countries,
                'cities' => $cities,
                'asset_classes' => $class,
                'types' => $types,
                'strategy' => $strategy
            ],
            'Country City list retrieved successfully'
        );
    }
}
