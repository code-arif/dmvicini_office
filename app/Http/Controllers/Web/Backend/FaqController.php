<?php

namespace App\Http\Controllers\Web\Backend;

use Exception;
use App\Models\Faq;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class FaqController extends Controller
{
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
                        <button type="button" class="btn btn-sm btn-primary editBtn"
                            data-id="' . $data->id . '"
                            data-question="' . e($data->question) . '"
                            data-answer="' . e($data->answer) . '">
                            <i class="fa fa-edit"></i>
                        </button>
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
}
