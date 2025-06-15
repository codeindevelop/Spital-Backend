<?php

namespace Modules\Blog\App\Http\Controllers\User\Comments;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\Blog\App\Http\Resources\CommentResource;
use Modules\Blog\App\Services\Comments\CreateCommentService;
use Modules\Blog\App\Services\Posts\GetPostByIdService;
use Symfony\Component\HttpFoundation\Response;

class CreateCommentController extends Controller
{
    protected CreateCommentService $commentService;
    protected GetPostByIdService $postService;

    public function __construct(CreateCommentService $commentService, GetPostByIdService $postService)
    {
        $this->commentService = $commentService;
        $this->postService = $postService;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'لطفاً ابتدا وارد شوید.'], 401);
        }

        $validator = Validator::make($request->all(), [
            'post_id' => ['required', 'uuid', 'exists:posts,id'],
            'content' => ['required', 'string'],
            'parent_id' => ['nullable', 'uuid', 'exists:post_comments,id'],
            'author_email' => ['nullable', 'email'],
            'author_url' => ['nullable', 'url'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $post = $this->postService->execute($request->input('post_id'));
        if (!$post->comment_status) {
            return response()->json(['error' => 'امکان ثبت کامنت برای این پست غیرفعال است.'], 403);
        }

        $comment = $this->commentService->execute($request->all(), $user);

        return response()->json([
            'data' => [
                'comment' => new CommentResource($comment),
                'message' => 'کامنت با موفقیت ثبت شد و در انتظار تأیید است.',
            ],
        ], Response::HTTP_CREATED);
    }
}
