<?php

namespace App\Http\Controllers\Web\Backend\Investment;

use Exception;
use App\Helper\Helper;
use App\Models\AssetClass;
use App\Models\Investment;
use App\Models\TaxStrategy;
use Illuminate\Http\Request;
use App\Models\InvestmentTypes;
use App\Models\InvestmentStrategy;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class InvestmentController extends Controller
{
    /**
     * Show investments data in datatable with filtering
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Investment::with([
                'assetClass:id,name',
                'investmentType:id,name',
                'strategy:id,name'
            ]);

            // Apply filters
            if ($request->filled('search_text')) {
                $query->where('title', 'like', '%' . $request->search_text . '%');
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('asset_class')) {
                $query->where('asset_class_id', $request->asset_class);
            }

            if ($request->filled('investment_type')) {
                $query->where('investment_type_id', $request->investment_type);
            }

            if ($request->filled('strategy')) {
                $query->where('investments_strategy_id', $request->strategy);
            }

            $investments = $query->latest('id')->get();

            return DataTables::of($investments)
                ->addIndexColumn()
                ->addColumn('title', function ($item) {
                    return strlen($item->title) > 40
                        ? substr($item->title, 0, 40) . '...'
                        : $item->title;
                })
                ->addColumn('asset_class', function ($item) {
                    return $item->assetClass ? $item->assetClass->name : '<span class="badge bg-secondary">N/A</span>';
                })
                ->addColumn('investment_type', function ($item) {
                    return $item->investmentType ? $item->investmentType->name : '<span class="badge bg-secondary">N/A</span>';
                })
                ->addColumn('location', function ($item) {
                    $location = array_filter([$item->city, $item->state, $item->country]);
                    return !empty($location) ? implode(', ', $location) : '<span class="text-muted">N/A</span>';
                })
                ->addColumn('status', function ($item) {
                    // Badge color based on status
                    $badgeClass = match ($item->status) {
                        'active' => 'success',
                        'closed' => 'danger',
                        'draft' => 'warning',
                        default => 'secondary'
                    };

                    $statusText = ucfirst($item->status);

                    return '<div class="dropdown">
                        <button class="btn btn-sm btn-' . $badgeClass . ' dropdown-toggle" type="button"
                            data-bs-toggle="dropdown" aria-expanded="false" style="min-width: 100px;">
                            ' . $statusText . '
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item changeStatus ' . ($item->status == 'draft' ? 'active' : '') . '"
                                href="javascript:void(0)"
                                data-id="' . $item->id . '"
                                data-status="draft"
                                data-current="' . $item->status . '">
                                    <i class="fa fa-circle text-warning me-2"></i> Draft
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item changeStatus ' . ($item->status == 'active' ? 'active' : '') . '"
                                href="javascript:void(0)"
                                data-id="' . $item->id . '"
                                data-status="active"
                                data-current="' . $item->status . '">
                                    <i class="fa fa-circle text-success me-2"></i> Active
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item changeStatus ' . ($item->status == 'closed' ? 'active' : '') . '"
                                href="javascript:void(0)"
                                data-id="' . $item->id . '"
                                data-status="closed"
                                data-current="' . $item->status . '">
                                    <i class="fa fa-circle text-danger me-2"></i> Closed
                                </a>
                            </li>
                        </ul>
                    </div>';
                })
                ->addColumn('created_at', fn($item) => $item->created_at->format('Y-m-d h:i A'))
                ->addColumn('action', function ($item) {
                    return '
                <div class="d-flex justify-content-start gap-2">
                    <a href="' . route('investment.show', $item->id) . '"
                        class="btn btn-sm btn-info" title="View Details">
                        <i class="fa fa-eye"></i>
                    </a>
                    <a href="' . route('investment.edit', $item->id) . '"
                        class="btn btn-sm btn-primary" title="Edit">
                        <i class="fa fa-pen"></i>
                    </a>
                    <button type="button" class="btn btn-sm btn-danger deleteBtn"
                        onclick="showDeleteConfirm(' . $item->id . ')" title="Delete">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>';
                })
                ->rawColumns(['title', 'asset_class', 'investment_type', 'location', 'status', 'action'])
                ->make(true);
        }

        // For the view
        $asset_classes = AssetClass::latest('id')->get();
        $investment_types = InvestmentTypes::latest('id')->get();
        $strategies = InvestmentStrategy::latest('id')->get();

        return view("backend.layouts.investment.investment", compact([
            'asset_classes',
            'investment_types',
            'strategies'
        ]));
    }

    /**
     * Create investment page
     */
    public function create()
    {
        $asset_classes = AssetClass::latest('id')->get();
        $investment_types = InvestmentTypes::latest('id')->get();
        $strategies = InvestmentStrategy::latest('id')->get();
        $tax_strategies = TaxStrategy::latest('id')->get();

        return view('backend.layouts.investment.create_investment', compact([
            'asset_classes',
            'investment_types',
            'strategies',
            'tax_strategies'
        ]));
    }

    /**
     * Store investment - Step 1: Basic Information
     */

    public function storeBasic(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'asset_class_id' => 'nullable|exists:asset_classes,id',
            'investment_type_id' => 'nullable|exists:investment_types,id',
            'investments_strategy_id' => 'nullable|exists:investment_strategies,id',
            'term' => 'nullable|string',
            'min_investment' => 'nullable|string',
            'mountain_image' => 'nullable|image|max:10240',
            'investment_details' => 'nullable|string',
            'country' => 'nullable|string|max:200',
            'city' => 'nullable|string|max:200',
            'state' => 'nullable|string|max:200',
            'address' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'status' => 'required|in:draft,active,closed',
        ]);

        try {
            DB::beginTransaction();

            if ($request->hasFile('mountain_image')) {
                $validated['mountain_image'] = Helper::fileUpload(
                    $request->file('mountain_image'),
                    'investment',
                    time() . '_' . str_replace(' ', '_', $request->title)
                );
            }

            $investment = Investment::create($validated);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Investment created successfully!',
                'investment_id' => $investment->id,
                'investment' => $investment
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Investment creation failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to create investment',
                'error' => config('app.debug') ? $e->getMessage() : 'An error occurred'
            ], 500);
        }
    }

    /**
     * Edit investment
     */
    public function edit($id)
    {
        $investment = Investment::with([
            'highlight',
            'documents',
            'images',
            'disclaimers',
            'tax_strategies'
        ])->findOrFail($id);

        $asset_classes = AssetClass::latest('id')->get();
        $investment_types = InvestmentTypes::latest('id')->get();
        $strategies = InvestmentStrategy::latest('id')->get();
        $tax_strategies = TaxStrategy::latest('id')->get();

        return view('backend.layouts.investment.edit_investment', compact(
            'investment',
            'asset_classes',
            'investment_types',
            'strategies',
            'tax_strategies'
        ));
    }


    /**
     * Update investment - Basic Information
     */
    public function updateBasic(Request $request, $id)
    {
        $investment = Investment::findOrFail($id);

        $validated = $request->validate([
            'title'                       => 'nullable|string|max:255',
            'asset_class_id'              => 'nullable|exists:asset_classes,id',
            'investment_type_id'          => 'nullable|exists:investment_types,id',
            'investments_strategy_id'     => 'nullable|exists:investment_strategies,id',
            'term'                        => 'nullable|string',
            'min_investment'              => 'nullable|string',
            'mountain_image'              => 'nullable|image|max:10240',
            'investment_details'          => 'nullable|string',
            'country'                     => 'nullable|string|max:200',
            'city'                        => 'nullable|string|max:200',
            'state'                       => 'nullable|string|max:200',
            'address'                     => 'nullable|string',
            'latitude'                    => 'nullable|numeric',
            'longitude'                   => 'nullable|numeric',
            'status'                      => 'nullable|in:draft,active,closed',
        ]);

        try {
            DB::beginTransaction();

            if ($request->hasFile('mountain_image')) {
                // Delete old image
                if ($investment->mountain_image && file_exists(public_path($investment->mountain_image))) {
                    @unlink(public_path($investment->mountain_image));
                }
                $validated['mountain_image'] = Helper::fileUpload(
                    $request->file('mountain_image'),
                    'investment',
                    time() . '_' . str_replace(' ', '_', $request->title)
                );
            }

            $investment->update($validated);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Investment updated successfully!',
                'investment' => $investment->fresh()
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update investment',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Delete investment
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $investment = Investment::with(['documents', 'images', 'highlight', 'disclaimers'])->findOrFail($id);

            // Delete mountain image
            if ($investment->mountain_image && file_exists(public_path($investment->mountain_image))) {
                @unlink(public_path($investment->mountain_image));
            }

            // Delete document files
            foreach ($investment->documents as $doc) {
                if ($doc->file_path && file_exists(public_path($doc->file_path))) {
                    @unlink(public_path($doc->file_path));
                }
            }

            // Delete image files
            foreach ($investment->images as $img) {
                if ($img->image_url && file_exists(public_path($img->image_url))) {
                    @unlink(public_path($img->image_url));
                }
            }

            // Delete the investment (cascades)
            $investment->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Investment deleted successfully!',
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error deleting investment!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update investment status
     */
    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:investments,id',
            'status' => 'required|in:draft,active,closed',
        ]);

        try {
            $investment = Investment::findOrFail($request->id);
            $investment->status = $request->status;
            $investment->save();

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully!',
                'status'  => ucfirst($investment->status),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Get investment data for edit
     */
    public function getInvestment($id)
    {
        try {
            $investment = Investment::with([
                'highlight',
                'documents',
                'images',
                'disclaimers' => fn($q) => $q->orderBy('id')
            ])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $investment
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Investment not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Show investment details page
     */
    public function show($id)
    {
        $investment = Investment::with([
            'assetClass',
            'investmentType',
            'strategy',
            'highlight',
            'documents',
            'images',
            'disclaimers'
        ])->findOrFail($id);

        // return $investment;exit();

        return view('backend.layouts.investment.show_investment', compact('investment'));
    }
}
