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

                ->addColumn('status', fn($item) => ucfirst($item->status))

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

                        <button type="button" class="btn btn-sm btn-secondary faqBtn"
                            data-id="' . $item->id . '">
                            <i class="fas fa-question-circle"></i> FAQ
                        </button>

                        <button type="button" class="btn btn-sm btn-danger deleteBtn"
                            onclick="showDeleteConfirm(' . $item->id . ')">
                            <i class="fa fa-trash"></i> Delete
                        </button>

                        <a class="btn btn-sm deleteBtn" style = "background: #6E0DD4; color: #fff"
                            onclick="showDeleteConfirm(' . $item->id . ')">
                            <i class="fa fa-eye"></i> View
                        </a>
                    </div>
                ';
                })


                ->rawColumns(['title', 'thumbnail', 'action'])
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
                $validated_data['category_id'] = $assetClass->id;
            }

            // Handle Investment Type
            if (!$request->investment_type_id && $request->investment_type) {
                $investmentType = InvestmentTypes::create([
                    'name'       => $request->investment_type,
                    'description' => null,
                ]);
                $validated_data['category_id'] = $investmentType->id;
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
}
