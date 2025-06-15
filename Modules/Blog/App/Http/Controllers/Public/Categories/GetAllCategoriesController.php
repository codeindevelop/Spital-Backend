<?php

namespace Modules\Blog\App\Http\Controllers\Public\Categories;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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
                'categories' => $categories->map(function ($category) {
                    return [
                        'id' => $category->id,
                        'name' => $category->name,
                        'slug' => $category->slug,
                        'parent' => $category->parent ? $category->parent->name : null,
                        'seo' => $category->seo,
                        'schema' => $category->schema,
                    ];
                }),
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
