<?php

namespace Modules\Blog\App\Http\Controllers\Public\Comments;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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
     * @param  Request  $request
     * @return JsonResponse
     */
    public function __invoke(string $postId, Request $request): JsonResponse
    {
        $validator = Validator::make(array_merge($request->all(), ['post_id' => $postId]), [
            'post_id' => ['required', 'uuid', 'exists:posts,id'],
            'per_page' => ['integer', 'min:1', 'max:100'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $comments = $this->commentService->execute($postId, $request->input('per_page', 15));

        return response()->json([
            'data' => [
                'comments' => $comments,
            ],
        ], Response::HTTP_OK);
    }
}
