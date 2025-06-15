<?php

namespace Modules\Blog\App\Http\Controllers\Admin\Comments;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Modules\Blog\App\Services\Comments\CreateCommentService;
use Symfony\Component\HttpFoundation\Response;

class CreateCommentController extends Controller
{
    protected CreateCommentService $commentService;

    public function __construct(CreateCommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    /**
     * Create a new comment for a post.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        if (!Auth::user()->can('comment:create')) {
            return response()->json(['error' => 'شما اجازه ایجاد کامنت را ندارید.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'post_id' => ['required', 'uuid', 'exists:posts,id'],
            'parent_id' => ['nullable', 'uuid', 'exists:post_comments,id'],
            'author_name' => ['nullable', 'string', 'max:255'],
            'author_email' => ['nullable', 'email', 'max:255'],
            'author_url' => ['nullable', 'url', 'max:255'],
            'content' => ['required', 'string'],
            'status' => ['nullable', 'in:pending,approved,rejected'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        try {
            $comment = $this->commentService->execute($request->all(), Auth::user());
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
                        'created_at' => $comment->created_at,
                    ],
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
}
