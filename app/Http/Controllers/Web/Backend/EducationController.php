<?php

namespace App\Http\Controllers\Web\Backend;

use Exception;
use App\Helper\Helper;
use App\Models\Education;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AssetClass;
use App\Models\PinnedEducation;
use Yajra\DataTables\Facades\DataTables;

class EducationController extends Controller
{
    //education list
    public function index(Request $request)
    {
        $asset_classes = AssetClass::get();

        if ($request->ajax()) {
            // eager load category relationship
            $educations = Education::with('asset_class')->latest()->get();


            return DataTables::of($educations)
                ->addIndexColumn()

                // Image (render as <img>)
                ->addColumn('image', function ($item) {
                    if ($item->image) {
                        return '<img src="' . asset('/' . $item->image) . '" alt="Image" width="50">';
                    }
                    return '-';
                })

                // Title
                ->addColumn('title', fn($item) => $item->title)

                // Sub Title
                ->addColumn('sub_title', fn($item) => $item->sub_title ?? '-')

                // Category
                ->addColumn('asset_class', function ($item) {
                    if ($item->asset_class && $item->asset_class->name) {
                        $name = e($item->asset_class->name);
                        return '<span class="badge rounded-pill bg-info text-dark">' . $name . '</span>';
                    }
                    return '<span class="badge rounded-pill red-accent">Uncategorized</span>';
                })

                // Created at
                ->addColumn('created_at', fn($item) => $item->created_at->format('Y-m-d h:i A'))

                // status (Pin/Unpin toggle) - Only one can be pinned at a time
                ->addColumn('status', function ($item) {
                    $isPinned = PinnedEducation::where('education_id', $item->id)
                        ->where('user_id', auth()->id())
                        ->exists();

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

                // Action buttons
                ->addColumn('action', function ($item) {
                    return '
                    <div class="d-flex justify-content-start gap-2">
                        <button type="button" class="btn btn-sm btn-primary editBtn"
                            data-id="' . $item->id . '"
                            data-title="' . e($item->title) . '"
                            data-sub_title="' . e($item->sub_title) . '"
                            data-description="' . e($item->description) . '"
                            data-asset_class_id="' . $item->asset_class_id . '"
                            data-image="' . $item->image . '">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-danger deleteBtn" onclick="showDeleteConfirm(' . $item->id . ')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                        ';
                })

                ->rawColumns(['action', 'image', 'asset_class', 'status'])
                ->make(true);
        }

        return view("backend.layouts.education.index", compact('asset_classes'));
    }

    //store education
    public function store(Request $request)
    {
        try {
            $validated_data = $request->validate([
                'title'       => 'required|string|max:250',
                'sub_title'   => 'nullable|string|max:250',
                'description' => 'nullable|string',
                'asset_class_id' => 'required|exists:asset_classes,id',
                'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            ]);

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
                'asset_class_id'   => 'required|exists:asset_classes,id',
                'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            ]);

            // Image upload (replace old image if new one uploaded)
            if ($request->hasFile('image')) {
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

    //education pinned/unpinned - Only one can be pinned at a time
    public function togglePinned($id)
    {
        $user = auth()->user();

        // Check if the education exists
        $education = Education::find($id);
        if (!$education) {
            return response()->json(['success' => false, 'message' => 'Item not found'], 404);
        }

        // Check if the user already pinned this education
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
            // First, unpin all other educations for this user
            PinnedEducation::where('user_id', $user->id)->delete();

            // Then pin this education
            PinnedEducation::create([
                'education_id' => $education->id,
                'user_id'      => $user->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => '📍 Education Pinned! (Previous pin removed)',
            ], 200);
        }
    }
}
