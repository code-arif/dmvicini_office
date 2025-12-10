<?php

namespace App\Http\Controllers\Web\Backend;

use Exception;
use App\Models\Faq;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Faq::latest();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('question', function ($data) {
                    return '<strong>' . e($data->question) . '</strong>';
                })
                ->addColumn('answer', function ($data) {
                    $text = strip_tags($data->answer);
                    return strlen($text) > 50 ? substr($text, 0, 50) . '...' : $text;
                })
                ->addColumn('status', function ($data) {
                    $checked = $data->status == 'active' ? 'checked' : '';
                    return '<div class="form-check form-switch d-flex justify-content-center">
                        <input onclick="showStatusChangeAlert(' . $data->id . ')"
                        type="checkbox"
                        class="form-check-input"
                        role="switch"
                        style="cursor: pointer; width: 50px; height: 24px;"
                        ' . $checked . '>
                    </div>';
                })
                ->addColumn('action', function ($data) {
                    return '<div class="d-flex justify-content-start gap-2">
                        <a href="' . route('admin.faq.edit', $data->id) . '" class="btn btn-sm btn-primary">
                            <i class="fa fa-edit"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-danger" onclick="showDeleteConfirm(' . $data->id . ')">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>';
                })
                ->rawColumns(['question', 'answer', 'status', 'action'])
                ->make(true);
        }
        return view('backend.layouts.faq.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.layouts.faq.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'question' => 'required|string|max:500',
                'answer' => 'required|string',
            ]);

            $validated['status'] = 'active';

            Faq::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'FAQ created successfully!',
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating FAQ!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $faq = Faq::findOrFail($id);
        return view('backend.layouts.faq.edit', compact('faq'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        try {
            $validated = $request->validate([
                'question' => 'required|string|max:500',
                'answer' => 'required|string',
            ]);

            $faq = Faq::findOrFail($id);
            $faq->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'FAQ updated successfully!',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating FAQ!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Change the status of the specified resource.
     */
    public function status(int $id): JsonResponse
    {
        $data = Faq::findOrFail($id);

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'FAQ not found.',
            ]);
        }

        $data->status = $data->status === 'active' ? 'inactive' : 'active';
        $data->save();

        return response()->json([
            'status' => 'success',
            'message' => 'FAQ Status Changed successfully!',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $data = Faq::findOrFail($id);

        if (empty($data)) {
            return response()->json([
                'success' => false,
                'message' => 'FAQ not found.',
            ], 404);
        }

        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'FAQ deleted successfully!',
        ], 200);
    }

    /**
     * Upload image for Summernote editor
     */
    public function uploadImage(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                // Store in public disk
                $path = $file->storeAs('faq-images', $filename, 'public');

                return response()->json([
                    'success' => true,
                    'url' => asset('storage/' . $path)
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No file uploaded'
            ], 400);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Image upload failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
