<?php

namespace Modules\Blog\App\Http\Controllers\Admin\Comments;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Modules\Blog\App\Services\Comments\DeleteCommentService;
use Symfony\Component\HttpFoundation\Response;

class DeleteCommentController extends Controller
{
    protected DeleteCommentService $commentService;

    public function __construct(DeleteCommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    /**
     * Delete a comment by its ID.
     *
     * @param  string  $id
     * @return JsonResponse
     */
    public function __invoke($id): JsonResponse
    {
        if (!Auth::user()->can('post:comment:delete')) {
            return response()->json(['error' => 'شما اجازه حذف کامنت را ندارید.'], 403);
        }

        $validator = Validator::make(['id' => $id], [
            'id' => ['required', 'uuid', 'exists:post_comments,id'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        try {
            $this->commentService->execute($id);
            return response()->json([
                'data' => [
                    'message' => 'کامنت با موفقیت حذف شد.',
                ],
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Failed to delete comment: '.$e->getMessage(), [
                'id' => $id,
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'خطایی در حذف کامنت رخ داد.'], 500);
        }
    }
}
