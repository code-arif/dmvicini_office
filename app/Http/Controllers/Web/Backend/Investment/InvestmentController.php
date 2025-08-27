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
    /*
    * show investments data in datatable
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

                // ->addColumn('title', fn($item) => e($item->title))
                ->addColumn('title', function ($item) {
                    return strlen($item->title) > 40
                        ? substr($item->title, 0, 40) . '...'
                        : $item->title;
                })

                ->addColumn('location', function ($item) {
                    return implode(', ', array_filter([$item->city, $item->state, $item->country]));
                })

                // ->addColumn('status', fn($item) => ucfirst($item->status))
                ->addColumn('status', function ($item) {
                    $statuses = ['draft', 'active', 'closed'];
                    $html = '<div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"            aria-expanded="false">'
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

                    $html .= '</ul>
                                </div>';
                    return $html;
                })


                ->addColumn('created_at', fn($item) => $item->created_at->format('Y-m-d h:i A'))

                ->addColumn('action', function ($item) {
                    return '
                    <div class="d-flex justify-content-start gap-2">
                        <a href="' . route('edit.investment', $item->id) . '"
                            class="btn btn-sm btn-primary">
                            <i class="fa fa-pen"></i> Edit
                        </a>

                        <button type="button" class="btn btn-sm btn-success highlightBtn"
                            data-id="' . $item->id . '">
                            <i class="fas fa-highlighter"></i> Highlight
                        </button>

                        <button type="button" class="btn btn-sm btn-info docBtn"
                            data-id="' . $item->id . '">
                            <i class="fas fa-file-upload"></i> Document
                        </button>

                        <button type="button" class="btn btn-sm btn-warning riskBtn"
                            data-id="' . $item->id . '">
                            <i class="fas fa-exclamation-triangle"></i> Risk
                        </button>

                        <button type="button" class="btn btn-sm btn-danger deleteBtn"
                            onclick="showDeleteConfirm(' . $item->id . ')">
                            <i class="fa fa-trash"></i> Delete
                        </button>

                        <a href="' . route('show.investment', $item->id) . '"
                            class="btn btn-sm" style="background-color: #6E0DD4; color: #fff;">
                            <i class="fa fa-eye"></i> View
                        </a>
                    </div>
                ';
                })


                ->rawColumns(['title', 'thumbnail', 'status', 'action'])
                ->make(true);
        }

        return view("backend.layouts.investment.investment");
    }

    /*
    * create investment page
    */
    public function create()
    {
        $asset_classes = AssetClass::latest('id')->get();
        $investment_types = InvestmentTypes::latest('id')->get();
        $strategies = InvestmentStrategy::latest('id')->get();
        return view('backend.layouts.investment.create_investment', compact(['asset_classes', 'investment_types', 'strategies']));
    }

    /*
    * Store investment
    */
    public function store(Request $request)
    {
        // dd($request->all());
        $validated_data = $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'nullable|string',
            'term' => 'nullable|string|max:255',
            'min_investment' => 'nullable|string',
            'targeted_irr' => 'nullable|string|max:255',
            'targeted_eps' => 'nullable|string|max:255',
            'banker_phone' => 'nullable|string',
            'banker_email' => 'nullable|string',
            'status' => 'required',
            'thumbnail' => 'nullable|max:5120',

            //location
            'country' => 'nullable|string|max:200',
            'city' => 'nullable|string|max:200',
            'state' => 'nullable|string|max:200',
            'address' => 'nullable|string',
            'map_url' => 'nullable|string',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',


            // Select or Create fields
            'asset_class_id' => 'nullable|exists:asset_classes,id',
            'asset_class' => 'nullable|string|max:255',
            'investment_type_id' => 'nullable|exists:investment_types,id',
            'investment_type' => 'nullable|string|max:255',
            'investments_strategy_id' => 'nullable|exists:investment_strategies,id',
            'investment_strategy' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            // Handle Asset Class
            if (!$request->asset_class_id && $request->asset_class) {
                $assetClass = AssetClass::create([
                    'name'       => $request->asset_class,
                    'description' => null,
                ]);
                $validated_data['asset_class_id'] = $assetClass->id;
            }

            // Handle Investment Type
            if (!$request->investment_type_id && $request->investment_type) {
                $investmentType = InvestmentTypes::create([
                    'name'       => $request->investment_type,
                    'description' => null,
                ]);
                $validated_data['investment_type_id'] = $investmentType->id;
            }

            // Handle Investment Strategy
            if (!$request->investments_strategy_id && $request->investment_strategy) {
                $strategy = InvestmentStrategy::create([
                    'name'       => $request->investment_strategy,
                    'description' => null,
                ]);
                $validated_data['investments_strategy_id'] = $strategy->id;
            }

            // Image upload
            if ($request->hasFile('thumbnail')) {
                $validated_data['thumbnail'] = Helper::fileUpload($request->file('thumbnail'), 'investment', time());
            }

            // Store Investment
            Investment::create($validated_data);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Investment created successfully!',
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error creating invest!',
                'error'   => $e->getMessage()
            ], 500);
        }
    }


    /*
    * Edit investment
    */
    public function edit($id)
    {
        $investment = Investment::findOrFail($id);

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
     * update investment
     */
    public function update(Request $request, $id)
    {
        $investment = Investment::findOrFail($id);

        $validated_data = $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'nullable|string',
            'term' => 'nullable|string|max:255',
            'min_investment' => 'nullable|string',
            'targeted_irr' => 'nullable|string|max:255',
            'targeted_eps' => 'nullable|string|max:255',
            'banker_phone' => 'nullable|string',
            'banker_email' => 'nullable|string',
            'status' => 'required',
            'thumbnail' => 'nullable|max:5120',

            //location
            'country' => 'nullable|string|max:200',
            'city' => 'nullable|string|max:200',
            'state' => 'nullable|string|max:200',
            'address' => 'nullable|string',
            'map_url' => 'nullable|string',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',

            // Select or Create fields
            'asset_class_id' => 'nullable|exists:asset_classes,id',
            'asset_class' => 'nullable|string|max:255',
            'investment_type_id' => 'nullable|exists:investment_types,id',
            'investment_type' => 'nullable|string|max:255',
            'investments_strategy_id' => 'nullable|exists:investment_strategies,id',
            'investment_strategy' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            // Handle Asset Class
            if (!$request->asset_class_id && $request->asset_class) {
                $assetClass = AssetClass::create([
                    'name' => $request->asset_class,
                    'description' => null,
                ]);
                $validated_data['asset_class_id'] = $assetClass->id;
            }

            // Handle Investment Type
            if (!$request->investment_type_id && $request->investment_type) {
                $investmentType = InvestmentTypes::create([
                    'name' => $request->investment_type,
                    'description' => null,
                ]);
                $validated_data['investment_type_id'] = $investmentType->id;
            }

            // Handle Investment Strategy
            if (!$request->investments_strategy_id && $request->investment_strategy) {
                $strategy = InvestmentStrategy::create([
                    'name' => $request->investment_strategy,
                    'description' => null,
                ]);
                $validated_data['investments_strategy_id'] = $strategy->id;
            }

            // Handle thumbnail update
            if ($request->hasFile('thumbnail')) {
                $validated_data['thumbnail'] = Helper::fileUpload($request->file('thumbnail'), 'investment', time());
            } else {
                // Keep old thumbnail if no new file is uploaded
                $validated_data['thumbnail'] = $investment->thumbnail;
            }

            // Update the investment
            $investment->update($validated_data);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Investment updated successfully!',
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error updating investment!',
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

            $investment = Investment::with(['documents', 'images'])->findOrFail($id);

            // Delete thumbnail file if exists
            if ($investment->thumbnail && file_exists(public_path($investment->thumbnail))) {
                @unlink(public_path($investment->thumbnail));
            }

            // Delete related document files
            foreach ($investment->documents as $doc) {
                if ($doc->file_path && file_exists(public_path($doc->file_path))) {
                    @unlink(public_path($doc->file_path));
                }
            }

            // Delete related image files
            foreach ($investment->images as $img) {
                if ($img->image_url && file_exists(public_path($img->image_url))) {
                    @unlink(public_path($img->image_url));
                }
            }

            // Delete the investment (this cascades to highlights, docs, images)
            $investment->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Investment and all related records deleted successfully!',
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

    /*
    * Update investment status
    */
    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:investments,id',
            'status' => 'required|in:draft,active,closed',
        ]);

        $investment = Investment::findOrFail($request->id);
        $investment->status = $request->status;
        $investment->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully!',
            'status'  => ucfirst($investment->status),
        ]);
    }


    /*
    * Show investment
    */
    public function show(Request $reques, $id)
    {
        $investment = Investment::with('assetClass', 'investmentType', 'strategy', 'highlight', 'documents', 'images')->find($id);
        $heroImageUrl = asset($investment->thumbnail) ?? asset('default/default_investment.avif');
        return view("backend.layouts.investment.investmentDetails", compact(['investment', 'heroImageUrl']));
    }
}
