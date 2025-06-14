<?php

namespace Modules\Blog\App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Blog\App\Services\PostService;
use Modules\Blog\App\Services\PostCategoryService;
use Modules\Blog\App\Services\PostCommentService;
use Symfony\Component\HttpFoundation\Response;

class UserPostController extends Controller
{
    protected PostService $postService;
    protected PostCategoryService $categoryService;
    protected PostCommentService $commentService;

    public function __construct(
        PostService $postService,
        PostCategoryService $categoryService,
        PostCommentService $commentService
    ) {
        $this->postService = $postService;
        $this->categoryService = $categoryService;
        $this->commentService = $commentService;
    }


    public function createComment(Request $request): JsonResponse
    {
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

        $post = $this->postService->getPostById($request->input('post_id'));
        if (!$post->comment_status) {
            return response()->json(['error' => 'امکان ثبت کامنت برای این پست غیرفعال است.'], 403);
        }

        try {
            $comment = $this->commentService->createComment($request->all(), auth()->id() ?? null);
            return response()->json([
                'data' => [
                    'comment' => $comment,
                    'message' => 'کامنت با موفقیت ثبت شد و در انتظار تأیید است.',
                ],
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['error' => 'خطایی در ثبت کامنت رخ داد.'], 500);
        }
    }
}
