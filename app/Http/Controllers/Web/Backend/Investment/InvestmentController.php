<?php

namespace App\Http\Controllers\Web\Backend\Investment;

use Exception;
use App\Helper\Helper;
use App\Models\AssetClass;
use App\Models\Investment;
use Illuminate\Http\Request;
use App\Models\InvestmentTypes;
use App\Models\InvestmentStrategy;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class InvestmentController extends Controller
{
    /**
     * Show investments data in datatable
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $investments = Investment::with([
                'assetClass:id,name',
                'investmentType:id,name',
                'strategy:id,name'
            ])->latest('id')->get();

            return DataTables::of($investments)
                ->addIndexColumn()
                ->addColumn('title', function ($item) {
                    return strlen($item->title) > 40
                        ? substr($item->title, 0, 40) . '...'
                        : $item->title;
                })
                ->addColumn('location', function ($item) {
                    return implode(', ', array_filter([$item->city, $item->state, $item->country]));
                })
                ->addColumn('status', function ($item) {
                    $statuses = ['draft', 'active', 'closed'];
                    $html = '<div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">'
                        . ucfirst($item->status) .
                        '</button>
                                <ul class="dropdown-menu">';

                    foreach ($statuses as $status) {
                        $activeClass = $item->status === $status ? 'active' : '';
                        $html .= '<li>
                                    <a class="dropdown-item changeStatus ' . $activeClass . '"
                                    href="javascript:void(0)"
                                    data-id="' . $item->id . '"
                                    data-status="' . $status . '">'
                            . ucfirst($status) . '</a>
                                </li>';
                    }

                    $html .= '</ul></div>';
                    return $html;
                })
                ->addColumn('created_at', fn($item) => $item->created_at->format('Y-m-d h:i A'))
                ->addColumn('action', function ($item) {
                    return '
                    <div class="d-flex justify-content-start gap-2">
                        <a href="' . route('investment.edit', $item->id) . '"
                            class="btn btn-sm btn-primary">
                            <i class="fa fa-pen"></i> Edit
                        </a>
                        <button type="button" class="btn btn-sm btn-danger deleteBtn"
                            onclick="showDeleteConfirm(' . $item->id . ')">
                            <i class="fa fa-trash"></i> Delete
                        </button>
                    </div>';
                })
                ->rawColumns(['title', 'status', 'action'])
                ->make(true);
        }

        return view("backend.layouts.investment.investment");
    }

    /**
     * Create investment page
     */
    public function create()
    {
        $asset_classes = AssetClass::latest('id')->get();
        $investment_types = InvestmentTypes::latest('id')->get();
        $strategies = InvestmentStrategy::latest('id')->get();

        return view('backend.layouts.investment.create_investment', compact([
            'asset_classes',
            'investment_types',
            'strategies'
        ]));
    }

    /**
     * Store investment - Step 1: Basic Information
     */
    public function storeBasic(Request $request)
    {
        $validated = $request->validate([
            'title'                       => 'required|string|max:255',
            'asset_class_id'              => 'nullable|exists:asset_classes,id',
            'investment_type_id'          => 'nullable|exists:investment_types,id',
            'investments_strategy_id'     => 'nullable|exists:investment_strategies,id',
            'tax_strategie_id'            => 'nullable|exists:tax_strategies,id',
            'term'                        => 'nullable|string',
            'min_investment'              => 'nullable|string',
            'mountain_image'              => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'investment_details'          => 'nullable|string',
            'country'                     => 'nullable|string|max:200',
            'city'                        => 'nullable|string|max:200',
            'state'                       => 'nullable|string|max:200',
            'address'                     => 'nullable|string',
            'latitude'                    => 'nullable|numeric',
            'longitude'                   => 'nullable|numeric',
            'status'                      => 'required|in:draft,active,closed',
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
                'investment_id' => $investment->id
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create investment',
                'error' => $e->getMessage()
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
            'disclaimers'
        ])->findOrFail($id);

        $asset_classes = AssetClass::latest('id')->get();
        $investment_types = InvestmentTypes::latest('id')->get();
        $strategies = InvestmentStrategy::latest('id')->get();

        return view('backend.layouts.investment.edit_investment', compact(
            'investment',
            'asset_classes',
            'investment_types',
            'strategies'
        ));
    }


    /**
     * Update investment - Basic Information
     */
    public function updateBasic(Request $request, $id)
    {
        $investment = Investment::findOrFail($id);

        $validated = $request->validate([
            'title'                       => 'required|string|max:255',
            'asset_class_id'              => 'nullable|exists:asset_classes,id',
            'investment_type_id'          => 'nullable|exists:investment_types,id',
            'investments_strategy_id'     => 'nullable|exists:investment_strategies,id',
            'tax_strategie_id'            => 'nullable|exists:tax_strategies,id',
            'term'                        => 'nullable|string',
            'min_investment'              => 'nullable|string',
            'mountain_image'              => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'investment_details'          => 'nullable|string',
            'country'                     => 'nullable|string|max:200',
            'city'                        => 'nullable|string|max:200',
            'state'                       => 'nullable|string|max:200',
            'address'                     => 'nullable|string',
            'latitude'                    => 'nullable|numeric',
            'longitude'                   => 'nullable|numeric',
            'status'                      => 'required|in:draft,active,closed',
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
}
