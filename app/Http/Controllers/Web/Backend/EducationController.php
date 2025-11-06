<?php

namespace App\Http\Controllers\Web\Backend;

use Exception;
use App\Helper\Helper;
use App\Models\Category;
use App\Models\Education;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PinnedEducation;
use Yajra\DataTables\Facades\DataTables;

class EducationController extends Controller
{
    //education list
    public function index(Request $request)
    {
        $categories = Category::get();
        if ($request->ajax()) {
            // eager load category relationship
            $educations = Education::with('category')->latest()->get();

            return DataTables::of($educations)
                ->addIndexColumn()

                // Title
                ->addColumn('title', fn($item) => $item->title)

                // Sub Title
                ->addColumn('sub_title', fn($item) => $item->sub_title ?? '-')

                // Category
                // ->addColumn('category', fn($item) => $item->category->title ?? 'Uncategorized')
                ->addColumn('category', function ($item) {
                    if ($item->category && $item->category->title) {
                        $title = e($item->category->title);
                        return '<span class="badge rounded-pill bg-info text-dark">' . $title . '</span>';
                    }
                    // return '<span class="badge rounded-pill bg-danger-subtle text-danger">Uncategorized</span>';
                    return '<span class="badge rounded-pill red-accent">Uncategorized</span>';
                })


                // Image (render as <img>)
                ->addColumn('image', function ($item) {
                    if ($item->image) {
                        return '<img src="' . asset('/' . $item->image) . '" alt="Image" width="50">';
                    }
                    return '-';
                })

                // Created at
                ->addColumn('created_at', fn($item) => $item->created_at->format('Y-m-d h:i A'))

                // Action buttons
                ->addColumn('action', function ($item) {
                    return '
                    <div class="d-flex justify-content-start gap-2">
                        <button type="button" class="btn btn-sm btn-primary editBtn"
                            data-id="' . $item->id . '"
                            data-title="' . e($item->title) . '"
                            data-sub_title="' . e($item->sub_title) . '"
                            data-description="' . e($item->description) . '"
                            data-category_id="' . $item->category_id . '"
                            data-image="' . $item->image . '">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button type="button" class="btn btn-sm btn-danger deleteBtn" onclick="showDeleteConfirm(' . $item->id . ')">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                        ';
                })
                // status (Pin/Unpin toggle)
                ->addColumn('status', function ($item) {
                    $isPinned = $item->pinnedByUser ? true : false;
                    $checked = $isPinned ? 'checked' : '';

                    return '<div class="form-check form-switch d-flex justify-content-center align-items-center">
                        <input onclick="togglePin(' . $item->id . ')"
                        type="checkbox"
                        class="form-check-input pin-toggle"
                        role="switch"
                        style="cursor: pointer; width: 50px; height: 24px;"
                        ' . $checked . '>
                        </div>';
                })

                ->rawColumns(['action', 'image', 'category', 'status'])
                ->make(true);
        }

        return view("backend.layouts.education.index", compact('categories'));
    }

    //store education
    public function store(Request $request)
    {
        // dd($request->all());
        try {
            $validated_data = $request->validate([
                'title'       => 'required|string|max:250',
                'sub_title'   => 'nullable|string|max:250',
                'description' => 'nullable|string',
                'category_id' => 'nullable|exists:categories,id',
                'category_name' => 'nullable|string|max:100',
                'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Category check (if no category_id but new category name given)
            if (!$request->category_id && $request->category_name) {
                $category = Category::create([
                    'title'       => $request->category_name,
                    'description' => null,
                ]);
                $validated_data['category_id'] = $category->id;
            }

            // Image upload
            if ($request->hasFile('image')) {
                $validated_data['image'] = Helper::fileUpload($request->file('image'), 'education', time());
            }

            $validated_data['user_id'] = auth()->id();

            Education::create($validated_data);

            return response()->json([
                'success' => true,
                'message' => 'Education item created successfully!',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating education item!',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    //update education
    public function update(Request $request, $id)
    {
        try {
            $education = Education::findOrFail($id);

            $validated_data = $request->validate([
                'title'         => 'required|string|max:250',
                'sub_title'     => 'nullable|string|max:250',
                'description'   => 'nullable|string',
                'category_id'   => 'nullable|exists:categories,id',
                'category_name' => 'nullable|string|max:100',
                'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Category check (if no category_id but new category name given)
            if (!$request->category_id && $request->category_name) {
                $category = Category::create([
                    'title'       => $request->category_name,
                    'description' => null,
                ]);
                $validated_data['category_id'] = $category->id;
            }

            // Image upload (replace old image if new one uploaded)
            if ($request->hasFile('image')) {
                // চাইলে আগের ফাইল delete করতে পারো
                if ($education->image && file_exists(public_path('/' . $education->image))) {
                    unlink(public_path('/' . $education->image));
                }
                $validated_data['image'] = Helper::fileUpload($request->file('image'), 'education', time());
            }

            $validated_data['user_id'] = auth()->id();

            $education->update($validated_data);

            return response()->json([
                'success' => true,
                'message' => 'Education item updated successfully!',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating education item!',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    //delete education
    public function destroy($id)
    {

        $item = Education::find($id);

        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Item not found'], 404);
        }

        if ($item->image) {
            Helper::deleteImage($item->image);
        }
        $item->delete();

        return response()->json(['success' => true, 'message' => 'Item deleted successfully']);
    }

    //education pinned/unpinned
    // education pinned/unpinned
    public function togglePinned($id)
    {
        $user = auth()->user();

        // Check if the education exists
        $education = Education::find($id);
        if (!$education) {
            return response()->json(['success' => false, 'message' => 'Item not found'], 404);
        }

        // Check if the user already pinned the education
        $alreadyPinned = PinnedEducation::where('education_id', $education->id)
            ->where('user_id', $user->id)
            ->first();

        if ($alreadyPinned) {
            // Unpin the education
            $alreadyPinned->delete();

            return response()->json([
                'success' => false,
                'message' => '📍 Education Unpinned!',
            ], 200);
        } else {
            // Pin the education
            PinnedEducation::create([
                'education_id' => $education->id,
                'user_id'      => $user->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => '📍 Education Pinned!',
            ], 200);
        }
    }
}
