<?php

namespace Modules\Blog\App\Http\Controllers\Admin\Posts;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Modules\Blog\App\Services\Posts\DeletePostService;
use Symfony\Component\HttpFoundation\Response;

class DeletePostController extends Controller
{
    protected DeletePostService $postService;

    public function __construct(DeletePostService $postService)
    {
        $this->postService = $postService;
    }

    /**
     * Delete a post by its ID.
     *
     * @param  string  $id
     * @return JsonResponse
     */
    public function __invoke($id): JsonResponse
    {
        if (!Auth::user()->can('post:delete')) {
            return response()->json(['error' => 'شما اجازه حذف پست را ندارید.'], 403);
        }

        $validator = Validator::make(['id' => $id], [
            'id' => ['required', 'uuid', 'exists:posts,id'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        try {
            $this->postService->execute($id);
            return response()->json([
                'data' => [
                    'message' => 'پست با موفقیت حذف شد.',
                ],
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Failed to delete post: '.$e->getMessage(), [
                'id' => $id,
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'خطایی در حذف پست رخ داد.'], 500);
        }
    }
}
