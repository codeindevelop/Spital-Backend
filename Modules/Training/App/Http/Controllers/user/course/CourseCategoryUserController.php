<?php

namespace Modules\Training\App\Http\Controllers\user\course;

use Modules\Files\App\Http\Controllers\Controller;
use Modules\Training\App\Models\Course\CourseCategory;
use Illuminate\Http\Request;

class CourseCategoryUserController extends Controller
{
    // Get all Categories
    public function index(): \Illuminate\Http\JsonResponse
    {
        $categories = CourseCategory::all();
        $categoryCount = $categories->count();
        return response()->json([
            'data' => [
                "categories" => $categories,
                "totalCount" => $categoryCount
            ]

        ], 200);
    }


    public function show($id): \Illuminate\Http\JsonResponse
    {
        $category = CourseCategory::findOrFail($id);

        return response()->json([
            'data' => [
                'category' => $category
            ]

        ], 200);
    }
}
