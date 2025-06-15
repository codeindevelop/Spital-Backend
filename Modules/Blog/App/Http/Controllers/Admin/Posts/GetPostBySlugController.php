<?php

namespace Modules\Blog\App\Http\Controllers\Admin\Posts;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\Blog\App\Services\Posts\GetPostBySlugService;
use Symfony\Component\HttpFoundation\Response;

class GetPostBySlugController extends Controller
{
    protected GetPostBySlugService $postService;

    public function __construct(GetPostBySlugService $postService)
    {
        $this->postService = $postService;
    }

    /**
     * Retrieve a post by its slug.
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

        return response()->json([
            'data' => [
                'post' => [
                    'id' => $post->id,
                    'title' => $post->title,
                    'slug' => $post->slug,
                    'category' => $post->category ? [
                        'id' => $post->category->id,
                        'name' => $post->category->name,
                        'slug' => $post->category->slug,
                    ] : ['name' => 'دسته‌بندی نشده'],
                    'content' => $post->content,
                    'featured_image' => $post->featured_image,
                    'comment_status' => $post->comment_status,
                    'status' => $post->status,
                    'visibility' => $post->visibility,
                    'published_at' => $post->published_at,
                    'seo' => $post->seo,
                    'schema' => $post->schema,
                ],
            ],
        ], Response::HTTP_OK);
    }
}
