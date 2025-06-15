<?php

namespace Modules\Blog\App\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Modules\Blog\App\Models\Post;
use Modules\Blog\App\Models\PostBookmark;
use Modules\Blog\App\Models\PostLike;
use Modules\Blog\App\Models\PostView;
use Modules\Blog\App\Models\TrendingPost;
use Modules\Seo\App\Models\post\PostSchema;
use Modules\Seo\App\Models\post\PostSeo;
use Ramsey\Uuid\Uuid;

/**
 * @method getChildCategoryIds(string $categoryId)
 */
class PostRepository
{
    public function getAllPosts(
        int $perPage,
        ?string $status,
        ?string $visibility,
        ?string $search,
        ?string $categoryId
    ): LengthAwarePaginator {
        $query = Post::query()->with(['category', 'comments']);

        if ($status) {
            $query->where('status', $status);
        }

        if ($visibility) {
            $query->where('visibility', $visibility);
        }

        if ($search) {
            $query->where('title', 'like', "%{$search}%");
        }

        if ($categoryId) {
            $categoryIds = $this->getChildCategoryIds($categoryId);
            $query->whereIn('category_id', $categoryIds);
        }

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

    public function getPostByShortLink(string $shortLink): ?Post
    {
        return Post::where('short_link', $shortLink)->first();
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

    public function findLike(string $postId, string $userId): ?PostLike
    {
        return PostLike::where('post_id', $postId)->where('user_id', $userId)->first();
    }

    public function createLike(array $data): PostLike
    {
        return PostLike::create($data);
    }

    public function deleteLike(string $likeId): void
    {
        PostLike::where('id', $likeId)->delete();
    }

    public function incrementLikesCount(string $postId): void
    {
        Post::where('id', $postId)->increment('likes_count');
    }

    public function decrementLikesCount(string $postId): void
    {
        Post::where('id', $postId)->decrement('likes_count');
    }

    public function createView(array $data): PostView
    {
        return PostView::create($data);
    }

    public function getViewsCount(string $postId): int
    {
        return PostView::where('post_id', $postId)->count();
    }

    public function addToTrending(string $postId, int $viewsCount): void
    {
        TrendingPost::updateOrCreate(
            ['post_id' => $postId],
            [
                'id' => Uuid::uuid4()->toString(),
                'views_count' => $viewsCount,
                'trending_at' => now(),
            ]
        );
    }

    public function getTrendingPosts(int $perPage): \Illuminate\Pagination\LengthAwarePaginator
    {
        return TrendingPost::with('post')->orderBy('views_count', 'desc')->paginate($perPage);
    }

    public function findBookmark(string $postId, string $userId): ?PostBookmark
    {
        return PostBookmark::where('post_id', $postId)->where('user_id', $userId)->first();
    }

    public function createBookmark(array $data): PostBookmark
    {
        return PostBookmark::create($data);
    }

    public function deleteBookmark(string $bookmarkId): void
    {
        PostBookmark::where('id', $bookmarkId)->delete();
    }
}
