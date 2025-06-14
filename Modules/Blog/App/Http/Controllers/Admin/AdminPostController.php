<?php

namespace Modules\Blog\App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\Blog\App\Services\PostService;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class AdminPostController extends Controller
{
    protected PostService $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function getAllPosts(Request $request): JsonResponse
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

        $posts = $this->postService->getAllPosts(
            $request->input('per_page', 15),
            $request->input('status'),
            $request->input('visibility'),
            $request->input('search'),
            $request->input('category_id')
        );

        return response()->json([
            'data' => [
                'posts' => $posts->map(function ($post) {
                    return [
                        'id' => $post->id,
                        'title' => $post->title,
                        'slug' => $post->slug,
                        'category' => $post->category ? [
                            'id' => $post->category->id,
                            'name' => $post->category->name,
                            'slug' => $post->category->slug,
                        ] : ['name' => 'دسته‌بندی نشده'],
                        'status' => $post->status,
                        'visibility' => $post->visibility,
                        'published_at' => $post->published_at,
                        'seo' => $post->seo,
                        'schema' => $post->schema,
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

    public function getPostById($id): JsonResponse
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

        $post = $this->postService->getPostById($id);

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

    public function getPostBySlug($slug): JsonResponse
    {
        $validator = Validator::make(['slug' => $slug], [
            'slug' => ['required', 'string', 'exists:posts,slug'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 404);
        }

        $post = $this->postService->getPostBySlug($slug);

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

    public function createPost(Request $request): JsonResponse
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
            $post = $this->postService->createPost($request->all(), Auth::id());
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
            Log::error('Failed to create post: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'خطایی در ایجاد پست رخ داد.'], 500);
        }
    }

    public function updatePost(Request $request, $id): JsonResponse
    {
        if (!Auth::user()->can('post:edit')) {
            return response()->json(['error' => 'شما اجازه ویرایش پست را ندارید.'], 403);
        }

        $validator = Validator::make(array_merge($request->all(), ['id' => $id]), [
            'id' => ['required', 'uuid', 'exists:posts,id'],
            'title' => ['string', 'max:255', 'unique:posts,title,' . $id],
            'slug' => ['nullable', 'string', 'max:255', 'unique:posts,slug,' . $id],
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
            'schema.slug' => ['nullable', 'string', 'max:255'],
            'schema.content' => ['nullable', 'string'],
            'schema.data' => ['nullable', 'array'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        try {
            $post = $this->postService->updatePost($id, $request->all(), Auth::id());
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
                    'message' => 'پست با موفقیت به‌روزرسانی شد.',
                ],
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Failed to update post: ' . $e->getMessage(), [
                'id' => $id,
                'request' => $request->all(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'خطایی در به‌روزرسانی پست رخ داد.'], 500);
        }
    }

    public function deletePost($id): JsonResponse
    {
        if (!Auth::user()->can('post:delete')) {
            return response()->json(['error' => 'شما اجازه حذف پست را ندارید.'], 403);
        }

        $validator = Validator::make(['id' => $id], [
            'id' => ['required', 'uuid', 'exists:posts,id'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        try {
            $this->postService->deletePost($id);
            return response()->json([
                'data' => [
                    'message' => 'پست با موفقیت حذف شد.',
                ],
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Failed to delete post: ' . $e->getMessage(), [
                'id' => $id,
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'خطایی در حذف پست رخ داد.'], 500);
        }
    }

    public function generateDemoPosts(Request $request): JsonResponse
    {
        if (!Auth::user()->can('post:create')) {
            return response()->json(['error' => 'شما اجازه ایجاد پست را ندارید.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'count' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        try {
            $count = $request->input('count');
            Artisan::call('db:seed', [
                '--class' => 'PostSeeder',
                '--count' => $count,
            ]);

            return response()->json([
                'data' => [
                    'message' => "ایجاد $count پست دمو با موفقیت انجام شد.",
                ],
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Failed to generate demo posts: ' . $e->getMessage(), [
                'count' => $request->input('count'),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'خطایی در ایجاد پست‌های دمو رخ داد.'], 500);
        }
    }
}
