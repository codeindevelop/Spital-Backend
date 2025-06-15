<?php

namespace Modules\Blog\App\Http\Controllers\Admin\Comments;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\Blog\App\Http\Resources\CommentResource;
use Modules\Blog\App\Services\Comments\GetCommentsByPostIdService;
use Symfony\Component\HttpFoundation\Response;

class GetCommentsByPostIdController extends Controller
{
    protected GetCommentsByPostIdService $commentService;

    public function __construct(GetCommentsByPostIdService $commentService)
    {
        $this->commentService = $commentService;
    }

    /**
     * Retrieve comments for a post by its ID with pagination.
     *
     * @param  string  $postId
     * @return JsonResponse
     */
    public function __invoke(string $postId): JsonResponse
    {
        if (!Auth::user()->can('post:comment:view')) {
            return response()->json(['error' => 'شما اجازه مشاهده کامنت‌ها را ندارید.'], 403);
        }

        $validator = Validator::make(['post_id' => $postId], [
            'post_id' => ['required', 'uuid', 'exists:posts,id'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $comments = $this->commentService->execute($postId, 15);

        return response()->json([
            'data' => [
                'comments' => CommentResource::collection($comments),
                'pagination' => [
                    'total' => $comments->total(),
                    'per_page' => $comments->perPage(),
                    'current_page' => $comments->currentPage(),
                    'last_page' => $comments->lastPage(),
                ],
            ],
        ], Response::HTTP_OK);
    }
}
