<?php

namespace Modules\Blog\App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use Modules\Blog\App\Services\PostCommentService;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class AdminPostCommentController extends Controller
{
    protected PostCommentService $commentService;

    public function __construct(PostCommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function getCommentsByPostId(Request $request, string $postId): JsonResponse
    {
        if (!Auth::user()->can('post:comment:view')) {
            return response()->json(['error' => 'شما اجازه مشاهده کامنت‌ها را ندارید.'], 403);
        }

        $validator = Validator::make(array_merge($request->all(), ['post_id' => $postId]), [
            'post_id' => ['required', 'uuid', 'exists:posts,id'],
            'per_page' => ['integer', 'min:1', 'max:100'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $comments = $this->commentService->getCommentsByPostId($postId, $request->input('per_page', 15));

        return response()->json([
            'data' => [
                'comments' => $comments,
            ],
        ], Response::HTTP_OK);
    }

    public function createComment(Request $request): JsonResponse
    {
//        if (!Auth::user()->can('post:comment:create')) {
//            return response()->json(['error' => 'شما اجازه ایجاد کامنت را ندارید.'], 403);
//        }

        $validator = Validator::make($request->all(), [
            'post_id' => ['required', 'uuid', 'exists:posts,id'],
            'content' => ['required', 'string'],
            'parent_id' => ['nullable', 'uuid', 'exists:post_comments,id'],
            'status' => ['nullable', 'in:pending,approved,spam'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }


        try {
            $comment = $this->commentService->createComment($request->all(), Auth::user());
            return response()->json([
                'data' => [
                    'comment' => $comment,
                    'message' => 'کامنت با موفقیت ایجاد شد.',
                ],
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            Log::error('Failed to create comment: '.$e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'خطایی در ایجاد کامنت رخ داد.'], 500);
        }
    }

    public function updateComment(Request $request, string $id): JsonResponse
    {
        if (!Auth::user()->can('post:comment:edit')) {
            return response()->json(['error' => 'شما اجازه ویرایش کامنت را ندارید.'], 403);
        }

        $validator = Validator::make(array_merge($request->all(), ['id' => $id]), [
            'id' => ['required', 'uuid', 'exists:post_comments,id'],
            'content' => ['nullable', 'string'],
            'status' => ['nullable', 'in:pending,approved,spam'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        try {
            $comment = $this->commentService->updateComment($id, $request->all(), Auth::id());
            return response()->json([
                'data' => [
                    'comment' => $comment,
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

    public function deleteComment(string $id): JsonResponse
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
            $this->commentService->deleteComment($id);
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
