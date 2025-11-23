<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Models\Education;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Models\PinnedEducation;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\EducationResource;

class EducationPageController extends Controller
{
    use ApiResponse;

    //education list
    public function getEducationlist(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        $query = Education::with('category:id,title')->latest();

        // Title search
        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        // Description search
        if ($request->filled('description')) {
            $query->where('description', 'like', '%' . $request->description . '%');
        }

        // Single category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Multiple categories
        if ($request->filled('category_ids')) {
            $ids = array_filter(explode(',', $request->category_ids));
            $query->whereIn('category_id', $ids);
        }

        // Date filtering
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        } elseif ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        } elseif ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Paginate with Resource
        $educations = $query->paginate($perPage);

        return $this->success(
            [
                'articles'  => EducationResource::collection($educations),
                'pagination'  => [
                    'total'        => $educations->total(),
                    'current_page' => $educations->currentPage(),
                    'last_page'    => $educations->lastPage(),
                    'per_page'     => $educations->perPage(),
                ],
            ],
            'Educations retrieved successfully.',
            200
        );
    }

    //eudcation details
    public function show($id)
    {
        $education = Education::with('category:id,title')->find($id);

        if (!$education) {
            return $this->error([], 'Education not found.', 404);
        }

        $educationData = [
            'id'          => $education->id,
            'title'       => $education->title,
            'sub_title'   => $education->sub_title,
            'description' => $education->description,
            'image'       => $education->image ? url('/' . $education->image) : null,
            'created_at'  => $education->created_at,
            'updated_at'  => $education->updated_at,

            'category'    => $education->category,
        ];

        return $this->success(
            $educationData,
            'Education fetched successfully.',
            200
        );
    }

    //pinned education
    public function pinnedEducation()
    {
        $pinnedEdu = PinnedEducation::with('education')->first();
        return $this->success($pinnedEdu, 'Pinned Education Retrieve Successfully.', 200);
    }

    // get categories
    public function getCategories(Request $request)
    {
        $categories = Category::select('id', 'title')
            ->orderBy('title')
            ->get();

        return $this->success(
            CategoryResource::collection($categories),
            'Categories retrieved successfully.',
            200
        );
    }
}
