<?php

namespace Modules\Blog\App\Services\Posts;

use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Modules\Blog\App\Helpers\PostShortLinkHelper;
use Modules\Blog\App\Helpers\SlugHelper;
use Modules\Blog\App\Models\Post;
use Modules\Blog\App\Models\PostCategory;
use Modules\Blog\App\Repositories\Posts\CreatePostRepository;
use Modules\Blog\App\Repositories\Posts\CreatePostSeoRepository;
use Modules\Blog\App\Repositories\Posts\CreatePostSchemaRepository;
use Modules\Seo\App\Models\post\PostSchema;
use Modules\Settings\App\Services\System\Blog\BlogSettingService;
use Ramsey\Uuid\Uuid;

class CreatePostService
{
    protected CreatePostRepository $postRepository;
    protected CreatePostSeoRepository $seoRepository;
    protected CreatePostSchemaRepository $schemaRepository;
    protected BlogSettingService $blogSettingService;
    protected BuildSchemaJsonService $schemaJsonService;

    public function __construct(
        CreatePostRepository $postRepository,
        CreatePostSeoRepository $seoRepository,
        CreatePostSchemaRepository $schemaRepository,
        BlogSettingService $blogSettingService,
        BuildSchemaJsonService $schemaJsonService
    ) {
        $this->postRepository = $postRepository;
        $this->seoRepository = $seoRepository;
        $this->schemaRepository = $schemaRepository;
        $this->blogSettingService = $blogSettingService;
        $this->schemaJsonService = $schemaJsonService;
    }

    /**
     * Create a new post with SEO and schema data.
     *
     * @param  array  $data
     * @param  string  $userId
     * @return Post
     * @throws \Exception
     */
    public function execute(array $data, string $userId): Post
    {
        try {
            if (!empty($data['category_id'])) {
                $categoryExists = PostCategory::where('id', $data['category_id'])->exists();
                if (!$categoryExists) {
                    throw ValidationException::withMessages([
                        'category_id' => 'دسته‌بندی معتبر نیست.',
                    ]);
                }
            }

            $settings = $this->blogSettingService->getSettings();

            $postData = [
                'id' => Uuid::uuid4()->toString(),
                'author_id' => $userId,
                'category_id' => $data['category_id'] ?? null,
                'title' => $data['title'],
                'slug' => $data['slug'] ?? SlugHelper::generatePersianSlug($data['title'], Post::class, 'slug'),
                'short_link' => PostShortLinkHelper::generateShortLink($data['id'] ?? Uuid::uuid4()->toString()),
                'summary' => $data['summary'] ?? null,
                'content' => $data['content'] ?? null,
                'post_type' => $data['post_type'] ?? 'article',
                'media_link' => $data['media_link'] ?? null,
                'featured_image' => $data['featured_image'] ?? null,
                'cover_image_id' => $data['cover_image']['id'] ?? null,
                'cover_image_name' => $data['cover_image']['name'] ?? null,
                'cover_image_alt' => $data['cover_image']['alt'] ?? null,
                'cover_image_preview' => $data['cover_image']['preview'] ?? $settings->default_cover_image,
                'cover_image_width' => $data['cover_image']['width'] ?? null,
                'cover_image_height' => $data['cover_image']['height'] ?? null,
                'comment_status' => $data['comment_status'] ?? true,
                'status' => $data['status'] ?? 'draft',
                'visibility' => $data['visibility'] ?? 'public',
                'password' => !empty($data['password']) ? bcrypt($data['password']) : null,
                'is_featured' => $data['is_featured'] ?? false,
                'is_trend' => $data['is_trend'] ?? false,
                'is_advertisement' => $data['is_advertisement'] ?? false,
                'reading_time' => $data['reading_time'] ?? null,
                'published_at' => $data['published_at'] ?? now(),
                'is_active' => $data['is_active'] ?? true,
                'created_by' => $userId,
                'updated_by' => $userId,
            ];

            $post = $this->postRepository->execute($postData);

            if (!empty($data['seo'])) {
                $seoData = array_merge($data['seo'], [
                    'id' => Uuid::uuid4()->toString(),
                    'post_id' => $post->id,
                    'created_by' => $userId,
                    'generator' => 'Spital CMS, Created By Abrecode.com',
                ]);
                $this->seoRepository->execute($post->id, $seoData);
            }

            if (!empty($data['schema']) && !empty($data['schema']['type'])) {
                $schemaData = [
                    'id' => Uuid::uuid4()->toString(),
                    'post_id' => $post->id,
                    'type' => $data['schema']['type'] ?? null,
                    'title' => $data['schema']['title'] ?? $data['title'],
                    'slug' => $data['schema']['slug'] ?? SlugHelper::generatePersianSlug(
                            $data['schema']['title'] ?? $data['title'],
                            PostSchema::class,
                            'slug'
                        ),
                    'content' => $data['schema']['content'] ?? null,
                    'description' => $data['schema']['description'] ?? null,
                    'data' => !empty($data['schema']['data']) ? $data['schema']['data'] : null,
                    'schema_json' => $this->schemaJsonService->execute(
                        $data['schema']['type'] ?? '',
                        $data['schema']['data'] ?? []
                    ),
                    'status' => $data['schema']['status'] ?? 'draft',
                    'visibility' => $data['schema']['visibility'] ?? 'public',
                    'language' => $data['schema']['language'] ?? 'fa',
                    'created_by' => $userId,
                ];
                $this->schemaRepository->execute($post->id, $schemaData);
            }

            return $post;
        } catch (\Exception $e) {
            Log::error('Error creating post: '.$e->getMessage(), [
                'data' => $data,
                'userId' => $userId,
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
}
