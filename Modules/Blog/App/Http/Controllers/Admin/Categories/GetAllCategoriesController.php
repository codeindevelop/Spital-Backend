<?php

namespace Modules\Blog\App\Http\Controllers\Admin\Categories;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\Blog\App\Http\Resources\CategoryResource;
use Modules\Blog\App\Services\Categories\GetAllCategoriesService;
use Symfony\Component\HttpFoundation\Response;

class GetAllCategoriesController extends Controller
{
    protected GetAllCategoriesService $categoryService;

    public function __construct(GetAllCategoriesService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Retrieve all categories with pagination and optional search.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        if (!Auth::user()->can('post:category:view')) {
            return response()->json(['error' => 'شما اجازه مشاهده دسته‌بندی‌ها را ندارید.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'per_page' => ['integer', 'min:1', 'max:100'],
            'search' => ['nullable', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $categories = $this->categoryService->execute(
            $request->input('per_page', 15),
            $request->input('search')
        );

        return response()->json([
            'data' => [
                'categories' => CategoryResource::collection($categories),
                'pagination' => [
                    'total' => $categories->total(),
                    'per_page' => $categories->perPage(),
                    'current_page' => $categories->currentPage(),
                    'last_page' => $categories->lastPage(),
                ],
            ],
        ], Response::HTTP_OK);
    }
}
