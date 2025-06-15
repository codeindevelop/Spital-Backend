<?php

namespace Modules\Blog\App\Http\Controllers\Admin\Posts;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\Blog\App\Services\Posts\GetPostByIdService;
use Symfony\Component\HttpFoundation\Response;

class GetPostByIdController extends Controller
{
    protected GetPostByIdService $postService;

    public function __construct(GetPostByIdService $postService)
    {
        $this->postService = $postService;
    }

    /**
     * Retrieve a post by its ID.
     *
     * @param  string  $id
     * @return JsonResponse
     */
    public function __invoke(string $id): JsonResponse
    {
        if (!Auth::user()->can('post:view')) {
            return response()->json(['error' => 'شما اجازه مشاهده پست‌ها را ندارید.'], 403);
        }

        $validator = Validator::make(['id' => $id], [
            'id' => ['required', 'uuid', 'exists:posts,id'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $post = $this->postService->execute($id);

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
