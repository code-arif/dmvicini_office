<?php

namespace App\Http\Controllers\Web\Backend;

use Exception;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    //show category list page
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $categories = Category::latest()->get();

            return DataTables::of($categories)
                ->addIndexColumn()

                ->addColumn('title', fn($item) => $item->title)

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
                        data-title="' . $item->title . '"
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

        return view("backend.layouts.category.index");
    }


    /**
     * Category store
     */
    public function store(Request $request)
    {
        try {
            $validated_data = $request->validate([
                'title' => 'required|max:100|string',
                'description' => 'nullable|string|max:200'
            ]);

            Category::create($validated_data);

            return response()->json([
                'success' => true,
                'message' => 'Item added successfully!',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating item!',
            ], 200);
        }
    }


    /*
    * Update category
    */
    public function update(Request $request, $id)
    {
        try {
            $validated_data = $request->validate([
                'title' => 'required|max:100|string',
                'description' => 'nullable|string|max:200'
            ]);

            $category = Category::findOrFail($id);
            $category->update($validated_data);

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
     * delete category
     **/
    public function destroy($id)
    {
        $item = Category::find($id);

        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Item not found'], 404);
        }

        $item->delete();

        return response()->json(['success' => true, 'message' => 'Item deleted successfully']);
    }
}
