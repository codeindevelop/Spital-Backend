<?php

namespace Modules\Blog\App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use Modules\Blog\App\Services\PostCategoryService;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class AdminPostCategoryController extends Controller
{
    protected PostCategoryService $categoryService;

    public function __construct(PostCategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function getAllCategories(Request $request): JsonResponse
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

        $categories = $this->categoryService->getAllCategories(
            $request->input('per_page', 15),
            $request->input('search')
        );

        return response()->json([
            'data' => [
                'categories' => $categories,
            ],
        ], Response::HTTP_OK);
    }

    public function getCategoryById(string $id): JsonResponse
    {
        if (!Auth::user()->can('post:category:view')) {
            return response()->json(['error' => 'شما اجازه مشاهده دسته‌بندی را ندارید.'], 403);
        }

        $validator = Validator::make(['id' => $id], [
            'id' => ['required', 'uuid', 'exists:post_categories,id'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $category = $this->categoryService->getCategoryById($id);

        return response()->json([
            'data' => [
                'category' => $category,
            ],
        ], Response::HTTP_OK);
    }

    public function createCategory(Request $request): JsonResponse
    {
        if (!Auth::user()->can('post:category:create')) {
            return response()->json(['error' => 'شما ایجاد دسته‌بندی را ندارید.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'unique:post_categories,name'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:post_categories,slug'],
            'description' => ['nullable', 'string'],
            'parent_id' => ['nullable', 'uuid', 'exists:post_categories,id'],
            'is_active' => ['nullable', 'boolean'],
            'seo' => ['nullable', 'array'],
            'seo.meta_title' => ['nullable', 'string', 'max:255'],
            'seo.meta_description' => ['nullable', 'string'],
            'schema' => ['nullable', 'array'],
            'schema.type' => ['required_with:schema', 'string', 'in:CollectionPage,BreadcrumbList'],
            'schema.title' => ['nullable', 'string', 'max:255'],
            'schema.slug' => ['nullable', 'string', 'max:255', 'unique:category_schemas,slug'],
            'schema.data' => ['nullable', 'array'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        try {
            $category = $this->categoryService->createCategory($request->all(), Auth::id());
            return response()->json([
                'data' => [
                    'category' => $category,
                    'message' => 'دسته‌بندی با موفقیت ایجاد شد.',
                ],
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            Log::error('Error creating category: '.$e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'خطایی در ایجاد دسته‌بندی رخ داد.'], 500);
        }
    }

    public function updateCategory(Request $request, string $id): JsonResponse
    {
        if (!Auth::user()->can('post:category:update')) {
            return response()->json(['error' => 'شما اجازه ویرایش دسته‌بندی را ندارید'], 403);
        }

        $validator = Validator::make(array_merge($request->all(), ['id' => $id]), [
            'id' => ['required', 'uuid', 'exists:post_categories,id'],
            'name' => ['string', 'max:255', 'unique:post_categories,name,'.$id],
            'slug' => ['nullable', 'string', 'max:255', 'unique:post_categories,slug,'.$id],
            'description' => ['nullable', 'string'],
            'parent_id' => ['nullable', 'uuid', 'exists:post_categories,id'],
            'is_active' => ['nullable', 'boolean'],
            'seo' => ['nullable', 'array'],
            'schema' => ['nullable', 'array'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        try {
            $category = $this->categoryService->updateCategory($id, $request->all(), Auth::id());
            return response()->json([
                'data' => [
                    'category' => $category,
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

    public function deleteCategory(string $id): JsonResponse
    {
        if (!Auth::user()->can('post:category:delete')) {
            return response()->json(['error' => 'شما اجازه حذف دسته‌بندی را ندارید.'], 403);
        }

        $validator = Validator::make(['id' => $id], [
            'id' => ['required', 'uuid', 'exists:post_categories,id'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        try {
            $this->categoryService->deleteCategory($id);
            return response()->json([
                'data' => [
                    'message' => 'دسته‌بندی با موفقیت حذف شد.',
                ],
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Failed to delete category: '.$e->getMessage(), [
                'id' => $id,
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'خطایی در حذف دسته‌بندی رخ داد.'], 500);
        }
    }
}
