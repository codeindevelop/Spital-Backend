<?php

namespace Modules\Blog\App\Repositories\Posts;

use Modules\Blog\App\Models\Post;

class IncrementLikesCountRepository
{
    /**
     * Increment the likes count for a post.
     *
     * @param  string  $postId
     * @return void
     */
    public function execute(string $postId): void
    {
        Post::where('id', $postId)->increment('likes_count');
    }
}
