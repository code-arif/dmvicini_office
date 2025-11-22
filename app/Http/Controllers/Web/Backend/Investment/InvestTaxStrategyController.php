<?php

namespace App\Http\Controllers\Web\Backend\Investment;

use Exception;
use App\Models\TaxStrategy;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class InvestTaxStrategyController extends Controller
{
    /*
    * show tax strategy in datatable
    */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $strategies = TaxStrategy::latest('id')->get();

            return DataTables::of($strategies)
                ->addIndexColumn()

                ->addColumn('name', fn($item) => $item->name)

                ->addColumn('description', function ($item) {
                    $text = strip_tags($item->description); // remove HTML tags
                    return strlen($text) > 50
                        ? substr($text, 0, 50) . '...'
                        : $text;
                })

                ->addColumn('created_at', fn($item) => $item->created_at->format('Y-m-d h:i A'))

                ->addColumn('action', function ($item) {
                    return '


                     <button type="button" class="btn btn-sm btn-primary editBtn"
                            data-id="' . $item->id . '"
                            data-name="' . e($item->name) . '"
                            data-description="' . e($item->description) . '">
                            <i class="fas fa-edit"></i> Edit
                    </button>

                     <button type="button" class="btn btn-sm btn-danger deleteBtn" onclick="showDeleteConfirm(' . $item->id . ')">
                        <i class="fa fa-trash"></i> Delete
                    </button>
                    ';
                })


                ->rawColumns(['description', 'action'])
                ->make();
        }

        return view("backend.layouts.investment.tax_strategy");
    }

    /**
     * Investment strategy store
     */
    public function store(Request $request)
    {
        // dd($request->all());
        try {
            $validated_data = $request->validate([
                'name' => 'required|max:100|string',
                'description' => 'nullable|string|max:5000'
            ]);

            TaxStrategy::create($validated_data);

            return response()->json([
                'success' => true,
                'message' => 'Strategy added successfully!',
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating stragegy!',
            ], status: 200);
        }
    }

    /*
    * Update tax strategy
    */
    public function update(Request $request, $id)
    {
        try {
            $validated_data = $request->validate([
                'name' => 'required|max:100|string',
                'description' => 'nullable|string|max:5000'
            ]);

            $item = TaxStrategy::findOrFail($id);
            $item->update($validated_data);

            return response()->json([
                'success' => true,
                'message' => 'Item updated successfully!',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating item!',
            ], 200);
        }
    }

    /**
     * Delete class
     **/
    public function destroy($id)
    {
        $item = TaxStrategy::find($id);

        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Type not found'], 404);
        }

        $item->delete();

        return response()->json(['success' => true, 'message' => 'Type deleted successfully']);
    }
}
