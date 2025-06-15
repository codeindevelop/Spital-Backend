<?php

namespace Modules\Blog\App\Http\Controllers\Public\Posts;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\Blog\App\Services\Posts\GetPostBySlugService;
use Modules\Blog\App\Services\Posts\RecordViewService;
use Symfony\Component\HttpFoundation\Response;

class GetPostBySlugController extends Controller
{
    protected GetPostBySlugService $postService;
    protected RecordViewService $recordViewService;

    public function __construct(
        GetPostBySlugService $postService,
        RecordViewService $recordViewService
    ) {
        $this->postService = $postService;
        $this->recordViewService = $recordViewService;
    }

    /**
     * Retrieve a post by its slug and record a view.
     *
     * @param  string  $slug
     * @return JsonResponse
     */
    public function __invoke(string $slug): JsonResponse
    {
        $validator = Validator::make(['slug' => $slug], [
            'slug' => ['required', 'string', 'exists:posts,slug'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 404);
        }

        $post = $this->postService->execute($slug);

        if ($post->visibility === 'private' && !Auth::check()) {
            return response()->json(['error' => 'این پست خصوصی است.'], 403);
        }

        $this->recordViewService->execute($post->id, Auth::id(), request()->ip());

        return response()->json([
            'data' => [
                'post' => [
                    'id' => $post->id,
                    'title' => $post->title,
                    'slug' => $post->slug,
                    'category' => $post->category ? $post->category->name : 'دسته‌بندی نشده',
                    'content' => $post->content,
                    'featured_image' => $post->featured_image,
                    'comment_status' => $post->comment_status,
                    'published_at' => $post->published_at,
                    'seo' => $post->seo,
                    'schema' => $post->schema,
                    'comments_count' => $post->comments->count(),
                    'comments' => $post->comments,
                ],
            ],
        ], Response::HTTP_OK);
    }
}
