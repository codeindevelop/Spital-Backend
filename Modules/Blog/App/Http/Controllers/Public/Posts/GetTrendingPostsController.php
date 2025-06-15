<?php

namespace Modules\Blog\App\Http\Controllers\Public\Posts;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Symfony\Component\HttpFoundation\Response;

class GetTrendingPostsController extends Controller
{
    protected GetTrendingPostsService $postService;

    public function __construct(GetTrendingPostsService $postService)
    {
        $this->postService = $postService;
    }

    /**
     * Retrieve trending posts with pagination.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'per_page' => ['integer', 'min:1', 'max:100'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $perPage = $request->input('per_page', 15);
        $trendingPosts = $this->postService->execute($perPage);

        return response()->json([
            'data' => [
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
                    'total' => $trendingPosts->total(),
                    'per_page' => $trendingPosts->perPage(),
                    'current_page' => $trendingPosts->currentPage(),
                    'last_page' => $trendingPosts->lastPage(),
                ],
            ],
        ], Response::HTTP_OK);
    }
}
