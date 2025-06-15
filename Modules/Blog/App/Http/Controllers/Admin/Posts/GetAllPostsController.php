<?php

namespace Modules\Blog\App\Http\Controllers\Admin\Posts;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\Blog\App\Http\Resources\PostResource;
use Modules\Blog\App\Services\Posts\GetAllPostsService;
use Symfony\Component\HttpFoundation\Response;

class GetAllPostsController extends Controller
{
    protected GetAllPostsService $postService;

    public function __construct(GetAllPostsService $postService)
    {
        $this->postService = $postService;
    }

    /**
     * Retrieve all posts with pagination and optional filters.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        if (!Auth::user()->can('post:view')) {
            return response()->json(['error' => 'شما اجازه مشاهده پست‌ها را ندارید.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'per_page' => ['integer', 'min:1', 'max:100'],
            'status' => ['nullable', 'in:draft,published,archived'],
            'visibility' => ['nullable', 'in:public,private,unlisted'],
            'search' => ['nullable', 'string', 'max:255'],
            'category_id' => ['nullable', 'uuid', 'exists:post_categories,id'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $posts = $this->postService->execute(
            $request->input('per_page', 15),
            $request->input('status'),
            $request->input('visibility'),
            $request->input('search'),
            $request->input('category_id')
        );

        return response()->json([
            'data' => [
                'posts' => PostResource::collection($posts),
                'pagination' => [
                    'total' => $posts->total(),
                    'per_page' => $posts->perPage(),
                    'current_page' => $posts->currentPage(),
                    'last_page' => $posts->lastPage(),
                ],
            ],
        ], Response::HTTP_OK);
    }
}
