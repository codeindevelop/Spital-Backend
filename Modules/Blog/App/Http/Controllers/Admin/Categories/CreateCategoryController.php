<?php

namespace Modules\Blog\App\Http\Controllers\Admin\Categories;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Modules\Blog\App\Services\Categories\CreateCategoryService;
use Symfony\Component\HttpFoundation\Response;

class CreateCategoryController extends Controller
{
    protected CreateCategoryService $categoryService;

    public function __construct(CreateCategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Create a new category.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        if (!Auth::user()->can('post:category:create')) {
            return response()->json(['error' => 'شما اجازه ایجاد دسته‌بندی را ندارید.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'unique:post_categories,name'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:post_categories,slug'],
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
            'schema.slug' => ['nullable', 'string', 'max:255', 'unique:category_schemas,slug'],
            'schema.content' => ['nullable', 'string'],
            'schema.data' => ['nullable', 'array'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        try {
            $category = $this->categoryService->execute($request->all(), Auth::id());
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
                    'message' => 'دسته‌بندی با موفقیت ایجاد شد.',
                ],
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            Log::error('Failed to create category: '.$e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'خطایی در ایجاد دسته‌بندی رخ داد.'], 500);
        }
    }
}
