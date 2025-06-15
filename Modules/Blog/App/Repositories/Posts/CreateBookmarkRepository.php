<?php

namespace Modules\Blog\App\Repositories\Posts;

use Modules\Blog\App\Models\PostBookmark;

class CreateBookmarkRepository
{
    /**
     * Create a bookmark for a post.
     *
     * @param  array  $data
     * @return PostBookmark
     */
    public function execute(array $data): PostBookmark
    {
        return PostBookmark::create($data);
    }
}
