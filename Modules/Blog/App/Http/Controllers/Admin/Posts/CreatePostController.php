<?php

namespace Modules\Blog\App\Http\Controllers\Admin\Posts;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Modules\Blog\App\Services\Posts\CreatePostService;
use Symfony\Component\HttpFoundation\Response;

class CreatePostController extends Controller
{
    protected CreatePostService $postService;

    public function __construct(CreatePostService $postService)
    {
        $this->postService = $postService;
    }

    /**
     * Create a new post.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        if (!Auth::user()->can('post:create')) {
            return response()->json(['error' => 'شما اجازه ایجاد پست را ندارید.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255', 'unique:posts,title'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:posts,slug'],
            'content' => ['nullable', 'string'],
            'featured_image' => ['nullable', 'string', 'max:255'],
            'comment_status' => ['nullable', 'boolean'],
            'status' => ['nullable', 'in:draft,published,archived'],
            'visibility' => ['nullable', 'in:public,private,unlisted'],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'published_at' => ['nullable', 'date'],
            'is_active' => ['nullable', 'boolean'],
            'category_id' => ['nullable', 'uuid', 'exists:post_categories,id'],
            'seo' => ['nullable', 'array'],
            'seo.meta_title' => ['nullable', 'string', 'max:255'],
            'seo.meta_keywords' => ['nullable', 'string', 'max:255'],
            'seo.meta_description' => ['nullable', 'string'],
            'schema' => ['nullable', 'array'],
            'schema.type' => [
                'required_with:schema', 'string',
                'in:Article,FAQPage,CollectionPage,BreadcrumbList',
            ],
            'schema.title' => ['nullable', 'string', 'max:255'],
            'schema.slug' => ['nullable', 'string', 'max:255', 'unique:post_schemas,slug'],
            'schema.content' => ['nullable', 'string'],
            'schema.data' => ['nullable', 'array'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        try {
            $post = $this->postService->execute($request->all(), Auth::id());
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
                    'message' => 'پست با موفقیت ایجاد شد.',
                ],
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            Log::error('Failed to create post: '.$e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'خطایی در ایجاد پست رخ داد.'], 500);
        }
    }
}
