<?php

namespace Modules\Blog\App\Services;

use App\Helpers\SlugHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\Blog\App\Models\Post;
use Modules\Blog\App\Repositories\PostRepository;
use Illuminate\Support\Str;
use Modules\Seo\App\Models\post\PostSchema;
use Ramsey\Uuid\Uuid;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class PostService
{
    protected PostRepository $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function getAllPosts(
        int $perPage = 15,
        ?string $status = null,
        ?string $visibility = null,
        ?string $search = null,
        ?string $categoryId = null
    ): \Illuminate\Contracts\Pagination\LengthAwarePaginator {
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

    public function createPost(array $data, string $userId): Post
    {
        try {
            if (!empty($data['category_id'])) {
                $categoryExists = \Modules\Blog\App\Models\PostCategory::where('id', $data['category_id'])->exists();
                if (!$categoryExists) {
                    throw ValidationException::withMessages([
                        'category_id' => 'دسته‌بندی معتبر نیست.',
                    ]);
                }
            }

            $postData = [
                'id' => Uuid::uuid4()->toString(),
                'author_id' => $userId,
                'category_id' => $data['category_id'] ?? null,
                'title' => $data['title'],
                'slug' => $data['slug'] ?? SlugHelper::generatePersianSlug($data['title'], Post::class, 'slug'),
                // استفاده از هلپر
                'content' => $data['content'] ?? null,
                'featured_image' => $data['featured_image'] ?? null,
                'comment_status' => $data['comment_status'] ?? true,
                'status' => $data['status'] ?? 'draft',
                'visibility' => $data['visibility'] ?? 'public',
                'password' => !empty($data['password']) ? bcrypt($data['password']) : null,
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
}
