<?php

namespace Modules\Blog\App\Http\Controllers\Admin\Posts;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Modules\Blog\App\Services\Posts\UpdatePostService;
use Symfony\Component\HttpFoundation\Response;

class UpdatePostController extends Controller
{
    protected UpdatePostService $postService;

    public function __construct(UpdatePostService $postService)
    {
        $this->postService = $postService;
    }

    /**
     * Update a post by its ID.
     *
     * @param  Request  $request
     * @param  string  $id
     * @return JsonResponse
     */
    public function __invoke(Request $request, $id): JsonResponse
    {
        if (!Auth::user()->can('post:edit')) {
            return response()->json(['error' => 'شما اجازه ویرایش پست را ندارید.'], 403);
        }

        $validator = Validator::make(array_merge($request->all(), ['id' => $id]), [
            'id' => ['required', 'uuid', 'exists:posts,id'],
            'title' => ['string', 'max:255', 'unique:posts,title,'.$id],
            'slug' => ['nullable', 'string', 'max:255', 'unique:posts,slug,'.$id],
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
            $post = $this->postService->execute($id, $request->all(), Auth::id());
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
            Log::error('Failed to update post: '.$e->getMessage(), [
                'id' => $id,
                'request' => $request->all(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'خطایی در به‌روزرسانی پست رخ داد.'], 500);
        }
    }
}
