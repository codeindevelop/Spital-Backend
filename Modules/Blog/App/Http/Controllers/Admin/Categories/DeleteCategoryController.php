<?php

namespace Modules\Blog\App\Http\Controllers\Admin\Categories;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Modules\Blog\App\Services\Categories\DeleteCategoryService;
use Symfony\Component\HttpFoundation\Response;

class DeleteCategoryController extends Controller
{
    protected DeleteCategoryService $categoryService;

    public function __construct(DeleteCategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Delete a category by its ID.
     *
     * @param  string  $id
     * @return JsonResponse
     */
    public function __invoke(string $id): JsonResponse
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
            $this->categoryService->execute($id);
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
