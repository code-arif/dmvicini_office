<?php

namespace App\Http\Controllers\Web\Backend\Investment;

use Exception;
use Illuminate\Http\Request;
use App\Models\InvestmentTypes;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class InvestmentTypeController extends Controller
{
    /*
    * show investment types in datatable
    */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $types = InvestmentTypes::latest('id')->get();

            return DataTables::of($types)
                ->addIndexColumn()

                ->addColumn('name', fn($item) => $item->name)

                ->addColumn('description', function ($item) {
                    return strlen($item->description) > 50
                        ? substr($item->description, 0, 50) . '...'
                        : $item->description;
                })

                ->addColumn('created_at', fn($item) => $item->created_at->format('Y-m-d h:i A'))

                ->addColumn('action', function ($item) {
                    return '
                    <button type="button" class="btn btn-sm btn-primary editBtn"
                        data-id="' . $item->id . '"
                        data-name="' . $item->name . '"
                        data-description="' . $item->description . '">
                        <i class="fa fa-edit"></i> Edit
                    </button>
                     <button type="button" class="btn btn-sm btn-danger deleteBtn" onclick="showDeleteConfirm(' . $item->id . ')">
                        <i class="fa fa-trash"></i> Delete
                    </button>
                    ';
                })


                ->rawColumns(['description', 'action'])
                ->make();
        }

        return view("backend.layouts.investment.investment_type");
    }

    /**
     * Investment type store
     */
    public function store(Request $request)
    {
        try {
            $validated_data = $request->validate([
                'name' => 'required|max:100|string',
                'description' => 'nullable|string|max:5000'
            ]);

            InvestmentTypes::create($validated_data);

            return response()->json([
                'success' => true,
                'message' => 'Type added successfully!',
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating type!',
            ], status: 200);
        }
    }

    /*
    * Update investment type
    */
    public function update(Request $request, $id)
    {
        try {
            $validated_data = $request->validate([
                'name' => 'required|max:100|string',
                'description' => 'nullable|string|max:5000'
            ]);

            $type = InvestmentTypes::findOrFail($id);
            $type->update($validated_data);

            return response()->json([
                'success' => true,
                'message' => 'Type updated successfully!',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating type!',
            ], 200);
        }
    }

    /**
     * Delete class
     **/
    public function destroy($id)
    {
        $item = InvestmentTypes::find($id);

        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Type not found'], 404);
        }

        $item->delete();

        return response()->json(['success' => true, 'message' => 'Type deleted successfully']);
    }
}
