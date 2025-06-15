<?php

namespace Modules\Blog\App\Services;

use App\Helpers\blog\PostShortLinkHelper;
use App\Helpers\blog\SlugHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Modules\Blog\App\Models\Post;
use Modules\Blog\App\Models\PostCategory;
use Modules\Blog\App\Repositories\PostRepository;
use Modules\Seo\App\Models\post\PostSchema;
use Modules\Settings\App\Services\Blog\BlogSettingService;
use Ramsey\Uuid\Uuid;

class PostService
{
    protected PostRepository $postRepository;
    protected BlogSettingService $blogSettingService;

    public function __construct(PostRepository $postRepository, BlogSettingService $blogSettingService)
    {
        $this->postRepository = $postRepository;
        $this->blogSettingService = $blogSettingService;
    }

    public function getAllPosts(
        int $perPage = null,
        string $status = null,
        string $visibility = null,
        string $search = null,
        string $categoryId = null
    ): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $settings = $this->blogSettingService->getSettings();
        $perPage = $perPage ?? ($visibility === 'public' ? $settings->public_posts_per_page : $settings->admin_posts_per_page);

        return $this->postRepository->getAllPosts($perPage, $status, $visibility, $search, $categoryId);
    }

    public function getPostById(string $id): array|Builder|Collection|Model
    {
        return $this->postRepository->getPostById($id);
    }

    public function getPostBySlug(string $slug): Builder|Model
    {
        return $this->postRepository->getPostBySlug($slug);
    }

    public function getPostByShortLink(string $shortLink)
    {
        return $this->postRepository->getPostByShortLink($shortLink);
    }

    public function createPost(array $data, string $userId): Post
    {
        try {
            if (!empty($data['category_id'])) {
                $categoryExists = PostCategory::where('id',
                    $data['category_id'])->exists();
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
                // استفاده از هلپر
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

            $post = $this->postRepository->createPost($postData);

            if (!empty($data['seo'])) {
                $seoData = array_merge($data['seo'], [
                    'id' => Uuid::uuid4()->toString(),
                    'post_id' => $post->id,
                    'created_by' => $userId,
                    'generator' => 'Spital CMS, Created By Abrecode.com',
                ]);
                $this->postRepository->createPostSeo($post->id, $seoData);
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
                        ), // استفاده از هلپر برای اسکیما
                    'content' => $data['schema']['content'] ?? null,
                    'description' => $data['schema']['description'] ?? null,
                    'data' => !empty($data['schema']['data']) ? $data['schema']['data'] : null,
                    'schema_json' => $this->buildSchemaJson(
                        $data['schema']['type'] ?? '',
                        $data['schema']['data'] ?? []
                    ),
                    'status' => $data['schema']['status'] ?? 'draft',
                    'visibility' => $data['schema']['visibility'] ?? 'public',
                    'language' => $data['schema']['language'] ?? 'fa',
                    'created_by' => $userId,
                ];
                $this->postRepository->createPostSchema($post->id, $schemaData);
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

    public function updatePost(string $id, array $data, string $userId): Post
    {
        $postData = array_filter([
            'author_id' => $userId,
            'category_id' => $data['category_id'] ?? null,
            'title' => $data['title'] ?? null,
            'slug' => $data['slug'] ?? null,
            'content' => $data['content'] ?? null,
            'featured_image' => $data['featured_image'] ?? null,
            'comment_status' => $data['comment_status'] ?? null,
            'status' => $data['status'] ?? null,
            'visibility' => $data['visibility'] ?? null,
            'password' => !empty($data['password']) ? bcrypt($data['password']) : null,
            'published_at' => $data['published_at'] ?? null,
            'is_active' => $data['is_active'] ?? null,
            'updated_by' => $userId,
        ]);

        $post = $this->postRepository->updatePost($id, $postData);

        if (!empty($data['seo'])) {
            $seoData = array_merge($data['seo'], ['created_by' => $userId]);
            $this->postRepository->updatePostSeo($post->id, $seoData);
        }

        if (!empty($data['schema']) && !empty($data['schema']['type'])) {
            $schemaData = [
                'type' => $data['schema']['type'] ?? null,
                'title' => $data['schema']['title'] ?? $data['title'],
                'slug' => $data['schema']['slug'] ?? ($data['slug'] ?? Str::slug($data['title'], '-', 'fa')),
                'content' => $data['schema']['content'] ?? null,
                'description' => $data['schema']['description'] ?? null,
                'data' => !empty($data['schema']['data']) ? $data['schema']['data'] : null,
                'schema_json' => $this->buildSchemaJson(
                    $data['schema']['type'] ?? '',
                    $data['schema']['data'] ?? []
                ),
                'status' => $data['schema']['status'] ?? 'draft',
                'visibility' => $data['schema']['visibility'] ?? 'public',
                'language' => $data['schema']['language'] ?? 'fa',
                'created_by' => $userId,
            ];
            $this->postRepository->updatePostSchema($post->id, $schemaData);
        }

        return $post;
    }

    public function deletePost(string $id): void
    {
        $this->postRepository->deletePost($id);
    }

    protected function buildSchemaJson(string $type, array $data): array
    {
        $schema = ['@context' => 'https://schema.org', '@type' => $type];

        switch ($type) {
            case 'Article':
                $schema = array_merge($schema, [
                    'headline' => $data['headline'] ?? null,
                    'description' => $data['description'] ?? null,
                    'author' => $data['author'] ?? null,
                    'datePublished' => $data['datePublished'] ?? null,
                    'image' => $data['image'] ?? null,
                ]);
                break;
            case 'FAQPage':
                $schema['mainEntity'] = array_map(function ($faq) {
                    return [
                        '@type' => 'Question',
                        'name' => $faq['question'] ?? null,
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text' => $faq['answer'] ?? null,
                        ],
                    ];
                }, $data['faq'] ?? []);
                break;
            // سایر انواع Schema
        }

        return array_filter($schema, fn($value) => !is_null($value));
    }


    public function likePost(string $postId, string $userId): void
    {
        try {
            $post = $this->postRepository->findPost($postId);
            if (!$post) {
                throw new \Exception('پست یافت نشد.');
            }

            $existingLike = $this->postRepository->findLike($postId, $userId);
            if ($existingLike) {
                throw new \Exception('شما قبلاً این پست را لایک کرده‌اید.');
            }

            $likeData = [
                'id' => Uuid::uuid4()->toString(),
                'post_id' => $postId,
                'user_id' => $userId,
                'liked_at' => now(),
            ];

            $this->postRepository->createLike($likeData);
            $this->postRepository->incrementLikesCount($postId);
        } catch (\Exception $e) {
            Log::error('Error liking post: '.$e->getMessage(), [
                'postId' => $postId,
                'userId' => $userId,
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    public function unlikePost(string $postId, string $userId): void
    {
        try {
            $post = $this->postRepository->findPost($postId);
            if (!$post) {
                throw new \Exception('پست یافت نشد.');
            }

            $like = $this->postRepository->findLike($postId, $userId);
            if (!$like) {
                throw new \Exception('شما این پست را لایک نکرده‌اید.');
            }

            $this->postRepository->deleteLike($like->id);
            $this->postRepository->decrementLikesCount($postId);
        } catch (\Exception $e) {
            Log::error('Error unliking post: '.$e->getMessage(), [
                'postId' => $postId,
                'userId' => $userId,
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    public function recordView(string $postId, ?string $userId, string $ipAddress): void
    {
        try {
            $viewData = [
                'id' => Uuid::uuid4()->toString(),
                'post_id' => $postId,
                'user_id' => $userId,
                'ip_address' => $ipAddress,
                'viewed_at' => now(),
            ];

            $this->postRepository->createView($viewData);

            // بررسی برای افزودن به پربازدیدها
            $viewsCount = $this->postRepository->getViewsCount($postId);
            if ($viewsCount > 5) { // آستانه ۵ بازدید
                $this->postRepository->addToTrending($postId, $viewsCount);
            }
        } catch (\Exception $e) {
            Log::error('Error recording view: '.$e->getMessage(), [
                'postId' => $postId,
                'userId' => $userId,
                'ipAddress' => $ipAddress,
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    public function bookmarkPost(string $postId, string $userId): void
    {
        try {
            $post = $this->postRepository->findPost($postId);
            if (!$post) {
                throw new \Exception('پست یافت نشد.');
            }

            $existingBookmark = $this->postRepository->findBookmark($postId, $userId);
            if ($existingBookmark) {
                throw new \Exception('شما قبلاً این پست را بوکمارک کرده‌اید.');
            }

            $bookmarkData = [
                'id' => Uuid::uuid4()->toString(),
                'post_id' => $postId,
                'user_id' => $userId,
                'bookmarked_at' => now(),
            ];

            $this->postRepository->createBookmark($bookmarkData);
        } catch (\Exception $e) {
            Log::error('Error bookmarking post: '.$e->getMessage(), [
                'postId' => $postId,
                'userId' => $userId,
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    public function unbookmarkPost(string $postId, string $userId): void
    {
        try {
            $bookmark = $this->postRepository->findBookmark($postId, $userId);
            if (!$bookmark) {
                throw new \Exception('شما این پست را بوکمارک نکرده‌اید.');
            }

            $this->postRepository->deleteBookmark($bookmark->id);
        } catch (\Exception $e) {
            Log::error('Error unbookmarking post: '.$e->getMessage(), [
                'postId' => $postId,
                'userId' => $userId,
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
}
