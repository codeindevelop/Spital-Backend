<?php

namespace Modules\Blog\App\Repositories\Posts;

use Modules\Blog\App\Models\PostView;

class GetViewsCountRepository
{
    /**
     * Get the views count for a post.
     *
     * @param  string  $postId
     * @return int
     */
    public function execute(string $postId): int
    {
        return PostView::where('post_id', $postId)->count();
    }
}
