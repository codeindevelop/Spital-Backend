<?php

namespace Modules\Blog\App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\Settings\App\Services\Blog\PostService;
use Modules\Settings\App\Services\Blog\PostCategoryService;
use Modules\Settings\App\Services\Blog\PostCommentService;
use Symfony\Component\HttpFoundation\Response;

class PublicPostController extends Controller
{
    protected PostService $postService;
    protected PostCategoryService $categoryService;
    protected PostCommentService $commentService;

    public function __construct(
        PostService $postService,
        PostCategoryService $categoryService,
        PostCommentService $commentService
    ) {
        $this->postService = $postService;
        $this->categoryService = $categoryService;
        $this->commentService = $commentService;
    }

    public function getAllPosts(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'per_page' => ['integer', 'min:1', 'max:100'],
            'search' => ['nullable', 'string', 'max:255'],
            'category_id' => ['nullable', 'uuid', 'exists:post_categories,id'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $posts = $this->postService->getAllPosts(
            $request->input('per_page', 15),
            'published',
            'public',
            $request->input('search'),
            $request->input('category_id')
        );

        $trendingPosts = $this->postService->getTrendingPosts(5); // ۵ پست پربازدید

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

    public function getPostByID(string $slug): JsonResponse
    {
        $validator = Validator::make(['slug' => $slug], [
            'slug' => ['required', 'string', 'exists:posts,slug'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 404);
        }

        $post = $this->postService->getPostBySlug($slug);

        if ($post->visibility === 'private' && !auth()->check()) {
            return response()->json(['error' => 'این پست خصوصی است.'], 403);
        }

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
                ],
            ],
        ], Response::HTTP_OK);
    }

    public function redirectShortLink(string $code): \Illuminate\Http\RedirectResponse
    {
        $post = $this->postService->getPostByShortLink(config('app.url').'/s/'.$code);
        if (!$post) {
            abort(404, 'پست یافت نشد.');
        }

        return redirect()->to("/posts/{$post->slug}");
    }

    public function getAllCategories(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'per_page' => ['integer', 'min:1', 'max:100'],
            'search' => ['nullable', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $categories = $this->categoryService->getAllCategories(
            $request->input('per_page', 15),
            $request->input('search')
        );

        return response()->json([
            'data' => [
                'categories' => $categories->map(function ($category) {
                    return [
                        'id' => $category->id,
                        'name' => $category->name,
                        'slug' => $category->slug,
                        'parent' => $category->parent ? $category->parent->name : null,
                        'seo' => $category->seo,
                        'schema' => $category->schema,
                    ];
                }),
                'pagination' => [
                    'total' => $categories->total(),
                    'per_page' => $categories->perPage(),
                    'current_page' => $categories->currentPage(),
                    'last_page' => $categories->lastPage(),
                ],
            ],
        ], Response::HTTP_OK);
    }

    public function getCategoryBySlug(string $slug): JsonResponse
    {
        $validator = Validator::make(['slug' => $slug], [
            'slug' => ['required', 'string', 'exists:post_categories,slug'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 404);
        }

        $category = $this->categoryService->getCategoryBySlug($slug);

        return response()->json([
            'data' => [
                'category' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'parent' => $category->parent ? $category->parent->name : null,
                    'seo' => $category->seo,
                    'schema' => $category->schema,
                ],
            ],
        ], Response::HTTP_OK);
    }

    public function getCommentsByPostId(string $postId, Request $request): JsonResponse
    {
        $validator = Validator::make(array_merge($request->all(), ['post_id' => $postId]), [
            'post_id' => ['required', 'uuid', 'exists:posts,id'],
            'per_page' => ['integer', 'min:1', 'max:100'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $comments = $this->commentService->getCommentsByPostId($postId, $request->input('per_page', 15));

        return response()->json([
            'data' => [
                'comments' => $comments,
            ],
        ], Response::HTTP_OK);
    }



    public function getPostBySlug(string $slug): JsonResponse
    {
        $post = $this->postService->getPostBySlug($slug);
        $this->postService->recordView($post->id, Auth::id(), request()->ip());

        return response()->json([
            'data' => [
                'post' => [
                    // ... سایر فیلدها
                    'comments_count' => $post->comments->count(),
                    'comments' => $post->comments,
                ],
            ],
        ], Response::HTTP_OK);
    }

    public function getTrendingPosts(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 15);
        $trendingPosts = $this->postService->getTrendingPosts($perPage);

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
