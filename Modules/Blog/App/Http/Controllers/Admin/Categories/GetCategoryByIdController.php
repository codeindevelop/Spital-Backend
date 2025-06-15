<?php

namespace Modules\Blog\App\Http\Controllers\Admin\Categories;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\Blog\App\Services\Categories\GetCategoryByIdService;
use Symfony\Component\HttpFoundation\Response;

class GetCategoryByIdController extends Controller
{
    protected GetCategoryByIdService $categoryService;

    public function __construct(GetCategoryByIdService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Retrieve a category by its ID.
     *
     * @param  string  $id
     * @return JsonResponse
     */
    public function __invoke(string $id): JsonResponse
    {
        if (!Auth::user()->can('post:category:view')) {
            return response()->json(['error' => 'شما اجازه مشاهده دسته‌بندی‌ها را ندارید.'], 403);
        }

        $validator = Validator::make(['id' => $id], [
            'id' => ['required', 'uuid', 'exists:post_categories,id'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $category = $this->categoryService->execute($id);

        return response()->json([
            'data' => [
                'category' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'parent' => $category->parent ? [
                        'id' => $category->parent->id,
                        'name' => $category->parent->name,
                        'slug' => $category->parent->slug,
                    ] : null,
                    'description' => $category->description,
                    'is_active' => $category->is_active,
                    'seo' => $category->seo,
                    'schema' => $category->schema,
                ],
            ],
        ], Response::HTTP_OK);
    }
}
