<?php

namespace Modules\Blog\App\Repositories\Posts;

use Modules\Blog\App\Models\PostLike;

class FindLikeRepository
{
    /**
     * Find a like for a post by user ID.
     *
     * @param  string  $postId
     * @param  string  $userId
     * @return PostLike|null
     */
    public function execute(string $postId, string $userId): ?PostLike
    {
        return PostLike::where('post_id', $postId)->where('user_id', $userId)->first();
    }
}
