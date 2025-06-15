<?php

namespace Modules\Blog\App\Services\Posts;

use Illuminate\Support\Str;
use Modules\Blog\App\Models\Post;
use Modules\Blog\App\Repositories\Posts\UpdatePostRepository;
use Modules\Blog\App\Repositories\Posts\UpdatePostSeoRepository;
use Modules\Blog\App\Repositories\Posts\UpdatePostSchemaRepository;

class UpdatePostService
{
    protected UpdatePostRepository $postRepository;
    protected UpdatePostSeoRepository $seoRepository;
    protected UpdatePostSchemaRepository $schemaRepository;
    protected BuildSchemaJsonService $schemaJsonService;

    public function __construct(
        UpdatePostRepository $postRepository,
        UpdatePostSeoRepository $seoRepository,
        UpdatePostSchemaRepository $schemaRepository,
        BuildSchemaJsonService $schemaJsonService
    ) {
        $this->postRepository = $postRepository;
        $this->seoRepository = $seoRepository;
        $this->schemaRepository = $schemaRepository;
        $this->schemaJsonService = $schemaJsonService;
    }

    /**
     * Update a post with SEO and schema data.
     *
     * @param  string  $id
     * @param  array  $data
     * @param  string  $userId
     * @return Post
     */
    public function execute(string $id, array $data, string $userId): Post
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

        $post = $this->postRepository->execute($id, $postData);

        if (!empty($data['seo'])) {
            $seoData = array_merge($data['seo'], ['created_by' => $userId]);
            $this->seoRepository->execute($post->id, $seoData);
        }

        if (!empty($data['schema']) && !empty($data['schema']['type'])) {
            $schemaData = [
                'type' => $data['schema']['type'] ?? null,
                'title' => $data['schema']['title'] ?? $data['title'],
                'slug' => $data['schema']['slug'] ?? ($data['slug'] ?? Str::slug($data['title'], '-', 'fa')),
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
    }
}
