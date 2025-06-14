<?php

namespace Modules\Blog\App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\Blog\App\Models\Post;
use Illuminate\Database\Eloquent\Builder;
use Modules\Seo\App\Models\post\PostSchema;
use Modules\Seo\App\Models\post\PostSeo;

class PostRepository
{
    public function getAllPosts(
        int $perPage,
        ?string $status = null,
        ?string $visibility = null,
        ?string $search = null,
        ?string $categoryId = null
    ): \Illuminate\Contracts\Pagination\LengthAwarePaginator {
        $query = Post::query()
            ->with(['author', 'category', 'seo', 'schema', 'createdBy', 'updatedBy'])
            ->when($status, fn(Builder $q) => $q->where('status', $status))
            ->when($visibility, fn(Builder $q) => $q->where('visibility', $visibility))
            ->when($search, fn(Builder $q) => $q->where('title', 'like', "%{$search}%")
                ->orWhere('slug', 'like', "%{$search}%"))
            ->when($categoryId, fn(Builder $q) => $q->where('category_id', $categoryId));

        return $query->paginate($perPage);
    }

    public function getPostById(string $id): Builder|array|Collection|Model
    {
        return Post::with(['author', 'category', 'seo', 'schema', 'createdBy', 'updatedBy'])
            ->findOrFail($id);
    }

    public function getPostBySlug(string $slug): Builder|Model
    {
        return Post::with(['author', 'category', 'seo', 'schema', 'createdBy', 'updatedBy'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();
    }

    public function createPost(array $data): Post
    {
        return Post::create($data);
    }

    public function createPostSeo(string $postId, array $data): PostSeo
    {
        return PostSeo::create(array_merge($data, ['post_id' => $postId]));
    }

    public function createPostSchema(string $postId, array $data): PostSchema
    {
        return PostSchema::create(array_merge($data, ['post_id' => $postId]));
    }

    public function updatePost(string $id, array $data): Post
    {
        $post = Post::findOrFail($id);
        $post->update(array_filter($data));
        return $post;
    }

    public function updatePostSeo(string $postId, array $data): PostSeo
    {
        $seo = PostSeo::where('post_id', $postId)->first();
        if ($seo) {
            $seo->update(array_filter($data));
        } else {
            $seo = PostSeo::create(array_merge($data, ['post_id' => $postId]));
        }
        return $seo;
    }

    public function updatePostSchema(string $postId, array $data): PostSchema
    {
        $schema = PostSchema::where('post_id', $postId)->first();
        if ($schema) {
            $schema->update(array_filter($data));
        } else {
            $schema = PostSchema::create(array_merge($data, ['post_id' => $postId]));
        }
        return $schema;
    }

    public function deletePost(string $id): void
    {
        $post = Post::findOrFail($id);
        $post->delete();
    }
}
