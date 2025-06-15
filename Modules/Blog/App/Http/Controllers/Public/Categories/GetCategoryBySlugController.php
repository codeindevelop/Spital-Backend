<?php

namespace Modules\Blog\App\Http\Controllers\Public\Categories;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Modules\Blog\App\Services\Categories\GetCategoryBySlugService;
use Symfony\Component\HttpFoundation\Response;

class GetCategoryBySlugController extends Controller
{
    protected GetCategoryBySlugService $categoryService;

    public function __construct(GetCategoryBySlugService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Retrieve a category by its slug.
     *
     * @param  string  $slug
     * @return JsonResponse
     */
    public function __invoke(string $slug): JsonResponse
    {
        $validator = Validator::make(['slug' => $slug], [
            'slug' => ['required', 'string', 'exists:post_categories,slug'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 404);
        }

        $category = $this->categoryService->execute($slug);

        return response()->json([
            'data' => [
                'category' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'parent' => $category->parent ? $category->parent->name : null,
                    'seo' => $category->seo,
                    'schema' => $category->schema,
                ],
            ],
        ], Response::HTTP_OK);
    }
}
