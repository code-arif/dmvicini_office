<?php

namespace App\Http\Controllers\Web\Backend\Investment;

use Exception;
use App\Models\AssetClass;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class AssetClassController extends Controller
{
    /*
    * show asset class in datatable
    */
    public function index(Request $request)
    {
        // return AssetClass::get();exit();
        if ($request->ajax()) {
            $classes = AssetClass::latest('id')->get();

            return DataTables::of($classes)
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

        return view("backend.layouts.investment.asset_class");
    }

    /**
     * Asset class store
     */
    public function store(Request $request)
    {
        // dd($request->all());
        try {
            $validated_data = $request->validate([
                'name' => 'required|max:100|string',
                'description' => 'nullable|string|max:5000'
            ]);

            AssetClass::create($validated_data);

            return response()->json([
                'success' => true,
                'message' => 'Class added successfully!',
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating class!',
            ], status: 200);
        }
    }

    /*
    * Update asset class
    */
    public function update(Request $request, $id)
    {
        try {
            $validated_data = $request->validate([
                'name' => 'required|max:100|string',
                'description' => 'nullable|string|max:5000'
            ]);

            $class = AssetClass::findOrFail($id);
            $class->update($validated_data);

            return response()->json([
                'success' => true,
                'message' => 'Class updated successfully!',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating class!',
            ], 200);
        }
    }

    /**
     * Delete class
     **/
    public function destroy($id)
    {
        $item = AssetClass::find($id);

        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Class not found'], 404);
        }

        $item->delete();

        return response()->json(['success' => true, 'message' => 'Class deleted successfully']);
    }
}
