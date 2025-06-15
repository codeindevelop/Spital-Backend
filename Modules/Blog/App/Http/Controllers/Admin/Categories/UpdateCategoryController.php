<?php

namespace Modules\Blog\App\Http\Controllers\Admin\Categories;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Modules\Blog\App\Services\Categories\UpdateCategoryService;
use Symfony\Component\HttpFoundation\Response;

class UpdateCategoryController extends Controller
{
    protected UpdateCategoryService $categoryService;

    public function __construct(UpdateCategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Update a category by its ID.
     *
     * @param  Request  $request
     * @param  string  $id
     * @return JsonResponse
     */
    public function __invoke(Request $request, $id): JsonResponse
    {
        if (!Auth::user()->can('post:category:update')) {
            return response()->json(['error' => 'شما اجازه ویرایش دسته‌بندی را ندارید.'], 403);
        }

        $validator = Validator::make(array_merge($request->all(), ['id' => $id]), [
            'id' => ['required', 'uuid', 'exists:post_categories,id'],
            'name' => ['string', 'max:255', 'unique:post_categories,name,'.$id],
            'slug' => ['nullable', 'string', 'max:255', 'unique:post_categories,slug,'.$id],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'parent_id' => ['nullable', 'uuid', 'exists:post_categories,id'],
            'seo' => ['nullable', 'array'],
            'seo.meta_title' => ['nullable', 'string', 'max:255'],
            'seo.meta_keywords' => ['nullable', 'string', 'max:255'],
            'seo.meta_description' => ['nullable', 'string'],
            'schema' => ['nullable', 'array'],
            'schema.type' => [
                'required_with:schema', 'string',
                'in:CollectionPage,BreadcrumbList',
            ],
            'schema.title' => ['nullable', 'string', 'max:255'],
            'schema.slug' => ['nullable', 'string', 'max:255'],
            'schema.content' => ['nullable', 'string'],
            'schema.data' => ['nullable', 'array'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        try {
            $category = $this->categoryService->execute($id, $request->all(), Auth::id());
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
                    'message' => 'دسته‌بندی با موفقیت به‌روزرسانی شد.',
                ],
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Failed to update category: '.$e->getMessage(), [
                'id' => $id,
                'request' => $request->all(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'خطایی در به‌روزرسانی دسته‌بندی رخ داد.'], 500);
        }
    }
}
