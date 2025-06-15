<?php

namespace Modules\Blog\App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Modules\Settings\App\Services\Blog\PostQuestionService;
use Modules\Settings\App\Services\Blog\PostService;
use Modules\Settings\App\Services\Blog\PostCategoryService;
use Modules\Settings\App\Services\Blog\PostCommentService;
use Symfony\Component\HttpFoundation\Response;

class UserPostController extends Controller
{
    protected PostService $postService;
    protected PostCategoryService $categoryService;
    protected PostCommentService $commentService;
    protected PostQuestionService $questionService;

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


    public function createQuestion(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'لطفاً ابتدا وارد شوید.'], 401);
        }

        $validator = Validator::make($request->all(), [
            'post_id' => ['required', 'uuid', 'exists:posts,id'],
            'content' => ['required', 'string'],
            'parent_id' => ['nullable', 'uuid', 'exists:post_questions,id'],
            'author_name' => ['nullable', 'string', 'max:255'],
            'author_email' => ['nullable', 'email', 'max:255'],
            'author_url' => ['nullable', 'url', 'max:255'],
            'status' => ['nullable', 'in:pending,approved,spam'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        try {
            $question = $this->questionService->createQuestion($request->all(), $user);
            return response()->json([
                'data' => [
                    'question' => $question,
                    'message' => 'سوال با موفقیت ثبت شد.',
                ],
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            Log::error('Failed to create question: '.$e->getMessage(), [
                'request' => $request->all(),
                'user_id' => $user->id,
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'خطایی در ثبت سوال رخ داد.'], 500);
        }
    }

    public function bookmarkPost(Request $request, string $postId): JsonResponse
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'لطفاً ابتدا وارد شوید.'], 401);
        }

        try {
            $this->postService->bookmarkPost($postId, $user->id);
            return response()->json([
                'data' => ['message' => 'پست با موفقیت بوکمارک شد.'],
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function unbookmarkPost(Request $request, string $postId): JsonResponse
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'لطفاً ابتدا وارد شوید.'], 401);
        }

        try {
            $this->postService->unbookmarkPost($postId, $user->id);
            return response()->json([
                'data' => ['message' => 'بوکمارک پست با موفقیت حذف شد.'],
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }


    public function likePost(Request $request, string $postId): JsonResponse
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'لطفاً ابتدا وارد شوید.'], 401);
        }

        try {
            $this->postService->likePost($postId, $user->id);
            return response()->json([
                'data' => ['message' => 'پست با موفقیت لایک شد.'],
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function unlikePost(Request $request, string $postId): JsonResponse
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'لطفاً ابتدا وارد شوید.'], 401);
        }

        try {
            $this->postService->unlikePost($postId, $user->id);
            return response()->json([
                'data' => ['message' => 'لایک پست با موفقیت حذف شد.'],
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}
