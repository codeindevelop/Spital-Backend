<?php

namespace Modules\Blog\App\Http\Controllers\Admin\Comments;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Modules\Blog\App\Services\Comments\UpdateCommentService;
use Symfony\Component\HttpFoundation\Response;

class UpdateCommentController extends Controller
{
    protected UpdateCommentService $commentService;

    public function __construct(UpdateCommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    /**
     * Update a comment by its ID.
     *
     * @param  Request  $request
     * @param  string  $id
     * @return JsonResponse
     */
    public function __invoke(Request $request, $id): JsonResponse
    {
        if (!Auth::user()->can('post:comment:edit')) {
            return response()->json(['error' => 'شما اجازه ویرایش کامنت را ندارید.'], 403);
        }

        $validator = Validator::make(array_merge($request->all(), ['id' => $id]), [
            'id' => ['required', 'uuid', 'exists:post_comments,id'],
            'content' => ['nullable', 'string'],
            'status' => ['nullable', 'in:pending,approved,rejected'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        try {
            $comment = $this->commentService->execute($id, $request->all(), Auth::id());
            return response()->json([
                'data' => [
                    'comment' => [
                        'id' => $comment->id,
                        'post_id' => $comment->post_id,
                        'parent_id' => $comment->parent_id,
                        'author_name' => $comment->author_name,
                        'author_email' => $comment->author_email,
                        'author_url' => $comment->author_url,
                        'content' => $comment->content,
                        'status' => $comment->status,
                        'updated_at' => $comment->updated_at,
                    ],
                    'message' => 'کامنت با موفقیت به‌روزرسانی شد.',
                ],
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Failed to update comment: '.$e->getMessage(), [
                'id' => $id,
                'request' => $request->all(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'خطایی در به‌روزرسانی کامنت رخ داد.'], 500);
        }
    }
}
