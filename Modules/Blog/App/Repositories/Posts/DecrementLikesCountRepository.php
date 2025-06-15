<?php

namespace Modules\Blog\App\Repositories\Posts;

use Modules\Blog\App\Models\Post;

class DecrementLikesCountRepository
{
    /**
     * Decrement the likes count for a post.
     *
     * @param  string  $postId
     * @return void
     */
    public function execute(string $postId): void
    {
        Post::where('id', $postId)->decrement('likes_count');
    }
}
