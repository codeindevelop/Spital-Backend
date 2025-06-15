<?php

namespace Modules\Blog\App\Http\Controllers\Public\Posts;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Blog\App\Services\Posts\GetAllPostsService;
use Modules\Blog\App\Services\Posts\GetTrendingPostsService;
use Symfony\Component\HttpFoundation\Response;

class GetAllPostsController extends Controller
{
    protected GetAllPostsService $postService;
    protected GetTrendingPostsService $trendingPostsService;

    public function __construct(
        GetAllPostsService $postService,
        GetTrendingPostsService $trendingPostsService
    ) {
        $this->postService = $postService;
        $this->trendingPostsService = $trendingPostsService;
    }

    /**
     * Retrieve all public published posts with pagination and optional filters.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'per_page' => ['integer', 'min:1', 'max:100'],
            'search' => ['nullable', 'string', 'max:255'],
            'category_id' => ['nullable', 'uuid', 'exists:post_categories,id'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $posts = $this->postService->execute(
            $request->input('per_page', 15),
            'published',
            'public',
            $request->input('search'),
            $request->input('category_id')
        );

        $trendingPosts = $this->trendingPostsService->execute(5);

        return response()->json([
            'data' => [
                'posts' => $posts,
                'trending_posts' => $trendingPosts->map(function ($trendingPost) {
                    return [
                        'id' => $trendingPost->post->id,
                        'title' => $trendingPost->post->title,
                        'slug' => $trendingPost->post->slug,
                        'summary' => $trendingPost->post->summary,
                        'views_count' => $trendingPost->views_count,
                    ];
                }),
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
