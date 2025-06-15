<?php

namespace Modules\Blog\App\Repositories\Posts;

use Modules\Blog\App\Models\PostBookmark;

class FindBookmarkRepository
{
    /**
     * Find a bookmark for a post by user ID.
     *
     * @param  string  $postId
     * @param  string  $userId
     * @return PostBookmark|null
     */
    public function execute(string $postId, string $userId): ?PostBookmark
    {
        return PostBookmark::where('post_id', $postId)->where('user_id', $userId)->first();
    }
}
